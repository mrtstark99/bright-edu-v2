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
    <section class="relative overflow-hidden pt-[140px] pb-0 bg-slate-50 w-full min-h-[60vh] lg:min-h-[75vh] flex items-end">
      <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-80" style="background-image: url('/assets/images/hero_new_bg.png');"></div>
      <!-- Black overlay with 15% opacity -->
      <div class="absolute inset-0 bg-black/15 pointer-events-none"></div>

<div class="relative mx-auto max-w-7xl px-5 lg:px-8 w-full z-10">
        <div class="grid gap-12 lg:gap-8 lg:grid-cols-2">
          <!-- Left: Content -->
          <div class="relative z-10 self-center my-12 lg:mb-24">
            
            <div class="space-y-10 relative z-10">
              <h1 class="reveal reveal-delay-100 text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold leading-[1.1] tracking-tight text-primary font-display">
              Du học Nhật Bản cùng <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-500">Bright Education</span>
            </h1>
            <p class="reveal reveal-delay-200 text-lg text-muted md:text-xl max-w-lg leading-relaxed">
              Quy trình linh động và minh bạch sẽ giúp các bước chuẩn bị du học của bạn thuận lợi hơn khi đồng hành cùng Bright Education.
            </p>
            <div class="reveal reveal-delay-300 flex flex-wrap items-center gap-4">
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
          <div class="relative z-10 reveal reveal-delay-300 mt-10 lg:mt-0 self-end">
             <div class="relative transform transition-transform hover:scale-[1.05] duration-700 w-full max-w-lg lg:max-w-[110%] mx-auto lg:ml-auto lg:-mr-8 scale-110 origin-bottom">
               <img 
                 src="/assets/images/hero-new.png" 
                 alt="Sinh viên Bright Education" 
                 class="w-full h-auto object-contain drop-shadow-2xl"
               />
             </div>
             
             <!-- Floating Badges -->
             <div class="absolute -left-2 sm:-left-8 bottom-1/4 glass rounded-2xl p-4 shadow-medium flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-primary">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                </div>
                <div>
                    <p class="text-[11px] text-muted font-semibold uppercase">Mentor 1-1</p>
                    <p class="text-sm font-bold text-primary">Đồng hành tại Nhật</p>
                </div>
             </div>
             
             <div class="absolute -right-2 sm:-right-8 top-1/4 glass rounded-2xl p-4 shadow-medium flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-primary">
                    <i class="bi bi-star-fill text-xl"></i>
                </div>
                <div>
                    <p class="text-[11px] text-muted font-semibold uppercase">Hỗ trợ trọn gói</p>
                    <p class="text-sm font-bold text-primary">Không phí ẩn</p>
                </div>
             </div>
          </div>
        </div>
      </div>
    </section>

    <section id="about" class="bg-white py-20 lg:py-28 relative">
      <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[1.2fr_1fr] items-center">
          
          <!-- Text Content -->
          <div class="space-y-6">
            <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display tracking-tight reveal">
              Bright Education <br/> <span class="text-primary text-2xl sm:text-3xl font-medium mt-1 block">Là người đồng hành của bạn</span>
            </h2>
            <p class="text-lg text-muted leading-relaxed reveal reveal-delay-100">
              Bright Education là đơn vị tư vấn du học Nhật Bản với trụ sở tại Hải Phòng và văn phòng hỗ trợ tại Tokyo, cùng nhiều đối tác đào tạo trên nhiều tỉnh thành ở Việt Nam. Chúng tôi tập trung vào trải nghiệm cá nhân hóa, cập nhật nhanh tiêu chí tuyển sinh của các trường.
            </p>
            <div class="grid sm:grid-cols-2 gap-4 pt-4 reveal reveal-delay-200">
              <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-slate-50 text-primary">
                  <i class="bi bi-shield-check text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-primary">Cam kết minh bạch</h4>
                  <p class="text-sm text-muted mt-1">Quản lý hồ sơ số hóa 100%, không phát sinh phí ẩn.</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-slate-50 text-primary">
                  <i class="bi bi-people text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-primary">Hỗ trợ tại Nhật</h4>
                  <p class="text-sm text-muted mt-1">Cộng đồng 2000+ học viên và mạng lưới nhà trọ/việc làm.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Right side: Impressive Collage Layout -->
          <div class="relative w-full h-[450px] sm:h-[550px] mt-12 lg:mt-0 reveal">
            <!-- Main image -->
            <div class="absolute top-0 right-0 w-[85%] h-[80%] rounded-[2rem] overflow-hidden shadow-hard z-10 group">
              <img src="/assets/images/about_counselor.png" alt="Tư vấn viên và học viên" class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-110">
              <div class="absolute inset-0 bg-primary/10 mix-blend-overlay"></div>
            </div>
            
            <!-- Secondary overlapping block -->
            <div class="absolute bottom-0 left-0 w-[65%] h-[40%] rounded-[2rem] bg-sage-100 p-6 sm:p-8 flex flex-col justify-center z-20 shadow-medium card-hover border border-white">
              <h4 class="text-3xl sm:text-4xl font-black text-primary font-display mb-2">2013</h4>
              <p class="text-sm font-medium text-primary-800 leading-relaxed">Năm thành lập. Hơn 10 năm kinh nghiệm đồng hành cùng giấc mơ Nhật Bản.</p>
            </div>
            
            <!-- Floating Stats Card 1 -->
            <div class="absolute top-1/4 -translate-y-1/2 -left-4 sm:-left-8 glass rounded-2xl p-4 shadow-hard z-30 flex items-center gap-4 float-slow">
               <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-primary font-black text-xl shadow-sm">
                 135
               </div>
               <div class="pr-2">
                 <p class="text-[10px] text-muted font-bold uppercase tracking-wider">Mạng lưới</p>
                 <p class="text-sm font-bold text-primary">Trường đối tác</p>
               </div>
            </div>

            <!-- Floating Stats Card 2 -->
            <div class="absolute bottom-[45%] right-0 translate-x-4 sm:translate-x-8 glass rounded-2xl p-4 shadow-hard z-30 flex items-center gap-4 float-slow" style="animation-delay: -3s;">
               <div class="pr-2 text-right">
                 <p class="text-[10px] text-muted font-bold uppercase tracking-wider">Việc làm</p>
                 <p class="text-sm font-bold text-primary">82% Tỷ lệ đỗ</p>
               </div>
               <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white shadow-sm">
                 <i class="bi bi-briefcase-fill text-xl"></i>
               </div>
            </div>
          </div>
          
        </div>
      </div>
    </section>

    <!-- Announcement moved to floating bubble -->

    <section id="why" class="bg-white mx-auto max-w-7xl px-5 lg:px-8 py-20 lg:py-28 relative">
      <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
      <div class="relative">
        <div class="mx-auto mb-12 max-w-2xl text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Các Chương Trình Du Học</h2>
        </div>
        
        <!-- Study Abroad Programs -->
        <div class="mb-16 reveal">
          <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Chương trình 1: Du học Ngôn ngữ -->
            <a href="/services" class="group relative rounded-3xl overflow-hidden shadow-medium hover:shadow-hard transition-all duration-500 card-hover block aspect-[3/4]">
              <img src="/assets/images/program_language.jpg" alt="Du học Ngôn ngữ" class="absolute inset-0 w-full h-full object-cover object-right transition-transform duration-[4s] group-hover:scale-110">
              <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent"></div>
              <div class="absolute bottom-0 left-0 right-0 p-6">
                <span class="inline-block text-[10px] font-bold uppercase tracking-widest text-white/70 mb-2">Phổ biến nhất</span>
                <h3 class="text-white font-display font-bold text-xl leading-tight mb-2">Du học Ngôn ngữ</h3>
                <p class="text-white/70 text-xs leading-relaxed">Trường Nhật ngữ 1-2 năm. Nền tảng vững cho các chương trình tiếp theo.</p>
                <div class="mt-4 flex items-center gap-2 text-white text-xs font-semibold group-hover:gap-3 transition-all">
                  Tìm hiểu thêm <i class="bi bi-arrow-right"></i>
                </div>
              </div>
            </a>

            <!-- Chương trình 2: Du học Đại học -->
            <a href="/services" class="group relative rounded-3xl overflow-hidden shadow-medium hover:shadow-hard transition-all duration-500 card-hover block aspect-[3/4]">
              <img src="/assets/images/program_university.jpg" alt="Du học Đại học" class="absolute inset-0 w-full h-full object-cover object-right transition-transform duration-[4s] group-hover:scale-110">
              <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent"></div>
              <div class="absolute bottom-0 left-0 right-0 p-6">
                <span class="inline-block text-[10px] font-bold uppercase tracking-widest text-white/70 mb-2">Bậc Đại học</span>
                <h3 class="text-white font-display font-bold text-xl leading-tight mb-2">Du học Đại học</h3>
                <p class="text-white/70 text-xs leading-relaxed">Vào thẳng ĐH Nhật hoặc qua trung gian Senmon Gakkou.</p>
                <div class="mt-4 flex items-center gap-2 text-white text-xs font-semibold group-hover:gap-3 transition-all">
                  Tìm hiểu thêm <i class="bi bi-arrow-right"></i>
                </div>
              </div>
            </a>

            <!-- Chương trình 3: Du học Nghề -->
            <a href="/services" class="group relative rounded-3xl overflow-hidden shadow-medium hover:shadow-hard transition-all duration-500 card-hover block aspect-[3/4]">
              <img src="/assets/images/program_senmon.jpg" alt="Du học Nghề" class="absolute inset-0 w-full h-full object-cover object-right transition-transform duration-[4s] group-hover:scale-110">
              <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent"></div>
              <div class="absolute bottom-0 left-0 right-0 p-6">
                <span class="inline-block text-[10px] font-bold uppercase tracking-widest text-white/70 mb-2">Kỹ năng nghề</span>
                <h3 class="text-white font-display font-bold text-xl leading-tight mb-2">Du học Trường Nghề</h3>
                <p class="text-white/70 text-xs leading-relaxed">Chuyên môn thực chiến, cơ hội việc làm lương cao tại Nhật.</p>
                <div class="mt-4 flex items-center gap-2 text-white text-xs font-semibold group-hover:gap-3 transition-all">
                  Tìm hiểu thêm <i class="bi bi-arrow-right"></i>
                </div>
              </div>
            </a>

            <!-- Chương trình 4: Kỹ năng đặc định -->
            <a href="/services" class="group relative rounded-3xl overflow-hidden shadow-medium hover:shadow-hard transition-all duration-500 card-hover block aspect-[3/4]">
              <img src="/assets/images/program_ssw.jpg" alt="Kỹ năng đặc định" class="absolute inset-0 w-full h-full object-cover object-right transition-transform duration-[4s] group-hover:scale-110">
              <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/50 to-transparent"></div>
              <div class="absolute bottom-0 left-0 right-0 p-6">
                <span class="inline-block text-[10px] font-bold uppercase tracking-widest text-white/70 mb-2">Visa lao động</span>
                <h3 class="text-white font-display font-bold text-xl leading-tight mb-2">Kỹ năng Đặc định</h3>
                <p class="text-white/70 text-xs leading-relaxed">Lộ trình nhanh sang Nhật làm việc có tay nghề (SSW).</p>
                <div class="mt-4 flex items-center gap-2 text-white text-xs font-semibold group-hover:gap-3 transition-all">
                  Tìm hiểu thêm <i class="bi bi-arrow-right"></i>
                </div>
              </div>
            </a>
          </div>
        </div>

        <div class="mx-auto mt-24 mb-12 max-w-2xl text-center reveal">
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Các lợi thế của chúng tôi</h2>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <article class="group rounded-3xl border border-transparent bg-white p-8 shadow-soft card-hover hover:border-slate-200 reveal">
            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-primary transition-colors group-hover:bg-sage-600 group-hover:text-white">
                <i class="bi bi-file-earmark-check text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-primary font-display">Hồ sơ chuẩn Nhật</h3>
            <p class="mt-3 text-[15px] text-muted leading-relaxed">Checklist COE cập nhật liên tục, kiểm định giấy tờ tài chính, luyện phỏng vấn sát thực tế theo yêu cầu từng trường.</p>
          </article>
          <article class="group rounded-3xl border border-transparent bg-white p-8 shadow-soft card-hover hover:border-slate-200 reveal reveal-delay-100">
            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-primary transition-colors group-hover:bg-sakura-600 group-hover:text-white">
                <i class="bi bi-piggy-bank text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-primary font-display">Giảm áp lực tài chính</h3>
            <p class="mt-3 text-[15px] text-muted leading-relaxed">Hỗ trợ săn học bổng đầu vào, tư vấn vay chứng minh tài chính, và lên kế hoạch việc làm thêm hợp pháp.</p>
          </article>
          <article class="group rounded-3xl border border-transparent bg-white p-8 shadow-soft card-hover hover:border-sand-200 reveal reveal-delay-200">
            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="bi bi-translate text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-primary font-display">Mentor song ngữ</h3>
            <p class="mt-3 text-[15px] text-muted leading-relaxed">Đội ngũ đã tốt nghiệp và làm việc tại Nhật, hỗ trợ tiếng Nhật thực chiến và chia sẻ văn hóa doanh nghiệp.</p>
          </article>
          <article class="group rounded-3xl border border-transparent bg-white p-8 shadow-soft card-hover hover:border-slate-200 reveal">
            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-primary transition-colors group-hover:bg-sage-600 group-hover:text-white">
                <i class="bi bi-house-heart text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-primary font-display">Hòa nhập bền vững</h3>
            <p class="mt-3 text-[15px] text-muted leading-relaxed">Hỗ trợ thuê nhà, mua bảo hiểm, hướng dẫn quản lý chi tiêu và sử dụng dịch vụ y tế an toàn tại Nhật.</p>
          </article>
          <article class="group rounded-3xl border border-transparent bg-white p-8 shadow-soft card-hover hover:border-slate-200 reveal reveal-delay-100">
            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-primary transition-colors group-hover:bg-sakura-600 group-hover:text-white">
                <i class="bi bi-briefcase text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-primary font-display">Lộ trình nghề nghiệp</h3>
            <p class="mt-3 text-[15px] text-muted leading-relaxed">Kết nối doanh nghiệp, hỗ trợ thực tập, chuyển đổi visa lao động và tham gia chương trình kỹ năng đặc định.</p>
          </article>
          <article class="group rounded-3xl border border-transparent bg-white p-8 shadow-soft card-hover hover:border-sand-200 reveal reveal-delay-200">
            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="bi bi-chat-quote text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-primary font-display">Cộng đồng đồng hành</h3>
            <p class="mt-3 text-[15px] text-muted leading-relaxed">Group kín hỗ trợ 2000+ học viên, workshop định kỳ, cập nhật chính sách từ Cục Xuất nhập cảnh.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="services" class="bg-white py-20 lg:py-28">
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto mb-16 max-w-2xl text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Các Bước Và Quy Trình</h2>
        </div>
        <div class="w-full">
          <!-- Services Grid -->
          <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-shadow hover:shadow-medium reveal">
              <div class="text-4xl font-black text-slate-100 mb-4 font-display">01</div>
              <h3 class="text-lg font-bold text-primary font-display">Định hướng cá nhân</h3>
              <ul class="mt-4 space-y-3 text-[14px] text-muted">
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Phân tích mục tiêu học tập</li>
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Lộ trình tiếng Nhật phù hợp</li>
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Kế hoạch tài chính cá nhân</li>
              </ul>
            </article>
            <article class="rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-shadow hover:shadow-medium reveal reveal-delay-100">
              <div class="text-4xl font-black text-slate-100 mb-4 font-display">02</div>
              <h3 class="text-lg font-bold text-primary font-display">Chuẩn bị hồ sơ</h3>
              <ul class="mt-4 space-y-3 text-[14px] text-muted">
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Dịch thuật công chứng chuẩn</li>
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Viết kế hoạch & thư bảo lãnh</li>
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Luyện phỏng vấn 1-1</li>
              </ul>
            </article>
            <article class="rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-shadow hover:shadow-medium reveal reveal-delay-200">
              <div class="text-4xl font-black text-slate-100 mb-4 font-display">03</div>
              <h3 class="text-lg font-bold text-primary font-display">Xin COE & visa</h3>
              <ul class="mt-4 space-y-3 text-[14px] text-muted">
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Nộp hồ sơ COE trực tiếp</li>
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Theo dõi với Cục XNC</li>
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Hỗ trợ visa Đại sứ quán</li>
              </ul>
            </article>
            <article class="rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-shadow hover:shadow-medium reveal reveal-delay-300">
              <div class="text-4xl font-black text-slate-100 mb-4 font-display">04</div>
              <h3 class="text-lg font-bold text-primary font-display">Hậu cần & hòa nhập</h3>
              <ul class="mt-4 space-y-3 text-[14px] text-muted">
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Tìm nhà & mua bảo hiểm</li>
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Đưa đón & đăng ký cư trú</li>
                <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-primary mt-0.5"></i> Hướng dẫn xin việc làm thêm</li>
              </ul>
            </article>
          </div>
        </div>
      </div>
    </section>

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
          .wiz-panel.wiz-active { display: grid; animation: wizFade 0.22s ease; }
          @keyframes wizFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        </style>

        <div id="calculator">
          <div class="flex flex-col lg:flex-row gap-8 items-stretch">

            <!-- Left: Step Wizard -->
            <div class="w-full lg:w-2/3 flex">
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col w-full reveal">

                <!-- Progress -->
                <div class="flex items-center justify-between mb-5">
                  <span class="text-xs font-bold text-muted uppercase tracking-widest">Bước <span id="wiz-num-label">1</span> / 5</span>
                  <div class="flex gap-1.5" id="wiz-pips">
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-primary" style="width:2rem" data-pip="0"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="1"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="2"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="3"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="4"></div>
                  </div>
                </div>

                <!-- Step heading -->
                <h4 class="text-lg font-bold text-midnight flex items-center gap-3 mb-1">
                  <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold shrink-0" id="wiz-badge">1</span>
                  <span id="wiz-title-text">Chọn gói Dịch vụ Bright Education</span>
                </h4>
                <p class="text-sm text-muted mb-5 pl-11" style="min-height:18px" id="wiz-subtitle"></p>

                <!-- Panels -->
                <div class="flex-1" id="wiz-panels">

                  <!-- Panel 0: Gói dịch vụ -->
                  <div class="wiz-panel wiz-active gap-4 sm:grid-cols-3" data-panel="0">
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_package" value="15000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Tiêu Chuẩn</div>
                        <div class="text-sm text-primary font-semibold mb-2">15.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">Xử lý hồ sơ cơ bản</div>
                      </div>
                    </label>
                    <label class="cursor-pointer relative">
                      <input type="radio" name="calc_package" value="20000000" class="peer sr-only" checked>
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-amber-400 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded-bl-lg">Khuyên dùng</div>
                        <div class="font-bold text-midnight mb-1">An Tâm</div>
                        <div class="text-sm text-primary font-semibold mb-2">20.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">Đón sân bay, dẫn đi làm giấy tờ tại Nhật</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_package" value="30000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Trọn Vẹn (VIP)</div>
                        <div class="text-sm text-primary font-semibold mb-2">30.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">Cam kết giới thiệu việc làm, đồng hành 24 tháng</div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 1: Khóa học tiếng Nhật -->
                  <div class="wiz-panel gap-4 sm:grid-cols-3" data-panel="1">
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_course" value="0" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Tự học / Đã có N4</div>
                        <div class="text-sm text-primary font-semibold mb-2">0đ</div>
                        <div class="text-xs text-muted mt-auto">Dành cho học sinh đã đủ trình độ</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_course" value="10000000" class="peer sr-only" checked>
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Cơ bản 3 tháng</div>
                        <div class="text-sm text-primary font-semibold mb-2">10.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">Chương trình chuẩn N5</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_course" value="15000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Chuyên sâu 6 tháng</div>
                        <div class="text-sm text-primary font-semibold mb-2">15.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">Luyện thi JLPT N4</div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 2: Trường Nhật Ngữ -->
                  <div class="wiz-panel gap-4 sm:grid-cols-2" data-panel="2">
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_school" value="110000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col justify-between">
                        <div>
                          <div class="font-bold text-midnight">Trường ở tỉnh xa</div>
                          <div class="text-xs text-muted mt-1">Hokkaido, Ibaraki, Oita... Học phí và sinh hoạt phí đều rất rẻ.</div>
                        </div>
                        <div class="text-sm text-primary font-bold mt-3">~ 110 Triệu VNĐ</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_school" value="125000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col justify-between">
                        <div>
                          <div class="font-bold text-midnight">Thành phố cỡ trung</div>
                          <div class="text-xs text-muted mt-1">Fukuoka, Chiba, Saitama... Dễ tìm việc làm, chi phí vừa phải.</div>
                        </div>
                        <div class="text-sm text-primary font-bold mt-3">~ 125 Triệu VNĐ</div>
                      </div>
                    </label>
                    <label class="cursor-pointer relative">
                      <input type="radio" name="calc_school" value="135000000" class="peer sr-only" checked>
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-amber-400 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded-bl-lg">Phổ biến</div>
                        <div>
                          <div class="font-bold text-midnight pr-12">Ngoại ô Tokyo / Osaka</div>
                          <div class="text-xs text-muted mt-1">Cách trung tâm 30-40p tàu. Cân bằng tốt giữa chi phí và cơ hội.</div>
                        </div>
                        <div class="text-sm text-primary font-bold mt-3">~ 135 Triệu VNĐ</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_school" value="145000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col justify-between">
                        <div>
                          <div class="font-bold text-midnight">Trung tâm Tokyo / Osaka</div>
                          <div class="text-xs text-muted mt-1">Sầm uất, nhiều cơ hội việc làm lương cao nhưng học phí đắt.</div>
                        </div>
                        <div class="text-sm text-primary font-bold mt-3">~ 145 Triệu VNĐ</div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 3: Sinh hoạt ban đầu -->
                  <div class="wiz-panel gap-4 sm:grid-cols-3" data-panel="3">
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_living" value="30000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Tiết Kiệm</div>
                        <div class="text-sm text-primary font-semibold mb-2">~ 30.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">KTX chung 4 người + 10 Man tiền mặt phòng thân</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_living" value="45000000" class="peer sr-only" checked>
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Cơ Bản</div>
                        <div class="text-sm text-primary font-semibold mb-2">~ 45.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">KTX tiêu chuẩn 2 người + 12 Man tiền mặt phòng thân</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_living" value="60000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Thoải Mái</div>
                        <div class="text-sm text-primary font-semibold mb-2">~ 60.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">Thuê phòng riêng + 15 Man tiền mặt phòng thân</div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 4: Thủ tục khác -->
                  <div class="wiz-panel gap-4 sm:grid-cols-3" data-panel="4">
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_other" value="8650000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Thấp Nhất</div>
                        <div class="text-sm text-primary font-semibold mb-2">8.650.000đ</div>
                        <div class="text-xs text-muted mt-auto">Tổng các mức thấp nhất (Săn vé giá rẻ)</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_other" value="13000000" class="peer sr-only" checked>
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Trung Bình</div>
                        <div class="text-sm text-primary font-semibold mb-2">13.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">Chi tiêu hợp lý, vé máy bay phổ thông</div>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input type="radio" name="calc_other" value="17000000" class="peer sr-only">
                      <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-primary/30 h-full flex flex-col">
                        <div class="font-bold text-midnight mb-1">Dự Tính An Toàn</div>
                        <div class="text-sm text-primary font-semibold mb-2">17.000.000đ</div>
                        <div class="text-xs text-muted mt-auto">Tổng mức cao nhất, bay thẳng giờ đẹp</div>
                      </div>
                    </label>
                  </div>

                </div><!-- /wiz-panels -->

                <!-- Footer nav -->
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between" style="min-height:36px">
                  <button id="wiz-back" class="text-sm text-muted hover:text-primary transition-colors flex items-center gap-1.5" style="visibility:hidden">
                    <i class="bi bi-arrow-left text-xs"></i> Quay lại
                  </button>
                  <span class="text-sm text-slate-400 italic" id="wiz-hint">Chọn một mục để tiếp tục →</span>
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
                    <span class="text-white/70">1. Dịch vụ Bright:</span>
                    <span class="font-semibold text-right" id="summary_package">20.000.000đ</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">2. Khóa học VN:</span>
                    <span class="font-semibold text-right" id="summary_course">10.000.000đ</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">3. Học phí trường:</span>
                    <span class="font-semibold text-right" id="summary_school">135.000.000đ</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">4. Ăn ở ban đầu:</span>
                    <span class="font-semibold text-right" id="summary_living">45.000.000đ</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">5. Thủ tục khác:</span>
                    <span class="font-semibold text-right" id="summary_other">13.000.000đ</span>
                  </div>
                </div>
                <div class="border-t border-white/10 pt-6 mt-6 relative z-10">
                  <div class="text-sm text-sage-300 font-bold tracking-widest uppercase mb-1">Tổng Cần Chuẩn Bị</div>
                  <div class="text-4xl font-black text-white font-display tracking-tight break-words">
                    <span id="summary_total">223.000.000</span><span class="text-xl ml-1 text-white/70 font-medium">VNĐ</span>
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
              const get = name => { const el = document.querySelector(`input[name="${name}"]:checked`); return el ? parseInt(el.value) : 0; };
              const vals = STEPS.map(s => get(s.name));
              const ids  = ['summary_package','summary_course','summary_school','summary_living','summary_other'];
              vals.forEach((v, i) => document.getElementById(ids[i]).textContent = fmt(v));
              const total = vals.reduce((a, b) => a + b, 0);
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
              document.getElementById('wiz-hint').textContent        = idx < 4 ? 'Chọn một mục để tiếp tục →' : 'Đã hoàn thành tất cả các bước ✓';
            }

            document.querySelectorAll('#calculator input[type="radio"]').forEach(input => {
              input.addEventListener('change', function() {
                updateSummary();
                const panelIdx = parseInt(this.closest('.wiz-panel').dataset.panel);
                if (panelIdx < 4) setTimeout(() => goToStep(panelIdx + 1), 320);
              });
            });

            document.getElementById('wiz-back').addEventListener('click', () => {
              if (currentStep > 0) goToStep(currentStep - 1);
            });

            goToStep(0);
            updateSummary();
          });
        </script>
      </div>
    </section>

    <section id="stories" class="bg-white py-20 lg:py-28 relative">
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto mb-16 max-w-2xl text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Câu chuyện từ học viên</h2>
          <p class="mt-4 text-lg text-muted">Hành trình thật, kinh nghiệm thực tế và mẹo sống tại Nhật.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <article class="flex h-full flex-col rounded-3xl bg-white p-8 shadow-soft border border-slate-100 card-hover reveal">
            <div class="mb-6 flex gap-1 text-primary text-lg">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p class="text-[15px] text-slate-700 leading-relaxed italic mb-6">"Bright Education hỗ trợ mình tìm trường có chương trình công nghệ phù hợp và việc làm thêm tại trung tâm dữ liệu. Visa nhận sau 12 ngày, giờ mình đã chuyển tiếp vào Đại học Tohoku."</p>
            <div class="mt-auto border-t border-slate-100 pt-5 flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-primary font-bold font-display">Q</div>
                <div>
                    <h3 class="font-bold text-primary font-display">Phạm Nhật Quang</h3>
                    <div class="text-[12px] text-muted font-medium mt-0.5">Kỳ 4/2024 • Tokyo • N2</div>
                </div>
            </div>
          </article>
          <article class="flex h-full flex-col rounded-3xl bg-white p-8 shadow-soft border border-slate-100 card-hover reveal reveal-delay-100">
            <div class="mb-6 flex gap-1 text-primary text-lg">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p class="text-[15px] text-slate-700 leading-relaxed italic mb-6">"Nhờ team luyện phỏng vấn với giáo viên Nhật nên mình đậu học bổng 70% học phí. Hỗ trợ tìm ký túc xá rất nhanh, chỉ 2 ngày là có phòng."</p>
            <div class="mt-auto border-t border-slate-100 pt-5 flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-primary font-bold font-display">N</div>
                <div>
                    <h3 class="font-bold text-primary font-display">Lê Thảo Nguyên</h3>
                    <div class="text-[12px] text-muted font-medium mt-0.5">Kỳ 10/2023 • Osaka • N3</div>
                </div>
            </div>
          </article>
          <article class="flex h-full flex-col rounded-3xl bg-white p-8 shadow-soft border border-slate-100 card-hover reveal reveal-delay-200">
            <div class="mb-6 flex gap-1 text-primary text-lg">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p class="text-[15px] text-slate-700 leading-relaxed italic mb-6">"Từ khâu dịch thuật hồ sơ đến hướng dẫn xin visa đều rất chi tiết. Sau khi sang Nhật mình được hỗ trợ đăng ký bảo hiểm và tìm công việc làm thêm tại siêu thị."</p>
            <div class="mt-auto border-t border-slate-100 pt-5 flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-bold font-display">T</div>
                <div>
                    <h3 class="font-bold text-primary font-display">Đặng Anh Thư</h3>
                    <div class="text-[12px] text-muted font-medium mt-0.5">Kỳ 7/2022 • Fukuoka • N4</div>
                </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Blog Section -->
    <?php if (!empty($latest_posts)): ?>
    <section class="bg-white py-20 lg:py-28 relative">
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
                src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" 
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
                        <p class="text-[15px] font-bold text-white mt-0.5"><?php echo getSetting('site_address', '207 Quang Trung, Thành Đông, Hải Phòng'); ?></p>
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
  </main>

<?php include 'includes/footer.php'; ?>
