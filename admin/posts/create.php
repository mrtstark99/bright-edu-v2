<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db = Database::getInstance();
$error = '';
$success = '';

// Get categories
$stmt = $db->prepare("SELECT id, name FROM categories ORDER BY name");
$stmt->execute();
$categories = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $slug = createSlug($title);
    $content = $_POST['content'] ?? '';
    $excerpt = sanitizeInput($_POST['excerpt'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $status = $_POST['status'] ?? 'draft';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $meta_title = sanitizeInput($_POST['meta_title'] ?? $title);
    $meta_description = sanitizeInput($_POST['meta_description'] ?? '');
    $meta_keywords = sanitizeInput($_POST['meta_keywords'] ?? '');
    
    // Validate
    if (empty($title) || empty($content) || empty($category_id)) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
    } else {
        // Check if slug exists
        $stmt = $db->prepare("SELECT id FROM posts WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetch()) {
            $slug .= '-' . time();
        }
        
        // Handle image upload
        $featured_image = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $upload = uploadImage($_FILES['featured_image'], 'posts');
            if ($upload['success']) {
                $featured_image = $upload['filepath'];
            } else {
                $error = $upload['message'];
            }
        }
        
        if (!$error) {
            $published_at = null;
            if (!empty($_POST['published_at'])) {
                $published_at = str_replace('T', ' ', $_POST['published_at']);
                if (strlen($published_at) === 16) {
                    $published_at .= ':00';
                }
            }
            if (($status === 'published' || $status === 'scheduled') && !$published_at) {
                $published_at = date('Y-m-d H:i:s');
            }
            
            $sql = "INSERT INTO posts (title, slug, excerpt, content, featured_image, category_id, author_id, status, featured, meta_title, meta_description, meta_keywords, published_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            if ($stmt->execute([$title, $slug, $excerpt, $content, $featured_image, $category_id, $_SESSION['user_id'], $status, $featured, $meta_title, $meta_description, $meta_keywords, $published_at])) {
                redirect('/admin/posts', 'Bài viết đã được tạo thành công.', 'success');
            } else {
                $error = 'Có lỗi xảy ra khi tạo bài viết.';
            }
        }
    }
}

$page_title = 'Thêm bài viết mới';
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';
?>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-midnight font-display">Thêm bài viết mới</h1>
        <a href="/admin/posts" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-xl transition-colors inline-flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
    
    <?php if ($error): ?>
    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 font-medium">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <?php echo csrfField(); ?>
        
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Nội dung chính -->
                <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h6 class="m-0 font-bold text-midnight font-display text-lg">Nội dung bài viết</h6>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="title" name="title" required placeholder="Nhập tiêu đề bài viết...">
                        </div>
                        
                        <div class="mb-6">
                            <label for="excerpt" class="block text-sm font-bold text-slate-700 mb-2">Mô tả ngắn</label>
                            <textarea class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="excerpt" name="excerpt" rows="3" placeholder="Đoạn mô tả ngắn hiển thị ở trang danh sách bài viết..."></textarea>
                        </div>
                        
                        <div>
                            <label for="content" class="block text-sm font-bold text-slate-700 mb-2">Nội dung <span class="text-red-500">*</span></label>
                            <textarea id="content" name="content" rows="15" required></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h5 class="m-0 font-bold text-midnight font-display text-lg">SEO Cài đặt</h5>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <label for="meta_title" class="block text-sm font-bold text-slate-700 mb-2">Meta Title</label>
                            <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="meta_title" name="meta_title">
                            <p class="text-xs text-slate-500 mt-1">Để trống sẽ sử dụng tiêu đề bài viết</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="meta_description" class="block text-sm font-bold text-slate-700 mb-2">Meta Description</label>
                            <textarea class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="meta_description" name="meta_description" rows="2"></textarea>
                        </div>
                        
                        <div>
                            <label for="meta_keywords" class="block text-sm font-bold text-slate-700 mb-2">Meta Keywords</label>
                            <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="meta_keywords" name="meta_keywords">
                            <p class="text-xs text-slate-500 mt-1">Các từ khóa cách nhau bằng dấu phẩy</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cài đặt xuất bản -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden sticky top-6">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h6 class="m-0 font-bold text-midnight font-display text-lg">Cài đặt xuất bản</h6>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <label for="category_id" class="block text-sm font-bold text-slate-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                            <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="category_id" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-bold text-slate-700 mb-2">Trạng thái</label>
                            <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="status" name="status">
                                <option value="draft">Nháp</option>
                                <option value="published">Đăng ngay</option>
                                <option value="scheduled">Lên lịch đăng</option>
                            </select>
                        </div>

                        <div class="mb-6" id="publish-time-container" style="display: none;">
                            <label for="published_at" class="block text-sm font-bold text-slate-700 mb-2">Thời gian xuất bản</label>
                            <input type="datetime-local" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="published_at" name="published_at" value="<?php echo date('Y-m-d\TH:i'); ?>">
                        </div>
                        
                        <div class="mb-6">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" id="featured" name="featured" class="peer sr-only">
                                    <div class="w-12 h-6 bg-slate-200 rounded-full peer-checked:bg-sky-500 transition-colors duration-200"></div>
                                    <div class="absolute left-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-6 shadow-sm"></div>
                                </div>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-midnight transition-colors">Bài viết nổi bật</span>
                            </label>
                        </div>
                        
                        <div class="mb-6">
                            <label for="featured_image" class="block text-sm font-bold text-slate-700 mb-2">Hình ảnh đại diện</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="featured_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 hover:border-sky-400 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="bi bi-cloud-arrow-up text-3xl text-slate-400 mb-2"></i>
                                        <p class="text-sm text-slate-500 font-medium">Click để tải ảnh lên</p>
                                    </div>
                                    <input id="featured_image" name="featured_image" type="file" class="hidden" accept="image/*" />
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-midnight hover:bg-slate-800 text-white font-semibold py-3 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                            <i class="bi bi-save"></i> Lưu bài viết
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<!-- Add TinyMCE or CKEditor for rich text editing -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500
    });

    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const timeContainer = document.getElementById('publish-time-container');
        
        function togglePublishTime() {
            if (statusSelect.value === 'published' || statusSelect.value === 'scheduled') {
                timeContainer.style.display = 'block';
            } else {
                timeContainer.style.display = 'none';
            }
        }
        
        statusSelect.addEventListener('change', togglePublishTime);
        togglePublishTime();
    });
</script>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
