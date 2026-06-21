<?php
require_once dirname(__DIR__) . '/config/config.php';
requireEditor();

$db = Database::getInstance();
$errors = []; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id     = $_POST['id'] ?? null;
    $name   = trim($_POST['name'] ?? '');
    $slug   = trim($_POST['slug'] ?? '') ?: generateSlug($name);
    $desc   = trim($_POST['description'] ?? '');

    if (!$name) $errors[] = 'Tên danh mục không được để trống.';

    if (empty($errors)) {
        if ($action === 'update' && $id) {
            $db->prepare("UPDATE categories SET name=?,slug=?,description=?,updated_at=datetime('now','localtime') WHERE id=?")
               ->execute([$name, $slug, $desc, $id]);
            $success = 'Đã cập nhật danh mục.';
        } else {
            $db->prepare("INSERT INTO categories (name,slug,description,created_at,updated_at) VALUES (?,?,?,datetime('now','localtime'),datetime('now','localtime'))")
               ->execute([$name, $slug, $desc]);
            $success = 'Đã thêm danh mục mới.';
        }
    }
}

if (isset($_GET['delete']) && isAdmin()) {
    $db->prepare("DELETE FROM categories WHERE id=?")->execute([(int)$_GET['delete']]);
    redirect('/admin/categories', 'Đã xóa danh mục.', 'success');
}

$edit_category = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit_category = $stmt->fetch();
}

$stmt = $db->prepare("SELECT c.*,COUNT(p.id) as post_count FROM categories c LEFT JOIN posts p ON c.id=p.category_id GROUP BY c.id ORDER BY c.name ASC");
$stmt->execute();
$categories = $stmt->fetchAll();

$page_title = 'Danh mục';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<div class="page-header">
    <div><h1>Danh mục</h1><p>Phân loại bài viết theo chủ đề</p></div>
</div>

<?php if ($errors): ?>
<div class="flex items-center gap-3 px-4 py-3 mb-5 rounded-2xl border text-sm font-medium bg-red-50 border-red-200 text-red-700">
    <i class="bi bi-x-circle-fill text-red-500"></i><?php echo implode(' · ', $errors); ?>
</div>
<?php elseif ($success): ?>
<div class="flex items-center gap-3 px-4 py-3 mb-5 rounded-2xl border text-sm font-medium bg-green-50 border-green-200 text-green-800">
    <i class="bi bi-check-circle-fill text-green-500"></i><?php echo $success; ?>
</div>
<?php endif; ?>
<?php displayFlashMessage(); ?>

<div class="grid lg:grid-cols-3 gap-5">
    <!-- Form -->
    <div>
        <div class="a-card sticky-panel">
            <div class="a-card-header">
                <h2><?php echo $edit_category ? 'Chỉnh sửa danh mục' : 'Thêm danh mục'; ?></h2>
            </div>
            <div class="a-card-body">
                <form method="POST">
                    <?php echo csrfField(); ?>
                    <input type="hidden" name="action" value="<?php echo $edit_category ? 'update' : 'create'; ?>">
                    <?php if ($edit_category): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_category['id']; ?>">
                    <?php endif; ?>

                    <div class="a-field">
                        <label class="a-label">Tên danh mục <span class="text-red-400">*</span></label>
                        <input type="text" name="name" class="a-input" required
                            value="<?php echo htmlspecialchars($edit_category['name'] ?? ''); ?>">
                    </div>
                    <div class="a-field">
                        <label class="a-label">Slug</label>
                        <input type="text" name="slug" class="a-input"
                            value="<?php echo htmlspecialchars($edit_category['slug'] ?? ''); ?>"
                            placeholder="Tự động tạo nếu để trống">
                    </div>
                    <div class="a-field">
                        <label class="a-label">Mô tả</label>
                        <textarea name="description" class="a-input" rows="3"><?php echo htmlspecialchars($edit_category['description'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn-adm w-full justify-center">
                        <?php echo $edit_category ? 'Cập nhật' : 'Thêm danh mục'; ?>
                    </button>
                    <?php if ($edit_category): ?>
                    <a href="/admin/categories" class="btn-adm-outline w-full justify-center mt-2">Huỷ</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="lg:col-span-2">
        <div class="a-card">
            <div class="a-card-header"><h2>Danh sách (<?php echo count($categories); ?>)</h2></div>
            <table class="a-table">
                <thead><tr>
                    <th>Tên</th><th>Slug</th><th class="text-center">Bài viết</th><th class="text-right">Thao tác</th>
                </tr></thead>
                <tbody>
                <?php if (empty($categories)): ?>
                <tr><td colspan="4" class="text-center py-10 text-slate-400">Chưa có danh mục nào.</td></tr>
                <?php endif; ?>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td class="font-semibold text-midnight"><?php echo htmlspecialchars($cat['name']); ?></td>
                    <td><code class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-xs"><?php echo $cat['slug']; ?></code></td>
                    <td class="text-center">
                        <span class="badge badge-inactive"><?php echo $cat['post_count']; ?></span>
                    </td>
                    <td>
                        <div class="flex justify-end gap-1.5">
                            <a href="?edit=<?php echo $cat['id']; ?>" class="btn-icon btn-icon-edit" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if (isAdmin() && $cat['post_count'] == 0): ?>
                            <a href="?delete=<?php echo $cat['id']; ?>" class="btn-icon btn-icon-del"
                                onclick="return confirm('Xóa danh mục này?')" title="Xóa">
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
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
