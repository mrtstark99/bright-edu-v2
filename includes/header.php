<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $page_title ?? DEFAULT_META_TITLE; ?></title>
  <meta name="description" content="<?php echo $page_description ?? DEFAULT_META_DESC; ?>" />
  
  <!-- Fonts: Inter for body, Quicksand for headings (BrightHome style) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome/Bootstrap Icons if needed -->
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
  <style>
    html { scroll-behavior: smooth; }
    body { background-color: #fafafa; }
    
    /* Typography Utilities */
    h1, h2, h3, h4, h5, h6 { font-family: 'Quicksand', sans-serif; letter-spacing: -0.02em; }
    
    /* Animations */
    .reveal { opacity: 0; transform: translateY(30px); }
    .reveal.show { opacity: 1; transform: translateY(0); transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1); }
    
    /* Card Interactions */
    .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .card-hover:hover { transform: translateY(-6px); box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.1); }
    
    /* Buttons */
    .btn-primary { 
        position: relative; overflow: hidden; transition: all 0.3s ease; 
        background: #0d243e;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(13, 36, 62, 0.4);
    }
    .btn-outline {
        transition: all 0.3s ease;
    }
    .btn-outline:hover {
        background-color: #f8fafc;
        border-color: #0d243e;
        color: #0d243e;
    }

    /* Glassmorphism utility */
    .glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }

    /* Soft gradients */
    .bg-mesh {
        background-color: #ffffff;
        background-image: 
            radial-gradient(at 0% 0%, #f1f5f9 0px, transparent 50%),
            radial-gradient(at 100% 0%, #e2e8f0 0px, transparent 50%);
    }

    /* Diverse Frames & Animations */
    .shape-blob {
        animation: morph 8s ease-in-out infinite;
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        transition: all 1s ease-in-out;
    }
    
    @keyframes morph {
        0% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
        50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
    }

    .shape-arch {
        border-radius: 20rem 20rem 2rem 2rem;
    }

    .parallax-bg {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .float-slow {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }
  </style>
</head>
<body class="text-ink font-sans antialiased bg-rice selection:bg-sage-200 selection:text-sage-900">
  
  <header class="group fixed inset-x-0 top-0 z-50 transition-all duration-300" id="main-header">
    <div id="header-inner" class="mx-auto flex h-20 w-full items-center justify-between border-b border-gray-100/50 bg-white/95 px-6 backdrop-blur-md transition-all duration-300 lg:px-12">
      <a class="flex items-center gap-3 group" href="/">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-ink text-sm font-bold text-white shadow-md transition-transform group-hover:scale-105 group-hover:rotate-3">
          BC
        </div>
        <div class="leading-tight">
          <span class="text-base font-bold text-primary tracking-tight font-display">Bright Education</span>
          <span class="block text-[10px] text-sage-600 font-semibold uppercase tracking-[0.2em]">Japan Study</span>
        </div>
      </a>
      
      <nav class="hidden items-center gap-1 text-[15px] font-medium text-muted md:flex">
        <a class="nav-link" href="/">Trang chủ</a>

        <!-- Du học dropdown -->
        <div class="nav-dropdown-trigger relative">
          <button class="nav-link flex items-center gap-1">
            Du học <i class="bi bi-chevron-down nav-chevron text-[11px] transition-transform duration-200"></i>
          </button>
          <div class="nav-dropdown absolute top-full left-1/2 -translate-x-1/2 pt-3 z-50">
            <div class="bg-white rounded-2xl shadow-hard border border-slate-100 p-2 w-56">
              <a href="/services" class="dd-link">
                <i class="bi bi-briefcase text-primary text-sm w-5"></i>
                <span>Dịch vụ tư vấn</span>
              </a>
              <a href="/process" class="dd-link">
                <i class="bi bi-arrow-right-circle text-primary text-sm w-5"></i>
                <span>Quy trình du học</span>
              </a>
              <a href="/documents" class="dd-link">
                <i class="bi bi-file-earmark-text text-primary text-sm w-5"></i>
                <span>Chuẩn bị hồ sơ</span>
              </a>
              <a href="/courses" class="dd-link">
                <i class="bi bi-journal-bookmark text-primary text-sm w-5"></i>
                <span>Khóa học tiếng Nhật</span>
              </a>
              <a href="/schools" class="dd-link">
                <i class="bi bi-building text-primary text-sm w-5"></i>
                <span>Trường Nhật Ngữ</span>
              </a>
            </div>
          </div>
        </div>

        <!-- Khám phá dropdown -->
        <div class="nav-dropdown-trigger relative">
          <button class="nav-link flex items-center gap-1">
            Khám phá <i class="bi bi-chevron-down nav-chevron text-[11px] transition-transform duration-200"></i>
          </button>
          <div class="nav-dropdown absolute top-full left-1/2 -translate-x-1/2 pt-3 z-50">
            <div class="bg-white rounded-2xl shadow-hard border border-slate-100 p-2 w-52">
              <a href="/blog" class="dd-link">
                <i class="bi bi-newspaper text-primary text-sm w-5"></i>
                <span>Blog & Cẩm nang</span>
              </a>
              <a href="/about" class="dd-link">
                <i class="bi bi-building text-primary text-sm w-5"></i>
                <span>Về Bright Education</span>
              </a>
            </div>
          </div>
        </div>

        <a class="nav-link" href="/consultation">Đặt lịch</a>
        <a class="nav-link" href="/contact">Liên hệ</a>
        <?php if (isLoggedIn() && (isAdmin() || isEditor())): ?>
          <a class="nav-link !text-primary font-semibold" href="/admin">Quản trị</a>
        <?php endif; ?>
      </nav>

      <style>
        .nav-link {
          padding: 6px 14px; border-radius: 10px; color: #6b7280;
          transition: color .2s, background .2s; text-decoration: none;
          font-weight: 500; white-space: nowrap;
          background: transparent; border: none; cursor: pointer; font-size: 15px;
          font-family: 'Inter', sans-serif;
        }
        .nav-link:hover { color: #0d243e; background: #f8fafc; }

        .nav-dropdown {
          visibility: hidden; opacity: 0; transform: translateY(6px);
          transition: opacity .2s ease, transform .2s ease, visibility .2s;
          pointer-events: none;
        }
        .nav-dropdown-trigger:hover .nav-dropdown {
          visibility: visible; opacity: 1; transform: translateY(0);
          pointer-events: auto;
        }
        .nav-dropdown-trigger:hover .nav-chevron { transform: rotate(180deg); }

        .dd-link {
          display: flex; align-items: center; gap: 10px;
          padding: 9px 12px; border-radius: 12px; color: #374151;
          text-decoration: none; font-size: 14px; font-weight: 500;
          transition: background .15s, color .15s;
        }
        .dd-link:hover { background: #f2f5f9; color: #0d243e; }
      </style>
      
      <div class="flex items-center gap-3">
        <a class="hidden rounded-full px-5 py-2.5 text-sm font-semibold text-white sm:inline-flex btn-primary" href="/contact">
          Tư vấn miễn phí
        </a>
        <!-- Mobile Menu Toggle Button -->
        <button id="mobile-menu-toggle" class="flex md:hidden h-10 w-10 items-center justify-center rounded-lg bg-slate-50 text-primary border border-slate-200 transition-colors hover:bg-slate-100" aria-label="Toggle Menu">
          <i class="bi bi-list text-2xl"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Mobile Menu Overlay -->
  <div id="mobile-menu" class="fixed inset-0 z-[60] bg-white flex flex-col opacity-0 pointer-events-none transition-all duration-300 translate-y-[-10px] overflow-y-auto">
      <!-- Header row -->
      <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-slate-100">
        <a href="/" class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-ink text-sm font-bold text-white">BC</div>
          <span class="font-bold text-primary font-display">Bright Education</span>
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
          <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 px-4 pt-4 pb-2">Du học</p>
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

        <!-- Khám phá group -->
        <div>
          <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 px-4 pt-4 pb-2">Khám phá</p>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/blog">
            <i class="bi bi-newspaper text-primary w-5 text-center text-sm"></i> Blog & Cẩm nang
          </a>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/about">
            <i class="bi bi-building text-primary w-5 text-center text-sm"></i> Về Bright Education
          </a>
        </div>

        <!-- CTA group -->
        <div>
          <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 px-4 pt-4 pb-2">Liên hệ</p>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/consultation">
            <i class="bi bi-calendar-check text-primary w-5 text-center text-sm"></i> Đặt lịch tư vấn Zoom
          </a>
          <a class="mobile-link flex items-center gap-3 px-4 py-3 rounded-2xl text-[16px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors" href="/contact">
            <i class="bi bi-envelope text-primary w-5 text-center text-sm"></i> Liên hệ
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
