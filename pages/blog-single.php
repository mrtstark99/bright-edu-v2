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
    SELECT p.*, c.name as category_name, c.slug as category_slug, u.full_name as author_name
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN users u ON p.author_id = u.id
    WHERE p.slug = ? AND p.status = 'published' AND p.published_at <= datetime('now', 'localtime')
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
    WHERE p.category_id = ? AND p.id != ? AND p.status = 'published' AND p.published_at <= datetime('now', 'localtime')
    ORDER BY p.published_at DESC
    LIMIT 3
");
$stmt->execute([$post['category_id'], $post['id']]);
$related_posts = $stmt->fetchAll();

// Loại bỏ tiêu đề trùng lặp ở đầu nội dung bài viết nếu trùng với tiêu đề chính
if (preg_match('/^\s*<(h[1-3])\b[^>]*>(.*?)<\/\1>/isu', $post['content'], $matches)) {
    $headingText = trim(strip_tags($matches[2]));
    $mainTitle = trim(html_entity_decode($post['title'], ENT_QUOTES, 'UTF-8'));
    
    $normHeading = preg_replace('/[^a-zA-Z0-9]/', '', mb_strtolower($headingText, 'UTF-8'));
    $normTitle = preg_replace('/[^a-zA-Z0-9]/', '', mb_strtolower($mainTitle, 'UTF-8'));
    
    $normHeading = preg_replace('/202\d/', '', $normHeading);
    $normTitle = preg_replace('/202\d/', '', $normTitle);
    
    if ($normHeading !== '' && (strpos($normTitle, $normHeading) !== false || strpos($normHeading, $normTitle) !== false)) {
        $post['content'] = preg_replace('/^\s*<(h[1-3])\b[^>]*>(.*?)<\/\1>/isu', '', $post['content'], 1);
    }
}

