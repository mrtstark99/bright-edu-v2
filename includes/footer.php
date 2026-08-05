  <footer class="bg-primary pt-16 pb-8 border-t border-primary-800">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16">
        
        <!-- Column 1: Brand & Info -->
        <div class="space-y-6">
          <a class="flex items-center group" href="/">
            <img src="<?= APP_URL ?>/assets/images/logo.svg" alt="Bright Education"
                 class="h-14 w-auto transition-transform group-hover:scale-105"
                 style="filter: brightness(0) invert(1);">
          </a>
          <p class="text-primary-100 text-sm leading-relaxed">
            <?php echo getSetting('site_footer_desc', 'Đồng hành cùng hàng ngàn học viên Việt Nam trên con đường chinh phục tri thức và xây dựng sự nghiệp tại Nhật Bản.'); ?>
          </p>
          <div class="flex items-center gap-4 pt-2">
            <a href="<?php echo getSetting('facebook_url', '#'); ?>" target="_blank" class="h-10 w-10 rounded-full bg-primary-800 flex items-center justify-center text-white hover:bg-white hover:text-primary transition-colors">
              <i class="bi bi-facebook text-lg"></i>
            </a>
            <a href="<?php echo getSetting('youtube_url', '#'); ?>" target="_blank" class="h-10 w-10 rounded-full bg-primary-800 flex items-center justify-center text-white hover:bg-white hover:text-primary transition-colors">
              <i class="bi bi-youtube text-lg"></i>
            </a>
            <a href="<?php echo getSetting('tiktok_url', '#'); ?>" target="_blank" class="h-10 w-10 rounded-full bg-primary-800 flex items-center justify-center text-white hover:bg-white hover:text-primary transition-colors">
              <i class="bi bi-tiktok text-lg"></i>
            </a>
          </div>
        </div>

        <!-- Column 2: Quick Links -->
        <div>
          <h3 class="text-white font-bold font-display tracking-wide mb-6">Liên Kết Nhanh</h3>
          <ul class="space-y-3 text-sm text-primary-100">
            <li><a href="/" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Trang chủ</a></li>
            <li><a href="/about" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Về chúng tôi</a></li>
            <li><a href="/schools" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Hệ thống trường</a></li>
            <li><a href="/qa" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Hỏi & Đáp</a></li>
            <li><a href="/blog" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Tin tức & Cẩm nang</a></li>
            <li><a href="/groups" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Đội ngũ & Cộng đồng</a></li>
            <li><a href="/contact" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Liên hệ</a></li>
          </ul>
        </div>

        <!-- Column 3: Services -->
        <div>
          <h3 class="text-white font-bold font-display tracking-wide mb-6">Dịch Vụ Của Chúng Tôi</h3>
          <ul class="space-y-3 text-sm text-primary-100">
            <li><a href="/services" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Tất cả dịch vụ</a></li>
            <li><a href="/courses" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Khóa học tiếng Nhật</a></li>
            <li><a href="/du-hoc" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Thông tin du học</a></li>
            <li><a href="/process" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Quy trình thủ tục</a></li>
            <li><a href="/documents" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Kho tài liệu</a></li>
            <li><a href="/consultation" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Đăng ký tư vấn</a></li>
          </ul>
        </div>

        <!-- Column 4: Contact -->
        <div>
          <h3 class="text-white font-bold font-display tracking-wide mb-6">Thông Tin Liên Hệ</h3>
          <ul class="space-y-4 text-sm text-primary-100">
            <li class="flex items-start gap-3">
              <i class="bi bi-geo-alt-fill text-white mt-0.5"></i>
              <span><?php echo getSetting('site_address', '207 Quang Trung, Thành Đông, Hải Phòng'); ?></span>
            </li>
            <li class="flex items-start gap-3">
              <i class="bi bi-telephone-fill text-white mt-1"></i>
              <div class="flex flex-col gap-1">
                <a href="tel:<?php echo str_replace(' ', '', getSetting('site_phone', '+84 0971044576')); ?>" class="hover:text-white transition-colors">VN: <?php echo getSetting('site_phone', '+84 0971044576'); ?></a>
                <a href="tel:<?php echo str_replace(' ', '', getSetting('site_phone_jp', '+81 08037316436')); ?>" class="hover:text-white transition-colors">JP: <?php echo getSetting('site_phone_jp', '+81 08037316436'); ?></a>
              </div>
            </li>
            <li class="flex items-center gap-3">
              <i class="bi bi-envelope-fill text-white"></i>
              <a href="mailto:<?php echo getSetting('site_email', 'contact@brighteducation.net'); ?>" class="hover:text-white transition-colors"><?php echo getSetting('site_email', 'contact@brighteducation.net'); ?></a>
            </li>
            <li class="flex items-center gap-3">
              <i class="bi bi-clock-fill text-white"></i>
              <span><?php echo getSetting('working_hours', 'Thứ 2 - Thứ 7: 8:00 - 17:30'); ?></span>
            </li>
          </ul>
        </div>

      </div>

      <!-- Bottom Bar -->
      <div class="pt-8 border-t border-primary-800 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-primary-200">
        <p>© <?php echo date('Y'); ?> Bright Education Japan. All rights reserved.</p>
        <div class="flex gap-6">
          <a href="#" class="hover:text-white transition-colors">Chính sách bảo mật</a>
          <a href="#" class="hover:text-white transition-colors">Điều khoản sử dụng</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Floating Action Buttons -->
  <div class="floating-action-buttons">
    <?php 
      // Fetch latest active announcement
      $db_footer = Database::getInstance();
      $stmt_footer = $db_footer->prepare("
          SELECT * FROM announcements 
          WHERE status = 'active' 
          AND (start_date IS NULL OR start_date <= datetime('now','localtime'))
          AND (end_date IS NULL OR end_date >= datetime('now','localtime'))
          ORDER BY priority DESC, created_at DESC
          LIMIT 1
      ");
      $stmt_footer->execute();
      $footer_announcement = $stmt_footer->fetch();
    ?>
    <?php 
      $fab_style = getSetting('fab_display_style', 'expanded'); 
      $is_active = ($fab_style === 'expanded') ? 'active' : '';
    ?>

    <!-- Contact Buttons List (Collapsible) -->
    <div class="fab-list <?php echo $is_active; ?>" id="fab-list">
      <?php if ($footer_announcement): ?>
      <!-- Announcement Bubble inside the list -->
      <div class="fab-notification">
        <div class="flex items-start gap-3">
          <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold shadow-sm relative">
            <i class="bi bi-bell-fill"></i>
            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
          </div>
          <div class="text-[13px] text-slate-700 leading-snug font-medium pr-4">
            <strong class="text-primary"><?php echo htmlspecialchars($footer_announcement['title']); ?>:</strong>
            <?php echo htmlspecialchars($footer_announcement['content']); ?>
          </div>
        </div>
        <button class="notif-close-btn" onclick="this.parentElement.style.display='none'" aria-label="Đóng thông báo">
          <i class="bi bi-x"></i>
        </button>
      </div>
      <?php endif; ?>
      <!-- Tham gia nhóm cộng đồng -->
      <a href="/groups" class="fab-btn fb-group-btn" aria-label="Nhóm cộng đồng">
        <i class="bi bi-people-fill text-xl"></i>
        <span class="fab-text">Nhóm</span>
      </a>

      <!-- Xem Fanpage -->
      <a href="<?php echo getSetting('facebook_url', '#'); ?>" target="_blank" rel="noopener noreferrer" class="fab-btn fanpage-btn" aria-label="Xem Fanpage">
        <i class="bi bi-facebook text-xl"></i>
        <span class="fab-text">Fanpage</span>
      </a>

      <!-- Zalo Chat -->
      <a href="https://zalo.me/<?php echo str_replace(' ', '', getSetting('zalo_phone', '')); ?>" target="_blank" rel="noopener noreferrer" class="fab-btn zalo-btn" aria-label="Chat Zalo">
        <div class="zalo-icon-text">Zalo</div>
        <span class="fab-text">Chat Zalo</span>
      </a>

      <!-- Messenger Chat Button -->
      <a href="https://m.me/<?php echo getSetting('messenger_id', '491649064036887'); ?>" target="_blank" rel="noopener noreferrer" class="fab-btn messenger-btn" aria-label="Chat với chúng tôi trên Messenger">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
          <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.899 1.441 5.478 3.687 7.161V22l3.458-1.898c.923.255 1.901.391 2.855.391 5.523 0 10-4.145 10-9.25S17.523 2 12 2zm.993 12.492l-2.549-2.718-4.974 2.718 5.467-5.803 2.611 2.718 4.912-2.718-5.467 5.803z"/>
        </svg>
        <span class="fab-text">Chat ngay</span>
      </a>
    </div>

    <!-- Main Toggle Button -->
    <button class="fab-main-trigger <?php echo $is_active; ?>" id="fab-trigger" aria-label="Mở menu liên hệ">
      <i class="bi bi-chat-dots-fill open-icon"></i>
      <i class="bi bi-x-lg close-icon"></i>
    </button>
  </div>

  <!-- FAB styles loaded via /assets/css/components.css -->

  <script>
    const revealElements = () => {
      const reveals = document.querySelectorAll('.reveal');
      const trigger = window.innerHeight * 0.85;
      reveals.forEach(el => {
        if (el.getBoundingClientRect().top < trigger) {
          el.classList.add('show');
        }
      });
    };
    revealElements();
    document.addEventListener('scroll', revealElements, { passive: true });

    // FAB Toggle Logic
    const fabTrigger = document.getElementById('fab-trigger');
    const fabList = document.getElementById('fab-list');

    if (fabTrigger && fabList) {
      fabTrigger.addEventListener('click', () => {
        fabTrigger.classList.toggle('active');
        fabList.classList.toggle('active');
      });
    }
  </script>
</body>
</html>
