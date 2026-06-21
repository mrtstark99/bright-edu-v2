<?php
require_once dirname(__DIR__) . '/config/config.php';
requireEditor();

$db = Database::getInstance();
$errors = []; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = $_POST['id'] ?? null;
    $title      = trim($_POST['title'] ?? '');
    $content    = trim($_POST['content'] ?? '');
    $type       = $_POST['type'] ?? 'info';
    $priority   = (int)($_POST['priority'] ?? 0);
    $status     = $_POST['status'] ?? 'active';
    $start_date = $_POST['start_date'] ?: null;
    $end_date   = $_POST['end_date'] ?: null;

    if (!$title) $errors[] = 'Tiêu đề không được để trống.';

    if (empty($errors)) {
        if ($id) {
            $db->prepare("UPDATE announcements SET title=?,content=?,type=?,priority=?,status=?,start_date=?,end_date=?,updated_at=datetime('now','localtime') WHERE id=?")
               ->execute([$title, $content, $type, $priority, $status, $start_date, $end_date, $id]);
            $success = 'Đã cập nhật thông báo.';
        } else {
            $db->prepare("INSERT INTO announcements (title,content,type,priority,status,start_date,end_date,created_at,updated_at) VALUES (?,?,?,?,?,?,?,datetime('now','localtime'),datetime('now','localtime'))")
               ->execute([$title, $content, $type, $priority, $status, $start_date, $end_date]);
            $success = 'Đã thêm thông báo mới.';
        }
    }
}

if (isset($_GET['delete']) && isAdmin()) {
    $db->prepare("DELETE FROM announcements WHERE id=?")->execute([(int)$_GET['delete']]);
    redirect('/admin/announcements', 'Đã xóa thông báo.', 'success');
}

$edit_ann = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM announcements WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit_ann = $stmt->fetch();
}

$stmt = $db->prepare("SELECT * FROM announcements ORDER BY priority DESC, created_at DESC");
$stmt->execute();
$announcements = $stmt->fetchAll();

$type_meta = [
    'info'    => ['Thông tin', 'badge-new'],
    'warning' => ['Cảnh báo',  'badge-pending'],
    'success' => ['Thành công','badge-active'],
    'danger'  => ['Nguy hiểm', 'badge-danger'],
];

$page_title = 'Thông báo';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<div class="page-header">
    <div><h1>Thông báo</h1><p>Banner thông báo hiển thị trên trang chủ</p></div>
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
                <h2><?php echo $edit_ann ? 'Chỉnh sửa' : 'Thêm thông báo'; ?></h2>
            </div>
            <div class="a-card-body">
                <form method="POST">
                    <?php echo csrfField(); ?>
                    <?php if ($edit_ann): ?><input type="hidden" name="id" value="<?php echo $edit_ann['id']; ?>"><?php endif; ?>

                    <div class="a-field">
                        <label class="a-label">Tiêu đề <span class="text-red-400">*</span></label>
                        <input type="text" name="title" class="a-input" required
                            value="<?php echo htmlspecialchars($edit_ann['title'] ?? ''); ?>">
                    </div>
                    <div class="a-field">
                        <label class="a-label">Nội dung</label>
                        <textarea name="content" class="a-input" rows="3"><?php echo htmlspecialchars($edit_ann['content'] ?? ''); ?></textarea>
                    </div>
                    <div class="a-field">
                        <label class="a-label">Loại</label>
                        <select name="type" class="a-input">
                            <?php foreach ($type_meta as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php echo ($edit_ann['type']??'info')===$k?'selected':''; ?>><?php echo $v[0]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3 a-field">
                        <div>
                            <label class="a-label">Ưu tiên</label>
                            <input type="number" name="priority" class="a-input" value="<?php echo $edit_ann['priority'] ?? 0; ?>">
                        </div>
                        <div>
                            <label class="a-label">Trạng thái</label>
                            <select name="status" class="a-input">
                                <option value="active" <?php echo ($edit_ann['status']??'active')==='active'?'selected':''; ?>>Hoạt động</option>
                                <option value="inactive" <?php echo ($edit_ann['status']??'')==='inactive'?'selected':''; ?>>Tạm ẩn</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 a-field">
                        <div>
                            <label class="a-label">Từ ngày</label>
                            <input type="date" name="start_date" class="a-input" value="<?php echo $edit_ann['start_date'] ?? ''; ?>">
                        </div>
                        <div>
                            <label class="a-label">Đến ngày</label>
                            <input type="date" name="end_date" class="a-input" value="<?php echo $edit_ann['end_date'] ?? ''; ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn-adm w-full justify-center">
                        <?php echo $edit_ann ? 'Cập nhật' : 'Thêm thông báo'; ?>
                    </button>
                    <?php if ($edit_ann): ?>
                    <a href="/admin/announcements" class="btn-adm-outline w-full justify-center mt-2">Huỷ</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- List -->
    <div class="lg:col-span-2">
        <div class="a-card">
            <div class="a-card-header"><h2>Danh sách (<?php echo count($announcements); ?>)</h2></div>
            <table class="a-table">
                <thead><tr>
                    <th>Tiêu đề</th><th>Loại</th><th class="text-center">Ưu tiên</th><th>Trạng thái</th><th class="text-right">Thao tác</th>
                </tr></thead>
                <tbody>
                <?php if (empty($announcements)): ?>
                <tr><td colspan="5" class="text-center py-10 text-slate-400">Chưa có thông báo nào.</td></tr>
                <?php endif; ?>
                <?php foreach ($announcements as $ann):
                    $tm = $type_meta[$ann['type']] ?? ['?','badge-inactive'];
                ?>
                <tr>
                    <td>
                        <div class="font-semibold text-midnight text-sm"><?php echo htmlspecialchars($ann['title']); ?></div>
                        <?php if ($ann['start_date'] || $ann['end_date']): ?>
                        <div class="text-xs text-slate-400 mt-0.5">
                            <?php if ($ann['start_date']): ?>Từ <?php echo date('d/m', strtotime($ann['start_date'])); ?> <?php endif; ?>
                            <?php if ($ann['end_date']): ?>→ <?php echo date('d/m', strtotime($ann['end_date'])); ?><?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge <?php echo $tm[1]; ?>"><?php echo $tm[0]; ?></span></td>
                    <td class="text-center text-sm font-semibold text-slate-600"><?php echo $ann['priority']; ?></td>
                    <td>
                        <span class="badge <?php echo $ann['status']==='active'?'badge-active':'badge-inactive'; ?>">
                            <?php echo $ann['status']==='active'?'Hoạt động':'Tạm ẩn'; ?>
                        </span>
                    </td>
                    <td>
                        <div class="flex justify-end gap-1.5">
                            <a href="?edit=<?php echo $ann['id']; ?>" class="btn-icon btn-icon-edit" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if (isAdmin()): ?>
                            <a href="?delete=<?php echo $ann['id']; ?>" class="btn-icon btn-icon-del"
                                onclick="return confirm('Xóa thông báo này?')" title="Xóa">
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
