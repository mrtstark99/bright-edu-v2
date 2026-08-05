<?php
require_once 'config/config.php';

$db = Database::getInstance();

// Get featured posts
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name 
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published' AND p.featured = 1
    ORDER BY p.published_at DESC
    LIMIT 3
");
$stmt->execute();
$featured_posts = $stmt->fetchAll();

// Get latest posts
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name 
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published'
    ORDER BY p.published_at DESC
    LIMIT 6
");
$stmt->execute();
$latest_posts = $stmt->fetchAll();

// Get active group consultation slots (upcoming)
$stmt = $db->prepare("
    SELECT * FROM consultation_slots
    WHERE type = 'group' AND status IN ('active','full')
      AND scheduled_date >= date('now','localtime')
    ORDER BY scheduled_date ASC, time_start ASC
    LIMIT 3
");
$stmt->execute();
$zoom_slots = $stmt->fetchAll();

// Get active announcements
$stmt = $db->prepare("
    SELECT * FROM announcements 
    WHERE status = 'active' 
    AND (start_date IS NULL OR start_date <= datetime('now','localtime'))
    AND (end_date IS NULL OR end_date >= datetime('now','localtime'))
    ORDER BY priority DESC, created_at DESC
    LIMIT 1
");
$stmt->execute();
$announcement = $stmt->fetch();

include 'includes/header.php';
?>

  <main id="hero">
    <style>
      /* Floating animations */
      @keyframes floatUp {
          0%, 100% { transform: translateY(0); }
          50% { transform: translateY(-8px); }
      }
      .animate-float-1 { animation: floatUp 4s infinite ease-in-out; }
      .animate-float-2 { animation: floatUp 4.5s infinite ease-in-out; animation-delay: 0.5s; }
      .animate-float-3 { animation: floatUp 4.8s infinite ease-in-out; animation-delay: 0.2s; }
      .animate-float-4 { animation: floatUp 5.2s infinite ease-in-out; animation-delay: 1.5s; }
      .animate-float-5 { animation: floatUp 5s infinite ease-in-out; animation-delay: 1s; }

      /* Morphing orange/primary glow blob behind image */
      @keyframes morphGlow {
          0%, 100% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; transform: translate(-50%, -50%) rotate(0deg) scale(1); }
          33% { border-radius: 70% 30% 52% 48% / 60% 40% 60% 40%; transform: translate(-50%, -50%) rotate(120deg) scale(1.1); }
          66% { border-radius: 28% 72% 37% 63% / 40% 60% 30% 70%; transform: translate(-50%, -50%) rotate(240deg) scale(0.9); }
      }
      .animate-morph-glow {
          animation: morphGlow 15s infinite linear;
      }

      /* Wave Bottom shape fill */
      .wave-bottom {
          position: absolute;
          bottom: 0;
          left: 0;
          width: 100%;
          overflow: hidden;
          line-height: 0;
          transform: rotate(180deg);
          z-index: 25;
      }
      .wave-bottom svg {
          position: relative;
          display: block;
          width: calc(100% + 1.3px);
          height: 60px;
      }
      .wave-bottom .shape-fill {
          fill: #ffffff; /* Match next block background */
      }
      @media (min-width: 768px) {
          .wave-bottom svg {
              height: 100px;
          }
      }
      .hero-bg-faded {
          position: relative;
          background-color: #f3f5f9;
      }
      .hero-bg-faded::before {
          content: "";
          position: absolute;
          inset: 0;
          background-image: url('/assets/images/sakura_bg.png');
          background-size: cover;
          background-position: center;
          background-repeat: no-repeat;
          opacity: 0.25; /* Subtly faded to 25% opacity */
          pointer-events: none;
          z-index: 0;
      }
    </style>

    <section class="hero-bg-faded relative pt-[140px] pb-24 lg:pb-32 w-full min-h-[70vh] lg:min-h-[85vh] flex items-center overflow-hidden">
      
      <!-- Background SVG curves for premium layered look -->
      <div class="absolute inset-0 z-0 opacity-15 pointer-events-none">
          <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full">
              <path d="M0,0 C30,40 70,10 100,50 L100,100 L0,100 Z" fill="#ffffff"></path>
              <path d="M0,50 C40,80 60,30 100,60 L100,100 L0,100 Z" fill="#c5d3df"></path>
          </svg>
      </div>

      <div class="relative mx-auto max-w-7xl px-5 lg:px-8 w-full z-10">
        <div class="grid gap-12 lg:gap-8 lg:grid-cols-2 items-center">
          <!-- Left: Content -->
          <div class="relative z-10 self-center">
            <div class="space-y-6 relative z-10 text-left">
              <span class="text-orange-600 font-bold tracking-wider uppercase text-xs mb-3 block">Chắp cánh tương lai</span>
              <h1 class="reveal text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold leading-[1.2] tracking-tight text-slate-900 font-display">
                Du học Nhật Bản cùng <span class="block mt-2 text-orange-600 text-[1.15em] drop-shadow-sm">Bright Education</span>
              </h1>
              <p class="reveal text-base sm:text-lg text-slate-600 max-w-lg leading-relaxed font-medium">
                Quy trình linh động và minh bạch sẽ giúp các bước chuẩn bị du học của bạn thuận lợi hơn khi đồng hành cùng Bright Education.
              </p>
              <div class="reveal flex flex-wrap items-center gap-4 pt-4">
                <a class="inline-flex items-center justify-center rounded-2xl bg-primary px-6 py-3.5 text-[15px] font-semibold text-white transition-all hover:bg-ink shadow-medium hover:shadow-hard btn-primary" href="/contact">
                  Đặt lịch tư vấn miễn phí <i class="bi bi-arrow-right ml-2"></i>
                </a>
                <a class="inline-flex items-center justify-center rounded-2xl bg-white border border-slate-200 px-6 py-3.5 text-[15px] font-semibold text-primary transition hover:bg-slate-50 hover:border-slate-300 shadow-soft" href="/services">
                  Xem quy trình <i class="bi bi-play-circle ml-2 text-primary"></i>
                </a>
              </div>
            </div>
          </div>

          <!-- Right: Image & Floating Badges -->
          <div class="relative z-10 reveal mt-12 lg:mt-0 flex justify-center lg:justify-end py-8">
            <div class="relative w-fit lg:-left-[40px]">
              <!-- Glow background (Dynamic morphing fluid blob) -->
              <div class="absolute top-1/2 left-1/2 w-[280px] h-[280px] md:w-[420px] md:h-[420px] bg-orange-600/10 blur-3xl z-0 animate-morph-glow"></div>

              <!-- Floating Card 1: Top Left - Solid White with smooth floating -->
              <div class="absolute top-[2%] left-[2%] md:left-[-15%] z-20 bg-white border border-slate-100 rounded-2xl p-2.5 md:p-3 shadow-soft hidden md:flex items-center gap-2 md:gap-3 animate-float-1">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary text-sm md:text-lg font-black shadow-sm">
                      <i class="bi bi-mortarboard-fill"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Hồ sơ</p>
                      <p class="text-[11px] md:text-sm font-black text-slate-800 leading-none">Đỗ COE 99.9%</p>
                  </div>
              </div>

              <!-- Floating Card 2: Top Right - Solid Orange with smooth floating -->
              <div class="absolute top-[18%] right-[2%] md:right-[-25%] z-20 bg-orange-600 text-white border border-orange-500/20 rounded-2xl p-2.5 md:p-3 shadow-xl hidden md:flex items-center gap-2 md:gap-3 animate-float-2">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center text-white text-sm md:text-lg font-black shadow-sm">
                      <i class="bi bi-cash-coin"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-orange-200 uppercase tracking-wider leading-none mb-1">Học phí</p>
                      <p class="text-[11px] md:text-sm font-black text-white leading-none">Minh bạch 100%</p>
                  </div>
              </div>

              <!-- Floating Card 3: Middle Left - Solid Navy with smooth floating -->
              <div class="absolute top-[43%] -left-[5%] md:-left-[32%] z-20 bg-primary text-white border-2 border-primary-800/20 rounded-2xl p-2.5 md:p-3 shadow-xl hidden md:flex items-center gap-2 md:gap-3 animate-float-3">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center text-white text-sm md:text-lg font-black shadow-sm">
                      <i class="bi bi-building"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-primary-200 uppercase tracking-wider leading-none mb-1">Đối tác</p>
                      <p class="text-[11px] md:text-sm font-black text-white leading-none">500+ Trường Nhật</p>
                  </div>
              </div>

              <!-- Floating Card 4: Bottom Left - Solid White with smooth floating -->
              <div class="absolute bottom-[6%] left-[2%] md:left-[-15%] z-20 bg-white border border-slate-100 rounded-2xl p-2.5 md:p-3 shadow-soft hidden md:flex items-center gap-2 md:gap-3 animate-float-4">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary text-sm md:text-lg font-black shadow-sm">
                      <i class="bi bi-headset"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Hỗ trợ</p>
                      <p class="text-[11px] md:text-sm font-black text-slate-800 leading-none">Tư vấn 24/7</p>
                  </div>
              </div>

              <!-- Floating Card 5: Bottom Right - Solid Orange with smooth floating -->
              <div class="absolute bottom-[10%] right-[2%] md:right-[-20%] z-20 bg-orange-600 text-white border border-orange-500/20 rounded-2xl p-2.5 md:p-3 shadow-xl hidden md:flex items-center gap-2 md:gap-3 animate-float-5">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center text-white text-sm md:text-lg font-black">
                      <i class="bi bi-suitcase-lg"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-orange-200 uppercase tracking-wider leading-none mb-1">Việc làm</p>
                      <p class="text-[11px] md:text-sm font-black text-white leading-none">Hỗ trợ làm thêm</p>
                  </div>
              </div>

              <!-- Mascot/Student Image -->
              <img src="/assets/images/hero-new.png" alt="Sinh viên Bright Education" class="w-auto object-contain max-h-[320px] md:max-h-[480px] lg:max-h-[520px] relative z-10 transform scale-[1.05] drop-shadow-2xl">
            </div>
          </div>
        </div>
      </div>

      <!-- Wave Bottom Curve -->
      <div class="wave-bottom">
          <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
              <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
          </svg>
      </div>
    </section>

    <!-- Các Chương Trình Du Học -->
    <section id="programs" class="bg-white py-20 lg:py-28 relative">
      <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
      <div class="mx-auto max-w-7xl px-5 lg:px-8 relative">

        <div class="mx-auto mb-12 max-w-2xl text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Các Chương Trình Du Học</h2>
        </div>
        <!-- Study Abroad Programs (Slider/Carousel Layout) -->
        <div class="relative w-full mb-16 reveal">
          
          <div class="relative">
            <!-- Slider Viewport -->
            <div class="overflow-hidden w-full rounded-[2rem] p-1">
              <div id="prog-slider-track" class="flex transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] gap-6" style="transform: translateX(0px);">
               
               <!-- Card 1: Du học Trường Nhật ngữ -->
               <article class="flex-shrink-0 w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group relative bg-white rounded-3xl border border-slate-100 shadow-soft hover:shadow-medium hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                  <!-- Image Header -->
                  <div class="relative h-[222px] overflow-hidden">
                    <img src="/assets/images/program_language.jpg" alt="Du học Trường Nhật ngữ" style="object-position: center -30px;" class="w-full h-full object-cover transition-transform duration-[4s] group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                  </div>
                  <!-- Card Content -->
                  <div class="p-6 flex flex-col flex-1 justify-between">
                    <div>
                      <h3 class="text-[17px] font-bold text-primary font-display mb-4">Du học Trường Nhật ngữ</h3>
                      <ul class="space-y-2.5 text-[13.5px] text-muted">
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đã tốt nghiệp THPT</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đạt chứng chỉ N5 trở lên</span></li>
                      </ul>
                    </div>
                    <a href="/services/japanese-language-school" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-2 text-[13.5px] font-semibold text-primary group-hover:gap-3 transition-all">
                      Xem chi tiết lộ trình <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
               </article>

               <!-- Card 2: Du học Trường Senmon -->
               <article class="flex-shrink-0 w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group relative bg-white rounded-3xl border border-slate-100 shadow-soft hover:shadow-medium hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                  <!-- Image Header -->
                  <div class="relative h-[222px] overflow-hidden">
                    <img src="/assets/images/program_senmon.jpg" alt="Du học Trường Senmon" style="object-position: center -30px;" class="w-full h-full object-cover transition-transform duration-[4s] group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                  </div>
                  <!-- Card Content -->
                  <div class="p-6 flex flex-col flex-1 justify-between">
                    <div>
                      <h3 class="text-[17px] font-bold text-primary font-display mb-4">Du học Trường Senmon</h3>
                      <ul class="space-y-2.5 text-[13.5px] text-muted">
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đã tốt nghiệp THPT</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đạt chứng chỉ N3 trở lên</span></li>
                      </ul>
                    </div>
                    <a href="/services/senmon-vocational-school" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-2 text-[13.5px] font-semibold text-primary group-hover:gap-3 transition-all">
                      Xem chi tiết lộ trình <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
               </article>

               <!-- Card 3: Du học Trường Đại học -->
               <article class="flex-shrink-0 w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group relative bg-white rounded-3xl border border-slate-100 shadow-soft hover:shadow-medium hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                  <!-- Image Header -->
                  <div class="relative h-[222px] overflow-hidden">
                    <img src="/assets/images/program_university.jpg" alt="Du học Trường Đại học" style="object-position: center -30px;" class="w-full h-full object-cover transition-transform duration-[4s] group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                  </div>
                  <!-- Card Content -->
                  <div class="p-6 flex flex-col flex-1 justify-between">
                    <div>
                      <h3 class="text-[17px] font-bold text-primary font-display mb-4">Du học Trường Đại học</h3>
                      <ul class="space-y-2.5 text-[13.5px] text-muted">
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đã tốt nghiệp THPT</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đạt chứng chỉ N2 trở lên</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-info-circle text-primary mt-0.5 shrink-0"></i> <span>Có thể cần thi EJU (tùy trường)</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-gift text-emerald-500 mt-0.5 shrink-0"></i> <span>Học bổng từ khi làm hồ sơ</span></li>
                      </ul>
                    </div>
                    <a href="/services/university-program" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-2 text-[13.5px] font-semibold text-primary group-hover:gap-3 transition-all">
                      Xem chi tiết lộ trình <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
               </article>

               <!-- Card 4: Du học Học bổng -->
               <article class="flex-shrink-0 w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group relative bg-white rounded-3xl border border-slate-100 shadow-soft hover:shadow-medium hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                  <!-- Image Header -->
                  <div class="relative h-[222px] overflow-hidden">
                    <img src="/assets/images/program_ssw.jpg" alt="Du học Chương trình Học bổng" style="object-position: center -30px;" class="w-full h-full object-cover transition-transform duration-[4s] group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                  </div>
                  <!-- Card Content -->
                  <div class="p-6 flex flex-col flex-1 justify-between">
                    <div>
                      <h3 class="text-[17px] font-bold text-primary font-display mb-4">Du học Học bổng</h3>
                      <p class="text-xs text-muted mb-3 -mt-2">(Điều dưỡng, báo, nhà hàng)</p>
                      <ul class="space-y-2.5 text-[13.5px] text-muted">
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đã tốt nghiệp THPT</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đạt chứng chỉ N5 trở lên</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-gift text-emerald-500 mt-0.5 shrink-0"></i> <span>Học phí từ tổ chức liên kết</span></li>
                      </ul>
                    </div>
                    <a href="/services/scholarship-program" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-2 text-[13.5px] font-semibold text-primary group-hover:gap-3 transition-all">
                      Xem chi tiết lộ trình <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
               </article>

               <!-- Card 5: Du học Hệ Đại học Tiếng Anh -->
               <article class="flex-shrink-0 w-full md:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group relative bg-white rounded-3xl border border-slate-100 shadow-soft hover:shadow-medium hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between h-full overflow-hidden">
                  <!-- Image Header -->
                  <div class="relative h-[222px] overflow-hidden">
                    <img src="/assets/images/whyus_tokyo.png" alt="Du học Hệ Đại học Tiếng Anh" style="object-position: center -30px;" class="w-full h-full object-cover transition-transform duration-[4s] group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                  </div>
                  <!-- Card Content -->
                  <div class="p-6 flex flex-col flex-1 justify-between">
                    <div>
                      <h3 class="text-[17px] font-bold text-primary font-display mb-4">Du học Hệ Đại học Tiếng Anh</h3>
                      <ul class="space-y-2.5 text-[13.5px] text-muted">
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Đã tốt nghiệp THPT</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i> <span>Chứng chỉ tiếng Anh yêu cầu</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-info-circle text-primary mt-0.5 shrink-0"></i> <span>Có thể cần thi EJU (tùy trường)</span></li>
                        <li class="flex items-start gap-2"><i class="bi bi-gift text-emerald-500 mt-0.5 shrink-0"></i> <span>Học bổng khi đăng ký hồ sơ</span></li>
                      </ul>
                    </div>
                    <a href="/services/english-track-university" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-2 text-[13.5px] font-semibold text-primary group-hover:gap-3 transition-all">
                      Xem chi tiết lộ trình <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
               </article>

            </div>
          </div>

          <!-- Prev Button -->
          <button id="prog-slide-prev" aria-label="Previous slide" class="absolute -left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-primary hover:border-primary/30 flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-300 disabled:opacity-40 disabled:pointer-events-none md:-left-6 lg:-left-12">
            <i class="bi bi-chevron-left text-lg"></i>
          </button>
          <!-- Next Button -->
          <button id="prog-slide-next" aria-label="Next slide" class="absolute -right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-primary hover:border-primary/30 flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-300 disabled:opacity-40 disabled:pointer-events-none md:-right-6 lg:-right-12">
            <i class="bi bi-chevron-right text-lg"></i>
          </button>
        </div>

          <!-- JavaScript for Slider Navigation -->
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const track = document.getElementById('prog-slider-track');
              if (!track) return;
              const cards = Array.from(track.children);
              if (cards.length === 0) return;

              const prevBtn = document.getElementById('prog-slide-prev');
              const nextBtn = document.getElementById('prog-slide-next');

              let index = 0;
              let interval;

              function getVisibleCards() {
                if (window.innerWidth >= 1024) return 4;
                if (window.innerWidth >= 768) return 2;
                return 1;
              }

              function getCardWidth() {
                return cards[0].getBoundingClientRect().width;
              }

              function getGap() {
                return 24; // gap-6 (1.5rem)
              }

              function updateSliderPosition() {
                const visible = getVisibleCards();
                const maxIndex = cards.length - visible;
                if (index > maxIndex) {
                  index = maxIndex;
                }
                if (index < 0) {
                  index = 0;
                }
                const cardWidth = getCardWidth();
                const gap = getGap();
                const offset = index * (cardWidth + gap);
                track.style.transform = `translateX(-${offset}px)`;

                // Enable/disable navigation buttons based on current index
                if (prevBtn && nextBtn) {
                  prevBtn.disabled = (index === 0);
                  nextBtn.disabled = (index === maxIndex);
                }
              }

              function slide() {
                const visible = getVisibleCards();
                const maxIndex = cards.length - visible;
                
                index++;
                if (index > maxIndex) {
                  index = 0;
                }
                
                updateSliderPosition();
              }

              function startAutoSlide() {
                stopAutoSlide();
                interval = setInterval(slide, 4000); // Autoplay every 4s
              }

              function stopAutoSlide() {
                if (interval) {
                  clearInterval(interval);
                }
              }

              // Handle manual navigation clicks
              if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                  stopAutoSlide();
                  if (index > 0) {
                    index--;
                    updateSliderPosition();
                  }
                  startAutoSlide();
                });
              }

              if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                  stopAutoSlide();
                  const visible = getVisibleCards();
                  const maxIndex = cards.length - visible;
                  if (index < maxIndex) {
                    index++;
                    updateSliderPosition();
                  }
                  startAutoSlide();
                });
              }

              // Initialize positioning
              updateSliderPosition();
              startAutoSlide();

              // Pause on hover
              const container = track.parentElement;
              container.addEventListener('mouseenter', stopAutoSlide);
              container.addEventListener('mouseleave', startAutoSlide);

              // Touch swipe support for mobile
              let startX = 0;
              let isDragging = false;

              container.addEventListener('touchstart', (e) => {
                stopAutoSlide();
                startX = e.touches[0].clientX;
                isDragging = true;
              }, { passive: true });

              container.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                const currentX = e.touches[0].clientX;
                const diff = currentX - startX;
                const cardWidth = getCardWidth();
                const gap = getGap();
                const currentOffset = index * (cardWidth + gap);
                const tempTranslate = -currentOffset + diff;
                track.style.transform = `translateX(${tempTranslate}px)`;
              }, { passive: true });

              container.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                isDragging = false;
                const endX = e.changedTouches[0].clientX;
                const diff = endX - startX;
                const threshold = 50; // min swipe distance in px
                
                const visible = getVisibleCards();
                const maxIndex = cards.length - visible;

                if (diff < -threshold && index < maxIndex) {
                  index++;
                } else if (diff > threshold && index > 0) {
                  index--;
                }
                
                updateSliderPosition();
                startAutoSlide();
              });

              // Handle resize to adjust offset
              window.addEventListener('resize', updateSliderPosition);
            });
          </script>
        </div>
      </div>
    </section>

    <section id="services" class="bg-slate-50 py-20 lg:py-28 relative">
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto mb-16 max-w-2xl text-center">
          <span class="text-primary font-bold tracking-wider uppercase text-xs mb-3 block">Lộ trình chuẩn hóa</span>
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Các Bước & Quy Trình</h2>
          <p class="mt-4 text-slate-500 text-[15px] max-w-xl mx-auto">Bright Education đồng hành cùng bạn qua 7 bước chuẩn bị chi tiết và chuyên nghiệp để hiện thực hóa giấc mơ du học Nhật Bản.</p>
        </div>
        <div class="w-full">
          <!-- Services Grid -->
          <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- Step 1 -->
            <article class="relative rounded-3xl border border-slate-100 bg-white p-5 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 reveal flex flex-col justify-between group">
              <div>
                <div class="w-full h-32 rounded-2xl overflow-hidden mb-4 relative">
                  <img src="/assets/images/step1_info.webp" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Tìm hiểu thông tin du học" />
                </div>
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <i class="bi bi-search text-sm"></i>
                  </div>
                  <span class="text-[11px] font-bold text-primary uppercase tracking-widest">Bước 01</span>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display">Tìm hiểu thông tin du học</h3>
                <ul class="mt-3 space-y-2 text-[12.5px] text-muted">
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-primary mt-0.5 shrink-0"></i> <span>Tìm hiểu điều kiện & chi phí</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-primary mt-0.5 shrink-0"></i> <span>Định hướng lộ trình tối ưu</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-primary mt-0.5 shrink-0"></i> <span>Tư vấn 1-1 miễn phí</span></li>
                </ul>
              </div>
              <div class="hidden lg:block absolute top-[182px] left-[calc(100%-8px)] w-[24px] h-0.5 border-t-2 border-dashed border-slate-200 z-10"></div>
            </article>

            <!-- Step 2 -->
            <article class="relative rounded-3xl border border-slate-100 bg-white p-5 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 reveal reveal-delay-100 flex flex-col justify-between group">
              <div>
                <div class="w-full h-32 rounded-2xl overflow-hidden mb-4 relative">
                  <img src="/assets/images/step2_study.jpg" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Học tiếng Nhật" />
                </div>
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <i class="bi bi-translate text-sm"></i>
                  </div>
                  <span class="text-[11px] font-bold text-orange-600 uppercase tracking-widest">Bước 02</span>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display">Học tiếng Nhật</h3>
                <ul class="mt-3 space-y-2 text-[12.5px] text-muted">
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-orange-600 mt-0.5 shrink-0"></i> <span>Đào tạo tiếng Nhật từ N5</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-orange-600 mt-0.5 shrink-0"></i> <span>Rèn luyện phản xạ giao tiếp</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-orange-600 mt-0.5 shrink-0"></i> <span>Đạt chứng chỉ cần thiết</span></li>
                </ul>
              </div>
              <div class="hidden lg:block absolute top-[182px] left-[calc(100%-8px)] w-[24px] h-0.5 border-t-2 border-dashed border-slate-200 z-10"></div>
            </article>

            <!-- Step 3 -->
            <article class="relative rounded-3xl border border-slate-100 bg-white p-5 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 reveal reveal-delay-200 flex flex-col justify-between group">
              <div>
                <div class="w-full h-32 rounded-2xl overflow-hidden mb-4 relative">
                  <img src="/assets/images/step3_school.jpg" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Chọn trường du học" />
                </div>
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                    <i class="bi bi-building-gear text-sm"></i>
                  </div>
                  <span class="text-[11px] font-bold text-sky-600 uppercase tracking-widest">Bước 03</span>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display">Chọn trường du học</h3>
                <ul class="mt-3 space-y-2 text-[12.5px] text-muted">
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-sky-600 mt-0.5 shrink-0"></i> <span>Chọn trường theo nguyện vọng</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-sky-600 mt-0.5 shrink-0"></i> <span>Khảo sát vị trí & học phí</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-sky-600 mt-0.5 shrink-0"></i> <span>Đăng ký hồ sơ vào trường</span></li>
                </ul>
              </div>
              <div class="hidden lg:block absolute top-[182px] left-[calc(100%-8px)] w-[24px] h-0.5 border-t-2 border-dashed border-slate-200 z-10"></div>
            </article>

            <!-- Step 4 -->
            <article class="relative rounded-3xl border border-slate-100 bg-white p-5 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 reveal reveal-delay-300 flex flex-col justify-between group">
              <div>
                <div class="w-full h-32 rounded-2xl overflow-hidden mb-4 relative">
                  <img src="/assets/images/step4_interview.png" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Phỏng vấn xin thư mời học" />
                </div>
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="bi bi-chat-dots text-sm"></i>
                  </div>
                  <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest">Bước 04</span>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display">Phỏng vấn xin thư mời</h3>
                <ul class="mt-3 space-y-2 text-[12.5px] text-muted">
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-indigo-600 mt-0.5 shrink-0"></i> <span>Chuẩn bị hồ sơ thi tuyển</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-indigo-600 mt-0.5 shrink-0"></i> <span>Luyện phỏng vấn trực tiếp 1-1</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-indigo-600 mt-0.5 shrink-0"></i> <span>Nhận thư mời nhập học</span></li>
                </ul>
              </div>
            </article>

            <!-- Step 5 -->
            <article class="relative rounded-3xl border border-slate-100 bg-white p-5 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 reveal flex flex-col justify-between group">
              <div>
                <div class="w-full h-32 rounded-2xl overflow-hidden mb-4 relative">
                  <img src="/assets/images/step5_coe.jpg" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Xin tư cách lưu trú - COE" />
                </div>
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="bi bi-file-earmark-check text-sm"></i>
                  </div>
                  <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-widest">Bước 05</span>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display">Xin tư cách lưu trú – COE</h3>
                <ul class="mt-3 space-y-2 text-[12.5px] text-muted">
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-emerald-600 mt-0.5 shrink-0"></i> <span>Dịch thuật công chứng hồ sơ</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-emerald-600 mt-0.5 shrink-0"></i> <span>Giải trình tài chính chặt chẽ</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-emerald-600 mt-0.5 shrink-0"></i> <span>Nộp hồ sơ lên Cục XNC Nhật</span></li>
                </ul>
              </div>
              <div class="hidden lg:block absolute top-[182px] left-[calc(100%-8px)] w-[24px] h-0.5 border-t-2 border-dashed border-slate-200 z-10"></div>
            </article>

            <!-- Step 6 -->
            <article class="relative rounded-3xl border border-slate-100 bg-white p-5 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 reveal reveal-delay-100 flex flex-col justify-between group">
              <div>
                <div class="w-full h-32 rounded-2xl overflow-hidden mb-4 relative">
                  <img src="/assets/images/step6_visa.jpg" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Xin visa du học Nhật Bản" />
                </div>
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <i class="bi bi-passport text-sm"></i>
                  </div>
                  <span class="text-[11px] font-bold text-rose-600 uppercase tracking-widest">Bước 06</span>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display">Xin visa du học Nhật Bản</h3>
                <ul class="mt-3 space-y-2 text-[12.5px] text-muted">
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-rose-600 mt-0.5 shrink-0"></i> <span>Nhận COE gốc từ Nhật Bản</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-rose-600 mt-0.5 shrink-0"></i> <span>Xin visa tại Đại sứ quán</span></li>
                  <li class="flex items-start gap-1.5"><i class="bi bi-check2 text-rose-600 mt-0.5 shrink-0"></i> <span>Đóng học phí sang trường</span></li>
                </ul>
              </div>
              <div class="hidden lg:block absolute top-[182px] left-[calc(100%-8px)] w-[24px] h-0.5 border-t-2 border-dashed border-slate-200 z-10"></div>
            </article>

            <!-- Step 7 -->
            <article class="relative rounded-3xl bg-gradient-to-br from-orange-600 to-orange-700 text-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 reveal reveal-delay-200 flex flex-col justify-between lg:col-span-2 group">
              <div class="lg:grid lg:grid-cols-[1.3fr_1fr] lg:gap-6 h-full items-center">
                <div class="flex flex-col justify-between h-full">
                  <div>
                    <div class="flex items-center gap-3 mb-3">
                      <div class="w-8 h-8 rounded-xl bg-white/20 text-white flex items-center justify-center shrink-0">
                        <i class="bi bi-luggage text-sm"></i>
                      </div>
                      <span class="text-[11px] font-bold text-orange-200 uppercase tracking-widest">Bước 07 · Xuất cảnh</span>
                    </div>
                    <h3 class="text-lg font-bold text-white font-display">Chuẩn bị hành trang Du học</h3>
                    <ul class="mt-3 space-y-2.5 text-[13px] text-orange-100">
                      <li class="flex items-start gap-1.5"><i class="bi bi-check2-circle text-white mt-0.5 shrink-0"></i> <span>Hướng dẫn hành lý, đổi tiền</span></li>
                      <li class="flex items-start gap-1.5"><i class="bi bi-check2-circle text-white mt-0.5 shrink-0"></i> <span>Đặt vé máy bay du học sinh</span></li>
                      <li class="flex items-start gap-1.5"><i class="bi bi-check2-circle text-white mt-0.5 shrink-0"></i> <span>Đăng ký Ký túc xá / Nhà ở</span></li>
                      <li class="flex items-start gap-1.5"><i class="bi bi-check-circle-fill text-white mt-0.5 shrink-0"></i> <span>Đưa đón & hòa nhập Nhật Bản</span></li>
                    </ul>
                  </div>
                </div>
                <div class="hidden lg:block w-full h-full min-h-[180px] rounded-2xl overflow-hidden relative">
                  <img src="/assets/images/step7_luggage.jpg" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Chuẩn bị hành trang Du học" />
                </div>
              </div>
            </article>

          </div>
        </div>
      </div>
    </section>

    <!-- Hành trang du học (Portal / Directory) -->
    <section id="portal" class="py-20 lg:py-28 bg-white relative">
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto mb-16 max-w-2xl text-center">
          <span class="text-primary font-bold tracking-wider uppercase text-xs mb-3 block">Hành trang thông tin</span>
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Tìm Hiểu Chi Tiết Thủ Tục & Dịch Vụ</h2>
          <p class="mt-4 text-slate-500 text-[15px] max-w-xl mx-auto">Hệ thống thông tin cẩm nang hỗ trợ đắc lực giúp bạn chủ động tìm hiểu và lựa chọn lộ trình phù hợp.</p>
        </div>
        <div class="relative">
          <div class="relative overflow-hidden w-full px-4 py-6 -mx-4 -my-6">
            <div id="portal-slider-track" class="flex transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] gap-6">

            <!-- Card 1: Các chương trình -->
            <article class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 flex flex-col justify-between shrink-0 w-full md:w-[calc((100%-24px)/2)] lg:w-[calc((100%-72px)/4)] reveal">
              <div>
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-5 shrink-0">
                  <i class="bi bi-briefcase text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display mb-3">Các chương trình</h3>
                <ul class="space-y-2 text-[13px] text-slate-500 leading-relaxed">
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-primary shrink-0 mt-0.5"></i>
                    <span>Du học Trường Nhật ngữ</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-primary shrink-0 mt-0.5"></i>
                    <span>Du học Trường Senmon</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-primary shrink-0 mt-0.5"></i>
                    <span>Du học Trường Đại học</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-primary shrink-0 mt-0.5"></i>
                    <span>Chương trình Học bổng</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-primary shrink-0 mt-0.5"></i>
                    <span>Hệ Đại học Tiếng Anh</span>
                  </li>
                </ul>
              </div>
              <a href="/services" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:gap-2.5 transition-all">
                Xem chi tiết <i class="bi bi-arrow-right"></i>
              </a>
            </article>

            <!-- Card 2: Quy trình du học -->
            <article class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 flex flex-col justify-between shrink-0 w-full md:w-[calc((100%-24px)/2)] lg:w-[calc((100%-72px)/4)] reveal reveal-delay-100">
              <div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center mb-5 shrink-0">
                  <i class="bi bi-arrow-right-circle text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display mb-3">Quy trình du học</h3>
                <ul class="space-y-2 text-[13px] text-slate-500 leading-relaxed">
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-orange-500 shrink-0 mt-0.5"></i>
                    <span>Định hướng & Chọn trường</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-orange-500 shrink-0 mt-0.5"></i>
                    <span>Đào tạo tiếng Nhật</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-orange-500 shrink-0 mt-0.5"></i>
                    <span>Xử lý hồ sơ & COE</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-orange-500 shrink-0 mt-0.5"></i>
                    <span>Xin Visa & Xuất cảnh</span>
                  </li>
                </ul>
              </div>
              <a href="/process" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:gap-2.5 transition-all">
                Xem chi tiết <i class="bi bi-arrow-right"></i>
              </a>
            </article>

            <!-- Card 3: Chuẩn bị hồ sơ -->
            <article class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 flex flex-col justify-between shrink-0 w-full md:w-[calc((100%-24px)/2)] lg:w-[calc((100%-72px)/4)] reveal reveal-delay-200">
              <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5 shrink-0">
                  <i class="bi bi-file-earmark-text text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display mb-3">Chuẩn bị hồ sơ</h3>
                <ul class="space-y-2 text-[13px] text-slate-500 leading-relaxed">
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-emerald-500 shrink-0 mt-0.5"></i>
                    <span>Giấy tờ học vấn & cá nhân</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-emerald-500 shrink-0 mt-0.5"></i>
                    <span>Hồ sơ người bảo lãnh</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-emerald-500 shrink-0 mt-0.5"></i>
                    <span>Chứng minh tài chính</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-emerald-500 shrink-0 mt-0.5"></i>
                    <span>Thủ tục xin COE & Visa</span>
                  </li>
                </ul>
              </div>
              <a href="/documents" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:gap-2.5 transition-all">
                Xem chi tiết <i class="bi bi-arrow-right"></i>
              </a>
            </article>

            <!-- Card 4: Khóa học tiếng Nhật -->
            <article class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 flex flex-col justify-between shrink-0 w-full md:w-[calc((100%-24px)/2)] lg:w-[calc((100%-72px)/4)] reveal reveal-delay-300">
              <div>
                <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mb-5 shrink-0">
                  <i class="bi bi-journal-bookmark text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display mb-3">Khóa học tiếng</h3>
                <ul class="space-y-2 text-[13px] text-slate-500 leading-relaxed">
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-sky-500 shrink-0 mt-0.5"></i>
                    <span>Lớp N5 - Nền tảng</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-sky-500 shrink-0 mt-0.5"></i>
                    <span>Lớp N4 - Chuyên sâu</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-sky-500 shrink-0 mt-0.5"></i>
                    <span>Khóa Luyện phỏng vấn 1-1</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-sky-500 shrink-0 mt-0.5"></i>
                    <span>Giáo viên bản xứ Tokyo</span>
                  </li>
                </ul>
              </div>
              <a href="/courses" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:gap-2.5 transition-all">
                Xem chi tiết <i class="bi bi-arrow-right"></i>
              </a>
            </article>

            <!-- Card 5: Trường Nhật Ngữ -->
            <article class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-soft transition-all duration-300 hover:shadow-medium hover:-translate-y-1 flex flex-col justify-between shrink-0 w-full md:w-[calc((100%-24px)/2)] lg:w-[calc((100%-72px)/4)] reveal reveal-delay-400">
              <div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5 shrink-0">
                  <i class="bi bi-building text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-display mb-3">Trường Nhật Ngữ</h3>
                <ul class="space-y-2 text-[13px] text-slate-500 leading-relaxed">
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-indigo-500 shrink-0 mt-0.5"></i>
                    <span class="font-bold text-slate-800">Hơn 600 trường nhật ngữ trên toàn quốc</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-indigo-500 shrink-0 mt-0.5"></i>
                    <span>Thông tin học phí chi tiết</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-indigo-500 shrink-0 mt-0.5"></i>
                    <span>Vị trí địa lý & Vùng miền</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <i class="bi bi-check2 text-indigo-500 shrink-0 mt-0.5"></i>
                    <span>Học bổng & Ký túc xá</span>
                  </li>
                </ul>
              </div>
              <a href="/schools" class="mt-6 pt-4 border-t border-slate-100 inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:gap-2.5 transition-all">
                Xem chi tiết <i class="bi bi-arrow-right"></i>
              </a>
            </article>

          </div>
        </div>

        <!-- Prev Button -->
        <button id="portal-prev-btn" aria-label="Previous slide" class="absolute -left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-primary hover:border-primary/30 flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-300 disabled:opacity-40 disabled:pointer-events-none md:-left-6 lg:-left-12">
          <i class="bi bi-chevron-left text-lg"></i>
        </button>
        <!-- Next Button -->
        <button id="portal-next-btn" aria-label="Next slide" class="absolute -right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-primary hover:border-primary/30 flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-300 disabled:opacity-40 disabled:pointer-events-none md:-right-6 lg:-right-12">
          <i class="bi bi-chevron-right text-lg"></i>
        </button>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const track = document.getElementById('portal-slider-track');
      if (!track) return;
      const cards = Array.from(track.children);
      if (cards.length === 0) return;

      const prevBtn = document.getElementById('portal-prev-btn');
      const nextBtn = document.getElementById('portal-next-btn');

      let index = 0;
      let interval;

      function getVisibleCards() {
        if (window.innerWidth >= 1024) return 4;
        if (window.innerWidth >= 768) return 2;
        return 1;
      }

      function getCardWidth() {
        return cards[0].getBoundingClientRect().width;
      }

      function getGap() {
        return 24; // gap-6 (1.5rem)
      }

      function updateSliderPosition() {
        const visible = getVisibleCards();
        const maxIndex = cards.length - visible;
        if (index > maxIndex) {
          index = maxIndex;
        }
        if (index < 0) {
          index = 0;
        }
        const cardWidth = getCardWidth();
        const gap = getGap();
        const offset = index * (cardWidth + gap);
        track.style.transform = `translateX(-${offset}px)`;

        // Enable/disable navigation buttons based on current index
        if (prevBtn && nextBtn) {
          prevBtn.disabled = (index === 0);
          nextBtn.disabled = (index === maxIndex);
        }
      }

      function slide() {
        const visible = getVisibleCards();
        const maxIndex = cards.length - visible;
        
        index++;
        if (index > maxIndex) {
          index = 0;
        }
        
        updateSliderPosition();
      }

      function startAutoSlide() {
        stopAutoSlide();
        interval = setInterval(slide, 4000); // Autoplay every 4s
      }

      function stopAutoSlide() {
        if (interval) {
          clearInterval(interval);
        }
      }

      // Handle manual navigation clicks
      if (prevBtn) {
        prevBtn.addEventListener('click', function() {
          stopAutoSlide();
          if (index > 0) {
            index--;
            updateSliderPosition();
          }
          startAutoSlide();
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function() {
          stopAutoSlide();
          const visible = getVisibleCards();
          const maxIndex = cards.length - visible;
          if (index < maxIndex) {
            index++;
            updateSliderPosition();
          }
          startAutoSlide();
        });
      }

      // Initialize positioning
      updateSliderPosition();
      startAutoSlide();

      // Pause on hover
      const container = track.parentElement;
      container.addEventListener('mouseenter', stopAutoSlide);
      container.addEventListener('mouseleave', startAutoSlide);

      // Touch swipe support for mobile
      let startX = 0;
      let isDragging = false;

      container.addEventListener('touchstart', (e) => {
        stopAutoSlide();
        startX = e.touches[0].clientX;
        isDragging = true;
      }, { passive: true });

      container.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        const currentX = e.touches[0].clientX;
        const diff = currentX - startX;
        const cardWidth = getCardWidth();
        const gap = getGap();
        const currentOffset = index * (cardWidth + gap);
        const tempTranslate = -currentOffset + diff;
        track.style.transform = `translateX(${tempTranslate}px)`;
      }, { passive: true });

      container.addEventListener('touchend', (e) => {
        if (!isDragging) return;
        isDragging = false;
        const endX = e.changedTouches[0].clientX;
        const diff = endX - startX;
        const threshold = 50; // min swipe distance in px
        
        const visible = getVisibleCards();
        const maxIndex = cards.length - visible;

        if (diff < -threshold && index < maxIndex) {
          index++;
        } else if (diff > threshold && index > 0) {
          index--;
        }
        
        updateSliderPosition();
        startAutoSlide();
      });

      // Handle resize to adjust offset
      window.addEventListener('resize', updateSliderPosition);
    });
  </script>

    <section id="cost" class="bg-slate-50 py-20 lg:py-28 relative">
      <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-sand-200 to-transparent"></div>
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-16">
          <span class="text-primary font-bold tracking-wider uppercase text-xs mb-3 block">Minh bạch chi phí</span>
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Tổng chi phí dự kiến năm đầu</h2>
          <p class="mt-4 text-lg text-muted">Hãy tùy chỉnh các lựa chọn dưới đây để xem chi tiết dự toán và chuẩn bị tài chính vững vàng cho lộ trình du học của bạn.</p>
        </div>

        <style>
          .wiz-panel { display: none; }
          .wiz-panel.wiz-active { display: flex; flex-direction: column; gap: 12px; animation: wizFade 0.22s ease; }
          @keyframes wizFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

          .calc-item input:checked + .calc-box {
            border-color: var(--color-primary, #0d243e);
            background-color: rgba(13, 36, 62, 0.04);
          }
          .calc-item input:checked + .calc-box .radio-dot {
            transform: scale(1);
          }

          .calc-item input:checked + .calc-box .calc-details {
            display: block;
            animation: slideDown 0.22s ease-out;
          }
          @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
          }
        </style>

        <div id="calculator">
          <div class="flex flex-col lg:flex-row gap-8 items-stretch">

            <!-- Left: Step Wizard -->
            <div class="w-full lg:w-2/3 flex">
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col w-full reveal">

                <!-- Progress -->
                <div class="flex items-center justify-between mb-5">
                  <span class="text-xs font-bold text-muted uppercase tracking-widest">Bước <span id="wiz-num-label">1</span> / 6</span>
                  <div class="flex gap-1.5" id="wiz-pips">
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-primary" style="width:2rem" data-pip="0"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="1"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="2"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="3"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="4"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="5"></div>
                  </div>
                </div>

                <!-- Step heading -->
                <h4 class="text-lg font-bold text-midnight flex items-center gap-3 mb-1">
                  <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold shrink-0" id="wiz-badge">1</span>
                  <span id="wiz-title-text">Chọn Hệ du học Nhật Bản</span>
                </h4>
                <p class="text-sm text-muted mb-5 pl-11" style="min-height:18px" id="wiz-subtitle"></p>

                <!-- Panels -->
                <div class="flex-1" id="wiz-panels">

                  <!-- Panel 0: Hệ du học -->
                  <div class="wiz-panel wiz-active" data-panel="0">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="Trường Nhật Ngữ" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Trường Nhật Ngữ</div>
                              <div class="text-xs text-muted mt-0.5">Lộ trình học tiếng Nhật tập trung từ 1.5 - 2 năm tại Nhật Bản</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">Phổ biến nhất</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lộ trình:</strong> Học tiếng tại VN (6-8 tháng) → Sang Nhật học Trường Nhật ngữ (1-2 năm) → Học lên Senmon/Đại học hoặc đổi visa đi làm.</p>
                          <p><strong>Yêu cầu:</strong> Tốt nghiệp THPT (trống dưới 5 năm), GPA &gt; 6.0, tiếng Nhật tối thiểu N5 (hoặc học cấp tốc tại Bright).</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="Trường Senmon" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Trường Senmon</div>
                              <div class="text-xs text-muted mt-0.5">Đào tạo nghề thực chiến 2 năm chuyên sâu (IT, Du lịch...)</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">Hướng nghiệp</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lộ trình:</strong> Đào tạo nghề thực hành 2 năm chuyên sâu (IT, Du lịch, Thiết kế, Khách sạn...) → Đi làm ngay với visa kỹ sư/nhân văn quốc tế.</p>
                          <p><strong>Yêu cầu:</strong> Tốt nghiệp THPT, trình độ tiếng Nhật tối thiểu N2 hoặc tốt nghiệp khóa học tại Trường Nhật ngữ bên Nhật.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="Trường Đại Học" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Trường Đại Học</div>
                              <div class="text-xs text-muted mt-0.5">Hệ cử nhân chính quy tại các trường đại học hàng đầu Nhật Bản</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">Chính quy</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lộ trình:</strong> Học cử nhân chính quy 4 năm tại Nhật → Nhận bằng Cử nhân quốc tế → Cơ hội thăng tiến cao và định cư lâu dài.</p>
                          <p><strong>Yêu cầu:</strong> Tốt nghiệp THPT, điểm GPA &gt; 6.5, thi kỳ thi EJU hoặc chứng chỉ tiếng Nhật tối thiểu N2.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="Chương Trình Học Bổng" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Chương Trình Học Bổng</div>
                              <div class="text-xs text-muted mt-0.5">Học bổng báo, điều dưỡng... Miễn 100% học phí & KTX</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">Học bổng</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lộ trình:</strong> Doanh nghiệp tài trợ 100% học phí & KTX → Vừa học vừa làm bán thời gian tại doanh nghiệp bảo trợ → Làm việc chính thức từ 3-5 năm sau tốt nghiệp.</p>
                          <p><strong>Yêu cầu:</strong> Tốt nghiệp THPT, sức khỏe tốt, cam kết tuân thủ hợp đồng lao động và học tập của đơn vị tài trợ học bổng.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="Hệ Đại Học Tiếng Anh" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Hệ Đại Học Tiếng Anh</div>
                              <div class="text-xs text-muted mt-0.5">Chương trình E-Track giảng dạy 100% bằng tiếng Anh</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">E-Track</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lộ trình:</strong> Học cử nhân chính quy 4 năm bằng 100% tiếng Anh tại các trường đại học quốc tế ở Nhật → Học thêm tiếng Nhật song song.</p>
                          <p><strong>Yêu cầu:</strong> Tốt nghiệp THPT, điểm GPA &gt; 7.0, chứng chỉ tiếng Anh (IELTS &gt; 5.5 hoặc TOEFL iBT &gt; 75 hoặc tương đương).</p>
                        </div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 1: Gói dịch vụ -->
                  <div class="wiz-panel" data-panel="1">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_package" value="15000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Tiêu Chuẩn</div>
                              <div class="text-xs text-muted mt-0.5">Xử lý hồ sơ cơ bản</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0" id="calc-package-price-text">15.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Dịch vụ bao gồm:</strong> Dịch thuật công chứng hồ sơ, giải trình tài chính chặt chẽ, luyện phỏng vấn visa và trường, hỗ trợ nộp COE và đặt vé máy bay.</p>
                        </div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 2: Khóa học tiếng Nhật -->
                  <div class="wiz-panel" data-panel="2">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_course" value="0" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Tự học / Đã có N4</div>
                              <div class="text-xs text-muted mt-0.5">Dành cho học sinh đã đủ trình độ</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">0đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Nội dung hỗ trợ:</strong> Bright Education hỗ trợ kiểm tra năng lực đầu vào miễn phí. Thích hợp cho học sinh tự học tại nhà hoặc đã có chứng chỉ tiếng Nhật JLPT N4 trở lên.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_course" value="10000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Cơ bản 3 tháng</div>
                              <div class="text-xs text-muted mt-0.5">Chương trình chuẩn N5</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">10.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Chi tiết khóa học:</strong> Đào tạo cấp tốc 3 tháng (5 buổi/tuần). Học bảng chữ cái, ngữ pháp nền tảng N5, phát âm chuẩn và phản xạ giao tiếp cơ bản.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_course" value="15000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Chuyên sâu 6 tháng</div>
                              <div class="text-xs text-muted mt-0.5">Luyện thi JLPT N4</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">15.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Chi tiết khóa học:</strong> Đào tạo bán trú 6 tháng từ N5 lên N4. Tích hợp luyện thi các chứng chỉ JLPT/NAT-TEST, kỹ năng phỏng vấn học bổng và xin việc làm thêm tại Nhật.</p>
                        </div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 3: Trường Nhật Ngữ -->
                  <div class="wiz-panel" data-panel="3">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_school" value="110000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Trường ở tỉnh xa</div>
                              <div class="text-xs text-muted mt-0.5">Hokkaido, Ibaraki, Oita... Học phí và sinh hoạt phí đều rất rẻ.</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 110.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Đặc điểm vùng:</strong> Học phí chỉ khoảng 60 - 70 Man/năm. Tiền thuê phòng chỉ khoảng 2 - 3 Man/tháng. Thích hợp cho học viên muốn tiết kiệm ngân sách ban đầu tối đa.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_school" value="125000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Thành phố cỡ trung</div>
                              <div class="text-xs text-muted mt-0.5">Fukuoka, Chiba, Saitama... Dễ tìm việc làm, chi phí vừa phải.</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 125.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Đặc điểm vùng:</strong> Học phí 70 - 75 Man/năm. Đầy đủ việc làm thêm phong phú nhưng giá cả sinh hoạt nhẹ nhàng hơn nhiều so với nội đô Tokyo.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_school" value="135000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="flex items-center gap-2">
                                <span class="font-bold text-midnight text-sm sm:text-base">Ngoại ô Tokyo / Osaka</span>
                                <span class="bg-amber-400 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded-full">Phổ biến</span>
                              </div>
                              <div class="text-xs text-muted mt-0.5">Cách trung tâm 30-40p tàu. Cân bằng tốt giữa chi phí và cơ hội.</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 135.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Đặc điểm vùng:</strong> Học phí 75 - 80 Man/năm. Lựa chọn tối ưu để tiếp cận cơ hội việc làm lớn tại trung tâm nhưng vẫn giữ mức sinh hoạt phí ở mức dễ chịu.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_school" value="145000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Trung tâm Tokyo / Osaka</div>
                              <div class="text-xs text-muted mt-0.5">Sầm uất, nhiều cơ hội việc làm lương cao nhưng học phí đắt.</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 145.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Đặc điểm vùng:</strong> Học phí &gt; 80 Man/năm. Chi phí thuê nhà đắt đỏ nhất. Bù lại, đây là trung tâm sầm uất với vô vàn cơ hội làm thêm lương cao và dễ tìm việc dài hạn.</p>
                        </div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 4: Sinh hoạt ban đầu -->
                  <div class="wiz-panel" data-panel="4">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_living" value="30000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Tiết Kiệm</div>
                              <div class="text-xs text-muted mt-0.5">KTX chung 4 người + 10 Man tiền mặt phòng thân</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 30.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Chi tiết ngân sách:</strong> Khoảng 8 Man đóng trước KTX chung cọc và tiền nhà 2-3 tháng + 10 Man chi tiêu ăn uống tối thiểu tháng đầu tiên.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_living" value="45000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Cơ Bản</div>
                              <div class="text-xs text-muted mt-0.5">KTX tiêu chuẩn 2 người + 12 Man tiền mặt phòng thân</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 45.000.000đ</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Chi tiết ngân sách:</strong> Khoảng 15 Man đóng trước KTX phòng đôi tiêu chuẩn 3 tháng + 12 Man chi tiêu ăn uống, đi lại thoải mái hơn trong thời gian đầu.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_living" value="60000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-355 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                          <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                          </div>
                          <div>
                            <div class="font-bold text-midnight text-sm sm:text-base">Thoải Mái</div>
                            <div class="text-xs text-muted mt-0.5">Thuê phòng riêng + 15 Man tiền mặt phòng thân</div>
                          </div>
                        </div>
                        <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 60.000.000đ</div>
                      </div>
                      <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                        <p><strong>Chi tiết ngân sách:</strong> Khoảng 25 Man đóng trước tiền thuê phòng riêng (cọc + lễ + tiền nhà 1 tháng) + 15 Man chi tiêu dư dả.</p>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 5: Thủ tục khác -->
                  <div class="wiz-panel" data-panel="5">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_other" value="8650000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                          <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                          </div>
                          <div>
                            <div class="font-bold text-midnight text-sm sm:text-base">Thấp Nhất</div>
                            <div class="text-xs text-muted mt-0.5">Tổng các mức thấp nhất (Săn vé giá rẻ)</div>
                          </div>
                        </div>
                        <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">8.650.000đ</div>
                      </div>
                      <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                        <p><strong>Bao gồm:</strong> Phí khám lao (~1.5M), lệ phí visa (~1.3M) cộng thêm vé máy bay giá rẻ một chiều (bay transit hoặc hãng giá rẻ).</p>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_other" value="13000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                          <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                          </div>
                          <div>
                            <div class="font-bold text-midnight text-sm sm:text-base">Trung Bình</div>
                            <div class="text-xs text-muted mt-0.5">Chi tiêu hợp lý, vé máy bay phổ thông</div>
                          </div>
                        </div>
                        <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">13.000.000đ</div>
                      </div>
                      <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                        <p><strong>Bao gồm:</strong> Lệ phí bắt buộc và vé máy bay bay thẳng phổ thông của các hãng hàng không uy tín như Vietnam Airlines.</p>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_other" value="17000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                          <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                          </div>
                          <div>
                            <div class="font-bold text-midnight text-sm sm:text-base">Dự Tính An Toàn</div>
                            <div class="text-xs text-muted mt-0.5">Tổng mức cao nhất, bay thẳng giờ đẹp</div>
                          </div>
                        </div>
                        <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">17.000.000đ</div>
                      </div>
                    </label>
                  </div>

                </div><!-- /wiz-panels -->

                <!-- Footer nav -->
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between" style="min-height:36px">
                  <button id="wiz-back" class="text-sm text-muted hover:text-primary transition-colors flex items-center gap-1.5" type="button" style="visibility:hidden">
                    <i class="bi bi-arrow-left text-xs"></i> Quay lại
                  </button>
                  <div class="flex items-center gap-4">
                    <span class="text-xs text-slate-400 italic hidden sm:inline" id="wiz-hint">Chọn một mục để tiếp tục →</span>
                    <button id="wiz-next" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-primary text-white hover:bg-slate-800 transition-all flex items-center gap-1 opacity-50 pointer-events-none" type="button">
                      Tiếp tục <i class="bi bi-arrow-right text-[10px]"></i>
                    </button>
                  </div>
                </div>

              </div>
            </div><!-- /left -->

            <!-- Right: Sticky Receipt -->
            <div class="w-full lg:w-1/3 reveal reveal-delay-200">
              <div class="sticky top-24 bg-midnight text-white rounded-3xl p-8 shadow-xl border-t-4 border-sage-500 relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute -left-16 -bottom-16 w-40 h-40 bg-sage-500/10 rounded-full blur-2xl"></div>
                <h4 class="text-xl font-bold font-display mb-6 border-b border-white/10 pb-4 flex items-center gap-2 relative z-10">
                  <i class="bi bi-receipt text-sage-400"></i> Phiếu Dự Toán
                </h4>
                <div class="space-y-4 mb-8 text-[15px] relative z-10">
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">Hệ du học:</span>
                    <span class="font-semibold text-right text-sage-300" id="summary_system">Chưa chọn</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">1. Dịch vụ Bright:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_package">Chưa chọn</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">2. Khóa học VN:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_course">Chưa chọn</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">3. Học phí trường:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_school">Chưa chọn</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">4. Ăn ở ban đầu:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_living">Chưa chọn</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">5. Thủ tục khác:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_other">Chưa chọn</span>
                  </div>
                </div>
                <div class="border-t border-white/10 pt-6 mt-6 relative z-10">
                  <div class="text-sm text-sage-300 font-bold tracking-widest uppercase mb-1">Tổng Cần Chuẩn Bị</div>
                  <div class="text-4xl font-black text-white font-display tracking-tight break-words">
                    <span id="summary_total">0</span><span class="text-xl ml-1 text-white/70 font-medium">VNĐ</span>
                  </div>
                  <p class="text-xs text-white/50 mt-3 italic">*Bảng dự toán mang tính tham khảo. Chi phí thực tế phụ thuộc tỷ giá Yên và nhu cầu tiêu dùng.</p>
                </div>
                <a href="/contact" class="w-full mt-8 block text-center bg-sage-500 hover:bg-sage-400 text-white rounded-xl py-4 font-bold transition-colors shadow-lg relative z-10">
                  Đăng ký tư vấn lộ trình này <i class="bi bi-arrow-right ml-1"></i>
                </a>
              </div>
            </div>

          </div>
        </div>

        <script>
          document.addEventListener('DOMContentLoaded', function() {

            const STEPS = [
              { title: 'Chọn Hệ du học Nhật Bản',                    sub: '',                                                              name: 'calc_system'  },
              { title: 'Chọn gói Dịch vụ Bright Education',          sub: '',                                                              name: 'calc_package' },
              { title: 'Chương trình học Tiếng Nhật tại Việt Nam',    sub: '',                                                              name: 'calc_course'  },
              { title: 'Lựa chọn Trường Nhật Ngữ (Năm đầu tiên)',    sub: '',                                                              name: 'calc_school'  },
              { title: 'Chi phí sinh hoạt ban đầu tại Nhật',          sub: '',                                                              name: 'calc_living'  },
              { title: 'Chi phí thủ tục khác tại VN',                 sub: 'Gồm: Khám lao phổi, Thi JLPT, Hộ chiếu, Vé máy bay',         name: 'calc_other'   },
            ];

            let currentStep = 0;

            const fmt   = v => new Intl.NumberFormat('vi-VN').format(v) + 'đ';
            const fmtNS = v => new Intl.NumberFormat('vi-VN').format(v);

            function updateSummary() {
              const systemEl = document.querySelector('input[name="calc_system"]:checked');
              const systemVal = systemEl ? systemEl.value : 'Chưa chọn';
              document.getElementById('summary_system').textContent = systemVal;

              const get = name => { const el = document.querySelector(`input[name="${name}"]:checked`); return el ? parseInt(el.value) : -1; };
              const vals = STEPS.slice(1).map(s => get(s.name));
              const ids  = ['summary_package','summary_course','summary_school','summary_living','summary_other'];
              vals.forEach((v, i) => {
                document.getElementById(ids[i]).textContent = v === -1 ? 'Chưa chọn' : fmt(v);
              });
              const total = vals.filter(v => v !== -1).reduce((a, b) => a + b, 0);
              const el    = document.getElementById('summary_total');
              const cur   = parseInt(el.textContent.replace(/\./g, '')) || 0;
              animateValue(el, cur, total, 400);
            }

            function animateValue(obj, start, end, dur) {
              let t0 = null;
              const tick = ts => {
                if (!t0) t0 = ts;
                const p = Math.min((ts - t0) / dur, 1);
                const e = 1 - Math.pow(1 - p, 3);
                obj.innerHTML = fmtNS(Math.floor(e * (end - start) + start));
                if (p < 1) requestAnimationFrame(tick); else obj.innerHTML = fmtNS(end);
              };
              requestAnimationFrame(tick);
            }

            function goToStep(idx) {
              document.querySelectorAll('.wiz-panel').forEach(p => p.classList.remove('wiz-active'));
              document.querySelector(`.wiz-panel[data-panel="${idx}"]`).classList.add('wiz-active');
              currentStep = idx;

              document.getElementById('wiz-num-label').textContent  = idx + 1;
              document.getElementById('wiz-badge').textContent       = idx + 1;
              document.getElementById('wiz-title-text').textContent  = STEPS[idx].title;
              document.getElementById('wiz-subtitle').textContent    = STEPS[idx].sub;

              document.querySelectorAll('[data-pip]').forEach(pip => {
                const i = parseInt(pip.dataset.pip);
                pip.style.backgroundColor = i <= idx ? 'var(--color-primary, #0d243e)' : '#e2e8f0';
                pip.style.width = i === idx ? '2rem' : (i < idx ? '1.5rem' : '1rem');
              });

              document.getElementById('wiz-back').style.visibility  = idx === 0 ? 'hidden' : 'visible';
              document.getElementById('wiz-hint').textContent        = idx < 5 ? 'Chọn một mục để tiếp tục →' : 'Đã hoàn thành tất cả các bước ✓';

              // Check if a radio is checked in the active step to enable/disable Next button
              const hasChecked = document.querySelector(`.wiz-panel[data-panel="${idx}"] input[type="radio"]:checked`) !== null;
              const nextBtn = document.getElementById('wiz-next');
              if (hasChecked) {
                nextBtn.classList.remove('opacity-50', 'pointer-events-none');
              } else {
                nextBtn.classList.add('opacity-50', 'pointer-events-none');
              }

              if (idx === 5) {
                nextBtn.innerHTML = 'Hoàn thành <i class="bi bi-check-circle ml-1"></i>';
              } else {
                nextBtn.innerHTML = 'Tiếp tục <i class="bi bi-arrow-right text-[10px]"></i>';
              }
            }

            const PRICES = {
              'Trường Nhật Ngữ': 15000000,
              'Trường Senmon': 30000000,
              'Trường Đại Học': 30000000,
              'Chương Trình Học Bổng': 15000000,
              'Hệ Đại Học Tiếng Anh': 30000000
            };

            document.querySelectorAll('input[name="calc_system"]').forEach(input => {
              input.addEventListener('change', function() {
                const val = this.value;
                const price = PRICES[val] || 15000000;
                
                // Update Step 1 (calc_package) input value and price text
                const packageInput = document.querySelector('input[name="calc_package"]');
                if (packageInput) {
                  packageInput.value = price;
                  // Reset it to force re-selection of Step 1 if the user navigates back & changes
                  packageInput.checked = false;
                }
                const priceText = document.getElementById('calc-package-price-text');
                if (priceText) {
                  priceText.textContent = new Intl.NumberFormat('vi-VN').format(price) + 'đ';
                }
                
                updateSummary();
              });
            });

            document.querySelectorAll('#calculator input[type="radio"]').forEach(input => {
              input.addEventListener('change', function() {
                updateSummary();
                const nextBtn = document.getElementById('wiz-next');
                nextBtn.classList.remove('opacity-50', 'pointer-events-none');
              });
            });

            document.getElementById('wiz-back').addEventListener('click', () => {
              if (currentStep > 0) goToStep(currentStep - 1);
            });

            document.getElementById('wiz-next').addEventListener('click', () => {
              if (currentStep < 5) {
                goToStep(currentStep + 1);
              } else {
                // Last step: scroll to contact
                const contactSec = document.getElementById('contact');
                if (contactSec) {
                  contactSec.scrollIntoView({ behavior: 'smooth' });
                }
              }
            });

            goToStep(0);
            updateSummary();
          });
        </script>
      </div>
    </section>

    <!-- Blog Section -->
    <?php if (!empty($latest_posts)): ?>
    <section id="blog" class="bg-white py-20 lg:py-28 relative">
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto mb-16 max-w-2xl text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Bài viết mới nhất</h2>
          <p class="mt-4 text-lg text-muted">Cập nhật kiến thức và kinh nghiệm sống tại Nhật Bản.</p>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach (array_slice($latest_posts, 0, 3) as $index => $post): ?>
          <article class="rounded-3xl overflow-hidden bg-white shadow-soft hover:shadow-medium transition-all duration-300 border border-slate-100 card-hover reveal reveal-delay-<?php echo $index * 100; ?>">
            <div class="relative overflow-hidden group">
                <?php if ($post['featured_image']): ?>
                <img 
                src="<?php echo getPostImage($post['featured_image']); ?>" 
                alt="<?php echo htmlspecialchars($post['title']); ?>"
                class="w-full h-56 sm:h-52 object-cover transition-transform duration-700 group-hover:scale-105"
                />
                <?php else: ?>
                <div class="w-full h-56 sm:h-52 bg-slate-100 flex items-center justify-center">
                    <i class="bi bi-image text-3xl text-slate-300"></i>
                </div>
                <?php endif; ?>
                <div class="absolute top-4 left-4">
                    <span class="inline-block rounded-full bg-white/90 backdrop-blur-sm px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-primary shadow-sm">
                        <?php echo htmlspecialchars($post['category_name']); ?>
                    </span>
                </div>
            </div>
            
            <div class="p-6 sm:p-8">
              <h3 class="text-[19px] font-bold text-primary font-display mb-3 line-clamp-2 hover:text-primary transition-colors">
                <a href="/blog/<?php echo $post['slug']; ?>">
                    <?php echo htmlspecialchars($post['title']); ?>
                </a>
              </h3>
              <p class="text-[14px] text-muted mb-6 line-clamp-3 leading-relaxed">
                <?php echo truncateText($post['excerpt'] ?: strip_tags($post['content']), 120); ?>
              </p>
              <div class="flex items-center justify-between mt-auto border-t border-slate-100 pt-5">
                <a href="/blog/<?php echo $post['slug']; ?>" class="text-primary font-bold text-sm flex items-center gap-1 hover:gap-2 transition-all">
                  Đọc tiếp <i class="bi bi-arrow-right"></i>
                </a>
                <span class="text-[12px] text-slate-400 font-medium"><i class="bi bi-calendar3 mr-1"></i><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>

        <div class="mt-14 text-center">
          <a href="/blog" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-8 py-3.5 text-[15px] font-bold text-primary transition hover:bg-slate-50 hover:border-slate-300 shadow-sm">
            Xem tất cả bài viết <i class="bi bi-arrow-right ml-2 text-primary"></i>
          </a>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Zoom Schedule Section -->
    <?php if (!empty($zoom_slots)): ?>
    <section id="zoom-schedule" class="bg-slate-50 py-20 lg:py-28 relative">
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto mb-16 max-w-2xl text-center">
          <span class="text-orange-600 font-bold tracking-wider uppercase text-xs mb-3 block">Tương tác trực tiếp</span>
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Lịch Hội Thảo & Tư Vấn Zoom</h2>
          <p class="mt-4 text-slate-500 text-[15px]">Đăng ký tham gia miễn phí các buổi chia sẻ thông tin trực tuyến từ chuyên gia Bright Education và các trường Nhật ngữ đối tác.</p>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($zoom_slots as $index => $slot): 
            $dateFormatted = date('d/m/Y', strtotime($slot['scheduled_date']));
            $dayOfWeek = ['Chủ Nhật','Thứ Hai','Thứ Ba','Thứ Tư','Thứ Năm','Thứ Sáu','Thứ Bảy'][date('w', strtotime($slot['scheduled_date']))];
          ?>
          <!-- Event Card -->
          <div class="bg-white rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-soft hover:shadow-medium hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full reveal reveal-delay-<?php echo $index * 100; ?>">
            <div>
              <div class="flex items-center justify-between gap-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold uppercase tracking-wider">
                  <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span> LIVE
                </span>
                <span class="text-xs font-bold text-slate-400"><i class="bi bi-calendar3 mr-1"></i><?php echo $slot['time_start']; ?>, <?php echo $dayOfWeek; ?> (<?php echo $dateFormatted; ?>)</span>
              </div>
              <h3 class="text-lg font-bold text-primary font-display mt-5 mb-2 leading-snug"><?php echo htmlspecialchars($slot['title']); ?></h3>
              <p class="text-[13.5px] text-slate-500 line-clamp-3 mb-4 leading-relaxed"><?php echo htmlspecialchars($slot['description']); ?></p>
              
              <div class="border-t border-slate-100 pt-4 mt-4 space-y-2.5 text-[13px] text-muted">
                <div class="flex items-center gap-2">
                  <i class="bi bi-clock text-orange-600"></i>
                  <span>Thời gian: <?php echo $slot['time_start']; ?> - <?php echo $slot['time_end']; ?></span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="bi bi-people text-orange-600"></i>
                  <span>Giới hạn: <?php echo $slot['max_participants']; ?> học viên</span>
                </div>
                <?php if ($slot['is_free']): ?>
                <div class="flex items-center gap-2">
                  <i class="bi bi-gift text-orange-600"></i>
                  <span class="text-green-600 font-bold">Hoàn toàn miễn phí</span>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <button type="button" class="w-full mt-6 py-3 bg-primary hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 zoom-btn" data-title="<?php echo htmlspecialchars($slot['title']); ?>">
              Đăng ký tham gia qua Zoom <i class="bi bi-arrow-right"></i>
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          document.querySelectorAll('.zoom-btn').forEach(btn => {
            btn.addEventListener('click', function() {
              const title = this.dataset.title;
              const contactSection = document.getElementById('contact');
              if (contactSection) {
                // Focus and populate message in form
                const messageTextarea = contactSection.querySelector('textarea[name="message"]');
                const nameInput = contactSection.querySelector('input[name="name"]');
                if (messageTextarea) {
                  messageTextarea.value = `Tôi muốn đăng ký tham gia buổi hội thảo Zoom trực tuyến: "${title}".`;
                }
                contactSection.scrollIntoView({ behavior: 'smooth' });
                if (nameInput) {
                  setTimeout(() => nameInput.focus(), 800);
                }
              }
            });
          });
        });
      </script>
    </section>
    <?php endif; ?>

    <section id="contact" class="bg-primary relative overflow-hidden">
      <!-- Background decorators -->
      <div class="absolute top-0 right-0 w-96 h-96 bg-slate-500/10 rounded-full mix-blend-screen filter blur-[100px] pointer-events-none"></div>
      <div class="absolute bottom-0 left-0 w-96 h-96 bg-slate-500/10 rounded-full mix-blend-screen filter blur-[100px] pointer-events-none"></div>

      <div class="mx-auto max-w-7xl px-5 lg:px-8 py-20 lg:py-28 relative z-10">
        <div class="grid gap-12 lg:grid-cols-[1.1fr_1fr] items-center">
          <div class="pr-0 lg:pr-10">
            <span class="text-primary-300 font-bold tracking-wider uppercase text-xs mb-3 block">Bắt đầu hành trình</span>
            <h2 class="text-3xl sm:text-4xl lg:text-[2.75rem] font-bold text-white font-display leading-[1.1]">Đặt lịch tư vấn <br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-primary-300">du học Nhật Bản</span></h2>
            <p class="mt-6 text-[17px] text-slate-300 leading-relaxed max-w-lg">
              Chúng tôi sẽ liên hệ trong vòng 24 giờ để đánh giá hồ sơ sơ bộ và đề xuất lộ trình phù hợp với năng lực của bạn.
            </p>
            
            <div class="mt-10 space-y-5">
                <div class="flex items-center gap-4 group">
                    <div class="h-12 w-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-primary-300 group-hover:bg-white/10 transition-colors">
                        <i class="bi bi-telephone-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[12px] text-slate-400 font-semibold uppercase tracking-wider">Hotline</p>
                        <p class="text-[15px] font-bold text-white mt-0.5">VN: <?php echo getSetting('site_phone', '+84 0971044576'); ?></p>
                        <p class="text-[15px] font-bold text-white mt-0.5">JP: <?php echo getSetting('site_phone_jp', '+81 08037316436'); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-4 group">
                    <div class="h-12 w-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-primary-300 group-hover:bg-white/10 transition-colors">
                        <i class="bi bi-envelope-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[12px] text-slate-400 font-semibold uppercase tracking-wider">Email</p>
                        <p class="text-[15px] font-bold text-white mt-0.5"><?php echo getSetting('site_email', 'contact@brighteducation.net'); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-4 group">
                    <div class="h-12 w-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-primary-300 group-hover:bg-white/10 transition-colors">
                        <i class="bi bi-geo-alt-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[12px] text-slate-400 font-semibold uppercase tracking-wider">Văn phòng</p>
                        <p class="text-[15px] font-bold text-white mt-0.5"><?php echo getSetting('site_address', 'Số 45 ngõ 207 Quang Trung, Phường Thành Đông, TP Hải Phòng, Việt Nam'); ?></p>
                    </div>
                </div>
            </div>

            <div class="mt-10 rounded-3xl bg-gradient-to-br from-white/5 to-transparent border border-white/10 p-6 backdrop-blur-md">
              <h4 class="text-white font-bold mb-2">Lịch tư vấn trực tiếp tháng này:</h4>
              <p class="text-sm text-slate-300 leading-relaxed">
                <span class="inline-block mr-3"><i class="bi bi-check2 text-primary-300 mr-1"></i>Thứ 3 & Thứ 6 (Hà Nội)</span>
                <span class="inline-block mr-3"><i class="bi bi-check2 text-primary-300 mr-1"></i>Thứ 5 (TP.HCM)</span>
                <span class="inline-block"><i class="bi bi-camera-video text-primary-300 mr-1"></i>Thứ 7 (Online qua Zoom)</span>
              </p>
            </div>
          </div>

          <!-- Contact Form -->
          <div class="relative">
            <form class="space-y-5 rounded-[2rem] bg-white p-8 sm:p-10 text-sm text-primary shadow-2xl reveal" method="POST" action="/api/contact.php">
                <?php echo csrfField(); ?>
                <h3 class="text-2xl font-bold text-primary font-display mb-2">Nhận tư vấn cá nhân</h3>
                <p class="text-muted mb-6">Miễn phí • Bảo mật thông tin</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <label class="block">
                    <span class="text-[12px] font-bold text-slate-700">Họ và tên <span class="text-red-500">*</span></span>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all" name="name" placeholder="Nguyễn Văn A" required />
                    </label>
                    <label class="block">
                    <span class="text-[12px] font-bold text-slate-700">Số điện thoại <span class="text-red-500">*</span></span>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all" name="phone" placeholder="+84 0971044576" required />
                    </label>
                </div>
                
                <label class="block">
                <span class="text-[12px] font-bold text-slate-700">Email</span>
                <input class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all" name="email" type="email" placeholder="email@example.com" />
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <label class="block">
                    <span class="text-[12px] font-bold text-slate-700">Kỳ nhập học</span>
                    <select class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all" name="intake_period">
                        <option>Tháng 4 năm 2025</option>
                        <option>Tháng 7 năm 2025</option>
                        <option>Tháng 10 năm 2025</option>
                        <option>Khác / Đang cân nhắc</option>
                    </select>
                    </label>
                    <label class="block">
                    <span class="text-[12px] font-bold text-slate-700">Trình độ tiếng Nhật</span>
                    <select class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all" name="japanese_level">
                        <option>Chưa học</option>
                        <option>N5</option>
                        <option>N4</option>
                        <option>N3</option>
                        <option>N2 trở lên</option>
                    </select>
                    </label>
                </div>
                
                <label class="block">
                <span class="text-[12px] font-bold text-slate-700">Ghi chú thêm</span>
                <textarea class="mt-2 h-28 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all resize-none" name="message" placeholder="Ví dụ: Mục tiêu muốn học IT ở Tokyo..."></textarea>
                </label>
                
                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-primary to-primary-800 px-4 py-4 text-[15px] font-bold text-white transition-transform hover:-translate-y-1 hover:shadow-tinted shadow-medium flex justify-center items-center gap-2">
                    Gửi yêu cầu tư vấn <i class="bi bi-send-fill text-white/80"></i>
                </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Fixed Scroll Spy Navigation on the Right -->
    <div id="scrollspy-container" class="fixed right-8 top-1/2 -translate-y-1/2 z-50 hidden xl:flex flex-col items-end w-[180px] h-[220px] overflow-hidden select-none pointer-events-none">
      <!-- Active Center Indicator -->
      <div class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-4 bg-orange-600/10 border border-orange-500 rounded-full flex items-center justify-center pointer-events-none z-10 shadow-sm">
        <div class="w-1.5 h-1.5 bg-orange-600 rounded-full animate-ping absolute"></div>
        <div class="w-1.5 h-1.5 bg-orange-600 rounded-full"></div>
      </div>
      
      <!-- Track -->
      <div id="scrollspy-track" class="flex flex-col items-end gap-6 transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] absolute right-6 w-full pointer-events-auto">
        <a href="#hero" class="scrollspy-item text-right font-display text-slate-400 select-none block hover:text-orange-600 transition-all duration-500 whitespace-nowrap cursor-pointer" data-target="hero">Trang chủ</a>
        <a href="#programs" class="scrollspy-item text-right font-display text-slate-400 select-none block hover:text-orange-600 transition-all duration-500 whitespace-nowrap cursor-pointer" data-target="programs">Chương trình</a>
        <a href="#services" class="scrollspy-item text-right font-display text-slate-400 select-none block hover:text-orange-600 transition-all duration-500 whitespace-nowrap cursor-pointer" data-target="services">Quy trình</a>
        <a href="#portal" class="scrollspy-item text-right font-display text-slate-400 select-none block hover:text-orange-600 transition-all duration-500 whitespace-nowrap cursor-pointer" data-target="portal">Thủ tục</a>
        <a href="#cost" class="scrollspy-item text-right font-display text-slate-400 select-none block hover:text-orange-600 transition-all duration-500 whitespace-nowrap cursor-pointer" data-target="cost">Dự toán</a>
        <a href="#blog" class="scrollspy-item text-right font-display text-slate-400 select-none block hover:text-orange-600 transition-all duration-500 whitespace-nowrap cursor-pointer" data-target="blog">Tin tức</a>
        <a href="#zoom-schedule" class="scrollspy-item text-right font-display text-slate-400 select-none block hover:text-orange-600 transition-all duration-500 whitespace-nowrap cursor-pointer" data-target="zoom-schedule">Lịch Zoom</a>
        <a href="#contact" class="scrollspy-item text-right font-display text-slate-400 select-none block hover:text-orange-600 transition-all duration-500 whitespace-nowrap cursor-pointer" data-target="contact">Liên hệ</a>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('scrollspy-container');
        const track = document.getElementById('scrollspy-track');
        const spyItems = Array.from(document.querySelectorAll('.scrollspy-item'));
        
        // Find matching section elements in the document
        const sections = spyItems.map(item => document.getElementById(item.dataset.target)).filter(el => el !== null);

        function updateScrollSpy() {
          if (sections.length === 0) return;

          // Find current active section
          let activeSection = sections[0];
          const scrollPos = window.scrollY + (window.innerHeight / 3);

          for (let i = 0; i < sections.length; i++) {
            const sec = sections[i];
            if (sec.offsetTop <= scrollPos) {
              activeSection = sec;
            } else {
              break;
            }
          }

          const activeId = activeSection.id;
          let activeIdx = spyItems.findIndex(item => item.dataset.target === activeId);
          if (activeIdx === -1) activeIdx = 0;

          const activeItem = spyItems[activeIdx];
          if (activeItem) {
            // Calculate translate offset to center active item in container
            const containerHeight = container.clientHeight;
            const itemHeight = activeItem.clientHeight;
            const itemOffsetTop = activeItem.offsetTop;
            const offset = (containerHeight / 2) - itemOffsetTop - (itemHeight / 2);

            track.style.transform = `translateY(${offset}px)`;

            // Update item classes based on distance from active index
            spyItems.forEach((item, idx) => {
              const dist = Math.abs(idx - activeIdx);
              item.className = 'scrollspy-item text-right font-display select-none block transition-all duration-500 whitespace-nowrap cursor-pointer';
              
              if (dist === 0) {
                // Active: Large, orange, no blur
                item.classList.add('text-sm', 'font-black', 'text-orange-600', 'scale-110', 'opacity-100');
                item.style.filter = 'none';
              } else if (dist === 1) {
                // Adjacent: Medium, slate, slight blur
                item.classList.add('text-xs', 'font-bold', 'text-slate-400', 'scale-95', 'opacity-60');
                item.style.filter = 'blur(0.5px)';
              } else {
                // Far: Small, faint, blurred
                item.classList.add('text-[10px]', 'font-semibold', 'text-slate-300/40', 'scale-85', 'opacity-25');
                item.style.filter = 'blur(1.5px)';
              }
            });
          }
        }

        // Smooth scroll on click
        spyItems.forEach(item => {
          item.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
              targetEl.scrollIntoView({ behavior: 'smooth' });
            }
          });
        });

        // Event listeners
        window.addEventListener('scroll', updateScrollSpy);
        window.addEventListener('resize', updateScrollSpy);

        // Initial setup
        setTimeout(updateScrollSpy, 150);
      });
    </script>
  </main>

<?php include 'includes/footer.php'; ?>
