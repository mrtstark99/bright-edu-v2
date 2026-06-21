<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db = Database::getInstance();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id         = (int)$_POST['booking_id'];
    $status     = $_POST['status'];
    $zoom_link  = trim($_POST['zoom_link'] ?? '');
    $admin_notes= trim($_POST['admin_notes'] ?? '');

    $allowed = ['pending','confirmed','cancelled','completed','no_show'];
    if (in_array($status, $allowed)) {
        $stmt = $db->prepare("
            UPDATE consultation_bookings
            SET status = ?, zoom_link = ?, admin_notes = ?, updated_at = datetime('now','localtime')
            WHERE id = ?
        ");
        $stmt->execute([$status, $zoom_link ?: null, $admin_notes ?: null, $id]);
    }
    redirect('/admin/consultations', 'Cập nhật thành công.', 'success');
}

// Handle delete
if (isset($_GET['delete']) && isAdmin()) {
    $id = (int)$_GET['delete'];
    // Decrement slot count if group booking
    $stmt = $db->prepare("SELECT slot_id, booking_type, status FROM consultation_bookings WHERE id = ?");
    $stmt->execute([$id]);
    $bk = $stmt->fetch();
    if ($bk && $bk['slot_id'] && $bk['booking_type'] === 'group' && $bk['status'] !== 'cancelled') {
        $db->exec("UPDATE consultation_slots SET current_participants = MAX(0, current_participants - 1),
            status = CASE WHEN status = 'full' THEN 'active' ELSE status END
            WHERE id = {$bk['slot_id']}");
    }
    $db->prepare("DELETE FROM consultation_bookings WHERE id = ?")->execute([$id]);
    redirect('/admin/consultations', 'Đã xóa lịch đặt.', 'success');
}

// Filters
$type_filter   = $_GET['type']   ?? '';
$status_filter = $_GET['status'] ?? '';
$search        = $_GET['search'] ?? '';
$page          = max(1, (int)($_GET['page'] ?? 1));
$per_page      = ADMIN_PER_PAGE;
$offset        = ($page - 1) * $per_page;

$where  = [];
$params = [];

if ($type_filter)   { $where[] = "b.booking_type = ?"; $params[] = $type_filter; }
if ($status_filter) { $where[] = "b.status = ?";       $params[] = $status_filter; }
if ($search) {
    $where[] = "(b.name LIKE ? OR b.email LIKE ? OR b.phone LIKE ?)";
    $s = "%$search%";
    array_push($params, $s, $s, $s);
}

$w = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $db->prepare("SELECT COUNT(*) as total FROM consultation_bookings b $w");
$stmt->execute($params);
$total = $stmt->fetch()['total'];

$stmt = $db->prepare("
    SELECT b.*, s.title as slot_title, s.scheduled_date, s.time_start, s.time_end
    FROM consultation_bookings b
    LEFT JOIN consultation_slots s ON b.slot_id = s.id
    $w
    ORDER BY b.created_at DESC
    LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$bookings = $stmt->fetchAll();

// Counts for badge
$stmt = $db->prepare("SELECT COUNT(*) as n FROM consultation_bookings WHERE status = 'pending'");
$stmt->execute();
$pending_count = $stmt->fetch()['n'];

$page_title = 'Quản lý đặt lịch tư vấn';
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';

$statusLabels = [
    'pending'   => ['label' => 'Chờ xác nhận', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
    'confirmed' => ['label' => 'Đã xác nhận',  'class' => 'bg-sky-50 text-sky-700 border-sky-200'],
    'completed' => ['label' => 'Hoàn thành',   'class' => 'bg-green-50 text-green-700 border-green-200'],
    'cancelled' => ['label' => 'Đã huỷ',       'class' => 'bg-slate-100 text-slate-500 border-slate-200'],
    'no_show'   => ['label' => 'Vắng mặt',     'class' => 'bg-red-50 text-red-600 border-red-200'],
];
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-midnight font-display">Lịch đặt tư vấn</h1>
        <?php if ($pending_count > 0): ?>
        <p class="text-sm text-amber-600 mt-1"><i class="bi bi-clock-history mr-1"></i><?php echo $pending_count; ?> yêu cầu đang chờ xác nhận</p>
        <?php endif; ?>
    </div>
    <a href="/admin/consultations/slots" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-ink transition-colors shadow-soft">
        <i class="bi bi-calendar-plus"></i> Quản lý khung giờ
    </a>
</div>

<?php displayFlashMessage(); ?>

<!-- Filters -->
<div class="bg-white rounded-3xl p-5 shadow-soft border border-slate-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Tìm kiếm</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tên, email, SĐT..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
        </div>
        <div class="w-40">
            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Loại</label>
            <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <option value="group"      <?php echo $type_filter==='group'      ?'selected':''; ?>>Nhóm</option>
                <option value="individual" <?php echo $type_filter==='individual' ?'selected':''; ?>>1-1</option>
            </select>
        </div>
        <div class="w-48">
            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Trạng thái</label>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                <option value="pending"   <?php echo $status_filter==='pending'   ?'selected':''; ?>>Chờ xác nhận</option>
                <option value="confirmed" <?php echo $status_filter==='confirmed' ?'selected':''; ?>>Đã xác nhận</option>
                <option value="completed" <?php echo $status_filter==='completed' ?'selected':''; ?>>Hoàn thành</option>
                <option value="cancelled" <?php echo $status_filter==='cancelled' ?'selected':''; ?>>Đã huỷ</option>
                <option value="no_show"   <?php echo $status_filter==='no_show'   ?'selected':''; ?>>Vắng mặt</option>
            </select>
        </div>
        <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-ink transition-colors">Tìm</button>
        <?php if ($search || $type_filter || $status_filter): ?>
        <a href="/admin/consultations" class="bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors">Xoá lọc</a>
        <?php endif; ?>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-4 font-bold">Khách hàng</th>
                    <th class="px-5 py-4 font-bold">Loại</th>
                    <th class="px-5 py-4 font-bold">Lịch / Chủ đề</th>
                    <th class="px-5 py-4 font-bold">Trạng thái</th>
                    <th class="px-5 py-4 font-bold">Ngày đặt</th>
                    <th class="px-5 py-4 font-bold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($bookings)): ?>
                <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">Chưa có lịch đặt nào.</td></tr>
                <?php endif; ?>
                <?php foreach ($bookings as $b):
                    $sl = $statusLabels[$b['status']] ?? ['label'=>$b['status'],'class'=>'bg-slate-100 text-slate-500 border-slate-200'];
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-bold text-midnight"><?php echo htmlspecialchars($b['name']); ?></div>
                        <div class="text-xs text-slate-400 mt-0.5"><?php echo htmlspecialchars($b['email']); ?></div>
                        <div class="text-xs text-slate-400"><?php echo htmlspecialchars($b['phone']); ?></div>
                    </td>
                    <td class="px-5 py-4">
                        <?php if ($b['booking_type'] === 'group'): ?>
                        <span class="inline-flex items-center gap-1 bg-primary-50 text-primary text-xs font-bold px-2.5 py-1 rounded-full">
                            <i class="bi bi-people-fill text-[10px]"></i> Nhóm
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                            <i class="bi bi-person-fill text-[10px]"></i> 1-1
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-slate-600">
                        <?php if ($b['slot_title']): ?>
                        <div class="font-medium text-midnight text-xs"><?php echo htmlspecialchars($b['slot_title']); ?></div>
                        <div class="text-xs text-slate-400">
                            <?php echo $b['scheduled_date'] ? date('d/m/Y', strtotime($b['scheduled_date'])) : ''; ?>
                            <?php echo $b['time_start'] ? '· ' . $b['time_start'] . ' – ' . $b['time_end'] : ''; ?>
                        </div>
                        <?php elseif ($b['preferred_date']): ?>
                        <div class="text-xs">Mong muốn: <?php echo date('d/m/Y', strtotime($b['preferred_date'])); ?></div>
                        <div class="text-xs text-slate-400"><?php echo htmlspecialchars($b['preferred_time'] ?? ''); ?></div>
                        <?php if ($b['topic']): ?><div class="text-xs text-slate-500 mt-1">📋 <?php echo htmlspecialchars($b['topic']); ?></div><?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-block border text-xs font-bold px-2.5 py-1 rounded-full <?php echo $sl['class']; ?>">
                            <?php echo $sl['label']; ?>
                        </span>
                        <?php if ($b['zoom_link']): ?>
                        <div class="mt-1"><a href="<?php echo htmlspecialchars($b['zoom_link']); ?>" target="_blank" class="text-xs text-sky-600 hover:underline flex items-center gap-1"><i class="bi bi-camera-video"></i> Link Zoom</a></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-slate-400 text-xs"><?php echo formatDate($b['created_at']); ?></td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-1.5">
                            <button type="button"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition-colors"
                                onclick="openEditModal(<?php echo htmlspecialchars(json_encode($b)); ?>)"
                                title="Xem & cập nhật">
                                <i class="bi bi-pencil-square text-sm"></i>
                            </button>
                            <?php if (isAdmin()): ?>
                            <a href="?delete=<?php echo $b['id']; ?>"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors"
                               onclick="return confirm('Xác nhận xóa lịch này?')" title="Xóa">
                                <i class="bi bi-trash text-sm"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-slate-100 flex justify-between items-center text-sm text-slate-500">
        <span>Tổng: <strong class="text-midnight"><?php echo $total; ?></strong> lịch đặt</span>
        <?php echo paginate($total, $page, $per_page, '/admin/consultations'); ?>
    </div>
</div>

<!-- Edit/View Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-medium">
            <div class="modal-header border-b border-slate-100 px-6 py-4">
                <h5 class="modal-title font-bold text-midnight font-display">Chi tiết & Cập nhật lịch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <?php echo csrfField(); ?>
                <input type="hidden" name="booking_id" id="modal_booking_id">
                <input type="hidden" name="update_status" value="1">
                <div class="modal-body px-6 py-5">
                    <div class="grid grid-cols-2 gap-4 mb-5 text-sm">
                        <div><span class="text-xs uppercase font-bold text-slate-400 block mb-1">Họ tên</span><strong id="md_name" class="text-midnight"></strong></div>
                        <div><span class="text-xs uppercase font-bold text-slate-400 block mb-1">Email</span><span id="md_email" class="text-slate-600"></span></div>
                        <div><span class="text-xs uppercase font-bold text-slate-400 block mb-1">Điện thoại</span><span id="md_phone"></span></div>
                        <div><span class="text-xs uppercase font-bold text-slate-400 block mb-1">Tiếng Nhật</span><span id="md_jp"></span></div>
                        <div><span class="text-xs uppercase font-bold text-slate-400 block mb-1">Loại</span><span id="md_type"></span></div>
                        <div><span class="text-xs uppercase font-bold text-slate-400 block mb-1">Ngày / Giờ mong muốn</span><span id="md_datetime"></span></div>
                    </div>
                    <div id="md_msg_wrap" class="mb-5 hidden">
                        <span class="text-xs uppercase font-bold text-slate-400 block mb-1">Nội dung / Ghi chú của khách</span>
                        <div id="md_msg" class="bg-slate-50 rounded-xl p-3 text-sm text-slate-700 whitespace-pre-wrap"></div>
                    </div>
                    <hr class="border-slate-100 my-4">
                    <div class="space-y-4">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Trạng thái</label>
                            <select name="status" id="modal_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                                <option value="pending">Chờ xác nhận</option>
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã huỷ</option>
                                <option value="no_show">Vắng mặt</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Link Zoom (gửi cho khách khi xác nhận)</label>
                            <input type="url" name="zoom_link" id="modal_zoom" placeholder="https://zoom.us/j/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Ghi chú nội bộ</label>
                            <textarea name="admin_notes" id="modal_notes" rows="3" placeholder="Ghi chú dành cho nhân viên..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary resize-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
                    <button type="button" class="bg-slate-100 text-slate-600 px-5 py-2 rounded-xl text-sm font-semibold hover:bg-slate-200" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="bg-primary text-white px-5 py-2 rounded-xl text-sm font-bold hover:bg-ink transition-colors">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(b) {
    document.getElementById('modal_booking_id').value = b.id;
    document.getElementById('md_name').textContent = b.name;
    document.getElementById('md_email').textContent = b.email;
    document.getElementById('md_phone').textContent = b.phone;
    document.getElementById('md_jp').textContent = b.japanese_level || '—';
    document.getElementById('md_type').textContent = b.booking_type === 'group' ? 'Tư vấn nhóm' : 'Tư vấn 1-1';
    const dtParts = [];
    if (b.preferred_date) dtParts.push(b.preferred_date.split('-').reverse().join('/'));
    if (b.preferred_time) dtParts.push(b.preferred_time);
    if (b.slot_title) dtParts.push(b.slot_title);
    document.getElementById('md_datetime').textContent = dtParts.join(' · ') || '—';

    const msg = [b.topic, b.message].filter(Boolean).join('\n');
    if (msg) {
        document.getElementById('md_msg').textContent = msg;
        document.getElementById('md_msg_wrap').classList.remove('hidden');
    } else {
        document.getElementById('md_msg_wrap').classList.add('hidden');
    }

    document.getElementById('modal_status').value = b.status;
    document.getElementById('modal_zoom').value = b.zoom_link || '';
    document.getElementById('modal_notes').value = b.admin_notes || '';

    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
