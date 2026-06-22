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
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

        <!-- Card 1 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">01</div>
          <div class="mb-4"><i class="bi bi-newspaper text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Blog & Cẩm nang</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">Chia sẻ kinh nghiệm thực tế từ cựu du học sinh, cẩm nang cuộc sống tại Nhật và cập nhật thủ tục visa, học bổng mới nhất.</p>
          <a href="/blog" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Đọc bài viết <i class="bi bi-arrow-right"></i>
          </a>
        </article>

        <!-- Card 2 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal reveal-delay-100">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">02</div>
          <div class="mb-4"><i class="bi bi-people text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Về Bright Education</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">Câu chuyện 10 năm hình thành, đội ngũ chuyên gia từng học tập và làm việc tại Nhật, cam kết đồng hành minh bạch.</p>
          <a href="/about" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Tìm hiểu thêm <i class="bi bi-arrow-right"></i>
          </a>
        </article>

        <!-- Card 3 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal reveal-delay-200">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">03</div>
          <div class="mb-4"><i class="bi bi-people-fill text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Cộng đồng</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">Kết nối với hơn 1200+ học viên đang sinh sống tại Nhật. Chia sẻ kinh nghiệm, hỏi đáp và hỗ trợ nhau trong cuộc sống xa nhà.</p>
          <a href="/groups" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Tham gia ngay <i class="bi bi-arrow-right"></i>
          </a>
        </article>

        <!-- Card 4 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal reveal-delay-300">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">04</div>
          <div class="mb-4"><i class="bi bi-calendar-check text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Đặt lịch tư vấn</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">Tư vấn 1-1 miễn phí với chuyên gia có kinh nghiệm thực tế tại Nhật. Hình thức Zoom hoặc trực tiếp tại văn phòng.</p>
          <a href="/consultation" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Đặt lịch ngay <i class="bi bi-arrow-right"></i>
          </a>
        </article>

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
