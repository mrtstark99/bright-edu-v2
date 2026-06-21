<?php
require_once dirname(__DIR__) . '/config/config.php';
requireEditor();

$db = Database::getInstance();

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['contact_id'];
    $status = $_POST['status'];
    $db->prepare("UPDATE contacts SET status=?, updated_at=datetime('now','localtime') WHERE id=?")->execute([$status, $id]);
    redirect('/admin/contacts', 'Cập nhật trạng thái thành công.', 'success');
}

if (isset($_GET['delete']) && isAdmin()) {
    $db->prepare("DELETE FROM contacts WHERE id=?")->execute([(int)$_GET['delete']]);
    redirect('/admin/contacts', 'Đã xóa liên hệ.', 'success');
}

$page          = max(1, (int)($_GET['page'] ?? 1));
$per_page      = ADMIN_PER_PAGE;
$offset        = ($page - 1) * $per_page;
$status_filter = $_GET['status'] ?? '';
$search        = $_GET['search'] ?? '';

$where = []; $params = [];
if ($status_filter) { $where[] = "status = ?"; $params[] = $status_filter; }
if ($search) {
    $where[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ? OR message LIKE ?)";
    $s = "%$search%"; array_push($params, $s, $s, $s, $s);
}
$w = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $db->prepare("SELECT COUNT(*) FROM contacts $w");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$stmt = $db->prepare("SELECT * FROM contacts $w ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$contacts = $stmt->fetchAll();

$status_meta = [
    'new'      => ['Mới',        'badge-new'],
    'read'     => ['Đã đọc',     'badge-inactive'],
    'replied'  => ['Đã trả lời', 'badge-active'],
    'archived' => ['Lưu trữ',    'badge-inactive'],
];

$page_title = 'Form liên hệ';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<div class="page-header">
    <div>
        <h1>Form liên hệ</h1>
        <p>Danh sách khách hàng gửi qua form liên hệ</p>
    </div>
</div>

<?php displayFlashMessage(); ?>

<!-- Filters -->
<div class="a-filter">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="a-label">Tìm kiếm</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                class="a-input" placeholder="Tên, email, SĐT...">
        </div>
        <div class="w-44">
            <label class="a-label">Trạng thái</label>
            <select name="status" onchange="this.form.submit()" class="a-input">
                <option value="">Tất cả</option>
                <?php foreach ($status_meta as $k => $v): ?>
                <option value="<?php echo $k; ?>" <?php echo $status_filter===$k?'selected':''; ?>><?php echo $v[0]; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-adm">Tìm</button>
        <?php if ($search || $status_filter): ?>
        <a href="/admin/contacts" class="btn-adm-outline">Xoá lọc</a>
        <?php endif; ?>
    </form>
</div>

<!-- Table -->
<div class="a-card">
    <div class="overflow-x-auto">
        <table class="a-table">
            <thead><tr>
                <th>Khách hàng</th>
                <th>Liên lạc</th>
                <th>Trình độ</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Ngày gửi</th>
                <th class="text-right">Thao tác</th>
            </tr></thead>
            <tbody>
            <?php if (empty($contacts)): ?>
            <tr><td colspan="7" class="text-center py-12 text-slate-400">
                <i class="bi bi-inbox text-4xl block mb-2 opacity-30"></i>Chưa có liên hệ nào.
            </td></tr>
            <?php endif; ?>
            <?php foreach ($contacts as $c):
                $sm = $status_meta[$c['status']] ?? ['?','badge-inactive'];
            ?>
            <tr>
                <td>
                    <div class="font-bold text-midnight"><?php echo htmlspecialchars($c['name']); ?></div>
                    <?php if ($c['intake_period']): ?>
                    <div class="text-xs text-slate-400 mt-0.5"><?php echo htmlspecialchars($c['intake_period']); ?></div>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="text-sm text-slate-600"><?php echo htmlspecialchars($c['email'] ?? '—'); ?></div>
                    <div class="text-xs text-slate-400"><?php echo htmlspecialchars($c['phone'] ?? '—'); ?></div>
                </td>
                <td>
                    <?php if ($c['japanese_level']): ?>
                    <span class="badge badge-inactive"><?php echo htmlspecialchars($c['japanese_level']); ?></span>
                    <?php else: echo '<span class="text-slate-300 text-xs">—</span>'; endif; ?>
                </td>
                <td class="max-w-[200px]">
                    <div class="text-xs text-slate-500 line-clamp-2"><?php echo htmlspecialchars(truncateText($c['message'] ?? '', 80)); ?></div>
                </td>
                <td>
                    <form method="POST" class="inline">
                        <?php echo csrfField(); ?>
                        <input type="hidden" name="contact_id" value="<?php echo $c['id']; ?>">
                        <input type="hidden" name="update_status" value="1">
                        <select name="status" onchange="this.form.submit()"
                            class="text-xs rounded-lg px-2 py-1.5 border border-slate-200 bg-slate-50 focus:outline-none cursor-pointer font-semibold">
                            <?php foreach ($status_meta as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php echo $c['status']===$k?'selected':''; ?>><?php echo $v[0]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
                <td class="text-xs text-slate-400 whitespace-nowrap"><?php echo formatDate($c['created_at']); ?></td>
                <td>
                    <div class="flex justify-end gap-1.5">
                        <button type="button" class="btn-icon btn-icon-view"
                            data-bs-toggle="modal" data-bs-target="#modal<?php echo $c['id']; ?>" title="Chi tiết">
                            <i class="bi bi-eye"></i>
                        </button>
                        <?php if (isAdmin()): ?>
                        <a href="?delete=<?php echo $c['id']; ?>" class="btn-icon btn-icon-del"
                            onclick="return confirm('Xóa liên hệ này?')" title="Xóa">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>

            <!-- Detail Modal -->
            <div class="modal fade" id="modal<?php echo $c['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-3xl border-0 shadow-medium">
                        <div class="modal-header border-b border-slate-100 px-6 py-4">
                            <h5 class="modal-title font-bold text-midnight font-display">Liên hệ #<?php echo $c['id']; ?> — <?php echo htmlspecialchars($c['name']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body px-6 py-5">
                            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                <div><div class="a-label">Họ tên</div><div class="font-semibold"><?php echo htmlspecialchars($c['name']); ?></div></div>
                                <div><div class="a-label">Email</div><a href="mailto:<?php echo $c['email']; ?>" class="text-sky-600"><?php echo htmlspecialchars($c['email']); ?></a></div>
                                <div><div class="a-label">Điện thoại</div><div><?php echo htmlspecialchars($c['phone']); ?></div></div>
                                <div><div class="a-label">Kỳ nhập học</div><div><?php echo htmlspecialchars($c['intake_period'] ?? '—'); ?></div></div>
                                <div><div class="a-label">Trình độ Nhật</div><div><?php echo htmlspecialchars($c['japanese_level'] ?? '—'); ?></div></div>
                                <div><div class="a-label">Ngày gửi</div><div><?php echo formatDate($c['created_at']); ?></div></div>
                            </div>
                            <div class="a-label">Nội dung</div>
                            <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 whitespace-pre-wrap"><?php echo htmlspecialchars($c['message'] ?? ''); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
        <span>Tổng <strong class="text-midnight"><?php echo $total; ?></strong> liên hệ</span>
        <?php echo paginate($total, $page, $per_page, '/admin/contacts'); ?>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
