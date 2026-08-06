<?php
require_once 'config/config.php';

$db = Database::getInstance();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = POSTS_PER_PAGE;
$offset = ($page - 1) * $per_page;

// Category filter
$category_slug = $_GET['category'] ?? '';
$where = "WHERE p.status = 'published' AND p.published_at <= datetime('now', 'localtime')";
$params = [];

if ($category_slug) {
    $where .= " AND c.slug = ?";
    $params[] = $category_slug;
}

// Get total posts
$stmt = $db->prepare("
    SELECT COUNT(*) as total 
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    $where
");
$stmt->execute($params);
$total = $stmt->fetch()['total'];

// Get posts
$sql = "
    SELECT p.*, c.name as category_name, c.slug as category_slug, u.full_name as author_name
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN users u ON p.author_id = u.id
    $where
    ORDER BY p.published_at DESC
    LIMIT $per_page OFFSET $offset
";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

// Get categories
$stmt = $db->prepare("SELECT * FROM categories WHERE status = 'active' ORDER BY name");
$stmt->execute();
$categories = $stmt->fetchAll();

$page_title = 'Blog - Bright Education';
include 'includes/header.php';
?>

<main class="pt-20 bg-slate-50 min-h-screen">
  
  <!-- Hero Section -->
  <section class="bg-primary text-white pt-16 pb-24 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="mx-auto max-w-7xl px-5 lg:px-8 relative z-10 text-center">
      
      <!-- Breadcrumb -->
      <nav class="flex items-center justify-center gap-2 text-xs font-semibold text-white/60 mb-6 uppercase tracking-wider" aria-label="Breadcrumb">
        <a href="/" class="hover:text-white transition-colors">Trang chủ</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-white">Blog & Tin tức</span>
      </nav>

      <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3.5 py-1 text-xs font-bold text-white/90 mb-4 border border-white/10 uppercase tracking-widest">
        <i class="bi bi-journal-text text-[10px] text-amber-400"></i> Góc chia sẻ kiến thức
      </span>
      <h1 class="text-3xl sm:text-[2.75rem] font-black font-display tracking-tight leading-tight mb-4">Blog & Tin Tức Du Học Nhật Bản</h1>
      <p class="text-base sm:text-lg text-white/85 max-w-3xl leading-relaxed mx-auto">
        Cập nhật tin tức mới nhất, kinh nghiệm chuẩn bị hồ sơ du học, học bổng và cuộc sống tại Nhật ngữ, Senmon, Đại học.
      </p>
    </div>
  </section>

  <!-- Content Section -->
  <section class="py-12 sm:py-16 -mt-10 relative z-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-5">
      
      <!-- Filters -->
      <div class="flex flex-wrap items-center justify-center gap-3 mb-12">
        <a href="/blog" class="inline-flex px-5 py-2.5 rounded-full text-[13px] uppercase tracking-wide font-bold transition-all <?php echo !$category_slug ? 'bg-midnight text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 hover:border-slate-300'; ?>">
          Tất cả bài viết
        </a>
        <?php foreach ($categories as $cat): ?>
        <a href="/blog?category=<?php echo $cat['slug']; ?>" 
           class="inline-flex px-5 py-2.5 rounded-full text-[13px] uppercase tracking-wide font-bold transition-all <?php echo $category_slug === $cat['slug'] ? 'bg-sage-600 text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:bg-sage-50 hover:text-sage-700 hover:border-sage-200'; ?>">
          <?php echo htmlspecialchars($cat['name']); ?>
        </a>
        <?php endforeach; ?>
      </div>

      <?php if (empty($posts)): ?>
        <div class="text-center py-20 bg-white rounded-[2.5rem] border border-slate-100 shadow-soft">
          <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
            <i class="bi bi-journal-x text-3xl"></i>
          </div>
          <h3 class="text-xl font-bold text-midnight font-display mb-2">Chưa có bài viết nào</h3>
          <p class="text-muted">Chúng tôi đang cập nhật nội dung cho chuyên mục này. Vui lòng quay lại sau.</p>
        </div>
      <?php else: ?>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($posts as $post): ?>
          <article class="group bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-soft hover:shadow-medium transition-all duration-300 card-hover flex flex-col h-full">
            <div class="relative overflow-hidden aspect-[4/3]">
              <?php if ($post['featured_image']): ?>
              <img src="<?php echo getPostImage($post['featured_image']); ?>" 
                   alt="<?php echo htmlspecialchars($post['title']); ?>"
                   loading="lazy" decoding="async"
                   class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
              <?php else: ?>
              <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                  <i class="bi bi-image text-3xl text-slate-300"></i>
              </div>
              <?php endif; ?>
              
              <!-- Category Badge overlay -->
              <div class="absolute top-4 left-4 z-10">
                <span class="inline-block rounded-full bg-white/95 backdrop-blur-sm px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-primary shadow-sm">
                  <?php echo htmlspecialchars($post['category_name']); ?>
                </span>
              </div>
            </div>
            
            <div class="p-6 sm:p-8 flex flex-col flex-grow">
              <div class="flex items-center gap-3 mb-4 text-[13px] text-muted font-medium">
                <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i> <?php echo date('d/m/Y', strtotime($post['published_at'])); ?></span>
                <?php if($post['author_name']): ?>
                <span class="text-slate-300">•</span>
                <span class="flex items-center gap-1"><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($post['author_name']); ?></span>
                <?php endif; ?>
              </div>
              
              <h2 class="text-xl font-bold text-midnight font-display mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                <a href="/blog/<?php echo $post['slug']; ?>">
                  <?php echo htmlspecialchars($post['title']); ?>
                </a>
              </h2>
              
              <p class="text-[15px] text-muted mb-6 line-clamp-3 leading-relaxed flex-grow">
                <?php echo truncateText($post['excerpt'] ?: strip_tags($post['content']), 150); ?>
              </p>
              
              <div class="mt-auto pt-5 border-t border-slate-100">
                <a href="/blog/<?php echo $post['slug']; ?>" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-ink transition-colors">
                  Đọc tiếp <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                </a>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
          <?php echo paginate($total, $page, $per_page, '/blog'); ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
