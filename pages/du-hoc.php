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
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Card 1: Dịch vụ tư vấn -->
        <a href="/services" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal">
          <div class="relative bg-gradient-to-br from-sky-400 to-blue-500 p-8 min-h-[160px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[90px] leading-none text-white/8 pointer-events-none"><i class="bi bi-briefcase"></i></div>
            <div class="w-13 h-13 w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
              <i class="bi bi-briefcase text-2xl text-white"></i>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Dịch vụ tư vấn</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">Tư vấn toàn diện từ định hướng ngành, lựa chọn trường phù hợp đến hỗ trợ việc làm thêm và đồng hành 24 tháng sau khi nhập học.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Xem chi tiết</span>
              <div class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-200">
                <i class="bi bi-arrow-right text-sm"></i>
              </div>
            </div>
          </div>
        </a>

        <!-- Card 2: Quy trình du học -->
        <a href="/process" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal reveal-delay-100">
          <div class="relative bg-gradient-to-br from-emerald-400 to-teal-500 p-8 min-h-[160px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[90px] leading-none text-white/8 pointer-events-none"><i class="bi bi-arrow-right-circle"></i></div>
            <div class="w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
              <i class="bi bi-arrow-right-circle text-2xl text-white"></i>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Quy trình du học</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">6 bước chuẩn hóa từ khảo sát năng lực, chọn trường, xin COE đến visa và đón tiếp tại sân bay Nhật Bản.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Xem chi tiết</span>
              <div class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-200">
                <i class="bi bi-arrow-right text-sm"></i>
              </div>
            </div>
          </div>
        </a>

        <!-- Card 3: Chuẩn bị hồ sơ -->
        <a href="/documents" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal reveal-delay-200">
          <div class="relative bg-gradient-to-br from-amber-400 to-orange-400 p-8 min-h-[160px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[90px] leading-none text-white/8 pointer-events-none"><i class="bi bi-file-earmark-text"></i></div>
            <div class="w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
              <i class="bi bi-file-earmark-text text-2xl text-white"></i>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Chuẩn bị hồ sơ</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">Hướng dẫn chi tiết từng loại giấy tờ cần thiết, checklist theo từng giai đoạn và những lưu ý tránh bị từ chối hồ sơ.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Xem chi tiết</span>
              <div class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-200">
                <i class="bi bi-arrow-right text-sm"></i>
              </div>
            </div>
          </div>
        </a>

        <!-- Card 4: Khóa học tiếng Nhật -->
        <a href="/courses" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal">
          <div class="relative bg-gradient-to-br from-fuchsia-400 to-pink-500 p-8 min-h-[160px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[90px] leading-none text-white/8 pointer-events-none"><i class="bi bi-journal-bookmark"></i></div>
            <div class="w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
              <i class="bi bi-journal-bookmark text-2xl text-white"></i>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Khóa học tiếng Nhật</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">Chương trình học từ N5 đến N4 ngay tại Việt Nam trước khi xuất cảnh, giúp bạn tự tin giao tiếp và học tập ở Nhật.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Xem chi tiết</span>
              <div class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-200">
                <i class="bi bi-arrow-right text-sm"></i>
              </div>
            </div>
          </div>
        </a>

        <!-- Card 5: Trường Nhật Ngữ -->
        <a href="/schools" class="group flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden reveal reveal-delay-100 sm:col-span-2 lg:col-span-1">
          <div class="relative bg-gradient-to-br from-indigo-400 to-violet-500 p-8 min-h-[160px] flex items-end overflow-hidden">
            <div class="absolute -top-2 -right-2 text-[90px] leading-none text-white/8 pointer-events-none"><i class="bi bi-building"></i></div>
            <div class="w-[52px] h-[52px] rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
              <i class="bi bi-building text-2xl text-white"></i>
            </div>
          </div>
          <div class="p-7 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-primary font-display mb-3">Trường Nhật Ngữ</h3>
            <p class="text-muted text-[15px] leading-relaxed flex-1">135+ trường đối tác tại các tỉnh thành Nhật Bản. So sánh học phí, vị trí và cơ hội việc làm thêm để chọn trường phù hợp nhất.</p>
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[14px] font-semibold text-primary">Xem chi tiết</span>
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
      <h2 class="text-2xl font-bold text-primary font-display mb-3">Chưa biết bắt đầu từ đâu?</h2>
      <p class="text-muted mb-8 max-w-lg mx-auto">Đặt lịch tư vấn miễn phí 1-1 với chuyên gia — chúng tôi sẽ giúp bạn xác định lộ trình phù hợp nhất.</p>
      <a href="/consultation" class="inline-flex items-center gap-2 bg-primary text-white rounded-2xl px-8 py-3.5 font-semibold text-[15px] hover:bg-ink transition-colors shadow-md">
        Đặt lịch tư vấn miễn phí <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
