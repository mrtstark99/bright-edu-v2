<?php
require_once dirname(__DIR__) . '/config/config.php';
requireAdmin();

$db = Database::getInstance();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== $_SESSION['user_id']) {
        $db->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
        redirect('/admin/users', 'Đã xóa người dùng.', 'success');
    } else {
        redirect('/admin/users', 'Không thể tự xóa chính mình.', 'error');
    }
}

$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = ADMIN_PER_PAGE;
$offset   = ($page - 1) * $per_page;
$search   = $_GET['search'] ?? '';

$where = ''; $params = [];
if ($search) {
    $where = "WHERE username LIKE ? OR email LIKE ? OR full_name LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
}

$stmt = $db->prepare("SELECT COUNT(*) FROM users $where");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$stmt = $db->prepare("SELECT id,username,email,full_name,role,status,created_at FROM users $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$users = $stmt->fetchAll();

$page_title = 'Người dùng';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<div class="page-header">
    <div><h1>Người dùng</h1><p>Quản lý tài khoản hệ thống</p></div>
</div>

<?php displayFlashMessage(); ?>

<div class="a-filter">
    <form method="GET" class="flex gap-3 items-end">
        <div class="flex-1 max-w-sm">
            <label class="a-label">Tìm kiếm</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                class="a-input" placeholder="Tên, email, username...">
        </div>
        <button type="submit" class="btn-adm">Tìm</button>
        <?php if ($search): ?><a href="/admin/users" class="btn-adm-outline">Xoá lọc</a><?php endif; ?>
    </form>
</div>

<div class="a-card">
    <div class="overflow-x-auto">
        <table class="a-table">
            <thead><tr>
                <th>Người dùng</th>
                <th>Email / Username</th>
                <th class="text-center">Vai trò</th>
                <th class="text-center">Trạng thái</th>
                <th>Ngày tạo</th>
                <th class="text-right">Thao tác</th>
            </tr></thead>
            <tbody>
            <?php if (empty($users)): ?>
            <tr><td colspan="6" class="text-center py-12 text-slate-400">Chưa có người dùng nào.</td></tr>
            <?php endif; ?>
            <?php foreach ($users as $u): ?>
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            <?php echo strtoupper(substr($u['full_name'] ?: $u['username'], 0, 1)); ?>
                        </div>
                        <span class="font-semibold text-midnight"><?php echo htmlspecialchars($u['full_name'] ?: $u['username']); ?></span>
                    </div>
                </td>
                <td>
                    <div class="text-sm text-slate-700"><?php echo htmlspecialchars($u['email']); ?></div>
                    <div class="text-xs text-slate-400">@<?php echo htmlspecialchars($u['username']); ?></div>
                </td>
                <td class="text-center">
                    <span class="badge <?php echo $u['role']==='admin'?'badge-pending':'badge-inactive'; ?> uppercase">
                        <?php echo htmlspecialchars($u['role']); ?>
                    </span>
                </td>
                <td class="text-center">
                    <span class="badge <?php echo $u['status']==='active'?'badge-active':'badge-danger'; ?>">
                        <?php echo $u['status']==='active'?'Hoạt động':'Đã khóa'; ?>
                    </span>
                </td>
                <td class="text-xs text-slate-400 whitespace-nowrap"><?php echo formatDate($u['created_at']); ?></td>
                <td>
                    <div class="flex justify-end gap-1.5">
                        <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                        <a href="?delete=<?php echo $u['id']; ?>" class="btn-icon btn-icon-del"
                            onclick="return confirm('Xóa người dùng này?')" title="Xóa">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
        <span>Tổng <strong class="text-midnight"><?php echo $total; ?></strong> người dùng</span>
        <?php echo paginate($total, $page, $per_page, '/admin/users'); ?>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
