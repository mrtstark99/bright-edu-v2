<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db = Database::getInstance();

// Handle create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'create' || $action === 'edit') {
        $id             = (int)($_POST['slot_id'] ?? 0);
        $type           = $_POST['type'] === 'individual' ? 'individual' : 'group';
        $title          = trim($_POST['title'] ?? '');
        $description    = trim($_POST['description'] ?? '');
        $zoom_link      = trim($_POST['zoom_link'] ?? '');
        $scheduled_date = trim($_POST['scheduled_date'] ?? '');
        $time_start     = trim($_POST['time_start'] ?? '');
        $time_end       = trim($_POST['time_end'] ?? '');
        $max_part       = max(1, (int)($_POST['max_participants'] ?? 30));
        $is_free        = isset($_POST['is_free']) ? 1 : 0;
        $status         = in_array($_POST['status'] ?? '', ['active','cancelled','completed']) ? $_POST['status'] : 'active';

        if (!$title || !$scheduled_date || !$time_start || !$time_end) {
            redirect('/admin/consultations/slots', 'Vui lòng điền đầy đủ tiêu đề, ngày và giờ.', 'danger');
        }

        if ($action === 'create') {
            $stmt = $db->prepare("
                INSERT INTO consultation_slots (type, title, description, zoom_link, scheduled_date, time_start, time_end, max_participants, is_free, status, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$type, $title, $description ?: null, $zoom_link ?: null, $scheduled_date, $time_start, $time_end, $max_part, $is_free, $status, $_SESSION['user_id'] ?? null]);
            redirect('/admin/consultations/slots', 'Đã tạo khung giờ mới thành công.', 'success');
        } else {
            $stmt = $db->prepare("
                UPDATE consultation_slots SET type=?, title=?, description=?, zoom_link=?, scheduled_date=?, time_start=?, time_end=?, max_participants=?, is_free=?, status=?, updated_at=datetime('now','localtime')
                WHERE id=?
            ");
            $stmt->execute([$type, $title, $description ?: null, $zoom_link ?: null, $scheduled_date, $time_start, $time_end, $max_part, $is_free, $status, $id]);
            redirect('/admin/consultations/slots', 'Đã cập nhật khung giờ.', 'success');
        }
    }

    if ($action === 'delete' && isAdmin()) {
        $id = (int)($_POST['slot_id'] ?? 0);
        $db->prepare("DELETE FROM consultation_slots WHERE id = ?")->execute([$id]);
        redirect('/admin/consultations/slots', 'Đã xóa khung giờ.', 'success');
    }
}

// Fetch slots
$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = ADMIN_PER_PAGE;
$offset   = ($page - 1) * $per_page;

$stmt = $db->prepare("SELECT COUNT(*) as total FROM consultation_slots");
$stmt->execute();
$total = $stmt->fetch()['total'];

