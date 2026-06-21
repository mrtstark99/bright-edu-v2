<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db = Database::getInstance();

if (isset($_GET['delete']) && isAdmin()) {
    $db->prepare("DELETE FROM posts WHERE id=?")->execute([(int)$_GET['delete']]);
    redirect('/admin/posts', 'Đã xóa bài viết.', 'success');
}

$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = ADMIN_PER_PAGE;
$offset   = ($page - 1) * $per_page;
$search   = $_GET['search'] ?? '';
$status_f = $_GET['status'] ?? '';

$where = []; $params = [];
if ($search) { $where[] = "(p.title LIKE ? OR p.content LIKE ?)"; array_push($params, "%$search%", "%$search%"); }
if ($status_f) { $where[] = "p.status = ?"; $params[] = $status_f; }
$w = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $db->prepare("SELECT COUNT(*) FROM posts p $w");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$stmt = $db->prepare("
    SELECT p.*, u.full_name as author_name, c.name as category_name
    FROM posts p
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    $w ORDER BY p.created_at DESC LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$posts = $stmt->fetchAll();

$status_meta = [
    'draft'     => ['Nháp',     'badge-inactive'],
    'published' => ['Đã đăng',  'badge-active'],
    'archived'  => ['Lưu trữ',  'badge-pending'],
];

$page_title = 'Bài viết';
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';
?>

<div class="page-header">
    <div><h1>Bài viết</h1><p>Quản lý nội dung blog</p></div>
    <a href="/admin/posts/create" class="btn-adm"><i class="bi bi-plus-lg"></i> Thêm bài viết</a>
</div>

<?php displayFlashMessage(); ?>

<div class="a-filter">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="a-label">Tìm kiếm</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="a-input" placeholder="Tiêu đề bài viết...">
        </div>
        <div class="w-40">
            <label class="a-label">Trạng thái</label>
            <select name="status" onchange="this.form.submit()" class="a-input">
                <option value="">Tất cả</option>
                <?php foreach ($status_meta as $k => $v): ?>
                <option value="<?php echo $k; ?>" <?php echo $status_f===$k?'selected':''; ?>><?php echo $v[0]; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-adm">Tìm</button>
        <?php if ($search || $status_f): ?>
        <a href="/admin/posts" class="btn-adm-outline">Xoá lọc</a>
        <?php endif; ?>
    </form>
</div>

<div class="a-card">
    <div class="overflow-x-auto">
        <table class="a-table">
            <thead><tr>
                <th>Bài viết</th>
                <th>Danh mục</th>
                <th>Tác giả</th>
                <th>Trạng thái</th>
                <th class="text-center">Lượt xem</th>
                <th>Ngày tạo</th>
                <th class="text-right">Thao tác</th>
            </tr></thead>
            <tbody>
            <?php if (empty($posts)): ?>
            <tr><td colspan="7" class="text-center py-12 text-slate-400">
                <i class="bi bi-file-earmark text-4xl block mb-2 opacity-30"></i>Chưa có bài viết nào.
            </td></tr>
            <?php endif; ?>
            <?php foreach ($posts as $post):
                $sm = $status_meta[$post['status']] ?? ['?','badge-inactive'];
            ?>
            <tr>
                <td class="max-w-[260px]">
                    <div class="flex items-center gap-3">
                        <?php if ($post['featured_image']): ?>
                        <img src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" class="w-10 h-10 rounded-lg object-cover flex-shrink-0 border border-slate-100">
                        <?php else: ?>
                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300 flex-shrink-0">
                            <i class="bi bi-image"></i>
                        </div>
                        <?php endif; ?>
                        <div>
                            <a href="/blog/<?php echo $post['slug']; ?>" target="_blank"
                                class="font-bold text-midnight hover:text-primary transition-colors line-clamp-1 text-sm">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                            <?php if ($post['featured']): ?>
                            <span class="inline-block mt-0.5 text-[10px] font-bold bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded uppercase">Nổi bật</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-inactive"><?php echo htmlspecialchars($post['category_name'] ?? '—'); ?></span></td>
                <td class="text-sm text-slate-600"><?php echo htmlspecialchars($post['author_name'] ?? '—'); ?></td>
                <td><span class="badge <?php echo $sm[1]; ?>"><?php echo $sm[0]; ?></span></td>
                <td class="text-center text-slate-500 text-sm"><?php echo formatNumber($post['views']); ?></td>
                <td class="text-xs text-slate-400 whitespace-nowrap"><?php echo formatDate($post['created_at']); ?></td>
                <td>
                    <div class="flex justify-end gap-1.5">
                        <a href="/admin/posts/edit?id=<?php echo $post['id']; ?>" class="btn-icon btn-icon-edit" title="Sửa">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if (isAdmin()): ?>
                        <a href="?delete=<?php echo $post['id']; ?>" class="btn-icon btn-icon-del"
                            onclick="return confirm('Xóa bài viết này?')" title="Xóa">
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
        <span>Tổng <strong class="text-midnight"><?php echo $total; ?></strong> bài viết</span>
        <?php echo paginate($total, $page, $per_page, '/admin/posts'); ?>
    </div>
</div>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
