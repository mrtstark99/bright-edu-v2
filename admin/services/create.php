<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db = Database::getInstance();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $status = $_POST['status'] ?? 'active';

    // Validation
    if (empty($title)) {
        $errors[] = 'Tiêu đề không được để trống.';
    }
    if (empty($description)) {
        $errors[] = 'Mô tả ngắn không được để trống.';
    }

    if (empty($errors)) {
        try {
            $stmt = $db->prepare("
                INSERT INTO services (title, description, content, icon, display_order, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, datetime('now','localtime'), datetime('now','localtime'))
            ");

            if ($stmt->execute([$title, $description, $content, $icon, $display_order, $status])) {
                redirect('/admin/services', 'Dịch vụ đã được tạo thành công.', 'success');
            } else {
                $errors[] = 'Có lỗi xảy ra khi tạo dịch vụ.';
            }
        } catch (Exception $e) {
            $errors[] = 'Lỗi: ' . $e->getMessage();
        }
    }
}

$page_title = 'Thêm dịch vụ mới';
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';
?>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-midnight font-display">Thêm dịch vụ mới</h1>
        <a href="/admin/services" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-xl transition-colors inline-flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 font-medium">
        <ul class="list-disc list-inside m-0">
            <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
        <?php echo csrfField(); ?>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h6 class="m-0 font-bold text-midnight font-display text-lg">Thông tin dịch vụ</h6>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required placeholder="Nhập tên dịch vụ...">
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Mô tả ngắn <span class="text-red-500">*</span></label>
                            <textarea class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="description" name="description" rows="3" required placeholder="Mô tả ngắn gọn về dịch vụ..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            <p class="text-xs text-slate-500 mt-2">Hiển thị trên trang chủ và danh sách dịch vụ.</p>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-bold text-slate-700 mb-2">Nội dung chi tiết</label>
                            <textarea class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="content" name="content" rows="8" placeholder="Nội dung chi tiết của dịch vụ (tùy chọn)..."><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden sticky top-6">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h6 class="m-0 font-bold text-midnight font-display text-lg">Cài đặt hiển thị</h6>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <label for="icon" class="block text-sm font-bold text-slate-700 mb-2">Icon class</label>
                            <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="icon" name="icon" value="<?php echo htmlspecialchars($_POST['icon'] ?? ''); ?>" placeholder="Ví dụ: bi bi-star">
                            <p class="text-xs text-slate-500 mt-2">
                                Sử dụng <a href="https://icons.getbootstrap.com/" target="_blank" class="text-sky-600 hover:underline">Bootstrap Icons</a>
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="display_order" class="block text-sm font-bold text-slate-700 mb-2">Thứ tự hiển thị</label>
                            <input type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="display_order" name="display_order" value="<?php echo htmlspecialchars($_POST['display_order'] ?? '0'); ?>">
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-bold text-slate-700 mb-2">Trạng thái</label>
                            <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="status" name="status">
                                <option value="active" <?php echo ($_POST['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                                <option value="inactive" <?php echo ($_POST['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Ẩn</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-midnight hover:bg-slate-800 text-white font-semibold py-3 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                            <i class="bi bi-save"></i> Tạo dịch vụ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
