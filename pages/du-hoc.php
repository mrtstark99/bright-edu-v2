<?php
require_once 'config/config.php';
$page_title = 'Du học Nhật Bản - Bright Education';
include 'includes/header.php';
?>

<main class="pt-20">

  <!-- Hero -->
  <section class="bg-primary pt-20 pb-24 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="mx-auto max-w-7xl px-5 lg:px-8 relative z-10 text-center">
      <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold text-white/80 uppercase tracking-widest mb-6">
        <i class="bi bi-mortarboard"></i> Hành trình du học
      </span>
      <h1 class="text-4xl md:text-[3.25rem] font-bold text-white font-display mb-5 tracking-tight">Du học Nhật Bản</h1>
      <p class="text-lg text-white/75 max-w-2xl mx-auto leading-relaxed">Khám phá toàn bộ lộ trình — từ dịch vụ tư vấn, quy trình chuẩn bị hồ sơ đến các chương trình học và trường Nhật Ngữ phù hợp với bạn.</p>
    </div>
  </section>

  <!-- Cards -->
  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

        <!-- Card 1 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">01</div>
          <div class="mb-4"><i class="bi bi-briefcase text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Dịch vụ tư vấn</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">Tư vấn toàn diện từ định hướng ngành, lựa chọn trường phù hợp đến hỗ trợ việc làm thêm và đồng hành 24 tháng sau khi nhập học.</p>
          <a href="/services" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Xem chi tiết <i class="bi bi-arrow-right"></i>
          </a>
        </article>

        <!-- Card 2 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal reveal-delay-100">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">02</div>
          <div class="mb-4"><i class="bi bi-arrow-right-circle text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Quy trình du học</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">6 bước chuẩn hóa từ khảo sát năng lực, chọn trường, xin COE đến visa và đón tiếp tại sân bay Nhật Bản.</p>
          <a href="/process" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Xem chi tiết <i class="bi bi-arrow-right"></i>
          </a>
        </article>

        <!-- Card 3 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal reveal-delay-200">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">03</div>
          <div class="mb-4"><i class="bi bi-file-earmark-text text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Chuẩn bị hồ sơ</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">Hướng dẫn chi tiết từng loại giấy tờ cần thiết, checklist theo từng giai đoạn và những lưu ý tránh bị từ chối hồ sơ.</p>
          <a href="/documents" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Xem chi tiết <i class="bi bi-arrow-right"></i>
          </a>
        </article>

        <!-- Card 4 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">04</div>
          <div class="mb-4"><i class="bi bi-journal-bookmark text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Khóa học tiếng Nhật</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">Chương trình học từ N5 đến N4 ngay tại Việt Nam trước khi xuất cảnh, giúp bạn tự tin giao tiếp và học tập ở Nhật.</p>
          <a href="/courses" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Xem chi tiết <i class="bi bi-arrow-right"></i>
          </a>
        </article>

        <!-- Card 5 -->
        <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all hover:shadow-medium hover:-translate-y-1 flex flex-col reveal reveal-delay-100 sm:col-span-2 lg:col-span-1">
          <div class="text-5xl font-black text-slate-100 mb-3 font-display leading-none">05</div>
          <div class="mb-4"><i class="bi bi-building text-2xl text-primary"></i></div>
          <h3 class="text-lg font-bold text-primary font-display mb-3">Trường Nhật Ngữ</h3>
          <p class="text-[14px] text-muted leading-relaxed flex-1">135+ trường đối tác tại các tỉnh thành Nhật Bản. So sánh học phí, vị trí và cơ hội việc làm thêm để chọn trường phù hợp nhất.</p>
          <a href="/schools" class="mt-6 pt-5 border-t border-slate-100 inline-flex items-center gap-2 text-[14px] font-semibold text-primary group-hover:gap-3 transition-all">
            Xem chi tiết <i class="bi bi-arrow-right"></i>
          </a>
        </article>

      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="bg-slate-50 py-16 border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 text-center">
      <h2 class="text-2xl font-bold text-primary font-display mb-3">Chưa biết bắt đầu từ đâu?</h2>
      <p class="text-muted mb-8 max-w-lg mx-auto">Đặt lịch tư vấn miễn phí 1-1 với chuyên gia — chúng tôi sẽ giúp bạn xác định lộ trình phù hợp nhất.</p>
      <a href="/consultation" class="inline-flex items-center gap-2 bg-primary text-white rounded-2xl px-8 py-3.5 font-semibold text-[15px] hover:bg-ink transition-colors shadow-md">
        Đặt lịch tư vấn miễn phí <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
