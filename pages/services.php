<?php
require_once 'config/config.php';

$page_title = 'Dịch Vụ & Chương Trình Du Học - Bright Education';
include 'includes/header.php';
?>

<main class="pt-20 bg-slate-50 min-h-screen">
  <!-- Page Header / Hero -->
  <section class="max-w-7xl mx-auto px-5 lg:px-8 mt-12 mb-16">
    <div class="flex flex-col lg:flex-row gap-12 items-center">
      <!-- Left Text -->
      <div class="w-full lg:w-1/2 pr-0 lg:pr-12 text-center lg:text-left">
        <span class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-xs font-bold text-primary uppercase tracking-widest mb-6">
          <i class="bi bi-compass"></i> Dịch vụ chuyên nghiệp
        </span>
        <h1 class="text-4xl lg:text-5xl font-black leading-[1.15] mb-6 text-primary font-display">
          Tối ưu chi phí, <br class="hidden lg:block"> <span class="text-orange-600 drop-shadow-sm">quy trình linh động</span>
        </h1>
        <p class="text-lg text-slate-600 mb-8 font-medium leading-relaxed max-w-xl mx-auto lg:mx-0">
          Các gói dịch vụ chuyên nghiệp, minh bạch và tiết kiệm chi phí giúp bạn hoàn thành mọi thủ tục đi Nhật một cách nhanh chóng và an tâm tuyệt đối.
        </p>
        <button onclick="scrollToIntake()" class="inline-flex px-8 py-4 bg-primary text-white font-bold rounded-full text-sm hover:bg-slate-800 transition-colors shadow-lg hover:-translate-y-1 transition-transform">
          Nhận Tư Vấn Miễn Phí
        </button>
      </div>

      <!-- Right Image -->
      <div class="w-full lg:w-1/2 relative mt-8 lg:mt-0">
        <div class="absolute -inset-4 bg-primary-50 rounded-[3rem] -z-10 transform rotate-2"></div>
        <img src="https://ik.imagekit.io/tvlk/blog/2025/02/Qf5ZVqSj-image.jpeg" alt="Dịch vụ Bright Education" class="w-full h-[350px] lg:h-[450px] object-cover rounded-[2.5rem] shadow-hard">
      </div>
    </div>
  </section>

  <!-- Service Cards Section (Horizontal rows on desktop, stacked on mobile) -->
  <section class="max-w-7xl mx-auto px-5 lg:px-8 mb-24">
    <div class="flex flex-col gap-8 w-full">
      
      <!-- Gói 1: Du học Trường Nhật ngữ -->
      <div class="bg-white rounded-[2.5rem] p-4 shadow-soft hover:shadow-medium transition-all border border-slate-100 group flex flex-col md:flex-row items-stretch gap-6 reveal">
        <!-- Cột trái: Ảnh nền & Tiêu đề -->
        <div class="w-full md:w-1/3 shrink-0 rounded-[2rem] overflow-hidden relative min-h-[250px] md:min-h-[300px]">
          <img src="/assets/images/program_language.jpg" alt="Du học Trường Nhật ngữ" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700">
          <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/45 to-transparent"></div>
          <div class="absolute bottom-6 left-6 right-6 text-left">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center mb-4 border border-white/30 shadow-lg">
              <i class="bi bi-translate text-3xl"></i>
            </div>
            <h2 class="text-2xl lg:text-3xl font-black text-white mb-2 leading-tight font-display">Trường Nhật Ngữ</h2>
          </div>
        </div>

        <!-- Cột giữa: Mô tả & Tính năng (checklist) -->
        <div class="w-full md:w-5/12 flex flex-col justify-center py-4 px-2 lg:px-4">
          <p class="text-slate-500 text-sm md:text-[14px] mb-6 leading-relaxed font-medium">Giải pháp toàn diện học tiếng Nhật tập trung từ 1.5 - 2 năm tại các thành phố lớn của Nhật Bản.</p>
          <ul class="space-y-4">
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Phí xử lý hồ sơ (Dịch thuật, công chứng)</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Luyện phỏng vấn phỏng vấn visa & phỏng vấn trường</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Hỗ trợ hồ sơ chứng minh tài chính</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Phí xin COE (Giấy tư cách lưu trú)</span>
            </li>
          </ul>
        </div>

        <!-- Cột phải: Giá & Nút bấm -->
        <div class="w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-100 pt-6 md:pt-0 pl-0 md:pl-8 pr-4 text-center flex flex-col justify-center">
          <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-3">Phí Dịch Vụ Trọn Gói</p>
          <div class="flex items-center justify-center gap-1 mb-8">
            <span class="text-3xl lg:text-4xl font-black text-orange-600">15.000.000</span>
            <span class="text-slate-500 font-bold text-sm">VNĐ</span>
          </div>
          <a href="/services/japanese-language-school" class="block w-full py-4 bg-primary text-white font-bold text-center rounded-2xl hover:bg-slate-800 transition-colors duration-300 shadow-md hover:-translate-y-1">
            Xem Chi Tiết Lộ Trình
          </a>
        </div>
      </div>

      <!-- Gói 2: Du học Trường Senmon -->
      <div class="bg-white rounded-[2.5rem] p-4 shadow-soft hover:shadow-medium transition-all border border-slate-100 group flex flex-col md:flex-row items-stretch gap-6 reveal">
        <!-- Cột trái: Ảnh nền & Tiêu đề -->
        <div class="w-full md:w-1/3 shrink-0 rounded-[2rem] overflow-hidden relative min-h-[250px] md:min-h-[300px]">
          <img src="/assets/images/program_senmon.jpg" alt="Du học Trường Senmon" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700">
          <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/45 to-transparent"></div>
          <div class="absolute bottom-6 left-6 right-6 text-left">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center mb-4 border border-white/30 shadow-lg">
              <i class="bi bi-tools text-3xl"></i>
            </div>
            <h2 class="text-2xl lg:text-3xl font-black text-white mb-2 leading-tight font-display">Trường Senmon</h2>
          </div>
        </div>

        <!-- Cột giữa: Mô tả & Tính năng -->
        <div class="w-full md:w-5/12 flex flex-col justify-center py-4 px-2 lg:px-4">
          <p class="text-slate-500 text-sm md:text-[14px] mb-6 leading-relaxed font-medium">Đào tạo nghề thực chiến 2 năm chuyên sâu (IT, Cơ khí, Du lịch, Ẩm thực...). Cam kết có việc làm lương cao.</p>
          <ul class="space-y-4">
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Định hướng lựa chọn chuyên ngành phù hợp</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Xử lý hồ sơ và luyện phỏng vấn trường</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Hỗ trợ xin học bổng từ các trường đối tác</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Hỗ trợ kết nối và cam kết giới thiệu việc làm</span>
            </li>
          </ul>
        </div>

        <!-- Cột phải: Giá & Nút bấm -->
        <div class="w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-100 pt-6 md:pt-0 pl-0 md:pl-8 pr-4 text-center flex flex-col justify-center">
          <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-3">Phí Dịch Vụ Trọn Gói</p>
          <div class="flex items-center justify-center gap-1 mb-8">
            <span class="text-3xl lg:text-4xl font-black text-orange-600">30.000.000</span>
            <span class="text-slate-500 font-bold text-sm">VNĐ</span>
          </div>
          <a href="/services/senmon-vocational-school" class="block w-full py-4 bg-primary text-white font-bold text-center rounded-2xl hover:bg-slate-800 transition-colors duration-300 shadow-md hover:-translate-y-1">
            Xem Chi Tiết Lộ Trình
          </a>
        </div>
      </div>

      <!-- Gói 3: Du học Trường Đại học -->
      <div class="bg-white rounded-[2.5rem] p-4 shadow-soft hover:shadow-medium transition-all border border-slate-100 group flex flex-col md:flex-row items-stretch gap-6 reveal">
        <!-- Cột trái: Ảnh nền & Tiêu đề -->
        <div class="w-full md:w-1/3 shrink-0 rounded-[2rem] overflow-hidden relative min-h-[250px] md:min-h-[300px]">
          <img src="/assets/images/program_university.webp" alt="Du học Trường Đại học" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700">
          <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/45 to-transparent"></div>
          <div class="absolute bottom-6 left-6 right-6 text-left">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center mb-4 border border-white/30 shadow-lg">
              <i class="bi bi-mortarboard text-3xl"></i>
            </div>
            <h2 class="text-2xl lg:text-3xl font-black text-white mb-2 leading-tight font-display">Trường Đại Học</h2>
          </div>
        </div>

        <!-- Cột giữa: Mô tả & Tính năng -->
        <div class="w-full md:w-5/12 flex flex-col justify-center py-4 px-2 lg:px-4">
          <p class="text-slate-500 text-sm md:text-[14px] mb-6 leading-relaxed font-medium">Lộ trình học cử nhân chính quy tại các trường đại học hàng đầu Nhật Bản, định cư lâu dài.</p>
          <ul class="space-y-4">
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Tư vấn chọn ngành nghề và chọn trường phù hợp</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Định hướng ôn thi kỳ thi EJU</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Hỗ trợ làm hồ sơ ứng tuyển học bổng</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Luyện phỏng vấn chuyên ngành 1-1</span>
            </li>
          </ul>
        </div>

        <!-- Cột phải: Giá & Nút bấm -->
        <div class="w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-100 pt-6 md:pt-0 pl-0 md:pl-8 pr-4 text-center flex flex-col justify-center">
          <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-3">Phí Dịch Vụ Trọn Gói</p>
          <div class="flex items-center justify-center gap-1 mb-8">
            <span class="text-3xl lg:text-4xl font-black text-orange-600">30.000.000</span>
            <span class="text-slate-500 font-bold text-sm">VNĐ</span>
          </div>
          <a href="/services/university-program" class="block w-full py-4 bg-primary text-white font-bold text-center rounded-2xl hover:bg-slate-800 transition-colors duration-300 shadow-md hover:-translate-y-1">
            Xem Chi Tiết Lộ Trình
          </a>
        </div>
      </div>

      <!-- Gói 4: Du học Học bổng -->
      <div class="bg-white rounded-[2.5rem] p-4 shadow-soft hover:shadow-medium transition-all border border-slate-100 group flex flex-col md:flex-row items-stretch gap-6 reveal">
        <!-- Cột trái: Ảnh nền & Tiêu đề -->
        <div class="w-full md:w-1/3 shrink-0 rounded-[2rem] overflow-hidden relative min-h-[250px] md:min-h-[300px]">
          <img src="/assets/images/program_ssw.jpg" alt="Du học Chương trình Học bổng" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700">
          <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/45 to-transparent"></div>
          <div class="absolute bottom-6 left-6 right-6 text-left">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center mb-4 border border-white/30 shadow-lg">
              <i class="bi bi-award text-3xl"></i>
            </div>
            <h2 class="text-2xl lg:text-3xl font-black text-white mb-2 leading-tight font-display">Chương Trình Học Bổng</h2>
          </div>
        </div>

        <!-- Cột giữa: Mô tả & Tính năng -->
        <div class="w-full md:w-5/12 flex flex-col justify-center py-4 px-2 lg:px-4">
          <p class="text-slate-500 text-sm md:text-[14px] mb-6 leading-relaxed font-medium">Học bổng báo, điều dưỡng, nhà hàng... Được các doanh nghiệp hỗ trợ toàn bộ học phí và ký túc xá.</p>
          <ul class="space-y-4">
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Miễn học phí 100% (do doanh nghiệp Nhật chi trả)</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Hỗ trợ ký túc xá miễn phí</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Bố trí công việc làm thêm có thu nhập ngay khi sang</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Cam kết giới thiệu việc làm chính thức sau khi ra trường</span>
            </li>
          </ul>
        </div>

        <!-- Cột phải: Giá & Nút bấm -->
        <div class="w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-100 pt-6 md:pt-0 pl-0 md:pl-8 pr-4 text-center flex flex-col justify-center">
          <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-3">Phí Dịch Vụ Trọn Gói</p>
          <div class="flex items-center justify-center gap-1 mb-8">
            <span class="text-3xl lg:text-4xl font-black text-orange-600">15.000.000</span>
            <span class="text-slate-500 font-bold text-sm">VNĐ</span>
          </div>
          <a href="/services/scholarship-program" class="block w-full py-4 bg-primary text-white font-bold text-center rounded-2xl hover:bg-slate-800 transition-colors duration-300 shadow-md hover:-translate-y-1">
            Xem Chi Tiết Lộ Trình
          </a>
        </div>
      </div>

      <!-- Gói 5: Du học Hệ Đại học Tiếng Anh -->
      <div class="bg-white rounded-[2.5rem] p-4 shadow-soft hover:shadow-medium transition-all border border-slate-100 group flex flex-col md:flex-row items-stretch gap-6 reveal">
        <!-- Cột trái: Ảnh nền & Tiêu đề -->
        <div class="w-full md:w-1/3 shrink-0 rounded-[2rem] overflow-hidden relative min-h-[250px] md:min-h-[300px]">
          <img src="/assets/images/whyus_tokyo-optimized.webp" alt="Du học Hệ Đại học Tiếng Anh" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
          <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/45 to-transparent"></div>
          <div class="absolute bottom-6 left-6 right-6 text-left">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center mb-4 border border-white/30 shadow-lg">
              <i class="bi bi-globe text-3xl"></i>
            </div>
            <h2 class="text-2xl lg:text-3xl font-black text-white mb-2 leading-tight font-display">Hệ Đại Học Tiếng Anh</h2>
          </div>
        </div>

        <!-- Cột giữa: Mô tả & Tính năng -->
        <div class="w-full md:w-5/12 flex flex-col justify-center py-4 px-2 lg:px-4">
          <p class="text-slate-500 text-sm md:text-[14px] mb-6 leading-relaxed font-medium">Chương trình cử nhân E-Track giảng dạy 100% bằng tiếng Anh tại Nhật Bản. Không lo áp lực tiếng Nhật đầu vào.</p>
          <ul class="space-y-4">
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Tư vấn hồ sơ ứng tuyển hệ English Track chính quy</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Hỗ trợ xin học bổng miễn học phí (30% - 100%) đầu vào</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Luyện viết luận luận & phỏng vấn tiếng Anh chuyên sâu</span>
            </li>
            <li class="flex items-start gap-3 text-sm font-semibold text-slate-700">
              <i class="bi bi-check-circle-fill text-primary text-lg shrink-0 -mt-0.5"></i>
              <span>Đào tạo tiếng Nhật giao tiếp song song khi sang học</span>
            </li>
          </ul>
        </div>

        <!-- Cột phải: Giá & Nút bấm -->
        <div class="w-full md:w-1/4 border-t md:border-t-0 md:border-l border-slate-100 pt-6 md:pt-0 pl-0 md:pl-8 pr-4 text-center flex flex-col justify-center">
          <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-3">Phí Dịch Vụ Trọn Gói</p>
          <div class="flex items-center justify-center gap-1 mb-8">
            <span class="text-3xl lg:text-4xl font-black text-orange-600">30.000.000</span>
            <span class="text-slate-500 font-bold text-sm">VNĐ</span>
          </div>
          <a href="/services/english-track-university" class="block w-full py-4 bg-primary text-white font-bold text-center rounded-2xl hover:bg-slate-800 transition-colors duration-300 shadow-md hover:-translate-y-1">
            Xem Chi Tiết Lộ Trình
          </a>
        </div>
      </div>

    </div>
  </section>

  <!-- Consultation Intake Form -->
  <section class="py-20 bg-white" id="intake-form-section">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
      <div class="bg-gradient-to-br from-primary to-slate-900 rounded-[2.5rem] p-8 sm:p-14 text-white relative overflow-hidden shadow-tinted border border-white/5">
        <!-- Decoration -->
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/5 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-white/5 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
          <!-- Left Col: Content -->
          <div class="lg:col-span-5 space-y-6">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold text-white/80 uppercase tracking-widest">
              <i class="bi bi-send"></i> Đăng Ký Tư Vấn Miễn Phí
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold font-display leading-tight">Bạn Đã Chọn Được Lộ Trình Phù Hợp?</h2>
            <p class="text-white/70 text-sm sm:text-base leading-relaxed">Hãy điền thông tin vào form để nhận cuộc gọi tư vấn 1-1 miễn phí từ đội ngũ chuyên gia của Bright Education. Chúng tôi sẽ hỗ trợ thiết lập lộ trình học tiếng, chuẩn bị hồ sơ du học và bảo lãnh visa tốt nhất cho bạn.</p>
            <div class="space-y-3.5 pt-4 text-sm text-white/85">
              <div class="flex items-center gap-3"><i class="bi bi-check-circle-fill text-emerald-400"></i> Tư vấn 1-1 chuyên sâu hoàn toàn miễn phí.</div>
              <div class="flex items-center gap-3"><i class="bi bi-check-circle-fill text-emerald-400"></i> Thiết lập lộ trình cá nhân hóa theo tài chính.</div>
              <div class="flex items-center gap-3"><i class="bi bi-check-circle-fill text-emerald-400"></i> Hỗ trợ kết nối phỏng vấn trực tiếp với trường bên Nhật.</div>
            </div>
          </div>

          <!-- Right Col: Form -->
          <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 text-slate-800 shadow-soft">
            <h3 class="text-xl font-bold text-primary font-display mb-2">Đăng Ký Nhận Lộ Trình</h3>
            <p class="text-xs text-slate-400 mb-6">Chúng tôi cam kết bảo mật 100% thông tin cá nhân của bạn.</p>
            
            <form method="POST" action="/api/contact.php" id="services-intake-form" class="space-y-4">
              <?php echo csrfField(); ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                  <label class="text-[11px] font-bold text-primary uppercase tracking-wider">Họ và tên <span class="text-rose-600">*</span></label>
                  <input type="text" name="name" required placeholder="Nguyễn Văn A" class="w-full px-4 py-3 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-slate-800 placeholder:text-slate-400">
                </div>
                <div class="space-y-1.5">
                  <label class="text-[11px] font-bold text-primary uppercase tracking-wider">Số điện thoại <span class="text-rose-600">*</span></label>
                  <input type="tel" name="phone" required placeholder="0981 xxx xxx" class="w-full px-4 py-3 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-slate-800 placeholder:text-slate-400">
                </div>
              </div>

              <div class="space-y-1.5">
                <label class="text-[11px] font-bold text-primary uppercase tracking-wider">Email <span class="text-rose-600">*</span></label>
                <input type="email" name="email" required placeholder="email@example.com" class="w-full px-4 py-3 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-slate-800 placeholder:text-slate-400">
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                  <label class="text-[11px] font-bold text-primary uppercase tracking-wider">Chương trình quan tâm</label>
                  <div class="relative">
                    <select name="message" id="program-select" class="w-full px-4 py-3 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-slate-800 appearance-none">
                      <option value="Du học Trường Nhật ngữ">Du học Trường Nhật ngữ</option>
                      <option value="Du học Trường Senmon">Du học Trường Senmon</option>
                      <option value="Du học Trường Đại học">Du học Trường Đại học</option>
                      <option value="Du học Chương trình Học bổng">Du học Chương trình Học bổng</option>
                      <option value="Du học Đại học Hệ tiếng Anh">Du học Đại học Hệ tiếng Anh</option>
                      <option value="Khác / Cần tư vấn thêm">Khác / Cần tư vấn thêm</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                      <i class="bi bi-chevron-down text-sm"></i>
                    </div>
                  </div>
                </div>

                <div class="space-y-1.5">
                  <label class="text-[11px] font-bold text-primary uppercase tracking-wider">Trình độ tiếng Nhật hiện tại</label>
                  <div class="relative">
                    <select name="japanese_level" class="w-full px-4 py-3 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-slate-800 appearance-none">
                      <option value="Chưa học">Chưa học tiếng Nhật</option>
                      <option value="N5">Đã học xong N5</option>
                      <option value="N4">Đã học xong N4</option>
                      <option value="N3">Đã có N3</option>
                      <option value="N2 trở lên">Đã có N2 trở lên</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                      <i class="bi bi-chevron-down text-sm"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="space-y-1.5">
                <label class="text-[11px] font-bold text-primary uppercase tracking-wider">Kỳ nhập học mong muốn</label>
                <div class="relative">
                  <select name="intake_period" class="w-full px-4 py-3 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-slate-800 appearance-none">
                    <option value="Tháng 4">Tháng 4 (Kỳ chính)</option>
                    <option value="Tháng 7">Tháng 7 (Kỳ phụ)</option>
                    <option value="Tháng 10">Tháng 10 (Kỳ phụ)</option>
                    <option value="Tháng 1">Tháng 1 (Kỳ phụ)</option>
                    <option value="Đang cân nhắc">Đang cân nhắc / Khác</option>
                  </select>
                  <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                    <i class="bi bi-chevron-down text-sm"></i>
                  </div>
                </div>
              </div>

              <button type="submit" class="w-full mt-4 bg-primary text-white py-3.5 rounded-xl font-bold hover:bg-slate-850 transition-colors shadow-soft hover:shadow-medium flex items-center justify-center gap-2 group text-sm">
                Đăng Ký Tư Vấn Ngay
                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<script>
// Scroll directly to the intake form
function scrollToIntake() {
  const formSection = document.getElementById('intake-form-section');
  if (formSection) {
    formSection.scrollIntoView({ behavior: 'smooth' });
  }
}

// Auto-select program in the dropdown and scroll to intake form
function registerForProgram(programName) {
  const programSelect = document.getElementById('program-select');
  if (programSelect) {
    programSelect.value = programName;
  }
  scrollToIntake();
}

// Handle Form Submission via Ajax
document.getElementById('services-intake-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const submitBtn = this.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;
  
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Đang xử lý...';
  
  fetch('/api/contact.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
    if (data.success) {
      alert('Cảm ơn bạn! Thông tin đăng ký lộ trình du học đã được gửi thành công. Bright Education sẽ liên hệ lại với bạn sớm nhất có thể.');
      this.reset();
    } else {
      alert('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại.'));
    }
  })
  .catch(error => {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
    alert('Có lỗi kết nối. Vui lòng kiểm tra lại mạng Internet và thử lại sau.');
  });
});
</script>

<?php include 'includes/footer.php'; ?>
