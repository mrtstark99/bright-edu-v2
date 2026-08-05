<?php
require_once 'config/config.php';

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('Location: /blog');
    exit;
}

$db = Database::getInstance();

// Get post
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name, u.full_name as author_name
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN users u ON p.author_id = u.id
    WHERE p.slug = ? AND p.status = 'published'
    LIMIT 1
");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    require_once 'pages/404.php';
    exit;
}

// Update view count
$stmt = $db->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
$stmt->execute([$post['id']]);

// Get related posts
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.category_id = ? AND p.id != ? AND p.status = 'published'
    ORDER BY p.published_at DESC
    LIMIT 3
");
$stmt->execute([$post['category_id'], $post['id']]);
$related_posts = $stmt->fetchAll();

$processed_post = buildPostTableOfContents($post['content']);
$post_content = $processed_post['content'];
$toc_items = $processed_post['items'];
$page_title = seoTitle($post['meta_title'] ?: $post['title']);
$description_source = $post['meta_description'] ?: getExcerpt($post['content'], 105);
$page_description = seoDescription($description_source . ' Xem hướng dẫn và nhận tư vấn từ Bright Education.');
$page_keywords = $post['meta_keywords'];
$page_image = getPostImage($post['featured_image']) ?: APP_URL . '/assets/images/favicon.png';
$og_type = 'article';

include 'includes/header.php';
?>

<main class="pt-24 bg-slate-50 min-h-screen">
  <article>
    <div class="mx-auto max-w-4xl px-4 sm:px-5 py-12 bg-white rounded-3xl border border-slate-200 shadow-soft mt-6">
      
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6 uppercase tracking-wider" aria-label="Breadcrumb">
        <a href="/" class="hover:text-primary transition-colors">Trang chủ</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <a href="/blog" class="hover:text-primary transition-colors">Blog</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-slate-400 truncate max-w-[150px] sm:max-w-xs md:max-w-md"><?php echo htmlspecialchars($post['title']); ?></span>
      </nav>

      <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-sm font-semibold text-midnight bg-midnight/10 px-3 py-1 rounded-full">
            <?php echo htmlspecialchars($post['category_name']); ?>
          </span>
          <span class="text-sm text-muted"><?php echo formatDate($post['published_at']); ?></span>
          <span class="text-sm text-muted">• <?php echo formatNumber($post['views']); ?> lượt xem</span>
        </div>
        
        <h1 class="text-3xl md:text-4xl font-bold text-midnight mb-4">
          <?php echo htmlspecialchars($post['title']); ?>
        </h1>
        
        <?php if ($post['excerpt']): ?>
        <p class="text-lg text-muted">
          <?php echo htmlspecialchars($post['excerpt']); ?>
        </p>
        <?php endif; ?>
        
        <div class="mt-4 text-sm text-muted">
          Bởi <span class="font-medium text-midnight"><?php echo htmlspecialchars($post['author_name']); ?></span>
        </div>
      </div>
      
      <?php if ($post['featured_image']): ?>
      <div class="mb-8">
        <img src="<?php echo getPostImage($post['featured_image']); ?>" 
             alt="<?php echo htmlspecialchars($post['title']); ?>"
             class="w-full rounded-2xl shadow-lg">
      </div>
      <?php endif; ?>

      <?php if (count($toc_items) >= 2): ?>
      <nav class="mb-8 rounded-2xl border border-primary-100 bg-primary-50 p-5 sm:p-6" aria-label="Mục lục bài viết">
        <h2 class="mb-4 text-lg font-bold text-primary">Mục lục bài viết</h2>
        <ol class="space-y-2 text-sm text-slate-700">
          <?php foreach ($toc_items as $item): ?>
          <li class="<?php echo $item['level'] === 3 ? 'ml-5' : ''; ?>">
            <a class="hover:text-primary hover:underline" href="#<?php echo htmlspecialchars($item['id']); ?>">
              <?php echo htmlspecialchars($item['label']); ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ol>
      </nav>
      <?php endif; ?>
      
      <div class="prose prose-lg max-w-none">
        <?php echo $post_content; ?>
      </div>

      <aside class="mt-10 rounded-2xl border border-primary-100 bg-primary-50 p-6" aria-label="Tư vấn du học Nhật Bản">
        <h2 class="text-xl font-bold text-primary">Cần lộ trình du học phù hợp?</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">Khám phá các chương trình du học Nhật Bản và nhận tư vấn theo học lực, ngân sách và mục tiêu của bạn.</p>
        <a href="/services" class="mt-4 inline-flex items-center gap-2 font-bold text-primary hover:underline">
          Xem dịch vụ tư vấn du học <i class="bi bi-arrow-right"></i>
        </a>
      </aside>
      
      <!-- Share buttons -->
      <div class="mt-12 pt-8 border-t border-slate-200">
        <p class="text-sm font-semibold text-midnight mb-4">Chia sẻ bài viết:</p>
        <div class="flex gap-3">
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(APP_URL . '/blog/' . $post['slug']); ?>" 
             target="_blank"
             class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Facebook
          </a>
          <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(APP_URL . '/blog/' . $post['slug']); ?>&text=<?php echo urlencode($post['title']); ?>" 
             target="_blank"
             class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">
            Twitter
          </a>
        </div>
      </div>
    </div>
  </article>
  
  <?php if (!empty($related_posts)): ?>
  <section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-5">
      <h2 class="text-2xl font-bold text-midnight mb-8">Bài viết liên quan</h2>
      
      <div class="grid gap-6 md:grid-cols-3">
        <?php foreach ($related_posts as $related): ?>
        <article class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-soft card-hover">
          <?php if ($related['featured_image']): ?>
          <img src="<?php echo getPostImage($related['featured_image']); ?>" 
               alt="<?php echo htmlspecialchars($related['title']); ?>"
               loading="lazy" decoding="async"
               class="w-full h-48 object-cover">
          <?php endif; ?>
          <div class="p-6">
            <h3 class="text-lg font-semibold text-midnight mb-2">
              <?php echo htmlspecialchars($related['title']); ?>
            </h3>
            <p class="text-sm text-muted mb-4">
              <?php echo truncateText($related['excerpt'] ?: strip_tags($related['content']), 100); ?>
            </p>
            <a href="/blog/<?php echo $related['slug']; ?>" class="text-midnight font-semibold hover:text-emerald-600">
              Đọc thêm →
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
