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

$page_title = $post['meta_title'] ?: $post['title'] . ' - Bright Education';
$page_description = $post['meta_description'] ?: getExcerpt($post['content']);
$page_keywords = $post['meta_keywords'];

include 'includes/header.php';
?>

<main class="pt-24">
  <article>
    <div class="mx-auto max-w-4xl px-4 sm:px-5 py-12">
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
      
      <div class="prose prose-lg max-w-none">
        <?php echo $post['content']; ?>
      </div>
      
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
