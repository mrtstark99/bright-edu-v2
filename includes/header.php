<?php
// Compute canonical URL dynamically and supply unique defaults for static pages.
$request_uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$canonical_url = APP_URL . $request_uri;
$static_meta_descriptions = [
    '/' => 'Tư vấn du học Nhật Bản, chọn trường, học bổng, hồ sơ và visa cùng Bright Education. Đăng ký tư vấn miễn phí.',
    '/services' => 'Khám phá chương trình du học Nhật ngữ, Senmon, đại học và học bổng. Nhận lộ trình phù hợp từ Bright Education.',
    '/schools' => 'Tra cứu trường Nhật ngữ theo khu vực, học phí và kỳ tuyển sinh. Liên hệ Bright Education để được tư vấn chọn trường.',
    '/courses' => 'Khóa học tiếng Nhật từ cơ bản đến luyện thi JLPT, hỗ trợ lộ trình du học. Đăng ký học thử cùng Bright Education.',
    '/process' => 'Tìm hiểu quy trình du học Nhật Bản từ chọn trường, chuẩn bị hồ sơ, xin COE đến visa và xuất cảnh.',
    '/documents' => 'Danh sách hồ sơ du học Nhật Bản cần chuẩn bị cho học sinh và người bảo lãnh. Nhận hướng dẫn từ Bright Education.',
    '/cost' => 'Dự tính học phí, sinh hoạt phí và chi phí hồ sơ du học Nhật Bản. Nhận bảng chi phí cá nhân hóa miễn phí.',
    '/blog' => 'Cẩm nang du học Nhật Bản về trường học, visa, học bổng, việc làm thêm và cuộc sống dành cho du học sinh.',
    '/contact' => 'Liên hệ Bright Education để được tư vấn lộ trình, chi phí, trường học và học bổng du học Nhật Bản.',
];
if (empty($page_description) && isset($static_meta_descriptions[$request_uri])) {
    $page_description = $static_meta_descriptions[$request_uri];
}
$page_description = seoDescription($page_description ?? '', DEFAULT_META_DESC);
$page_title = trim((string)($page_title ?? DEFAULT_META_TITLE));
$ga_id = trim((string)getSetting('ga_id', ''));
$gsc_verification = trim((string)getSetting('gsc_verification', ''));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($page_title ?? DEFAULT_META_TITLE); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($page_description ?? DEFAULT_META_DESC); ?>" />
  <?php if ($gsc_verification !== ''): ?>
  <meta name="google-site-verification" content="<?php echo htmlspecialchars($gsc_verification); ?>" />
  <?php endif; ?>
  <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords ?? DEFAULT_META_KEYWORDS); ?>" />
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>" />
  <link rel="icon" type="image/png" href="/assets/images/favicon.png" />
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="<?php echo $og_type ?? 'website'; ?>" />
  <meta property="og:title" content="<?php echo htmlspecialchars($page_title ?? DEFAULT_META_TITLE); ?>" />
  <meta property="og:description" content="<?php echo htmlspecialchars($page_description ?? DEFAULT_META_DESC); ?>" />
  <meta property="og:image" content="<?php echo htmlspecialchars($page_image ?? APP_URL . '/assets/images/favicon.png'); ?>" />
  <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>" />
  <meta property="og:site_name" content="Bright Education" />

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="<?php echo htmlspecialchars($canonical_url); ?>" />
  <meta property="twitter:title" content="<?php echo htmlspecialchars($page_title ?? DEFAULT_META_TITLE); ?>" />
  <meta property="twitter:description" content="<?php echo htmlspecialchars($page_description ?? DEFAULT_META_DESC); ?>" />
  <meta property="twitter:image" content="<?php echo htmlspecialchars($page_image ?? APP_URL . '/assets/images/favicon.png'); ?>" />

  <!-- Schema.org JSON-LD -->
  <script type="application/ld+json">
  <?php
  $schemas = [
      [
          "@context" => "https://schema.org",
          "@type" => "EducationalOrganization",
          "name" => "Bright Education",
          "url" => APP_URL,
          "logo" => APP_URL . "/assets/images/logo.svg",
          "description" => DEFAULT_META_DESC,
          "address" => [
              "@type" => "PostalAddress",
              "streetAddress" => getSetting('site_address', ''),
              "addressCountry" => "VN"
          ],
          "contactPoint" => [
              "@type" => "ContactPoint",
              "telephone" => "+84 0971044576",
              "contactType" => "customer service",
              "areaServed" => "VN",
              "availableLanguage" => "Vietnamese"
          ]
      ]
  ];

  if (isset($post) && !empty($post)) {
      $schemas[] = [
          "@context" => "https://schema.org",
          "@type" => "BlogPosting",
          "headline" => $post['title'],
          "description" => $page_description,
          "image" => $page_image ?? (APP_URL . '/assets/images/favicon.png'),
          "datePublished" => date('c', strtotime($post['published_at'] ?? $post['created_at'])),
          "dateModified" => date('c', strtotime($post['updated_at'])),
          "author" => [
              "@type" => "Person",
              "name" => $post['author_name'] ?? 'Bright Education'
          ],
          "publisher" => [
              "@type" => "Organization",
              "name" => "Bright Education",
              "logo" => [
                  "@type" => "ImageObject",
                  "url" => APP_URL . "/assets/images/logo.svg"
              ]
          ]
      ];
  }

  if (isset($data) && isset($data['title'])) {
      $schemas[] = [
          "@context" => "https://schema.org",
          "@type" => "Service",
          "name" => $data['title'],
          "description" => $data['subtitle'] ?? '',
          "serviceType" => $data['title'],
          "areaServed" => ["VN", "JP"],
          "url" => $canonical_url,
          "provider" => [
              "@type" => "Organization",
              "name" => "Bright Education",
              "sameAs" => APP_URL
          ]
      ];
  }

  if (isset($faq_schema) && !empty($faq_schema)) {
      $schemas[] = $faq_schema;
  }

  echo json_encode($schemas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  ?>
  </script>

  <?php if (preg_match('/^G-[A-Z0-9]+$/i', $ga_id)): ?>
  <!-- Google Analytics 4 -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo rawurlencode($ga_id); ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', <?php echo json_encode(strtoupper($ga_id)); ?>);
  </script>
  <?php endif; ?>

  <!-- Fonts: Inter for body, Quicksand for headings (BrightHome style) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome/Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Optimize CDN Loading -->
  <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { 
            sans: ['Inter', 'ui-sans-serif', 'system-ui'],
            display: ['Quicksand', 'ui-sans-serif', 'system-ui']
          },
          colors: {
            primary: {
              DEFAULT: '#0d243e',
              50: '#f2f5f9',
              100: '#e1e8f0',
              200: '#c5d3df',
              300: '#9bb7ca',
              400: '#6b92af',
              500: '#487596',
              600: '#345b7b',
              700: '#2a4964',
              800: '#253e54',
              900: '#0d243e',
            },
            sage: { 50: '#f2f5f9', 100: '#e1e8f0', 200: '#c5d3df', 300: '#9bb7ca', 400: '#345b7b', 500: '#0d243e', 600: '#0d243e', 900: '#0d243e' },
            sakura: { 50: '#f2f5f9', 100: '#e1e8f0', 200: '#c5d3df', 300: '#9bb7ca', 400: '#345b7b', 500: '#0d243e', 600: '#0d243e', 900: '#0d243e' },
            sand: { 50: '#ffffff', 100: '#f8fafc', 200: '#e2e8f0' },
            midnight: '#0d243e', 
            ink: '#111827',
            muted: '#6b7280',
            rice: '#ffffff'
          },
          boxShadow: {
            'soft': '0 4px 20px -2px rgba(1, 53, 103, 0.05)',
            'medium': '0 12px 32px -4px rgba(1, 53, 103, 0.08)',
            'hard': '0 24px 48px -12px rgba(1, 53, 103, 0.12)',
            'tinted': '0 20px 40px -8px rgba(1, 53, 103, 0.15)',
          },
          borderRadius: {
            '4xl': '2rem',
            '5xl': '2.5rem',
            'blob': '40% 60% 70% 30% / 40% 50% 60% 50%',
          }
        }
      }
    }
  </script>
  <!-- Global component styles (after Tailwind to override correctly) -->
  <link rel="stylesheet" href="/assets/css/components.css?v=<?= filemtime(APP_ROOT . '/assets/css/components.css') ?>">
  <style>
    /* Đảm bảo heading dùng Quicksand (override Tailwind reset) */
    h1, h2, h3, h4, h5, h6 { font-family: 'Quicksand', ui-sans-serif, system-ui, sans-serif !important; }
    table th, table td { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
  </style>
</head>
<body class="text-ink font-sans antialiased bg-rice selection:bg-sage-200 selection:text-sage-900">
  
  <header class="group fixed inset-x-0 top-0 z-50 transition-all duration-300" id="main-header">
    <div class="w-full h-20 border-b border-gray-100/50 bg-white/95 backdrop-blur-md transition-all duration-300" id="header-inner">
    <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-5 lg:px-8">
      <a class="flex items-center group mr-10" href="/">
        <img src="<?= APP_URL ?>/assets/images/logo.svg" alt="Bright Education" class="h-14 w-auto transition-transform group-hover:scale-105">
      </a>
      
      <nav class="hidden items-center gap-1 text-[15px] font-medium text-muted md:flex">
        <a class="nav-link" href="/">Trang chủ</a>

        <a class="nav-link" href="/qa">Hỏi đáp</a>

        <a class="nav-link" href="/services">Dịch vụ</a>
        <a class="nav-link" href="/courses">Khóa học</a>
        <a class="nav-link" href="/blog">Blog</a>
        <a class="nav-link" href="/consultation">Tư vấn Zoom</a>
        <?php if (isLoggedIn() && (isAdmin() || isEditor())): ?>
          <a class="nav-link !text-primary font-semibold" href="/admin">Quản trị</a>
        <?php endif; ?>
      </nav>

      <!-- Nav styles loaded via /assets/css/components.css -->
      
      <div class="flex items-center gap-3">
        <?php if (isLoggedIn()): ?>
            <?php if (isAdmin() || isEditor()): ?>
            <!-- Admin/Editor: keep dropdown for quick admin panel access -->
            <div class="relative group nav-dropdown-trigger hidden sm:block">
                <button class="flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-primary transition-colors">
                    <i class="bi bi-person-circle text-lg"></i>
                    <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'Tài khoản') ?></span>
                    <i class="bi bi-chevron-down text-xs nav-chevron transition-transform"></i>
                </button>
                <div class="nav-dropdown absolute right-0 top-full mt-2 w-48 rounded-2xl bg-white p-2 shadow-tinted border border-slate-100 z-50">
                    <a href="/admin" class="dd-link"><i class="bi bi-shield-check text-primary"></i> Quản trị</a>
                    <a href="/profile" class="dd-link"><i class="bi bi-person text-primary"></i> Hồ sơ</a>
                    <a href="/logout" class="dd-link text-red-600 hover:bg-red-50 hover:text-red-700"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
                </div>
            </div>
            <?php else: ?>
            <!-- Regular user: 1 click goes directly to profile, no popup -->
            <a href="/profile" class="hidden sm:flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-primary transition-colors">
                <i class="bi bi-person-circle text-lg"></i>
                <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'Tài khoản') ?></span>
            </a>
            <?php endif; ?>

        <?php else: ?>
            <a href="/login" class="hidden sm:inline-flex rounded-full px-4 py-2 text-sm font-semibold text-primary bg-primary-50 hover:bg-primary-100 transition-colors">Đăng nhập</a>
        <?php endif; ?>
        
        <a class="hidden rounded-full px-5 py-2.5 text-sm font-semibold text-white sm:inline-flex btn-primary" href="/contact">
          Tư vấn miễn phí
        </a>
        <!-- Mobile Menu Toggle Button -->
        <button id="mobile-menu-toggle" class="flex md:hidden h-10 w-10 items-center justify-center rounded-lg bg-slate-50 text-primary border border-slate-200 transition-colors hover:bg-slate-100" aria-label="Toggle Menu">
          <i class="bi bi-list text-2xl"></i>
        </button>
      </div>
    </div>
    </div>
  </header>

  <!-- Mobile Menu Overlay -->
  <div id="mobile-menu" class="fixed inset-0 z-[60] bg-slate-50/98 backdrop-blur-xl flex flex-col opacity-0 pointer-events-none transition-all duration-300 translate-y-[-10px] overflow-y-auto">
      <!-- Header row -->
      <div class="flex items-center justify-between px-6 py-4 bg-white/80 backdrop-blur-md sticky top-0 z-10 border-b border-slate-100/80 shadow-soft">
        <a href="/" class="flex items-center">
          <img src="<?= APP_URL ?>/assets/images/logo.svg" alt="Bright Education" class="h-10 w-auto">
        </a>
        <button id="mobile-menu-close" class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 text-primary hover:bg-primary hover:text-white transition-all duration-300" aria-label="Close Menu">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
      </div>

      <div class="flex-1 px-5 py-6 space-y-5">
        <!-- User Profile / Authentication Card -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-soft">
          <?php if (isLoggedIn()): ?>
            <div class="flex items-center gap-3.5 mb-4">
              <div class="h-12 w-12 rounded-2xl bg-primary text-white flex items-center justify-center font-bold text-xl shadow-medium font-display uppercase">
                <?= mb_substr($_SESSION['user_name'] ?? 'T', 0, 1, 'utf-8') ?>
              </div>
              <div>
                <p class="text-[15px] font-bold text-midnight leading-tight"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Tài khoản') ?></p>
                <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-primary-50 text-primary">
                  <?= htmlspecialchars($_SESSION['role'] ?? 'Học viên') ?>
                </span>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-2.5">
              <a href="/profile" class="mobile-link flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 text-xs font-bold text-slate-700 transition-all">
                <i class="bi bi-person text-sm"></i> Hồ sơ
              </a>
              <a href="/logout" class="mobile-link flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-red-50 hover:bg-red-100 text-xs font-bold text-red-600 transition-all">
                <i class="bi bi-box-arrow-right text-sm"></i> Đăng xuất
              </a>
            </div>
          <?php else: ?>
            <div class="space-y-3">
              <div class="text-left">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Tài khoản</p>
                <p class="text-[13px] font-medium text-slate-500">Đăng nhập để cập nhật lộ trình du học của riêng bạn</p>
              </div>
              <div class="grid grid-cols-2 gap-2.5">
                <a href="/login" class="mobile-link flex items-center justify-center py-3 px-4 rounded-xl bg-primary-50 text-xs font-bold text-primary hover:bg-primary-100 transition-all text-center">
                  Đăng nhập
                </a>
                <a href="/register" class="mobile-link flex items-center justify-center py-3 px-4 rounded-xl bg-primary text-xs font-bold text-white hover:bg-midnight transition-all text-center">
                  Đăng ký
                </a>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <nav class="space-y-4">
          <!-- Du học group -->
          <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-soft">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 px-3 pb-3 border-b border-slate-50 mb-2">Du học Nhật Bản</p>
            <div class="space-y-1">
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/services">
                <i class="bi bi-briefcase text-primary text-base w-5 text-center"></i> Dịch vụ tư vấn
              </a>
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/process">
                <i class="bi bi-arrow-right-circle text-primary text-base w-5 text-center"></i> Quy trình du học
              </a>
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/documents">
                <i class="bi bi-file-earmark-text text-primary text-base w-5 text-center"></i> Chuẩn bị hồ sơ
              </a>
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/courses">
                <i class="bi bi-journal-bookmark text-primary text-base w-5 text-center"></i> Khóa học tiếng Nhật
              </a>
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/schools">
                <i class="bi bi-building text-primary text-base w-5 text-center"></i> Trường Nhật Ngữ
              </a>
            </div>
          </div>

          <!-- Danh mục group -->
          <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-soft">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 px-3 pb-3 border-b border-slate-50 mb-2">Khám phá</p>
            <div class="space-y-1">
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/">
                <i class="bi bi-house-fill text-primary text-base w-5 text-center"></i> Trang chủ
              </a>
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/blog">
                <i class="bi bi-newspaper text-primary text-base w-5 text-center"></i> Blog & Cẩm nang
              </a>
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/about">
                <i class="bi bi-info-square text-primary text-base w-5 text-center"></i> Về Bright Education
              </a>
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/qa">
                <i class="bi bi-chat-text text-primary text-base w-5 text-center"></i> Hỏi đáp
              </a>
            </div>
          </div>

          <!-- Liên hệ & Admin group -->
          <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-soft">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 px-3 pb-3 border-b border-slate-50 mb-2">Hỗ trợ & Quản trị</p>
            <div class="space-y-1">
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-all" href="/consultation">
                <i class="bi bi-calendar-check text-primary text-base w-5 text-center"></i> Đặt lịch tư vấn Zoom
              </a>
              <?php if (isLoggedIn() && (isAdmin() || isEditor())): ?>
              <a class="mobile-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-semibold text-emerald-600 bg-emerald-50/50 hover:bg-emerald-50 transition-all" href="/admin">
                <i class="bi bi-shield-check text-emerald-600 text-base w-5 text-center"></i> Bảng điều khiển Admin
              </a>
              <?php endif; ?>
            </div>
          </div>
        </nav>
      </div>

      <!-- Action Footer -->
      <div class="p-5 bg-white/50 backdrop-blur-md border-t border-slate-100 sticky bottom-0 z-10">
        <a class="mobile-link flex items-center justify-center gap-2 w-full bg-primary text-white rounded-2xl py-4 text-[15px] font-bold hover:bg-midnight transition-colors shadow-medium" href="/consultation">
          <i class="bi bi-camera-video-fill"></i> Đặt lịch tư vấn miễn phí
        </a>
      </div>
  </div>


  <script>
    // Header scroll effect (Full width, shrink height, expand on hover)
    window.addEventListener('scroll', () => {
      const header = document.getElementById('main-header');
      const inner = document.getElementById('header-inner');
      
      if (window.scrollY > 20) {
        // Scrolled down -> Shrink & Transparent
        header.classList.add('scrolled');
        inner.classList.remove('h-20', 'bg-white/95');
        inner.classList.add('h-14', 'bg-white/60', 'shadow-soft', 'group-hover:h-20', 'group-hover:bg-white/95');
      } else {
        // At top -> Full height & Solid
        header.classList.remove('scrolled');
        inner.classList.add('h-20', 'bg-white/95');
        inner.classList.remove('h-14', 'bg-white/60', 'shadow-soft', 'group-hover:h-20', 'group-hover:bg-white/95');
      }
    });

    // Simple Reveal Animation
    document.addEventListener("DOMContentLoaded", () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
    });

    // Mobile Menu Toggle Logic
    document.addEventListener("DOMContentLoaded", () => {
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const closeBtn = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const links = mobileMenu?.querySelectorAll('.mobile-link');

        function openMenu() {
            if (!mobileMenu) return;
            mobileMenu.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-[-10px]');
            mobileMenu.classList.add('opacity-100', 'translate-y-0');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            if (!mobileMenu) return;
            mobileMenu.classList.add('opacity-0', 'pointer-events-none', 'translate-y-[-10px]');
            mobileMenu.classList.remove('opacity-100', 'translate-y-0');
            document.body.style.overflow = '';
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openMenu);
        if (closeBtn) closeBtn.addEventListener('click', closeMenu);
        if (links) {
            links.forEach(link => {
                link.addEventListener('click', closeMenu);
            });
        }
    });
  </script>
