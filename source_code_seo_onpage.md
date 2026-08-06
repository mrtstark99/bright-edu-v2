# 💻 Hướng dẫn Mã nguồn PHP: Tối ưu SEO Onpage động
**Dự án:** Bright Education | **Lĩnh vực:** Phát triển mã nguồn & SEO Onpage

Để các bài viết chuẩn SEO mà AI Agent soạn thảo (ví dụ: các thẻ Meta, Title, Schema, Content) được hiển thị đúng định dạng và có hiệu quả với Google Bot, mã nguồn PHP của website cần được cấu hình động theo hướng dẫn dưới đây.

---

## 1. Cấu hình Tiêu đề & Meta Tags động (Trong `includes/header.php` hoặc `layout.php`)

Tránh việc sử dụng tiêu đề cứng cho mọi trang. Đoạn code dưới đây sẽ tự động nhận diện nếu đang ở trang chi tiết bài viết và in ra Title / Meta Description tương ứng từ Database:

```php
<?php
// Thiết lập giá trị mặc định cho trang chủ hoặc các trang tĩnh
$page_title = "Bright Education | Trung Tâm Tư Vấn Du Học Nhật Bản Uy Tín";
$meta_desc = "Bright Education chuyên tư vấn du học Nhật Bản uy tín với lộ trình rõ ràng, chi phí minh bạch, hỗ trợ săn học bổng và giới thiệu việc làm thêm miễn phí.";
$canonical_url = "https://brighteducation.net" . $_SERVER['REQUEST_URI'];

// Giả sử $post là mảng dữ liệu bài viết được truy vấn từ Database cho trang chi tiết
if (isset($post) && is_array($post)) {
    // Độ dài Title tốt nhất là từ 50-60 ký tự
    $page_title = htmlspecialchars($post['title']);
    
    // Nếu tiêu đề bài viết chưa chứa tên thương hiệu, tự động nối thêm
    if (strpos(strtolower($page_title), 'bright education') === false) {
        $page_title .= " | Bright Education";
    }
    
    // Độ dài Meta Description tốt nhất là từ 120-155 ký tự
    if (!empty($post['meta_description'])) {
        $meta_desc = htmlspecialchars($post['meta_description']);
    } else {
        // Fallback nếu bài viết chưa có meta description: Lấy 150 ký tự đầu của nội dung
        $meta_desc = htmlspecialchars(mb_substr(strip_tags($post['content']), 0, 150)) . "...";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Tiêu đề & Meta Description Động -->
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $meta_desc; ?>">
    
    <!-- Thẻ Canonical Động (Tránh lỗi trùng lặp nội dung do URL có tham số phụ) -->
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
```

---

## 2. Cài đặt Cấu trúc Structured Data Schema động (Trong trang chi tiết bài viết)

Thêm đoạn mã JSON-LD Schema này vào phần `<head>` của trang chi tiết bài viết. Google Bot sẽ đọc cấu trúc này để hiển thị bài viết của anh dưới dạng **Rich Snippet** đẹp mắt trên trang kết quả tìm kiếm:

```php
<?php if (isset($post) && is_array($post)): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "<?php echo addslashes($post['title']); ?>",
  "description": "<?php echo addslashes($post['meta_description'] ?? ''); ?>",
  "image": "<?php echo htmlspecialchars($post['featured_image'] ?? 'https://brighteducation.net/assets/images/default-blog.png'); ?>",
  "datePublished": "<?php echo date('c', strtotime($post['created_at'])); ?>",
  "dateModified": "<?php echo date('c', strtotime($post['updated_at'])); ?>",
  "author": {
    "@type": "Person",
    "name": "<?php echo htmlspecialchars($post['author_name'] ?? 'Bright Education'); ?>"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Bright Education",
    "logo": {
      "@type": "ImageObject",
      "url": "https://brighteducation.net/assets/images/logo.png"
    }
  }
}
</script>
<?php endif; ?>
```

---

## 3. Cấu trúc layout trang chi tiết bài viết (Ví dụ: `post.php` hoặc `blog-detail.php`)

Đảm bảo trang bài viết tuân thủ cấu trúc heading phân cấp chuẩn SEO (Chỉ có **duy nhất 1 thẻ H1** cho tiêu đề lớn nhất):

```html
<main class="container mx-auto px-4 py-8">
    <article class="max-w-4xl mx-auto">
        <!-- Thẻ H1 duy nhất trên trang bài viết chi tiết -->
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
            <?php echo htmlspecialchars($post['title']); ?>
        </h1>
        
        <!-- Metadata hiển thị cho người đọc -->
        <div class="flex items-center gap-4 text-sm text-slate-500 mb-8 border-b border-slate-100 pb-4">
            <span>Tác giả: <strong><?php echo htmlspecialchars($post['author_name'] ?? 'Ban biên tập'); ?></strong></span>
            <span>·</span>
            <span>Ngày đăng: <?php echo date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])); ?></span>
            <span>·</span>
            <span>Danh mục: <a href="/blog/<?php echo $post['category_slug'] ?? ''; ?>" class="text-brand-600 font-semibold"><?php echo htmlspecialchars($post['category_name'] ?? 'Tin tức'); ?></a></span>
        </div>

        <!-- Render Nội dung HTML từ Database -->
        <!-- class "prose" của Tailwind CSS Typography sẽ tự động định dạng đẹp mắt cho H2, H3, p, table, ul/ol -->
        <div class="prose prose-slate max-w-none prose-headings:font-bold prose-a:text-brand-600 hover:prose-a:underline">
            <?php echo $post['content']; ?>
        </div>
    </article>
</main>
```
