<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $page_title ?? DEFAULT_META_TITLE; ?></title>
  <meta name="description" content="<?php echo $page_description ?? DEFAULT_META_DESC; ?>" />
  <link rel="icon" type="image/png" href="/assets/images/favicon.png" />
  
  <!-- Fonts: Inter for body, Quicksand for headings (BrightHome style) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome/Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
  <link rel="stylesheet" href="/assets/css/components.css">
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
            <div class="relative group nav-dropdown-trigger hidden sm:block">
                <button class="flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-primary transition-colors">
                    <i class="bi bi-person-circle text-lg"></i>
                    <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'Tài khoản') ?></span>
                    <i class="bi bi-chevron-down text-xs nav-chevron transition-transform"></i>
                </button>
                <div class="nav-dropdown absolute right-0 top-full mt-2 w-48 rounded-2xl bg-white p-2 shadow-tinted border border-slate-100 z-50">
                    <?php if (isAdmin() || isEditor()): ?>
                        <a href="/admin" class="dd-link"><i class="bi bi-shield-check text-primary"></i> Quản trị</a>
                    <?php endif; ?>
                    <a href="/profile" class="dd-link"><i class="bi bi-person text-primary"></i> Hồ sơ</a>
                    <a href="/logout" class="dd-link text-red-600 hover:bg-red-50 hover:text-red-700"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
                </div>
            </div>
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
  <div id="mobile-menu" class="fixed inset-0 z-[60] bg-white flex flex-col opacity-0 pointer-events-none transition-all duration-300 translate-y-[-10px] overflow-y-auto">
      <!-- Header row -->
      <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-slate-100">
        <a href="/" class="flex items-center">
          <img src="<?= APP_URL ?>/assets/images/logo.svg" alt="Bright Education" class="h-9 w-auto">
        </a>
        <button id="mobile-menu-close" class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 text-primary hover:bg-slate-200 transition-colors" aria-label="Close Menu">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
      </div>

      <nav class="flex-1 px-4 py-5 space-y-1">
        <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[17px] font-bold text-midnight hover:bg-slate-50 transition-colors" href="/">
          <i class="bi bi-house-fill text-primary w-5 text-center"></i> Trang chủ
        </a>

        <!-- Du học group -->
        <div>
          <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 px-4 pt-2 pb-2">— Danh mục Du học</p>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/services">
            <i class="bi bi-briefcase text-primary w-5 text-center text-sm"></i> Dịch vụ tư vấn
          </a>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/process">
            <i class="bi bi-arrow-right-circle text-primary w-5 text-center text-sm"></i> Quy trình du học
          </a>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/documents">
            <i class="bi bi-file-earmark-text text-primary w-5 text-center text-sm"></i> Chuẩn bị hồ sơ
          </a>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/courses">
            <i class="bi bi-journal-bookmark text-primary w-5 text-center text-sm"></i> Khóa học tiếng Nhật
          </a>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/schools">
            <i class="bi bi-building text-primary w-5 text-center text-sm"></i> Trường Nhật Ngữ
          </a>
        </div>

        <!-- Danh mục group -->
        <div>
          <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 px-4 pt-2 pb-2">— Danh mục</p>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/blog">
            <i class="bi bi-newspaper text-primary w-5 text-center text-sm"></i> Blog & Cẩm nang
          </a>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/about">
            <i class="bi bi-building text-primary w-5 text-center text-sm"></i> Về Bright Education
          </a>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/qa">
            <i class="bi bi-chat-text text-primary w-5 text-center text-sm"></i> Hỏi đáp
          </a>
        </div>

        <!-- CTA group -->
        <div>
          <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 px-4 pt-4 pb-2">Liên hệ</p>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/consultation">
            <i class="bi bi-calendar-check text-primary w-5 text-center text-sm"></i> Đặt lịch tư vấn Zoom
          </a>
          <?php if (isLoggedIn() && (isAdmin() || isEditor())): ?>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-primary hover:bg-primary-50 transition-colors" href="/admin">
            <i class="bi bi-shield-check w-5 text-center text-sm"></i> Quản trị
          </a>
          <?php endif; ?>
        </div>
      </nav>

      <div class="px-4 pb-8 pt-2">
        <a class="mobile-link flex items-center justify-center gap-2 w-full bg-primary text-white rounded-2xl py-4 text-[16px] font-bold hover:bg-ink transition-colors shadow-medium" href="/consultation">
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
