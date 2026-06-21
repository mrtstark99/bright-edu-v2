<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db = Database::getInstance();

// Handle delete
if (isset($_GET['delete']) && isAdmin()) {
    $id = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
    if ($stmt->execute([$id])) {
        redirect('/admin/services', 'Dịch vụ đã được xóa thành công.', 'success');
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = ADMIN_PER_PAGE;
$offset = ($page - 1) * $per_page;

// Search
$search = $_GET['search'] ?? '';
$where = '';
$params = [];

if ($search) {
    $where = "WHERE title LIKE ? OR description LIKE ?";
    $params = ["%$search%", "%$search%"];
}

// Get total services
$stmt = $db->prepare("SELECT COUNT(*) as total FROM services $where");
$stmt->execute($params);
$total = $stmt->fetch()['total'];

// Get services
$sql = "
    SELECT *
    FROM services
    $where
    ORDER BY display_order ASC, created_at DESC
    LIMIT $per_page OFFSET $offset
";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();

$page_title = 'Quản lý dịch vụ';
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';
?>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-midnight font-display">Quản lý dịch vụ</h1>
        <a href="/admin/services/create" class="bg-sage-500 hover:bg-sage-600 text-white font-semibold py-2.5 px-5 rounded-xl transition-colors inline-flex items-center gap-2">
            <i class="bi bi-plus-circle"></i> Thêm dịch vụ
        </a>
    </div>

    <?php displayFlashMessage(); ?>

    <!-- Search and Filter -->
    <div class="bg-white rounded-3xl p-6 shadow-soft border border-slate-100 mb-8">
        <form method="GET" action="">
            <div class="flex max-w-lg">
                <input type="text" name="search" class="w-full bg-slate-50 border border-slate-200 rounded-l-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500" placeholder="Tìm kiếm tiêu đề, mô tả dịch vụ..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-r-xl text-sm font-semibold transition-colors flex items-center gap-2" type="submit">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
            </div>
        </form>
    </div>

    <!-- Services Table -->
    <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">ID</th>
                        <th class="px-6 py-4 font-bold">Icon</th>
                        <th class="px-6 py-4 font-bold">Tiêu đề</th>
                        <th class="px-6 py-4 font-bold">Mô tả</th>
                        <th class="px-6 py-4 font-bold text-center">Thứ tự</th>
                        <th class="px-6 py-4 font-bold text-center">Trạng thái</th>
                        <th class="px-6 py-4 font-bold">Ngày tạo</th>
                        <th class="px-6 py-4 font-bold text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($services)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-500">Chưa có dịch vụ nào.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($services as $service): ?>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 text-slate-500">#<?php echo $service['id']; ?></td>
                        <td class="px-6 py-4">
                            <?php if ($service['icon']): ?>
                            <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-sky-500 border border-slate-100">
                                <i class="<?php echo htmlspecialchars($service['icon']); ?> text-xl"></i>
                            </div>
                            <?php else: ?>
                            <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                                <i class="bi bi-card-text"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-midnight"><?php echo htmlspecialchars($service['title']); ?></span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs">
                            <p class="truncate" title="<?php echo htmlspecialchars($service['description']); ?>">
                                <?php echo htmlspecialchars($service['description']); ?>
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-semibold text-xs">
                                <?php echo $service['display_order']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($service['status'] === 'active'): ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                Hoạt động
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                Ẩn
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs"><?php echo formatDate($service['created_at']); ?></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="/admin/services/edit?id=<?php echo $service['id']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition-colors" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if (isAdmin()): ?>
                                <a href="/admin/services?delete=<?php echo $service['id']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-sakura-50 text-sakura-600 hover:bg-sakura-100 transition-colors" onclick="return confirm('Bạn có chắc muốn xóa dịch vụ này?')" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-slate-100 flex justify-center">
            <?php echo paginate($total, $page, $per_page, '/admin/services'); ?>
        </div>
    </div>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
