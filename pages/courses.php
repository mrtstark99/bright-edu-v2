<?php
$page_title = "Khóa học Tiếng Nhật - Bright Education";
include 'includes/header.php';
?>

<main class="pt-20">
  <!-- Hero Section -->
  <section class="relative bg-midnight overflow-hidden py-24 sm:py-32">
    <!-- Background Effects -->
    <div class="absolute inset-0 z-0">
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-sage-500/20 rounded-full blur-3xl mix-blend-screen"></div>
      <div class="absolute bottom-0 -left-24 w-72 h-72 bg-sky-500/20 rounded-full blur-3xl mix-blend-screen"></div>
      <div class="absolute inset-0 bg-[url('/assets/images/pattern-grid.svg')] opacity-5"></div>
    </div>
    
    <div class="container mx-auto px-6 lg:px-12 relative z-10">
      <div class="max-w-3xl">
        <span class="inline-flex items-center justify-center bg-white/10 text-sage-300 backdrop-blur-md px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide uppercase mb-6 border border-white/10">
          Chương Trình Đào Tạo
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white font-display leading-tight mb-6">
          Nền tảng ngôn ngữ<br>
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-sage-300 to-sky-300">vững chắc cho tương lai</span>
        </h1>
        <p class="text-lg text-slate-300 mb-10 max-w-2xl leading-relaxed">
          Tại Bright Education, chúng tôi không chỉ dạy tiếng Nhật. Chúng tôi trang bị cho bạn chìa khóa để tự tin bước ra thế giới, hòa nhập văn hóa và sẵn sàng cho hành trình du học thành công.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="#khoa-hoc" class="bg-sage-500 hover:bg-sage-400 text-white rounded-xl px-8 py-3.5 font-bold transition-all shadow-lg hover:shadow-sage-500/30 flex items-center gap-2">
            Xem các khóa học <i class="bi bi-arrow-down"></i>
          </a>
          <a href="/contact" class="bg-white/10 hover:bg-white/20 text-white backdrop-blur-md border border-white/10 rounded-xl px-8 py-3.5 font-bold transition-all flex items-center gap-2">
            Đăng ký test đầu vào miễn phí
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Lợi thế cạnh tranh (Why us?) -->
  <section class="py-24 bg-white relative">
    <div class="container mx-auto px-6 lg:px-12">
      <div class="text-center mb-16 max-w-2xl mx-auto">
        <h2 class="text-3xl sm:text-4xl font-black text-midnight font-display mb-4">Tại sao chọn học tại Bright?</h2>
        <p class="text-muted text-lg">Môi trường học tập tiêu chuẩn Nhật Bản ngay tại Việt Nam.</p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-soft transition-all hover:-translate-y-1">
          <div class="w-14 h-14 rounded-2xl bg-sky-100 flex items-center justify-center text-sky-500 text-2xl mb-6">
            <i class="bi bi-person-video3"></i>
          </div>
          <h3 class="text-xl font-bold text-midnight mb-3">Giáo viên bản xứ</h3>
          <p class="text-muted">Đội ngũ Sensei người Nhật trực tiếp giảng dạy giao tiếp và luyện phát âm chuẩn giọng Tokyo.</p>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-soft transition-all hover:-translate-y-1">
          <div class="w-14 h-14 rounded-2xl bg-sage-100 flex items-center justify-center text-sage-500 text-2xl mb-6">
            <i class="bi bi-award"></i>
          </div>
          <h3 class="text-xl font-bold text-midnight mb-3">Cam kết đầu ra JLPT</h3>
          <p class="text-muted">Chương trình bám sát đề thi năng lực tiếng Nhật, cam kết học viên đạt N5/N4 đúng lộ trình.</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-soft transition-all hover:-translate-y-1">
          <div class="w-14 h-14 rounded-2xl bg-sakura-100 flex items-center justify-center text-sakura-500 text-2xl mb-6">
            <i class="bi bi-people"></i>
          </div>
          <h3 class="text-xl font-bold text-midnight mb-3">Lớp học sĩ số nhỏ</h3>
          <p class="text-muted">Tối đa 15 học viên/lớp đảm bảo giáo viên có thể kèm cặp và tương tác với từng cá nhân.</p>
        </div>

        <!-- Card 4 -->
        <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-soft transition-all hover:-translate-y-1">
          <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-500 text-2xl mb-6">
            <i class="bi bi-mic"></i>
          </div>
          <h3 class="text-xl font-bold text-midnight mb-3">Luyện phỏng vấn 1-1</h3>
          <p class="text-muted">Rèn luyện kỹ năng trả lời phỏng vấn Cục xuất nhập cảnh và trường Nhật ngữ tự tin nhất.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Danh sách khóa học -->
  <section id="khoa-hoc" class="py-24 bg-slate-50 border-t border-slate-100 relative">
    <!-- Decorative -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-sage-100/50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-sky-100/50 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>

    <div class="container mx-auto px-6 lg:px-12 relative z-10">
      <div class="text-center mb-16 max-w-2xl mx-auto">
        <h2 class="text-3xl sm:text-4xl font-black text-midnight font-display mb-4">Các Khóa Học Tiêu Biểu</h2>
        <p class="text-muted text-lg">Lựa chọn lộ trình phù hợp nhất với mục tiêu của bạn.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        
        <!-- Course 1 -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-medium transition-all group flex flex-col">
          <div class="p-8 pb-6 bg-slate-50/50 border-b border-slate-100">
            <span class="inline-block bg-sky-100 text-sky-600 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-lg mb-4">Cho người mới bắt đầu</span>
            <h3 class="text-2xl font-bold text-midnight mb-2 group-hover:text-sky-500 transition-colors">Khóa N5 - Nền Tảng</h3>
            <p class="text-muted text-sm line-clamp-2">Làm quen với bảng chữ cái, ngữ pháp cơ bản và giao tiếp đời sống hàng ngày.</p>
          </div>
          <div class="p-8 flex flex-col flex-grow">
            <div class="flex items-end gap-1 mb-6">
              <span class="text-3xl font-black text-midnight">10.000.000</span>
              <span class="text-muted font-medium mb-1">VNĐ</span>
            </div>
            <ul class="space-y-4 mb-8 text-sm text-slate-600 flex-grow">
              <li class="flex items-start gap-3"><i class="bi bi-clock text-sky-500 mt-0.5"></i> <span>Thời lượng: <strong>3 tháng</strong> (5 buổi/tuần)</span></li>
              <li class="flex items-start gap-3"><i class="bi bi-book text-sky-500 mt-0.5"></i> <span>Giáo trình: Minna no Nihongo I</span></li>
              <li class="flex items-start gap-3"><i class="bi bi-bullseye text-sky-500 mt-0.5"></i> <span>Mục tiêu: Đạt chứng chỉ N5, giao tiếp cơ bản</span></li>
            </ul>
            <a href="/contact" class="block w-full py-3.5 px-4 text-center rounded-xl bg-slate-100 hover:bg-sky-50 text-midnight hover:text-sky-600 font-bold transition-colors">
              Đăng ký học
            </a>
          </div>
        </div>

        <!-- Course 2 -->
        <div class="bg-white rounded-3xl border-2 border-sage-500 overflow-hidden shadow-md hover:shadow-xl hover:shadow-sage-500/20 transition-all group flex flex-col relative scale-105 z-10">
          <div class="absolute top-0 right-0 bg-sage-500 text-white text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-bl-xl">Bán chạy nhất</div>
          <div class="p-8 pb-6 bg-sage-50 border-b border-sage-100">
            <span class="inline-block bg-white text-sage-600 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-lg mb-4 shadow-sm">Tiêu chuẩn du học</span>
            <h3 class="text-2xl font-bold text-midnight mb-2">Khóa N4 - Chuyên Sâu</h3>
            <p class="text-muted text-sm line-clamp-2">Đào tạo chuyên sâu ngữ pháp, từ vựng và kỹ năng nghe hiểu đáp ứng kỳ thi năng lực.</p>
          </div>
          <div class="p-8 flex flex-col flex-grow">
            <div class="flex items-end gap-1 mb-6">
              <span class="text-4xl font-black text-midnight">15.000.000</span>
              <span class="text-muted font-medium mb-1">VNĐ</span>
            </div>
            <ul class="space-y-4 mb-8 text-sm text-slate-600 flex-grow">
              <li class="flex items-start gap-3"><i class="bi bi-clock text-sage-500 mt-0.5 text-lg"></i> <span>Thời lượng: <strong>6 tháng</strong> (5 buổi/tuần)</span></li>
              <li class="flex items-start gap-3"><i class="bi bi-book text-sage-500 mt-0.5 text-lg"></i> <span>Giáo trình: Minna no Nihongo I & II</span></li>
              <li class="flex items-start gap-3"><i class="bi bi-check2-circle text-sage-500 mt-0.5 text-lg"></i> <span>Cam kết đầu ra JLPT/NAT-TEST N4</span></li>
              <li class="flex items-start gap-3"><i class="bi bi-chat-text text-sage-500 mt-0.5 text-lg"></i> <span>Tặng kèm khóa luyện giao tiếp 1 tháng</span></li>
            </ul>
            <a href="/contact" class="block w-full py-4 px-4 text-center rounded-xl bg-sage-500 hover:bg-sage-400 text-white font-bold transition-colors shadow-md">
              Đăng ký ngay
            </a>
          </div>
        </div>

        <!-- Course 3 -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-medium transition-all group flex flex-col">
          <div class="p-8 pb-6 bg-slate-50/50 border-b border-slate-100">
            <span class="inline-block bg-sakura-100 text-sakura-600 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-lg mb-4">Khóa ngắn hạn</span>
            <h3 class="text-2xl font-bold text-midnight mb-2 group-hover:text-sakura-500 transition-colors">Luyện Phỏng Vấn</h3>
            <p class="text-muted text-sm line-clamp-2">Trang bị kỹ năng tác phong, cách trả lời phỏng vấn Cục XNC và Trường Nhật Ngữ.</p>
          </div>
          <div class="p-8 flex flex-col flex-grow">
            <div class="flex items-end gap-1 mb-6">
              <span class="text-3xl font-black text-midnight">3.500.000</span>
              <span class="text-muted font-medium mb-1">VNĐ</span>
            </div>
            <ul class="space-y-4 mb-8 text-sm text-slate-600 flex-grow">
              <li class="flex items-start gap-3"><i class="bi bi-clock text-sakura-500 mt-0.5"></i> <span>Thời lượng: <strong>1 tháng</strong> (3 buổi/tuần)</span></li>
              <li class="flex items-start gap-3"><i class="bi bi-mic text-sakura-500 mt-0.5"></i> <span>Thực hành mock-interview 1-1 liên tục</span></li>
              <li class="flex items-start gap-3"><i class="bi bi-award text-sakura-500 mt-0.5"></i> <span>Chỉnh sửa phát âm, tác phong chuẩn Nhật</span></li>
            </ul>
            <a href="/contact" class="block w-full py-3.5 px-4 text-center rounded-xl bg-slate-100 hover:bg-sakura-50 text-midnight hover:text-sakura-600 font-bold transition-colors">
              Đăng ký học
            </a>
          </div>
        </div>

      </div>

      <!-- Các khóa học ngắn hạn -->
      <div class="mt-20 pt-16 border-t border-slate-200 relative z-10">
        <div class="text-center mb-12">
          <h3 class="text-2xl font-bold text-midnight font-display mb-3">Các Chuyên Đề Ngắn Hạn</h3>
          <p class="text-muted">Dành cho các bạn đã có nền tảng tiếng Nhật, cần chuẩn bị kỹ năng đặc thù.</p>
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Short Course 1 -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-sky-300 hover:shadow-soft transition-all group">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <i class="bi bi-person-badge text-xl"></i>
            </div>
            <h4 class="font-bold text-midnight mb-2 line-clamp-2 min-h-[48px]">Luyện phỏng vấn trường Senmon</h4>
            <div class="text-sky-600 font-black mb-3">1.500.000 VNĐ</div>
            <p class="text-sm text-muted mb-4 line-clamp-2">Kỹ năng trả lời tự tin, đúng trọng tâm ngành học.</p>
            <a href="/contact" class="text-sm font-bold text-sky-500 hover:text-sky-600 flex items-center gap-1">Đăng ký <i class="bi bi-arrow-right"></i></a>
          </div>

          <!-- Short Course 2 -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-sage-300 hover:shadow-soft transition-all group">
            <div class="w-10 h-10 rounded-xl bg-sage-50 text-sage-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <i class="bi bi-bank text-xl"></i>
            </div>
            <h4 class="font-bold text-midnight mb-2 line-clamp-2 min-h-[48px]">Luyện phỏng vấn Đại học (Dai)</h4>
            <div class="text-sage-600 font-black mb-3">2.000.000 VNĐ</div>
            <p class="text-sm text-muted mb-4 line-clamp-2">Luyện tập mock-interview với các câu hỏi học thuật khó.</p>
            <a href="/contact" class="text-sm font-bold text-sage-500 hover:text-sage-600 flex items-center gap-1">Đăng ký <i class="bi bi-arrow-right"></i></a>
          </div>

          <!-- Short Course 3 -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-sakura-300 hover:shadow-soft transition-all group">
            <div class="w-10 h-10 rounded-xl bg-sakura-50 text-sakura-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <i class="bi bi-pencil-square text-xl"></i>
            </div>
            <h4 class="font-bold text-midnight mb-2 line-clamp-2 min-h-[48px]">Cách viết Lý do du học & Entry Sheet</h4>
            <div class="text-sakura-600 font-black mb-3">1.000.000 VNĐ</div>
            <p class="text-sm text-muted mb-4 line-clamp-2">Hướng dẫn viết hồ sơ và lý do du học thuyết phục.</p>
            <a href="/contact" class="text-sm font-bold text-sakura-500 hover:text-sakura-600 flex items-center gap-1">Đăng ký <i class="bi bi-arrow-right"></i></a>
          </div>

          <!-- Short Course 4 -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-amber-300 hover:shadow-soft transition-all group">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <i class="bi bi-briefcase text-xl"></i>
            </div>
            <h4 class="font-bold text-midnight mb-2 line-clamp-2 min-h-[48px]">Phỏng vấn xin việc (Arubaito)</h4>
            <div class="text-amber-600 font-black mb-3">1.000.000 VNĐ</div>
            <p class="text-sm text-muted mb-4 line-clamp-2">Cách gọi điện thoại, chuẩn bị CV và phỏng vấn làm thêm.</p>
            <a href="/contact" class="text-sm font-bold text-amber-500 hover:text-amber-600 flex items-center gap-1">Đăng ký <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Đội ngũ giáo viên (Giả định) -->
  <section class="py-24 bg-white relative overflow-hidden">
    <div class="container mx-auto px-6 lg:px-12 relative z-10">
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
        <div class="max-w-2xl">
          <h2 class="text-3xl sm:text-4xl font-black text-midnight font-display mb-4">Đội ngũ Sensei Tâm huyết</h2>
          <p class="text-muted text-lg">Giáo viên tại Bright Education đều có chứng chỉ N2/N1 và kỹ năng sư phạm xuất sắc.</p>
        </div>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Sensei 1 -->
        <div class="group relative rounded-3xl overflow-hidden aspect-[3/4]">
          <div class="absolute inset-0 bg-slate-200">
            <!-- Placeholder for image, use abstract shape for now -->
            <div class="w-full h-full bg-sage-100 flex items-center justify-center">
              <i class="bi bi-person-fill text-sage-300 text-6xl"></i>
            </div>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-midnight/90 via-midnight/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
          <div class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 transition-transform">
            <div class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-white text-xs inline-block mb-3 border border-white/20">Trưởng khoa Tiếng Nhật</div>
            <h4 class="text-xl font-bold text-white mb-1">Cô Nguyễn Minh Anh</h4>
            <p class="text-sage-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity delay-100">JLPT N1 - 5 năm kinh nghiệm giảng dạy</p>
          </div>
        </div>

        <!-- Sensei 2 -->
        <div class="group relative rounded-3xl overflow-hidden aspect-[3/4]">
          <div class="absolute inset-0 bg-slate-200">
            <div class="w-full h-full bg-sky-100 flex items-center justify-center">
              <i class="bi bi-person-fill text-sky-300 text-6xl"></i>
            </div>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-midnight/90 via-midnight/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
          <div class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 transition-transform">
            <div class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-white text-xs inline-block mb-3 border border-white/20">Giáo viên Bản xứ</div>
            <h4 class="text-xl font-bold text-white mb-1">Thầy Tanaka Sho</h4>
            <p class="text-sky-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity delay-100">Chuyên luyện giao tiếp & Phỏng vấn</p>
          </div>
        </div>

        <!-- Sensei 3 -->
        <div class="group relative rounded-3xl overflow-hidden aspect-[3/4]">
          <div class="absolute inset-0 bg-slate-200">
            <div class="w-full h-full bg-sakura-100 flex items-center justify-center">
              <i class="bi bi-person-fill text-sakura-300 text-6xl"></i>
            </div>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-midnight/90 via-midnight/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
          <div class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 transition-transform">
            <div class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-white text-xs inline-block mb-3 border border-white/20">Giáo viên Tiếng Nhật</div>
            <h4 class="text-xl font-bold text-white mb-1">Cô Trần Thu Hà</h4>
            <p class="text-sakura-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity delay-100">JLPT N2 - Chuyên gia luyện thi chữ Hán</p>
          </div>
        </div>

        <!-- Sensei 4 -->
        <div class="group relative rounded-3xl overflow-hidden aspect-[3/4]">
          <div class="absolute inset-0 bg-slate-200">
            <div class="w-full h-full bg-amber-100 flex items-center justify-center">
              <i class="bi bi-person-fill text-amber-300 text-6xl"></i>
            </div>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-midnight/90 via-midnight/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
          <div class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 transition-transform">
            <div class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-white text-xs inline-block mb-3 border border-white/20">Giáo viên Tiếng Nhật</div>
            <h4 class="text-xl font-bold text-white mb-1">Thầy Lê Hoàng</h4>
            <p class="text-amber-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity delay-100">JLPT N2 - Năng động, truyền cảm hứng</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-sage-600"></div>
    <div class="absolute inset-0 bg-[url('/assets/images/pattern-grid.svg')] opacity-10 mix-blend-overlay"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-sage-400 rounded-full blur-3xl opacity-50 translate-x-1/3 -translate-y-1/3"></div>
    
    <div class="container mx-auto px-6 lg:px-12 relative z-10">
      <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl p-10 md:p-16 text-center max-w-4xl mx-auto shadow-2xl">
        <h2 class="text-3xl sm:text-4xl font-black text-white font-display mb-6">Bạn chưa biết mình đang ở trình độ nào?</h2>
        <p class="text-sage-100 text-lg mb-10 max-w-2xl mx-auto">Đăng ký tham gia bài test năng lực tiếng Nhật hoàn toàn miễn phí tại Bright Education để nhận lộ trình học tập cá nhân hóa.</p>
        <div class="flex flex-wrap justify-center gap-4">
          <a href="/contact" class="bg-white text-sage-600 hover:bg-slate-50 hover:scale-105 rounded-xl px-8 py-4 font-bold transition-all shadow-xl flex items-center gap-2">
            Đăng ký Test miễn phí <i class="bi bi-arrow-right"></i>
          </a>
          <a href="tel:0912345678" class="bg-white/10 text-white hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl px-8 py-4 font-bold transition-all flex items-center gap-2">
            <i class="bi bi-telephone"></i> Hotline tư vấn
          </a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
