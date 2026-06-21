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
    <section class="relative overflow-hidden bg-mesh min-h-[90vh] flex items-center pt-24 pb-16">
      
      <div class="relative mx-auto max-w-7xl px-5 lg:px-8 w-full">
        <div class="grid gap-12 lg:gap-8 lg:grid-cols-2 items-center">
          <!-- Left: Content -->
          <div class="space-y-8 z-10">
            <div class="reveal">
              <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 backdrop-blur-sm px-4 py-1.5 text-xs font-bold text-primary shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-slate-500"></span>
                </span>
                Tư vấn toàn diện • Hơn 10 năm kinh nghiệm
              </span>
            </div>
            <h1 class="reveal reveal-delay-100 text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold leading-[1.1] tracking-tight text-primary font-display">
              Du học Nhật Bản trọn vẹn cùng <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-500">Bright Education</span>
            </h1>
            <p class="reveal reveal-delay-200 text-lg text-muted md:text-xl max-w-lg leading-relaxed">
              Từ định hướng ngành, luyện phỏng vấn, xin visa đến hỗ trợ việc làm thêm. Đã đồng hành cùng 1200+ học viên chinh phục 48 tỉnh thành.
            </p>
            <div class="reveal reveal-delay-300 flex flex-wrap items-center gap-4">
              <a class="inline-flex items-center justify-center rounded-2xl bg-primary px-6 py-3.5 text-[15px] font-semibold text-white transition-all hover:bg-ink shadow-medium hover:shadow-hard btn-primary" href="/contact">
                Đặt lịch tư vấn miễn phí <i class="bi bi-arrow-right ml-2"></i>
              </a>
              <a class="inline-flex items-center justify-center rounded-2xl bg-white border border-slate-200 px-6 py-3.5 text-[15px] font-semibold text-primary transition hover:bg-slate-50 hover:border-slate-300 shadow-soft" href="/services">
                Xem quy trình <i class="bi bi-play-circle ml-2 text-primary"></i>
              </a>
            </div>
            
            <div class="reveal reveal-delay-400 grid grid-cols-3 gap-6 pt-8 border-t border-slate-200/60 mt-8">
              <div>
                <dt class="text-3xl font-bold text-primary font-display">98.5%</dt>
                <dd class="text-[11px] font-semibold uppercase tracking-wider text-muted mt-1">Tỷ lệ đỗ COE</dd>
              </div>
              <div>
                <dt class="text-3xl font-bold text-primary font-display">7.4<span class="text-xl">tỷ</span></dt>
                <dd class="text-[11px] font-semibold uppercase tracking-wider text-muted mt-1">Học bổng đạt</dd>
              </div>
              <div>
                <dt class="text-3xl font-bold text-primary font-display">135+</dt>
                <dd class="text-[11px] font-semibold uppercase tracking-wider text-muted mt-1">Đối tác trường</dd>
              </div>
            </div>
          </div>

          <!-- Right: Image & Floating Badges -->
          <div class="relative z-10 reveal reveal-delay-300 mt-10 lg:mt-0">
             <div class="relative shape-blob overflow-hidden shadow-hard aspect-[4/5] sm:aspect-square lg:aspect-[4/5] transform transition-transform hover:scale-[1.02] duration-700 max-w-md mx-auto lg:ml-auto lg:mr-0 float-slow">
               <img 
                 src="/assets/images/hero_main.png" 
                 alt="Sinh viên Bright Education" 
                 class="absolute inset-0 w-full h-full object-cover scale-105"
               />
               <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 via-transparent to-primary-800/10 mix-blend-overlay"></div>
             </div>
             
             <!-- Floating Badges -->
             <div class="absolute -left-2 sm:-left-8 bottom-1/4 glass rounded-2xl p-4 shadow-medium flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-primary">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                </div>
                <div>
                    <p class="text-[11px] text-muted font-semibold uppercase">Mentor 1-1</p>
                    <p class="text-sm font-bold text-primary">Đồng hành tại Nhật</p>
                </div>
             </div>
             
             <div class="absolute -right-2 sm:-right-8 top-1/4 glass rounded-2xl p-4 shadow-medium flex items-center gap-4 animate-bounce" style="animation-duration: 4s; animation-delay: 1s;">
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
              Bright Education là đơn vị tư vấn du học Nhật Bản với trụ sở tại Hà Nội và văn phòng hỗ trợ tại Tokyo. Chúng tôi tập trung vào trải nghiệm cá nhân hóa, cập nhật nhanh tiêu chí tuyển sinh của các trường.
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
          <span class="text-primary font-bold tracking-wider uppercase text-xs mb-3 block">Điểm khác biệt</span>
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Vì sao chọn Bright Education?</h2>
          <p class="mt-4 text-lg text-muted">Kết hợp thế mạnh môi trường Nhật và mạng lưới cố vấn thực chiến.</p>
        </div>
        
        <!-- Youthful Leaf Shape Banner -->
        <div class="rounded-[4rem_1rem_4rem_1rem] sm:rounded-[8rem_2rem_8rem_2rem] overflow-hidden mb-16 shadow-hard reveal relative h-64 md:h-80 w-full group">
            <img src="/assets/images/whyus_tokyo.png" alt="Tokyo Cityscape" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[4s] group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-tr from-primary/95 via-primary/60 to-transparent mix-blend-multiply"></div>
            
            <!-- Decorative Elements -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-12 left-1/4 w-32 h-32 bg-sage-400/20 rounded-full blur-xl"></div>
            
            <div class="absolute inset-y-0 left-0 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
                <div class="flex items-center gap-3 mb-4">
                  <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 backdrop-blur text-white shadow-sm"><i class="bi bi-globe-americas"></i></span>
                  <span class="text-white text-xs font-bold tracking-widest uppercase">Tầm nhìn toàn cầu</span>
                </div>
                <h3 class="text-white text-3xl sm:text-4xl lg:text-5xl font-display font-black leading-[1.1] tracking-tight">Kết nối tri thức <br/> <span class="text-sage-200">Không giới hạn</span></h3>
                <p class="text-primary-50 mt-4 max-w-md hidden sm:block text-[15px] leading-relaxed">Trải nghiệm giáo dục tiên tiến và môi trường sống hiện đại bậc nhất tại Nhật Bản cùng lộ trình cá nhân hóa 100%.</p>
            </div>
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
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Dịch vụ tư vấn toàn diện</h2>
          <p class="mt-4 text-lg text-muted">Chúng tôi quản lý từng bước trong hành trình du học để bạn yên tâm tập trung học tập.</p>
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

    <section id="process" class="bg-mesh border-t border-slate-100">
      <div class="mx-auto max-w-7xl px-5 lg:px-8 py-20 lg:py-28">
        <div class="mx-auto mb-20 max-w-2xl text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Quy trình 6 bước chuẩn hóa</h2>
          <p class="mt-4 text-lg text-muted">Mỗi bước đều có cố vấn chuyên trách kiểm tra chi tiết, loại bỏ rủi ro giấy tờ.</p>
        </div>
        
        <div class="relative mx-auto max-w-4xl">
          <!-- Central Line -->
          <div class="absolute left-6 md:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-sage-200 via-sakura-200 to-transparent -translate-x-1/2 hidden sm:block"></div>
          
          <div class="space-y-12 sm:space-y-24">
            
            <!-- Component OXY Template (I will inline it to avoid PHP complexity) -->
            <?php
              function renderTimelineDetails($t1, $t4, $t7, $t10, $explanation) {
                  return '
                  <details class="group bg-white rounded-2xl border border-slate-200 shadow-sm transition-all hover:shadow-medium w-full max-w-[300px] mx-auto sm:mx-0">
                      <summary class="flex items-center justify-between cursor-pointer p-3 sm:p-4 font-bold text-primary text-[14px] list-none outline-none marker:hidden [&::-webkit-details-marker]:hidden">
                          <div class="flex items-center gap-2.5">
                              <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-sage-500 group-hover:bg-primary group-hover:text-white transition-colors">
                                  <i class="bi bi-calendar2-week"></i>
                              </div>
                              <span>Xem hạn 4 kỳ</span>
                          </div>
                          <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-open:rotate-180 transition-transform">
                              <i class="bi bi-chevron-down text-xs"></i>
                          </div>
                      </summary>
                      <div class="p-4 pt-2 border-t border-slate-100 mt-2">
                          <p class="text-[13px] text-slate-600 mb-4 leading-relaxed">'.$explanation.'</p>
                          <ul class="space-y-2.5">
                              <li class="flex items-center justify-between text-[13px]">
                                  <span class="font-bold text-slate-700 bg-slate-50 px-2 py-1 rounded-md border border-slate-200">Kỳ Tháng 1</span>
                                  <span class="font-black text-primary font-display whitespace-nowrap">'.$t1.'</span>
                              </li>
                              <li class="flex items-center justify-between text-[13px]">
                                  <span class="font-bold text-sakura-700 bg-sakura-50 px-2 py-1 rounded-md border border-sakura-200">Kỳ Tháng 4</span>
                                  <span class="font-black text-primary font-display whitespace-nowrap">'.$t4.'</span>
                              </li>
                              <li class="flex items-center justify-between text-[13px]">
                                  <span class="font-bold text-sage-700 bg-sage-50 px-2 py-1 rounded-md border border-sage-200">Kỳ Tháng 7</span>
                                  <span class="font-black text-primary font-display whitespace-nowrap">'.$t7.'</span>
                              </li>
                              <li class="flex items-center justify-between text-[13px]">
                                  <span class="font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded-md border border-amber-200">Kỳ Tháng 10</span>
                                  <span class="font-black text-primary font-display whitespace-nowrap">'.$t10.'</span>
                              </li>
                          </ul>
                      </div>
                  </details>';
              }
            ?>

            <!-- Step 1 -->
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between group reveal">
              <div class="sm:w-5/12 sm:text-right order-2 sm:order-1 mt-4 sm:mt-0 ml-16 sm:ml-0 pr-0 sm:pr-12">
                <h3 class="text-xl font-bold text-primary font-display">Khảo sát & Lên kế hoạch</h3>
                <p class="mt-3 text-[15px] text-muted">Đánh giá tiếng Nhật, học lực, tài chính. Lựa chọn kỳ nhập học phù hợp và lập lộ trình học tiếng tại VN.</p>
              </div>
              <div class="absolute left-0 sm:left-1/2 flex h-14 w-14 items-center justify-center rounded-full bg-white border-[4px] border-slate-200 text-xl font-black text-primary shadow-sm sm:-translate-x-1/2 transition-transform group-hover:scale-110 z-10 font-display">1</div>
              <div class="sm:w-5/12 order-3 sm:order-3 hidden sm:block">
                 <?= renderTimelineDetails('Tháng 5', 'Tháng 8', 'Tháng 11', 'Tháng 2', 'Thời điểm lý tưởng để bắt đầu lập kế hoạch tài chính và luyện tiếng Nhật nền tảng trước khi nhập học.') ?>
              </div>
            </div>

            <!-- Step 2 -->
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between group reveal">
              <div class="sm:w-5/12 order-1 sm:order-1 hidden sm:block">
                 <?= renderTimelineDetails('Tháng 7', 'Tháng 10', 'Tháng 1', 'Tháng 4', 'Đăng ký nguyện vọng chọn trường Nhật ngữ. Cần đặt cọc giữ chỗ và hoàn thiện bộ hồ sơ cá nhân.') ?>
              </div>
              <div class="absolute left-0 sm:left-1/2 flex h-14 w-14 items-center justify-center rounded-full bg-white border-[4px] border-slate-200 text-xl font-black text-primary shadow-sm sm:-translate-x-1/2 transition-transform group-hover:scale-110 z-10 font-display">2</div>
              <div class="sm:w-5/12 order-2 sm:order-3 mt-4 sm:mt-0 ml-16 sm:ml-0 pl-0 sm:pl-12">
                <h3 class="text-xl font-bold text-primary font-display">Chọn trường & Đặt cọc</h3>
                <p class="mt-3 text-[15px] text-muted">Đề xuất 3-5 trường theo tiêu chí. Hỗ trợ đăng ký giữ chỗ và chuẩn bị bộ hồ sơ tiêu chuẩn.</p>
              </div>
            </div>

            <!-- Step 3 -->
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between group reveal">
              <div class="sm:w-5/12 sm:text-right order-2 sm:order-1 mt-4 sm:mt-0 ml-16 sm:ml-0 pr-0 sm:pr-12">
                <h3 class="text-xl font-bold text-primary font-display">Xin tư cách lưu trú (COE)</h3>
                <p class="mt-3 text-[15px] text-muted">Kiểm tra chéo hồ sơ, nộp sang trường, theo dõi phản hồi Cục XNC và luyện phỏng vấn online.</p>
              </div>
              <div class="absolute left-0 sm:left-1/2 flex h-14 w-14 items-center justify-center rounded-full bg-white border-[4px] border-slate-200 text-xl font-black text-primary shadow-sm sm:-translate-x-1/2 transition-transform group-hover:scale-110 z-10 font-display">3</div>
              <div class="sm:w-5/12 order-3 sm:order-3 hidden sm:block">
                 <?= renderTimelineDetails('Tháng 9', 'Tháng 11', 'Tháng 3', 'Tháng 5', 'Hồ sơ được gửi sang Nhật xin tư cách lưu trú. Thời gian xét duyệt của Cục XNC thường từ 2-3 tháng.') ?>
              </div>
            </div>

            <!-- Step 4 -->
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between group reveal">
              <div class="sm:w-5/12 order-1 sm:order-1 hidden sm:block">
                 <?= renderTimelineDetails('Tháng 12', 'Tháng 3', 'Tháng 6', 'Tháng 9', 'Đã có COE! Nộp hồ sơ xin Visa tại Đại sứ quán Nhật Bản. Kết quả thường có nhanh chóng sau 1-2 tuần.') ?>
              </div>
              <div class="absolute left-0 sm:left-1/2 flex h-14 w-14 items-center justify-center rounded-full bg-white border-[4px] border-slate-200 text-xl font-black text-primary shadow-sm sm:-translate-x-1/2 transition-transform group-hover:scale-110 z-10 font-display">4</div>
              <div class="sm:w-5/12 order-2 sm:order-3 mt-4 sm:mt-0 ml-16 sm:ml-0 pl-0 sm:pl-12">
                <h3 class="text-xl font-bold text-primary font-display">Xin Visa du học</h3>
                <p class="mt-3 text-[15px] text-muted">Chuẩn bị hồ sơ Đại sứ quán, hướng dẫn nộp học phí, lăn tay và nhận kết quả Visa nhanh chóng.</p>
              </div>
            </div>

            <!-- Step 5 -->
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between group reveal">
              <div class="sm:w-5/12 sm:text-right order-2 sm:order-1 mt-4 sm:mt-0 ml-16 sm:ml-0 pr-0 sm:pr-12">
                <h3 class="text-xl font-bold text-primary font-display">Hành trang trước khi bay</h3>
                <p class="mt-3 text-[15px] text-muted">Tham gia workshop văn hóa, hướng dẫn xếp hành lý, mở thẻ ngân hàng nội địa và đặt vé máy bay.</p>
              </div>
              <div class="absolute left-0 sm:left-1/2 flex h-14 w-14 items-center justify-center rounded-full bg-white border-[4px] border-slate-200 text-xl font-black text-primary shadow-sm sm:-translate-x-1/2 transition-transform group-hover:scale-110 z-10 font-display">5</div>
              <div class="sm:w-5/12 order-3 sm:order-3 hidden sm:block">
                 <?= renderTimelineDetails('Cuối T12', 'Cuối T3', 'Cuối T6', 'Cuối T9', 'Chủ động đặt vé máy bay sớm, chuẩn bị hành lý và tham gia buổi hướng dẫn văn hóa trước khi xuất cảnh.') ?>
              </div>
            </div>

            <!-- Step 6 -->
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between group reveal">
              <div class="sm:w-5/12 order-1 sm:order-1 hidden sm:block">
                 <?= renderTimelineDetails('Đầu T1', 'Đầu T4', 'Đầu T7', 'Đầu T10', 'Nhập cảnh Nhật Bản. Sẽ có đại diện trường và đối tác đưa đón sân bay, hỗ trợ đăng ký thủ tục nhập cư ban đầu.') ?>
              </div>
              <div class="absolute left-0 sm:left-1/2 flex h-14 w-14 items-center justify-center rounded-full bg-white border-[4px] border-slate-200 text-xl font-black text-primary shadow-sm sm:-translate-x-1/2 transition-transform group-hover:scale-110 z-10 font-display">6</div>
              <div class="sm:w-5/12 order-2 sm:order-3 mt-4 sm:mt-0 ml-16 sm:ml-0 pl-0 sm:pl-12">
                <h3 class="text-xl font-bold text-primary font-display">Đón tiếp & Hòa nhập</h3>
                <p class="mt-3 text-[15px] text-muted">Mentor đón tại sân bay Nhật, đưa về nhà ở, đăng ký cư trú phường và hướng dẫn đi làm thêm.</p>
              </div>
            </div>
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

        <div id="calculator">
          <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Steps -->
            <div class="w-full lg:w-2/3 space-y-8">
              
              <!-- Step 1: Dịch vụ Bright -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm reveal">
                <h4 class="text-lg font-bold text-midnight mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-sage-100 text-sage-600 flex items-center justify-center text-sm">1</span> 
                  Chọn gói Dịch vụ Bright Education
                </h4>
                <div class="grid sm:grid-cols-3 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_package" value="15000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Tiêu Chuẩn</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">15.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Xử lý hồ sơ cơ bản</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_package" value="20000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col relative overflow-hidden">
                      <div class="absolute top-0 right-0 bg-amber-400 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded-bl-lg">Khuyên dùng</div>
                      <div class="font-bold text-midnight mb-1">An Tâm</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">20.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Đón sân bay, dẫn đi làm giấy tờ tại Nhật</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_package" value="30000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Trọn Vẹn (VIP)</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">30.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Cam kết giới thiệu việc làm, đồng hành 24 tháng</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Step 2: Khóa học tiếng Nhật tại VN -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm reveal">
                <h4 class="text-lg font-bold text-midnight mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-sage-100 text-sage-600 flex items-center justify-center text-sm">2</span> 
                  Chương trình học Tiếng Nhật tại Việt Nam
                </h4>
                <div class="grid sm:grid-cols-3 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_course" value="0" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Tự học / Đã có N4</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">0đ</div>
                      <div class="text-xs text-muted mt-auto">Dành cho học sinh đã đủ trình độ</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_course" value="10000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Cơ bản 3 tháng</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">10.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Chương trình chuẩn N5</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_course" value="15000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Chuyên sâu 6 tháng</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">15.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Luyện thi JLPT N4</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Step 3: Trường Nhật Ngữ -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm reveal">
                <h4 class="text-lg font-bold text-midnight mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-sage-100 text-sage-600 flex items-center justify-center text-sm">3</span> 
                  Lựa chọn Trường Nhật Ngữ (Năm đầu tiên)
                </h4>
                <div class="grid sm:grid-cols-2 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_school" value="110000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col justify-between">
                      <div>
                        <div class="font-bold text-midnight">Trường ở tỉnh xa</div>
                        <div class="text-xs text-muted mt-1">Hokkaido, Ibaraki, Oita... Học phí và sinh hoạt phí đều rất rẻ.</div>
                      </div>
                      <div class="text-sm text-sage-600 font-bold mt-3">~ 110 Triệu VNĐ</div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_school" value="125000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col justify-between">
                      <div>
                        <div class="font-bold text-midnight">Thành phố cỡ trung</div>
                        <div class="text-xs text-muted mt-1">Fukuoka, Chiba, Saitama... Dễ tìm việc làm, chi phí vừa phải.</div>
                      </div>
                      <div class="text-sm text-sage-600 font-bold mt-3">~ 125 Triệu VNĐ</div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_school" value="135000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col justify-between relative overflow-hidden">
                      <div class="absolute top-0 right-0 bg-amber-400 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded-bl-lg">Phổ biến</div>
                      <div>
                        <div class="font-bold text-midnight pr-12">Ngoại ô Tokyo / Osaka</div>
                        <div class="text-xs text-muted mt-1">Cách trung tâm 30-40p tàu. Cân bằng tốt giữa chi phí và cơ hội.</div>
                      </div>
                      <div class="text-sm text-sage-600 font-bold mt-3">~ 135 Triệu VNĐ</div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_school" value="145000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col justify-between">
                      <div>
                        <div class="font-bold text-midnight">Trung tâm Tokyo / Osaka</div>
                        <div class="text-xs text-muted mt-1">Sầm uất, nhiều cơ hội việc làm lương cao nhưng học phí đắt.</div>
                      </div>
                      <div class="text-sm text-sage-600 font-bold mt-3">~ 145 Triệu VNĐ</div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Step 4: KTX & Tiền mặt -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm reveal">
                <h4 class="text-lg font-bold text-midnight mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-sage-100 text-sage-600 flex items-center justify-center text-sm">4</span> 
                  Chi phí sinh hoạt ban đầu tại Nhật
                </h4>
                <div class="grid sm:grid-cols-3 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_living" value="30000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Tiết Kiệm</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">~ 30.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">KTX chung 4 người + 10 Man tiền mặt phòng thân</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_living" value="45000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Cơ Bản</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">~ 45.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">KTX tiêu chuẩn 2 người + 12 Man tiền mặt phòng thân</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_living" value="60000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Thoải Mái</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">~ 60.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Thuê phòng riêng + 15 Man tiền mặt phòng thân</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Step 5: Thủ tục khác -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm reveal">
                <h4 class="text-lg font-bold text-midnight mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-sage-100 text-sage-600 flex items-center justify-center text-sm">5</span> 
                  Chi phí thủ tục khác tại VN
                </h4>
                <p class="text-sm text-muted mb-4">Gồm: Khám lao phổi, Thi JLPT, Hộ chiếu, Vé máy bay</p>
                <div class="grid sm:grid-cols-3 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_other" value="8650000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Thấp Nhất</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">8.650.000đ</div>
                      <div class="text-xs text-muted mt-auto">Tổng các mức thấp nhất (Săn vé giá rẻ)</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_other" value="13000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Trung Bình</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">13.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Chi tiêu hợp lý, vé máy bay phổ thông</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_other" value="17000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sage-500 peer-checked:bg-sage-50 transition-all hover:border-sage-300 h-full flex flex-col">
                      <div class="font-bold text-midnight mb-1">Dự Tính An Toàn</div>
                      <div class="text-sm text-sage-600 font-semibold mb-2">17.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Tổng mức cao nhất, bay thẳng giờ đẹp</div>
                      <div class="absolute top-4 right-4 text-sage-500 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                </div>
              </div>

            </div>

            <!-- Right Column: Sticky Receipt -->
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
            const formatCurrency = (value) => {
              return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
            };

            const formatCurrencyNoSuffix = (value) => {
              return new Intl.NumberFormat('vi-VN').format(value);
            };

            const updateSummary = () => {
              let total = 0;
              
              const packageVal = parseInt(document.querySelector('input[name="calc_package"]:checked').value);
              const courseVal = parseInt(document.querySelector('input[name="calc_course"]:checked').value);
              const schoolVal = parseInt(document.querySelector('input[name="calc_school"]:checked').value);
              const livingVal = parseInt(document.querySelector('input[name="calc_living"]:checked').value);
              const otherVal = parseInt(document.querySelector('input[name="calc_other"]:checked').value);

              document.getElementById('summary_package').textContent = formatCurrency(packageVal);
              document.getElementById('summary_course').textContent = formatCurrency(courseVal);
              document.getElementById('summary_school').textContent = formatCurrency(schoolVal);
              document.getElementById('summary_living').textContent = formatCurrency(livingVal);
              document.getElementById('summary_other').textContent = formatCurrency(otherVal);

              total = packageVal + courseVal + schoolVal + livingVal + otherVal;
              
              const totalEl = document.getElementById('summary_total');
              const currentText = totalEl.textContent.replace(/\./g, '');
              const currentTotal = parseInt(currentText) || 0;
              
              animateValue(totalEl, currentTotal, total, 400);
            };

            function animateValue(obj, start, end, duration) {
              let startTimestamp = null;
              const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const easeProgress = 1 - Math.pow(1 - progress, 3); // easeOutCubic
                obj.innerHTML = formatCurrencyNoSuffix(Math.floor(easeProgress * (end - start) + start));
                if (progress < 1) {
                  window.requestAnimationFrame(step);
                } else {
                  obj.innerHTML = formatCurrencyNoSuffix(end);
                }
              };
              window.requestAnimationFrame(step);
            }

            const inputs = document.querySelectorAll('#calculator input[type="radio"]');
            inputs.forEach(input => {
              input.addEventListener('change', updateSummary);
            });

            // Init
            if(document.getElementById('calculator')) {
                updateSummary();
            }
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
                        <p class="text-[15px] font-bold text-white mt-0.5"><?php echo getSetting('site_phone', '0981 456 789'); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-4 group">
                    <div class="h-12 w-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-primary-300 group-hover:bg-white/10 transition-colors">
                        <i class="bi bi-envelope-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[12px] text-slate-400 font-semibold uppercase tracking-wider">Email</p>
                        <p class="text-[15px] font-bold text-white mt-0.5"><?php echo getSetting('site_email', 'japan@brightconnect.vn'); ?></p>
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
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all" name="phone" placeholder="0981 456 789" required />
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
