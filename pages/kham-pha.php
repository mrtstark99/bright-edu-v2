<?php
require_once 'config/config.php';
$page_title = 'Khám phá - Bright Education';
include 'includes/header.php';
?>

<main class="pt-20">

  <!-- Hero -->
  <section class="bg-primary pt-20 pb-24 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="mx-auto max-w-7xl px-5 lg:px-8 relative z-10 text-center">
      <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold text-white/80 uppercase tracking-widest mb-6">
        <i class="bi bi-compass"></i> Khám phá
      </span>
      <h1 class="text-4xl md:text-[3.25rem] font-bold text-white font-display mb-5 tracking-tight">Khám phá Bright Education</h1>
      <p class="text-lg text-white/75 max-w-2xl mx-auto leading-relaxed">Tìm hiểu câu chuyện của chúng tôi, đọc kinh nghiệm thực tế từ cựu du học sinh và kết nối với cộng đồng hơn 1200+ học viên.</p>
    </div>
  </section>

  <!-- Cards -->
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        <!-- Card 1: Blog & Cẩm nang -->
        <a href="/blog" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal">
          <div class="relative bg-gradient-to-br from-slate-700 to-slate-900 p-10 min-h-[200px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[110px] leading-none text-white/8 pointer-events-none"><i class="bi bi-newspaper"></i></div>
            <div>
              <div class="w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center mb-4">
                <i class="bi bi-newspaper text-2xl text-white"></i>
              </div>
              <div class="flex flex-wrap gap-2">
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">Kinh nghiệm</span>
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">Cẩm nang</span>
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">Tin tức</span>
              </div>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Blog & Cẩm nang</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">Chia sẻ kinh nghiệm thực tế từ cựu du học sinh, cẩm nang cuộc sống tại Nhật và những cập nhật mới nhất về thủ tục visa, học bổng.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Đọc bài viết</span>
              <div class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-200">
                <i class="bi bi-arrow-right text-sm"></i>
              </div>
            </div>
          </div>
        </a>

        <!-- Card 2: Về Bright Education -->
        <a href="/about" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal reveal-delay-100">
          <div class="relative bg-gradient-to-br from-violet-700 to-violet-900 p-10 min-h-[200px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[110px] leading-none text-white/8 pointer-events-none"><i class="bi bi-people"></i></div>
            <div>
              <div class="w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center mb-4">
                <i class="bi bi-people text-2xl text-white"></i>
              </div>
              <div class="flex flex-wrap gap-2">
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">10+ năm kinh nghiệm</span>
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">1200+ học viên</span>
              </div>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Về Bright Education</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">Câu chuyện 10 năm hình thành và phát triển, đội ngũ chuyên gia từng học tập và làm việc tại Nhật, và cam kết đồng hành minh bạch.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Tìm hiểu thêm</span>
              <div class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-200">
                <i class="bi bi-arrow-right text-sm"></i>
              </div>
            </div>
          </div>
        </a>

        <!-- Card 3: Cộng đồng -->
        <a href="/groups" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal">
          <div class="relative bg-gradient-to-br from-cyan-600 to-cyan-900 p-10 min-h-[200px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[110px] leading-none text-white/8 pointer-events-none"><i class="bi bi-people-fill"></i></div>
            <div>
              <div class="w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center mb-4">
                <i class="bi bi-people-fill text-2xl text-white"></i>
              </div>
              <div class="flex flex-wrap gap-2">
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">Nhóm Zalo</span>
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">Facebook Group</span>
              </div>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Cộng đồng du học sinh</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">Kết nối với hơn 1200+ học viên đang học tập và sinh sống tại Nhật. Chia sẻ kinh nghiệm, hỏi đáp và hỗ trợ nhau trong cuộc sống xa nhà.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Tham gia ngay</span>
              <div class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-200">
                <i class="bi bi-arrow-right text-sm"></i>
              </div>
            </div>
          </div>
        </a>

        <!-- Card 4: Đặt lịch tư vấn -->
        <a href="/consultation" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal reveal-delay-100">
          <div class="relative bg-gradient-to-br from-primary to-[#1e3f62] p-10 min-h-[200px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[110px] leading-none text-white/8 pointer-events-none"><i class="bi bi-calendar-check"></i></div>
            <div>
              <div class="w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center mb-4">
                <i class="bi bi-calendar-check text-2xl text-white"></i>
              </div>
              <div class="flex flex-wrap gap-2">
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">Miễn phí</span>
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">Tư vấn 1-1</span>
                <span class="text-[11px] font-bold bg-white/15 text-white px-2.5 py-1 rounded-full">Zoom / offline</span>
              </div>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Đặt lịch tư vấn</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">Đặt lịch tư vấn 1-1 miễn phí với chuyên gia có kinh nghiệm thực tế tại Nhật. Tư vấn qua Zoom hoặc trực tiếp tại văn phòng.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Đặt lịch ngay</span>
              <div class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-200">
                <i class="bi bi-arrow-right text-sm"></i>
              </div>
            </div>
          </div>
        </a>

      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="bg-slate-50 py-16 border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 text-center">
      <h2 class="text-2xl font-bold text-primary font-display mb-3">Bắt đầu hành trình của bạn</h2>
      <p class="text-muted mb-8 max-w-lg mx-auto">Để lại thông tin và nhận tư vấn lộ trình du học Nhật Bản phù hợp hoàn toàn miễn phí.</p>
      <a href="/contact" class="inline-flex items-center gap-2 bg-primary text-white rounded-2xl px-8 py-3.5 font-semibold text-[15px] hover:bg-ink transition-colors shadow-md">
        Liên hệ ngay <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
