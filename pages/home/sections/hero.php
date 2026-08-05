  <link rel="stylesheet" href="/assets/css/home.css?v=<?= filemtime(APP_ROOT . '/assets/css/home.css') ?>">
  
  <style>
    /* Premium Slide-Show Animations */
    .hero-slides-wrapper {
        position: relative;
        width: 100%;
        min-height: 520px;
    }
    .hero-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        transform: translateY(20px);
        z-index: 1;
    }
    .hero-slide.active {
        position: relative; /* dictate wrapper height for fluid responsiveness */
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0);
        z-index: 10;
    }
    .hero-slide .slide-up-element {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .hero-slide.active .slide-up-element {
        opacity: 1;
        transform: translateY(0);
    }
    .hero-slide .delay-1 { transition-delay: 100ms; }
    .hero-slide .delay-2 { transition-delay: 250ms; }
    .hero-slide .delay-3 { transition-delay: 400ms; }
    .hero-slide .delay-4 { transition-delay: 550ms; }

    /* Custom Indicators dots styling */
    .indicator-dot {
        cursor: pointer;
        background-color: rgba(255, 255, 255, 0.35);
        border: none;
        outline: none;
    }
    .indicator-dot.active {
        width: 2rem !important;
        background-color: #ffffff !important;
        border-radius: 9999px;
    }
  </style>

  <section class="home-hero hero-bg-faded relative pt-[140px] pb-28 lg:pb-36 w-full min-h-[70vh] lg:min-h-[85vh] flex items-center overflow-hidden">
    
    <!-- Background SVG curves for premium layered look -->
    <div class="absolute inset-0 z-0 opacity-15 pointer-events-none">
        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full">
            <path d="M0,0 C30,40 70,10 100,50 L100,100 L0,100 Z" fill="#ffffff"></path>
            <path d="M0,50 C40,80 60,30 100,60 L100,100 L0,100 Z" fill="#c5d3df"></path>
        </svg>
    </div>

    <!-- Nav arrows -->
    <button id="hero-prev" class="absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 flex items-center justify-center transition-all select-none hidden md:flex" aria-label="Slide trước">
      <i class="bi bi-chevron-left text-xl"></i>
    </button>
    <button id="hero-next" class="absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 flex items-center justify-center transition-all select-none hidden md:flex" aria-label="Slide tiếp theo">
      <i class="bi bi-chevron-right text-xl"></i>
    </button>

    <div class="relative mx-auto max-w-7xl px-5 lg:px-8 w-full z-10">
      <div class="hero-slides-wrapper w-full relative">
        
        <!-- SLIDE 1: General Overview -->
        <div class="hero-slide active grid gap-12 lg:gap-8 lg:grid-cols-2 items-center w-full">
          <!-- Left: Content -->
          <div class="relative z-10 self-center">
            <div class="space-y-6 relative z-10 text-left">
              <span class="home-eyebrow font-bold tracking-wider uppercase text-xs mb-3 block slide-up-element delay-1 text-primary-300">Chắp cánh tương lai</span>
              <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold leading-[1.2] tracking-tight text-white font-display slide-up-element delay-2">
                Du học Nhật Bản cùng <span class="block mt-2 text-primary-300 text-[1.15em] drop-shadow-sm">Bright Education</span>
              </h1>
              <p class="text-base sm:text-lg text-slate-300 max-w-lg leading-relaxed font-medium slide-up-element delay-3">
                Quy trình linh động và minh bạch sẽ giúp các bước chuẩn bị du học của bạn thuận lợi hơn khi đồng hành cùng Bright Education.
              </p>
              <div class="flex flex-wrap items-center gap-4 pt-4 slide-up-element delay-4">
                <a class="home-hero-primary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-primary transition-all shadow-medium" href="/consultation">
                  Đặt lịch tư vấn miễn phí <i class="bi bi-arrow-right ml-2"></i>
                </a>
                <a class="home-hero-secondary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-white transition" href="/services">
                  Xem quy trình <i class="bi bi-play-circle ml-2 text-primary"></i>
                </a>
              </div>
            </div>
          </div>

          <!-- Right: Image & Floating Badges -->
          <div class="relative z-10 mt-12 lg:mt-0 flex justify-center lg:justify-end py-8 slide-up-element delay-2">
            <div class="relative w-fit lg:-left-[40px]">
              <div class="absolute top-1/2 left-1/2 w-[280px] h-[280px] md:w-[420px] md:h-[420px] bg-orange-600/10 blur-3xl z-0 animate-morph-glow"></div>

              <!-- Floating Card 1 -->
              <div class="absolute top-[2%] left-[2%] md:left-[-15%] z-20 bg-white border border-slate-100 rounded-2xl p-2.5 md:p-3 shadow-soft hidden md:flex items-center gap-2 md:gap-3 animate-float-1">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary text-sm md:text-lg font-black shadow-sm">
                      <i class="bi bi-mortarboard-fill"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Hồ sơ</p>
                      <p class="text-[11px] md:text-sm font-black text-slate-800 leading-none">Đỗ COE 99.9%</p>
                  </div>
              </div>

              <!-- Floating Card 2 -->
              <div class="absolute top-[18%] right-[2%] md:right-[-25%] z-20 bg-orange-600 text-white border border-orange-500/20 rounded-2xl p-2.5 md:p-3 shadow-xl hidden md:flex items-center gap-2 md:gap-3 animate-float-2">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center text-white text-sm md:text-lg font-black shadow-sm">
                      <i class="bi bi-cash-coin"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-orange-200 uppercase tracking-wider leading-none mb-1">Học phí</p>
                      <p class="text-[11px] md:text-sm font-black text-white leading-none">Minh bạch 100%</p>
                  </div>
              </div>

              <!-- Floating Card 3 -->
              <div class="absolute top-[43%] -left-[5%] md:-left-[32%] z-20 bg-primary text-white border-2 border-primary-800/20 rounded-2xl p-2.5 md:p-3 shadow-xl hidden md:flex items-center gap-2 md:gap-3 animate-float-3">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center text-white text-sm md:text-lg font-black shadow-sm">
                      <i class="bi bi-building"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-primary-200 uppercase tracking-wider leading-none mb-1">Đối tác</p>
                      <p class="text-[11px] md:text-sm font-black text-white leading-none">500+ Trường Nhật</p>
                  </div>
              </div>

              <!-- Floating Card 4 -->
              <div class="absolute bottom-[6%] left-[2%] md:left-[-15%] z-20 bg-white border border-slate-100 rounded-2xl p-2.5 md:p-3 shadow-soft hidden md:flex items-center gap-2 md:gap-3 animate-float-4">
                  <div class="w-8 h-8 md:w-10 md:h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary text-sm md:text-lg font-black shadow-sm">
                      <i class="bi bi-headset"></i>
                  </div>
                  <div class="text-left">
                      <p class="text-[8px] md:text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Hỗ trợ</p>
                      <p class="text-[11px] md:text-sm font-black text-slate-800 leading-none">Tư vấn 24/7</p>
                  </div>
              </div>

              <!-- Floating Card 5 -->
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

        <!-- SLIDE 2: Japanese Language School -->
        <div class="hero-slide grid gap-12 lg:gap-8 lg:grid-cols-2 items-center w-full">
          <div class="relative z-10 self-center">
            <div class="space-y-6 text-left">
              <span class="home-eyebrow font-bold tracking-wider uppercase text-xs mb-3 block slide-up-element delay-1 text-primary-300">Lộ trình nền tảng</span>
              <h2 class="text-4xl sm:text-5xl lg:text-[3.2rem] font-extrabold leading-[1.2] tracking-tight text-white font-display slide-up-element delay-2">
                Trường Nhật Ngữ <span class="block mt-2 text-primary-300 text-[0.9em] drop-shadow-sm">Đào tạo tiếng tập trung</span>
              </h2>
              <p class="text-base sm:text-lg text-slate-300 max-w-lg leading-relaxed font-medium slide-up-element delay-3">
                Giải pháp toàn diện học tiếng Nhật bài bản từ 1.5 - 2 năm tại Nhật Bản, làm bước đệm vững chắc chuyển tiếp lên Đại học, Senmon hoặc đi làm.
              </p>
              <div class="flex flex-wrap items-center gap-4 pt-4 slide-up-element delay-4">
                <a class="home-hero-primary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-primary transition-all shadow-medium" href="/consultation">
                  Tư vấn lộ trình miễn phí <i class="bi bi-arrow-right ml-2"></i>
                </a>
                <a class="home-hero-secondary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-white transition" href="/services/japanese-language-school">
                  Xem chi tiết lộ trình
                </a>
              </div>
            </div>
          </div>
          
          <!-- Right Stats Panel Card -->
          <div class="relative z-10 mt-8 lg:mt-0 flex justify-center lg:justify-end py-8 w-full max-w-md mx-auto slide-up-element delay-2">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[2.5rem] p-8 w-full shadow-hard text-white relative text-left">
              <div class="absolute -top-6 -left-6 w-14 h-14 bg-orange-600 rounded-2xl flex items-center justify-center shadow-lg border border-orange-500/30">
                <i class="bi bi-translate text-2xl text-white"></i>
              </div>
              <h4 class="text-xl font-bold font-display mb-4 text-primary-300">Đào tạo & Hồ sơ</h4>
              <div class="space-y-4">
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-check-circle text-emerald-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Chứng chỉ yêu cầu</p><p class="text-sm font-bold">N5 trở lên (Sẽ đào tạo từ đầu)</p></div>
                </div>
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-calendar-event text-sky-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Kỳ tuyển sinh chính</p><p class="text-sm font-bold">Tháng 4, Tháng 7, Tháng 10, Tháng 1</p></div>
                </div>
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-shield-check text-amber-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Tỷ lệ đỗ COE</p><p class="text-sm font-bold">99.9% (Bright Education trọn gói)</p></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SLIDE 3: Senmon Vocational School -->
        <div class="hero-slide grid gap-12 lg:gap-8 lg:grid-cols-2 items-center w-full">
          <div class="relative z-10 self-center">
            <div class="space-y-6 text-left">
              <span class="home-eyebrow font-bold tracking-wider uppercase text-xs mb-3 block slide-up-element delay-1 text-primary-300">Học nghề thực tiễn</span>
              <h2 class="text-4xl sm:text-5xl lg:text-[3.2rem] font-extrabold leading-[1.2] tracking-tight text-white font-display slide-up-element delay-2">
                Trường Chuyên Môn <span class="block mt-2 text-primary-300 text-[0.9em] drop-shadow-sm">Học nghề & Việc làm ngay</span>
              </h2>
              <p class="text-base sm:text-lg text-slate-300 max-w-lg leading-relaxed font-medium slide-up-element delay-3">
                Đào tạo thực hành nghề chuyên sâu từ 2 - 3 năm. Nhận bằng Chuyên viên được công nhận toàn Nhật Bản, chuyển đổi visa đi làm ngay.
              </p>
              <div class="flex flex-wrap items-center gap-4 pt-4 slide-up-element delay-4">
                <a class="home-hero-primary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-primary transition-all shadow-medium" href="/consultation">
                  Tư vấn chọn ngành nghề <i class="bi bi-arrow-right ml-2"></i>
                </a>
                <a class="home-hero-secondary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-white transition" href="/services/senmon-vocational-school">
                  Xem chi tiết lộ trình
                </a>
              </div>
            </div>
          </div>
          
          <!-- Right Grid Majors Card -->
          <div class="relative z-10 mt-8 lg:mt-0 flex justify-center lg:justify-end py-8 w-full max-w-md mx-auto slide-up-element delay-2">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[2.5rem] p-8 w-full shadow-hard text-white relative text-left">
              <div class="absolute -top-6 -left-6 w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg border border-indigo-500/30">
                <i class="bi bi-tools text-2xl text-white"></i>
              </div>
              <h4 class="text-xl font-bold font-display mb-4 text-primary-300">Đào tạo nghề chuyên sâu</h4>
              <div class="space-y-4">
                <p class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Các ngành nghề nổi bật:</p>
                <div class="grid grid-cols-2 gap-2.5">
                  <div class="p-3 bg-white/5 rounded-xl border border-white/5 text-xs font-bold flex items-center gap-2"><i class="bi bi-laptop text-indigo-400"></i> CNTT / Lập trình</div>
                  <div class="p-3 bg-white/5 rounded-xl border border-white/5 text-xs font-bold flex items-center gap-2"><i class="bi bi-palette text-rose-400"></i> Thiết kế / Anime</div>
                  <div class="p-3 bg-white/5 rounded-xl border border-white/5 text-xs font-bold flex items-center gap-2"><i class="bi bi-cup-hot text-amber-400"></i> Khách sạn / DL</div>
                  <div class="p-3 bg-white/5 rounded-xl border border-white/5 text-xs font-bold flex items-center gap-2"><i class="bi bi-gear-fill text-emerald-400"></i> Công nghệ Ô tô</div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/10 text-center">
                  <span class="text-xs text-slate-400">Yêu cầu: N3 tiếng Nhật trở lên hoặc tốt nghiệp trường tiếng.</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SLIDE 4: University Program -->
        <div class="hero-slide grid gap-12 lg:gap-8 lg:grid-cols-2 items-center w-full">
          <div class="relative z-10 self-center">
            <div class="space-y-6 text-left">
              <span class="home-eyebrow font-bold tracking-wider uppercase text-xs mb-3 block slide-up-element delay-1 text-primary-300">Học vấn đỉnh cao</span>
              <h2 class="text-4xl sm:text-5xl lg:text-[3.2rem] font-extrabold leading-[1.2] tracking-tight text-white font-display slide-up-element delay-2">
                Đại Học Chính Quy <span class="block mt-2 text-primary-300 text-[0.9em] drop-shadow-sm">Săn học bổng trực tiếp</span>
              </h2>
              <p class="text-base sm:text-lg text-slate-300 max-w-lg leading-relaxed font-medium slide-up-element delay-3">
                Chương trình cử nhân 4 năm chuẩn quốc tế. Hỗ trợ săn học bổng giảm 30% - 100% học phí trực tiếp từ các trường đại học hàng đầu đối tác.
              </p>
              <div class="flex flex-wrap items-center gap-4 pt-4 slide-up-element delay-4">
                <a class="home-hero-primary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-primary transition-all shadow-medium" href="/consultation">
                  Săn học bổng Đại học <i class="bi bi-arrow-right ml-2"></i>
                </a>
                <a class="home-hero-secondary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-white transition" href="/services/university-program">
                  Xem chi tiết lộ trình
                </a>
              </div>
            </div>
          </div>
          
          <!-- Right University Stats Panel Card -->
          <div class="relative z-10 mt-8 lg:mt-0 flex justify-center lg:justify-end py-8 w-full max-w-md mx-auto slide-up-element delay-2">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[2.5rem] p-8 w-full shadow-hard text-white relative text-left">
              <div class="absolute -top-6 -left-6 w-14 h-14 bg-sky-500 rounded-2xl flex items-center justify-center shadow-lg border border-sky-400/30">
                <i class="bi bi-mortarboard-fill text-2xl text-white"></i>
              </div>
              <h4 class="text-xl font-bold font-display mb-4 text-primary-300">Cử nhân chính quy 4 năm</h4>
              <div class="space-y-4">
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-gift text-emerald-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Học bổng liên kết</p><p class="text-sm font-bold">Tài trợ 30% - 100% học phí cử nhân</p></div>
                </div>
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-globe text-indigo-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Hệ đào tạo linh hoạt</p><p class="text-sm font-bold">Tiếng Nhật (N2+) & Tiếng Anh (IELTS/E-Track)</p></div>
                </div>
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-check2-circle text-orange-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Trường đối tác</p><p class="text-sm font-bold">Hơn 50 trường Đại học danh tiếng tại Nhật</p></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SLIDE 5: Scholarship Program -->
        <div class="hero-slide grid gap-12 lg:gap-8 lg:grid-cols-2 items-center w-full">
          <div class="relative z-10 self-center">
            <div class="space-y-6 text-left">
              <span class="home-eyebrow font-bold tracking-wider uppercase text-xs mb-3 block slide-up-element delay-1 text-primary-300">Tối ưu tài chính</span>
              <h2 class="text-4xl sm:text-5xl lg:text-[3.2rem] font-extrabold leading-[1.2] tracking-tight text-white font-display slide-up-element delay-2">
                Học Bổng Toàn Phần <span class="block mt-2 text-primary-300 text-[0.9em] drop-shadow-sm">Du học tự túc phí 0đ</span>
              </h2>
              <p class="text-base sm:text-lg text-slate-300 max-w-lg leading-relaxed font-medium slide-up-element delay-3">
                Học bổng điều dưỡng, báo chí, nhà hàng... Hỗ trợ 100% học phí, tài trợ ký túc xá và cam kết có việc làm thêm lương cao ngay sau khi sang Nhật.
              </p>
              <div class="flex flex-wrap items-center gap-4 pt-4 slide-up-element delay-4">
                <a class="home-hero-primary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-primary transition-all shadow-medium" href="/consultation">
                  Xem điều kiện học bổng <i class="bi bi-arrow-right ml-2"></i>
                </a>
                <a class="home-hero-secondary inline-flex items-center justify-center rounded-2xl px-6 py-3.5 text-[15px] font-semibold text-white transition" href="/services/scholarship-program">
                  Xem chi tiết lộ trình
                </a>
              </div>
            </div>
          </div>
          
          <!-- Right Scholarship Stats Panel Card -->
          <div class="relative z-10 mt-8 lg:mt-0 flex justify-center lg:justify-end py-8 w-full max-w-md mx-auto slide-up-element delay-2">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[2.5rem] p-8 w-full shadow-hard text-white relative text-left">
              <div class="absolute -top-6 -left-6 w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-lg border border-emerald-400/30">
                <i class="bi bi-cash-coin text-2xl text-white"></i>
              </div>
              <h4 class="text-xl font-bold font-display mb-4 text-primary-300">Học bổng doanh nghiệp & tổ chất</h4>
              <div class="space-y-4">
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-gift text-emerald-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Học phí & Ký túc xá</p><p class="text-sm font-bold">Tài trợ 100% (Không phát sinh nợ)</p></div>
                </div>
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-briefcase text-sky-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Việc làm thêm</p><p class="text-sm font-bold">Bố trí việc làm ngay, lương 110k - 140k JPY/tháng</p></div>
                </div>
                <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                  <i class="bi bi-shield-check text-amber-400 text-lg"></i>
                  <div><p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Cam kết đầu ra</p><p class="text-sm font-bold">Hỗ trợ hồ sơ chuyên môn đặc định đi làm lâu dài</p></div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Slide Indicators Navigation dots -->
      <div class="absolute -bottom-12 sm:-bottom-16 left-1/2 -translate-x-1/2 z-30 flex gap-3">
        <button class="w-8 h-2.5 rounded-full indicator-dot active" data-slide="0" aria-label="Slide 1"></button>
        <button class="w-2.5 h-2.5 rounded-full indicator-dot" data-slide="1" aria-label="Slide 2"></button>
        <button class="w-2.5 h-2.5 rounded-full indicator-dot" data-slide="2" aria-label="Slide 3"></button>
        <button class="w-2.5 h-2.5 rounded-full indicator-dot" data-slide="3" aria-label="Slide 4"></button>
        <button class="w-2.5 h-2.5 rounded-full indicator-dot" data-slide="4" aria-label="Slide 5"></button>
      </div>

    </div>

    <!-- Wave Bottom Curve -->
    <div class="wave-bottom">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const slides = document.querySelectorAll('.hero-slide');
      const dots = document.querySelectorAll('.indicator-dot');
      const prevBtn = document.getElementById('hero-prev');
      const nextBtn = document.getElementById('hero-next');
      let currentSlide = 0;
      let slideInterval = null;

      function showSlide(index) {
        if (slides.length === 0) return;

        // Wrap slides index
        if (index >= slides.length) index = 0;
        if (index < 0) index = slides.length - 1;

        slides.forEach((slide, i) => {
          if (i === index) {
            slide.classList.add('active');
          } else {
            slide.classList.remove('active');
          }
        });

        dots.forEach((dot, i) => {
          if (i === index) {
            dot.classList.add('active');
          } else {
            dot.classList.remove('active');
          }
        });

        currentSlide = index;
      }

      function nextSlide() {
        showSlide(currentSlide + 1);
      }

      function prevSlide() {
        showSlide(currentSlide - 1);
      }

      function startAutoSlide() {
        stopAutoSlide();
        slideInterval = setInterval(nextSlide, 6000); // Change slide every 6 seconds
      }

      function stopAutoSlide() {
        if (slideInterval) {
          clearInterval(slideInterval);
          slideInterval = null;
        }
      }

      // Dots navigation click handlers
      dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
          stopAutoSlide();
          showSlide(i);
          startAutoSlide();
        });
      });

      // Navigation arrows click handlers
      if (prevBtn) {
        prevBtn.addEventListener('click', () => {
          stopAutoSlide();
          prevSlide();
          startAutoSlide();
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', () => {
          stopAutoSlide();
          nextSlide();
          startAutoSlide();
        });
      }

      // Initialize
      showSlide(0);
      startAutoSlide();

      // Pause slide change when user hovers mouse over the slideshow container
      const wrapper = document.querySelector('.hero-slides-wrapper');
      if (wrapper) {
        wrapper.addEventListener('mouseenter', stopAutoSlide);
        wrapper.addEventListener('mouseleave', startAutoSlide);
      }
    });
  </script>