$stmt = $db->prepare("
    SELECT s.*,
           (SELECT COUNT(*) FROM consultation_bookings b WHERE b.slot_id = s.id AND b.status != 'cancelled') as booking_count
    FROM consultation_slots s
    ORDER BY s.scheduled_date DESC, s.time_start DESC
    LIMIT $per_page OFFSET $offset
");
$stmt->execute();
$slots = $stmt->fetchAll();

$page_title = 'Quản lý khung giờ tư vấn';
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';

$statusBadge = [
    'active'    => 'bg-green-50 text-green-700',
    'full'      => 'bg-amber-50 text-amber-700',
    'cancelled' => 'bg-slate-100 text-slate-500',
    'completed' => 'bg-blue-50 text-blue-700',
];
$statusLabel = ['active' => 'Đang mở', 'full' => 'Đầy chỗ', 'cancelled' => 'Đã huỷ', 'completed' => 'Hoàn thành'];
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-midnight font-display">Khung giờ tư vấn</h1>
        <p class="text-sm text-muted mt-1">Tạo và quản lý các buổi tư vấn nhóm qua Zoom</p>
    </div>
    <div class="flex gap-3">
        <a href="/admin/consultations" class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors">
            <i class="bi bi-calendar-check"></i> Xem đặt lịch
        </a>
        <button onclick="openCreateModal()" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-ink transition-colors shadow-soft">
            <i class="bi bi-plus-circle"></i> Tạo khung giờ mới
        </button>
    </div>
</div>

<?php displayFlashMessage(); ?>

<!-- Table -->
<div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-4 font-bold">Buổi tư vấn</th>
                    <th class="px-5 py-4 font-bold">Ngày & Giờ</th>
                    <th class="px-5 py-4 font-bold">Loại</th>
                    <th class="px-5 py-4 font-bold text-center">Người đăng ký</th>
                    <th class="px-5 py-4 font-bold">Link Zoom</th>
                    <th class="px-5 py-4 font-bold">Trạng thái</th>
                    <th class="px-5 py-4 font-bold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($slots)): ?>
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <i class="bi bi-calendar-event text-5xl text-slate-200 block mb-3"></i>
                        <p class="text-slate-400 font-medium">Chưa có khung giờ nào</p>
                        <button onclick="openCreateModal()" class="mt-4 bg-primary text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-ink transition-colors">Tạo buổi đầu tiên</button>
                    </td>
                </tr>
                <?php endif; ?>
                <?php foreach ($slots as $slot):
                    $isPast = strtotime($slot['scheduled_date']) < strtotime(date('Y-m-d'));
                    $badgeClass = $statusBadge[$slot['status']] ?? 'bg-slate-100 text-slate-500';
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors <?php echo $isPast ? 'opacity-60' : ''; ?>">
                    <td class="px-5 py-4">
                        <div class="font-bold text-midnight"><?php echo htmlspecialchars($slot['title']); ?></div>
                        <?php if ($slot['description']): ?>
                        <div class="text-xs text-slate-400 mt-0.5 line-clamp-1"><?php echo htmlspecialchars($slot['description']); ?></div>
                        <?php endif; ?>
                        <?php if ($slot['is_free']): ?><span class="text-[10px] bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-bold mt-1 inline-block">Miễn phí</span><?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-slate-600">
                        <div class="font-semibold"><?php echo date('d/m/Y', strtotime($slot['scheduled_date'])); ?></div>
                        <div class="text-xs text-slate-400"><?php echo $slot['time_start']; ?> – <?php echo $slot['time_end']; ?></div>
                    </td>
                    <td class="px-5 py-4">
                        <?php if ($slot['type'] === 'group'): ?>
                        <span class="inline-flex items-center gap-1 bg-primary-50 text-primary text-xs font-bold px-2 py-0.5 rounded-full"><i class="bi bi-people-fill text-[10px]"></i> Nhóm</span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full"><i class="bi bi-person-fill text-[10px]"></i> 1-1</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="font-bold text-midnight"><?php echo $slot['current_participants']; ?></span>
                        <span class="text-slate-400 text-xs">/ <?php echo $slot['max_participants']; ?></span>
                        <?php if ($slot['booking_count'] > 0): ?>
                        <div><a href="/admin/consultations?slot_id=<?php echo $slot['id']; ?>" class="text-[10px] text-sky-600 hover:underline"><?php echo $slot['booking_count']; ?> đặt lịch</a></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4">
                        <?php if ($slot['zoom_link']): ?>
                        <a href="<?php echo htmlspecialchars($slot['zoom_link']); ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-sky-600 hover:underline"><i class="bi bi-camera-video-fill"></i> Mở Zoom</a>
                        <?php else: ?>
                        <span class="text-xs text-slate-300">Chưa có</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full <?php echo $badgeClass; ?>">
                            <?php echo $statusLabel[$slot['status']] ?? $slot['status']; ?>
                        </span>
                        <?php if ($isPast && $slot['status'] === 'active'): ?>
                        <div class="text-[10px] text-slate-400 mt-0.5">Đã qua ngày</div>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-1.5">
                            <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($slot)); ?>)"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors" title="Sửa">
                                <i class="bi bi-pencil text-sm"></i>
                            </button>
                            <?php if (isAdmin()): ?>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="slot_id" value="<?php echo $slot['id']; ?>">
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors" title="Xóa"
                                    onclick="return confirm('Xác nhận xóa buổi này? Tất cả đặt lịch liên quan cũng bị ảnh hưởng.')">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-slate-100 flex justify-between items-center text-sm text-slate-500">
        <span>Tổng: <strong class="text-midnight"><?php echo $total; ?></strong> khung giờ</span>
        <?php echo paginate($total, $page, $per_page, '/admin/consultations/slots'); ?>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="slotModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-medium">
            <div class="modal-header border-b border-slate-100 px-6 py-4">
                <h5 class="modal-title font-bold text-midnight font-display" id="slotModalTitle">Tạo khung giờ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <?php echo csrfField(); ?>
                <input type="hidden" name="action" id="form_action" value="create">
                <input type="hidden" name="slot_id" id="form_slot_id" value="">
                <div class="modal-body px-6 py-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Tiêu đề buổi <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="f_title" required placeholder="VD: Tư vấn du học Nhật tháng 6"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Loại buổi</label>
                            <select name="type" id="f_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                                <option value="group">Tư vấn nhóm (Zoom)</option>
                                <option value="individual">Tư vấn 1-1</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Mô tả ngắn</label>
                        <textarea name="description" id="f_desc" rows="2" placeholder="Chủ đề sẽ được thảo luận trong buổi này..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Ngày <span class="text-red-500">*</span></label>
                            <input type="date" name="scheduled_date" id="f_date" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Giờ bắt đầu <span class="text-red-500">*</span></label>
                            <input type="time" name="time_start" id="f_start" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Giờ kết thúc <span class="text-red-500">*</span></label>
                            <input type="time" name="time_end" id="f_end" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Số người tối đa</label>
                            <input type="number" name="max_participants" id="f_max" min="1" max="500" value="30"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Trạng thái</label>
                            <select name="status" id="f_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                                <option value="active">Đang mở đăng ký</option>
                                <option value="cancelled">Đã huỷ</option>
                                <option value="completed">Hoàn thành</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Link Zoom</label>
                        <input type="url" name="zoom_link" id="f_zoom" placeholder="https://zoom.us/j/..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>
                    <div class="flex items-center gap-3 py-1">
                        <input type="checkbox" name="is_free" id="f_free" value="1" class="w-4 h-4 rounded" checked>
                        <label for="f_free" class="text-sm font-medium text-midnight cursor-pointer">Buổi tư vấn miễn phí</label>
                    </div>
                </div>
                <div class="modal-footer border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
                    <button type="button" class="bg-slate-100 text-slate-600 px-5 py-2 rounded-xl text-sm font-semibold hover:bg-slate-200" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="bg-primary text-white px-5 py-2 rounded-xl text-sm font-bold hover:bg-ink transition-colors" id="slotSubmitBtn">Tạo khung giờ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('slotModalTitle').textContent = 'Tạo khung giờ mới';
    document.getElementById('slotSubmitBtn').textContent = 'Tạo khung giờ';
    document.getElementById('form_action').value = 'create';
    document.getElementById('form_slot_id').value = '';
    document.getElementById('f_title').value = '';
    document.getElementById('f_type').value = 'group';
    document.getElementById('f_desc').value = '';
    document.getElementById('f_date').value = '';
    document.getElementById('f_start').value = '';
    document.getElementById('f_end').value = '';
    document.getElementById('f_max').value = 30;
    document.getElementById('f_status').value = 'active';
    document.getElementById('f_zoom').value = '';
    document.getElementById('f_free').checked = true;
    new bootstrap.Modal(document.getElementById('slotModal')).show();
}

function openEditModal(s) {
    document.getElementById('slotModalTitle').textContent = 'Chỉnh sửa khung giờ';
    document.getElementById('slotSubmitBtn').textContent = 'Lưu thay đổi';
    document.getElementById('form_action').value = 'edit';
    document.getElementById('form_slot_id').value = s.id;
    document.getElementById('f_title').value = s.title;
    document.getElementById('f_type').value = s.type;
    document.getElementById('f_desc').value = s.description || '';
    document.getElementById('f_date').value = s.scheduled_date;
    document.getElementById('f_start').value = s.time_start;
    document.getElementById('f_end').value = s.time_end;
    document.getElementById('f_max').value = s.max_participants;
    document.getElementById('f_status').value = s.status;
    document.getElementById('f_zoom').value = s.zoom_link || '';
    document.getElementById('f_free').checked = s.is_free == 1;
    new bootstrap.Modal(document.getElementById('slotModal')).show();
}
</script>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
