<?php
require_once 'config/config.php';

$db = Database::getInstance();

// 1. Lấy 3 bài viết mới nhất
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name 
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published'
    ORDER BY p.published_at DESC
    LIMIT 3
");
$stmt->execute();
$blog_posts = $stmt->fetchAll();

// 2. Lấy tối đa 3 cộng đồng hoạt động
$stmt = $db->prepare("
    SELECT * FROM community_groups 
    WHERE status = 'active'
    ORDER BY display_order ASC
    LIMIT 3
");
$stmt->execute();
$community_groups = $stmt->fetchAll();

// 3. Lấy tối đa 3 lịch tư vấn sắp diễn ra
$stmt = $db->prepare("
    SELECT * FROM consultation_slots 
    WHERE status = 'active'
      AND scheduled_date >= date('now','localtime')
    ORDER BY scheduled_date ASC, time_start ASC
    LIMIT 3
");
$stmt->execute();
$consultation_slots = $stmt->fetchAll();

// 4. Lấy 3 trường Nhật ngữ tiêu biểu từ file JSON
$explore_schools = [];
$schools_file = APP_ROOT . '/schools_data.json';
if (file_exists($schools_file)) {
    $schools_json = file_get_contents($schools_file);
    $schools_data = json_decode($schools_json, true);
    if (!empty($schools_data['schools'])) {
        $explore_schools = array_slice($schools_data['schools'], 0, 3);
    }
}

$page_title = 'Khám phá - Bright Education';
include 'includes/header.php';
?>

<main class="pt-24 bg-slate-50 min-h-screen">

  <!-- Interactive Explorer Section -->
  <section class="py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
      
      <!-- Section Header -->
      <div class="mb-10 text-center lg:text-left">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-primary font-display tracking-tight flex items-center justify-center lg:justify-start gap-2.5">
          <i class="bi bi-compass text-primary"></i> Khám phá Bright Education
        </h1>
        <p class="text-muted mt-2 text-[15px] sm:text-base max-w-2xl leading-relaxed">Tìm hiểu câu chuyện của chúng tôi, đọc kinh nghiệm thực tế từ cựu du học sinh và kết nối với cộng đồng học viên.</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Cột Trái: Các thẻ/Tab điều hướng (Tab Controls) -->
        <div class="lg:col-span-4 space-y-4">
          
          <!-- Tab 1: Blog -->
          <button onclick="switchTab('blog')" id="tab-btn-blog" 
            class="tab-btn w-full text-left group rounded-3xl border-2 border-primary bg-white p-6 shadow-medium transition-all hover:shadow-medium flex flex-col active-tab">
            <div class="flex items-center justify-between w-full mb-3">
              <div class="text-3xl font-black text-primary font-display leading-none">01</div>
              <div><i class="bi bi-newspaper text-xl text-primary"></i></div>
            </div>
            <h3 class="text-base font-bold text-primary font-display">Blog & Cẩm nang</h3>
            <p class="text-[13px] text-muted leading-relaxed mt-2">Chia sẻ kinh nghiệm thực tế từ cựu du học sinh và thủ tục visa, học bổng mới nhất.</p>
          </button>

          <!-- Tab 2: Về Chúng Tôi -->
          <button onclick="switchTab('about')" id="tab-btn-about" 
            class="tab-btn w-full text-left group rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition-all hover:shadow-medium flex flex-col">
            <div class="flex items-center justify-between w-full mb-3">
              <div class="text-3xl font-black text-slate-200 font-display leading-none group-hover:text-primary transition-colors">02</div>
              <div><i class="bi bi-people text-xl text-slate-400 group-hover:text-primary transition-colors"></i></div>
            </div>
            <h3 class="text-base font-bold text-slate-800 font-display group-hover:text-primary transition-colors">Về Bright Education</h3>
            <p class="text-[13px] text-muted leading-relaxed mt-2">Câu chuyện 10 năm hình thành, đội ngũ chuyên gia và cam kết đồng hành minh bạch.</p>
          </button>

          <!-- Tab 3: Cộng đồng -->
          <button onclick="switchTab('community')" id="tab-btn-community" 
            class="tab-btn w-full text-left group rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition-all hover:shadow-medium flex flex-col">
            <div class="flex items-center justify-between w-full mb-3">
              <div class="text-3xl font-black text-slate-200 font-display leading-none group-hover:text-primary transition-colors">03</div>
              <div><i class="bi bi-people-fill text-xl text-slate-400 group-hover:text-primary transition-colors"></i></div>
            </div>
            <h3 class="text-base font-bold text-slate-800 font-display group-hover:text-primary transition-colors">Cộng đồng</h3>
            <p class="text-[13px] text-muted leading-relaxed mt-2">Kết nối với hơn 1200+ học viên đang sinh sống và học tập tại Nhật Bản.</p>
          </button>

          <!-- Tab 4: Trường học -->
          <button onclick="switchTab('schools')" id="tab-btn-schools" 
            class="tab-btn w-full text-left group rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition-all hover:shadow-medium flex flex-col">
            <div class="flex items-center justify-between w-full mb-3">
              <div class="text-3xl font-black text-slate-200 font-display leading-none group-hover:text-primary transition-colors">04</div>
              <div><i class="bi bi-building text-xl text-slate-400 group-hover:text-primary transition-colors"></i></div>
            </div>
            <h3 class="text-base font-bold text-slate-800 font-display group-hover:text-primary transition-colors">Danh sách Trường Nhật Ngữ</h3>
            <p class="text-[13px] text-muted leading-relaxed mt-2">Hệ thống các trường ngôn ngữ uy tín liên kết tuyển sinh cùng Bright Education.</p>
          </button>

        </div>

        <!-- Cột Phải: Nội dung xem trước tương ứng (Tab Panels) -->
        <div class="lg:col-span-8 bg-white border border-slate-100 rounded-[2rem] p-6 sm:p-8 shadow-sm min-h-[480px] flex flex-col justify-between">
          
          <!-- Panel 1: Blog & Cẩm nang -->
          <div id="panel-blog" class="tab-panel space-y-6 flex-1 flex flex-col justify-between">
            <div class="space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h2 class="text-2xl font-bold text-primary font-display">Cẩm nang & Tin tức nổi bật</h2>
                <p class="text-sm text-muted mt-1">Cập nhật những bài viết chia sẻ kinh nghiệm sống, làm việc và học tập tại Nhật Bản.</p>
              </div>

              <?php if (empty($blog_posts)): ?>
                <div class="text-center py-10 bg-slate-50 rounded-2xl border border-slate-100 text-muted">
                  <i class="bi bi-journal-x text-3xl block mb-2 text-slate-300"></i>
                  <p class="text-sm font-medium">Chưa có bài viết nào được đăng tải.</p>
                </div>
              <?php else: ?>
                <div class="space-y-4">
                  <?php foreach ($blog_posts as $post): ?>
                    <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" class="flex flex-col sm:flex-row gap-4 p-4 rounded-2xl border border-slate-50 hover:border-slate-100 hover:bg-slate-50/50 transition-all duration-300 group/item">
                      <?php if (!empty($post['featured_image'])): ?>
                        <div class="w-full sm:w-32 h-20 rounded-xl overflow-hidden shrink-0">
                          <img src="<?= htmlspecialchars(strpos($post['featured_image'], 'http') === 0 ? $post['featured_image'] : UPLOAD_URL . $post['featured_image']) ?>" class="w-full h-full object-cover group-hover/item:scale-105 transition-transform duration-500" alt="<?= htmlspecialchars($post['title']) ?>">
                        </div>
                      <?php else: ?>
                        <div class="w-full sm:w-32 h-20 bg-slate-100 rounded-xl flex items-center justify-center shrink-0">
                          <i class="bi bi-image text-slate-300 text-xl"></i>
                        </div>
                      <?php endif; ?>
                      <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5">
                          <span class="inline-block text-[10px] font-bold text-orange-600 bg-orange-50 px-2.5 py-0.5 rounded-full uppercase tracking-wider"><?= htmlspecialchars($post['category_name'] ?? 'Du học') ?></span>
                          <span class="text-[11px] text-muted"><i class="bi bi-clock mr-1"></i><?= formatDate($post['published_at'] ?? $post['created_at']) ?></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm sm:text-base leading-snug line-clamp-1 group-hover/item:text-primary transition-colors"><?= htmlspecialchars($post['title']) ?></h4>
                        <p class="text-[12px] text-muted leading-relaxed line-clamp-2 mt-1"><?= htmlspecialchars($post['excerpt'] ?? strip_tags($post['content'] ?? '')) ?></p>
                      </div>
                    </a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end items-center gap-3 mt-6">
              <a href="/consultation" class="inline-flex items-center gap-2 border border-slate-200 text-slate-600 rounded-2xl px-5 py-2.5 font-semibold text-[14px] hover:bg-slate-50 transition-colors shadow-soft">
                <i class="bi bi-calendar-check text-slate-400"></i> Đặt lịch tư vấn
              </a>
              <a href="/blog" class="inline-flex items-center gap-2 bg-primary text-white rounded-2xl px-5 py-2.5 font-semibold text-[14px] hover:bg-ink transition-colors shadow-sm">
                Xem tất cả bài viết <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Panel 2: Về Chúng Tôi -->
          <div id="panel-about" class="tab-panel space-y-6 flex-1 flex flex-col justify-between hidden">
            <div class="space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h2 class="text-2xl font-bold text-primary font-display">Chào bạn, chúng tôi là Bright Education</h2>
                <p class="text-sm text-muted mt-1">Câu chuyện hành trình thực tế của những cựu du học sinh.</p>
              </div>

              <div class="flex flex-col sm:flex-row gap-5 items-center bg-slate-50 p-5 rounded-2xl border border-slate-100">
                <div class="w-20 h-20 rounded-full overflow-hidden shrink-0 border-4 border-white shadow-sm">
                  <img src="/assets/images/about_team.jpg" alt="Hoàng Minh Hiếu" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 text-center sm:text-left">
                  <h4 class="font-bold text-slate-800 text-[15px] sm:text-base">Hoàng Minh Hiếu <span class="text-xs font-normal text-muted block mt-0.5">Người sáng lập | Cựu Giáo viên trường Nhật ngữ Yamate</span></h4>
                  <p class="text-[12px] text-muted leading-relaxed mt-2">"Trải qua 7 năm sinh sống và làm giáo viên quản lý học sinh Việt Nam tại Tokyo, tôi hiểu rõ những thách thức mà du học sinh phải đối mặt. Bright Education hướng tới sự minh bạch tuyệt đối."</p>
                </div>
              </div>

              <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100/50">
                  <div class="text-xl sm:text-2xl font-black text-primary font-display">7+</div>
                  <div class="text-[10px] font-semibold text-muted uppercase tracking-wider mt-1">Năm tại Nhật</div>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100/50">
                  <div class="text-xl sm:text-2xl font-black text-primary font-display">1200+</div>
                  <div class="text-[10px] font-semibold text-muted uppercase tracking-wider mt-1">Học viên hỗ trợ</div>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100/50">
                  <div class="text-xl sm:text-2xl font-black text-primary font-display">60+</div>
                  <div class="text-[10px] font-semibold text-muted uppercase tracking-wider mt-1">Trường liên kết</div>
                </div>
              </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end items-center gap-3 mt-6">
              <a href="/consultation" class="inline-flex items-center gap-2 border border-slate-200 text-slate-600 rounded-2xl px-5 py-2.5 font-semibold text-[14px] hover:bg-slate-50 transition-colors shadow-soft">
                <i class="bi bi-calendar-check text-slate-400"></i> Đặt lịch tư vấn
              </a>
              <a href="/about" class="inline-flex items-center gap-2 bg-primary text-white rounded-2xl px-5 py-2.5 font-semibold text-[14px] hover:bg-ink transition-colors shadow-sm">
                Tìm hiểu thêm câu chuyện <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Panel 3: Cộng đồng -->
          <div id="panel-community" class="tab-panel space-y-6 flex-1 flex flex-col justify-between hidden">
            <div class="space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h2 class="text-2xl font-bold text-primary font-display">Kết nối Cộng đồng Học viên</h2>
                <p class="text-sm text-muted mt-1">Chia sẻ kinh nghiệm sống, tìm nhà trọ, việc làm thêm và học tập tại Nhật Bản.</p>
              </div>

              <?php if (empty($community_groups)): ?>
                <div class="text-center py-10 bg-slate-50 rounded-2xl border border-slate-100 text-muted">
                  <i class="bi bi-people-fill text-3xl block mb-2 text-slate-300"></i>
                  <p class="text-sm font-medium">Mạng lưới cộng đồng đang được cập nhật.</p>
                </div>
              <?php else: ?>
                <div class="space-y-3.5">
                  <?php foreach ($community_groups as $group): ?>
                    <a href="<?= htmlspecialchars($group['url']) ?>" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl border border-slate-50 hover:border-slate-100 hover:bg-slate-50/50 transition-all duration-300 group/item">
                      <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 text-white font-bold text-xl <?php
                        switch(strtolower($group['platform'])) {
                          case 'facebook': echo 'bg-blue-600'; break;
                          case 'zalo': echo 'bg-sky-500'; break;
                          case 'youtube': echo 'bg-red-600'; break;
                          default: echo 'bg-primary';
                        }
                      ?>">
                        <?php
                        switch(strtolower($group['platform'])) {
                          case 'facebook': echo '<i class="bi bi-facebook"></i>'; break;
                          case 'zalo': echo '<span class="text-xs uppercase font-extrabold">Zalo</span>'; break;
                          case 'youtube': echo '<i class="bi bi-youtube"></i>'; break;
                          case 'telegram': echo '<i class="bi bi-telegram"></i>'; break;
                          default: echo '<i class="bi bi-link-45deg"></i>';
                        }
                        ?>
                      </div>
                      <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-slate-800 text-sm sm:text-base leading-snug group-hover/item:text-primary transition-colors"><?= htmlspecialchars($group['name']) ?></h4>
                        <p class="text-[12px] text-muted leading-relaxed line-clamp-1 mt-0.5"><?= htmlspecialchars($group['description'] ?? 'Cộng đồng du học Nhật Bản Bright Education') ?></p>
                      </div>
                      <div class="shrink-0 text-right">
                        <?php if (!empty($group['member_count'])): ?>
                          <span class="inline-block text-[11px] font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full"><?= htmlspecialchars($group['member_count']) ?> thành viên</span>
                        <?php endif; ?>
                      </div>
                    </a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end items-center gap-3 mt-6">
              <a href="/consultation" class="inline-flex items-center gap-2 border border-slate-200 text-slate-600 rounded-2xl px-5 py-2.5 font-semibold text-[14px] hover:bg-slate-50 transition-colors shadow-soft">
                <i class="bi bi-calendar-check text-slate-400"></i> Đặt lịch tư vấn
              </a>
              <a href="/groups" class="inline-flex items-center gap-2 bg-primary text-white rounded-2xl px-5 py-2.5 font-semibold text-[14px] hover:bg-ink transition-colors shadow-sm">
                Tham gia cộng đồng <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Panel 5: Danh sách trường -->
          <div id="panel-schools" class="tab-panel space-y-6 flex-1 flex flex-col justify-between hidden">
            <div class="space-y-6">
              <div class="border-b border-slate-100 pb-4">
                <h2 class="text-2xl font-bold text-primary font-display">Trường Nhật Ngữ liên kết tiêu biểu</h2>
                <p class="text-sm text-muted mt-1">Danh sách một số trường Nhật ngữ chất lượng hàng đầu tại các khu vực của Nhật Bản.</p>
              </div>

              <?php if (empty($explore_schools)): ?>
                <div class="text-center py-10 bg-slate-50 rounded-2xl border border-slate-100 text-muted">
                  <i class="bi bi-building text-3xl block mb-2 text-slate-300"></i>
                  <p class="text-sm font-medium">Danh sách trường đang được cập nhật.</p>
                </div>
              <?php else: ?>
                <div class="space-y-3.5">
                  <?php foreach ($explore_schools as $school): ?>
                    <div class="p-4 rounded-2xl border border-slate-50 hover:border-slate-100 hover:bg-slate-50/30 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                      <div>
                        <span class="inline-block text-[9px] font-bold text-orange-600 bg-orange-50 px-2.5 py-0.5 rounded-full uppercase tracking-wider mb-1.5"><?= htmlspecialchars($school['prefecture'] ?? 'Nhật Bản') ?></span>
                        <h4 class="font-bold text-slate-800 text-sm sm:text-base leading-snug"><?= htmlspecialchars($school['name_jp'] ?? $school['name_en']) ?></h4>
                        <p class="text-[12px] text-muted mt-1.5"><i class="bi bi-geo-alt-fill text-primary mr-1"></i>Khu vực: <?= htmlspecialchars($school['area'] ?? 'Khác') ?> (<?= htmlspecialchars($school['macro_region'] ?? '') ?>)</p>
                      </div>
                      <div class="shrink-0">
                        <?php if (!empty($school['website'])): ?>
                          <a href="<?= htmlspecialchars($school['website']) ?>" target="_blank" class="inline-flex items-center gap-1 text-[12px] font-semibold text-primary hover:underline">
                            Website trường <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                          </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end items-center gap-3 mt-6">
              <a href="/consultation" class="inline-flex items-center gap-2 border border-slate-200 text-slate-600 rounded-2xl px-5 py-2.5 font-semibold text-[14px] hover:bg-slate-50 transition-colors shadow-soft">
                <i class="bi bi-calendar-check text-slate-400"></i> Đặt lịch tư vấn
              </a>
              <a href="/schools" class="inline-flex items-center gap-2 bg-primary text-white rounded-2xl px-5 py-2.5 font-semibold text-[14px] hover:bg-ink transition-colors shadow-sm">
                Xem tất cả trường <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="bg-white py-16 border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 text-center">
      <h2 class="text-2xl font-bold text-primary font-display mb-3">Sẵn sàng bắt đầu hành trình của bạn?</h2>
      <p class="text-muted mb-8 max-w-lg mx-auto">Để lại thông tin để được đội ngũ chuyên viên hỗ trợ xây dựng lộ trình du học cá nhân hóa miễn phí.</p>
      <a href="/contact" class="inline-flex items-center gap-2 bg-primary text-white rounded-2xl px-8 py-3.5 font-semibold text-[15px] hover:bg-ink transition-colors shadow-md">
        Liên hệ tư vấn ngay <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </section>

</main>

<script>
function switchTab(tabName) {
  // 1. Ẩn tất cả các panel nội dung bên phải
  document.querySelectorAll('.tab-panel').forEach(panel => {
    panel.classList.add('hidden');
  });
  
  // 2. Hiển thị panel tương ứng
  const targetPanel = document.getElementById('panel-' + tabName);
  if (targetPanel) {
    targetPanel.classList.remove('hidden');
  }

  // 3. Reset style của toàn bộ các tab ở cột trái
  document.querySelectorAll('.tab-btn').forEach(btn => {
    // Xóa các class active (border-primary, bg-slate-50, shadow)
    btn.classList.remove('border-primary', 'bg-slate-50', 'shadow-medium', 'active-tab');
    btn.classList.add('border-slate-100');
    
    // Reset màu sắc chữ, icon, số về mặc định (slate)
    const number = btn.querySelector('.font-display');
    const icon = btn.querySelector('.bi');
    const title = btn.querySelector('h3');
    
    if (number) {
      number.className = "text-3xl font-black text-slate-200 font-display leading-none group-hover:text-primary transition-colors";
    }
    if (icon) {
      // Thay thế class màu của icon về mặc định
      icon.className = icon.className.replace('text-primary', 'text-slate-400');
      if (!icon.className.includes('text-slate-400')) {
        icon.classList.add('text-slate-400');
      }
    }
    if (title) {
      title.className = "text-base font-bold text-slate-800 font-display group-hover:text-primary transition-colors";
    }
  });

  // 4. Thiết lập style active cho tab được click
  const activeBtn = document.getElementById('tab-btn-' + tabName);
  if (activeBtn) {
    activeBtn.classList.remove('border-slate-100');
    activeBtn.classList.add('border-primary', 'bg-slate-50', 'shadow-medium', 'active-tab');
    
    const number = activeBtn.querySelector('.font-display');
    const icon = activeBtn.querySelector('.bi');
    const title = activeBtn.querySelector('h3');
    
    if (number) {
      number.className = "text-3xl font-black text-primary font-display leading-none transition-colors";
    }
    if (icon) {
      icon.className = icon.className.replace('text-slate-400', 'text-primary');
    }
    if (title) {
      title.className = "text-base font-bold text-primary font-display transition-colors";
    }
  }
}

// On page load, check URL parameter 'tab'
document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const tab = urlParams.get('tab');
  if (tab && ['blog', 'about', 'community', 'consultation', 'schools'].includes(tab)) {
    switchTab(tab);
  }
});
</script>

<?php include 'includes/footer.php'; ?>