// Loại bỏ khung Mục lục tự sinh thủ công trong nội dung bài viết
$toc_pattern = '/<(h[2-4]|p|div)\b[^>]*>(?:<strong>)?\s*(?:\|\s*)?(?:Mục\s+lục|Table\s+of\s+contents|Table\s+of\s+Content)\s*(?:<\/strong>)?<\/\1>\s*<(ol|ul)\b[^>]*>.*?<\/\2>/isu';
$post['content'] = preg_replace($toc_pattern, '', $post['content']);

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
  <div class="mx-auto max-w-7xl px-4 sm:px-5 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Cột trái: Nội dung chính bài viết (chiếm 8/12 cột) -->
      <article class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 shadow-soft p-6 sm:p-8 md:p-10">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6 uppercase tracking-wider" aria-label="Breadcrumb">
          <a href="/" class="hover:text-primary transition-colors">Trang chủ</a>
          <i class="bi bi-chevron-right text-[10px]"></i>
          <a href="/blog" class="hover:text-primary transition-colors">Blog</a>
          <i class="bi bi-chevron-right text-[10px]"></i>
          <span class="text-slate-400 truncate max-w-[150px] sm:max-w-xs md:max-w-md"><?php echo htmlspecialchars($post['title']); ?></span>
        </nav>

        <!-- Thẻ H1 duy nhất trên trang bài viết chi tiết -->
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
          <?php echo htmlspecialchars($post['title']); ?>
        </h1>
        
        <!-- Metadata hiển thị cho người đọc -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-sm text-slate-500 mb-8 border-b border-slate-100 pb-4">
          <span>Tác giả: <strong><?php echo htmlspecialchars($post['author_name'] ?? 'Ban biên tập'); ?></strong></span>
          <span>·</span>
          <span>Ngày đăng: <?php echo date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])); ?></span>
          <span>·</span>
          <span>Danh mục: <a href="/blog?category=<?php echo urlencode($post['category_slug'] ?? ''); ?>" class="text-primary-600 font-semibold"><?php echo htmlspecialchars($post['category_name'] ?? 'Tin tức'); ?></a></span>
          <span>·</span>
          <span>Lượt xem: <?php echo formatNumber($post['views']); ?></span>
        </div>

        <?php if ($post['excerpt']): ?>
        <p class="text-lg text-slate-600 mb-6 italic leading-relaxed">
          <?php echo htmlspecialchars($post['excerpt']); ?>
        </p>
        <?php endif; ?>
        
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
        
        <!-- Render Nội dung HTML từ Database với định dạng prose -->
        <div class="prose prose-slate max-w-none prose-headings:font-bold prose-a:text-primary-600 hover:prose-a:underline">
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
      </article>

      <!-- Cột phải: Sidebar (chiếm 4/12 cột) -->
      <aside class="lg:col-span-4 space-y-8">
        
        <!-- Box đăng ký tư vấn du học (CTA) -->
        <div class="bg-gradient-to-br from-slate-900 to-[#0d243e] text-white rounded-3xl border border-primary/20 shadow-soft p-6 sm:p-8 relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
          <div class="relative z-10">
            <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-white/90 mb-4 border border-white/10 uppercase tracking-widest">
              Tư vấn miễn phí
            </span>
            <h2 class="text-2xl font-black font-display tracking-tight leading-tight">Cần Lộ Trình Du Học Phù Hợp?</h2>
            <p class="mt-3 text-sm leading-relaxed text-white/80">
              Hãy để các chuyên gia của Bright Education đồng hành, hỗ trợ bạn hoạch định chi tiết chọn trường và săn học bổng du học Nhật Bản tối ưu.
            </p>
            <a href="/contact" class="mt-6 inline-flex items-center justify-center gap-2 w-full py-3 bg-white text-midnight hover:bg-slate-100 font-bold rounded-2xl shadow-md transition">
              Đăng ký tư vấn ngay <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
        
        <!-- Box danh sách danh mục blog -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-soft p-6">
          <h3 class="text-lg font-bold text-midnight mb-4 flex items-center gap-2">
            <i class="bi bi-grid-fill text-primary"></i> Chuyên mục
          </h3>
          <ul class="space-y-2.5">
            <?php
            $stmtCat = $db->prepare("SELECT * FROM categories WHERE status = 'active' ORDER BY name");
            $stmtCat->execute();
            $sidebar_categories = $stmtCat->fetchAll();
            foreach ($sidebar_categories as $s_cat):
              $isActive = ($post['category_id'] === $s_cat['id']);
            ?>
            <li>
              <a href="/blog?category=<?php echo $s_cat['slug']; ?>" 
                 class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium transition <?php echo $isActive ? 'bg-primary/5 text-primary font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-midnight'; ?>">
                <span><?php echo htmlspecialchars($s_cat['name']); ?></span>
                <i class="bi bi-chevron-right text-xs <?php echo $isActive ? 'text-primary' : 'text-slate-400'; ?>"></i>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        
        <!-- Box tin mới nhất -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-soft p-6">
          <h3 class="text-lg font-bold text-midnight mb-4 flex items-center gap-2">
            <i class="bi bi-lightning-fill text-amber-500"></i> Bài viết mới nhất
          </h3>
          <ul class="space-y-4">
            <?php
            $stmtLatest = $db->prepare("
              SELECT id, title, slug, published_at, featured_image 
              FROM posts 
              WHERE status = 'published' AND id != ? AND published_at <= datetime('now', 'localtime')
              ORDER BY published_at DESC 
              LIMIT 4
            ");
            $stmtLatest->execute([$post['id']]);
            $latest_posts = $stmtLatest->fetchAll();
            foreach ($latest_posts as $l_post):
            ?>
            <li class="flex gap-3.5 group">
              <?php if ($l_post['featured_image']): ?>
              <a href="/blog/<?php echo $l_post['slug']; ?>" class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 border border-slate-100">
                <img src="<?php echo getPostImage($l_post['featured_image']); ?>" 
                     alt="<?php echo htmlspecialchars($l_post['title']); ?>" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
              </a>
              <?php endif; ?>
              <div class="flex flex-col justify-center min-w-0">
                <h4 class="text-sm font-bold text-midnight leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                  <a href="/blog/<?php echo $l_post['slug']; ?>">
                    <?php echo htmlspecialchars($l_post['title']); ?>
                  </a>
                </h4>
                <span class="text-[11px] text-slate-400 mt-1"><?php echo date('d/m/Y', strtotime($l_post['published_at'])); ?></span>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </aside>
    </div>
  </div>

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
