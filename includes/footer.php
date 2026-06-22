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
            <li><a href="/services" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Dịch vụ tư vấn</a></li>
            <li><a href="/blog" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Cẩm nang du học</a></li>
            <li><a href="/contact" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Liên hệ</a></li>
          </ul>
        </div>

        <!-- Column 3: Services -->
        <div>
          <h3 class="text-white font-bold font-display tracking-wide mb-6">Dịch Vụ Của Chúng Tôi</h3>
          <ul class="space-y-3 text-sm text-primary-100">
            <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Tư vấn chọn trường</a></li>
            <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Hỗ trợ làm hồ sơ Visa</a></li>
            <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Đào tạo tiếng Nhật</a></li>
            <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Tìm kiếm học bổng</a></li>
            <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Hỗ trợ việc làm tại Nhật</a></li>
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
            <li class="flex items-center gap-3">
              <i class="bi bi-telephone-fill text-white"></i>
              <a href="tel:<?php echo str_replace(' ', '', getSetting('site_phone', '0981 456 789')); ?>" class="hover:text-white transition-colors"><?php echo getSetting('site_phone', '0981 456 789'); ?></a>
            </li>
            <li class="flex items-center gap-3">
              <i class="bi bi-envelope-fill text-white"></i>
              <a href="mailto:<?php echo getSetting('site_email', 'japan@brightconnect.vn'); ?>" class="hover:text-white transition-colors"><?php echo getSetting('site_email', 'japan@brightconnect.vn'); ?></a>
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

  <style>
    .floating-action-buttons {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 50;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 16px;
    }

    /* FAB List & Toggle Styling */
    .fab-list {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 12px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(20px) scale(0.8);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      transform-origin: bottom right;
    }

    .fab-list.active {
      opacity: 1;
      visibility: visible;
      transform: translateY(0) scale(1);
    }

    .fab-main-trigger {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: #0d243e;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      box-shadow: 0 8px 32px rgba(13, 36, 62, 0.25);
      cursor: pointer;
      border: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 70;
      position: relative;
    }

    .fab-main-trigger:hover {
      transform: scale(1.1) rotate(5deg);
      background: #1a3a5a;
    }

    .fab-main-trigger .close-icon {
      display: none;
      font-size: 20px;
    }

    .fab-main-trigger.active {
      background: #f1f5f9;
      color: #0d243e;
      transform: rotate(90deg);
    }

    .fab-main-trigger.active .open-icon {
      display: none;
    }

    .fab-main-trigger.active .close-icon {
      display: block;
    }

    /* Notification Bubble Style */
    .fab-notification {
      background: white;
      border-radius: 18px 18px 4px 18px;
      padding: 14px 16px;
      box-shadow: 0 10px 40px rgba(13, 36, 62, 0.15);
      border: 1px solid rgba(13, 36, 62, 0.05);
      max-width: 280px;
      margin-bottom: 8px;
      position: relative;
      opacity: 0;
      animation: fab-notif-pop 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) 2s both, fab-notif-float 3s ease-in-out infinite 2.6s;
      transform-origin: bottom right;
      z-index: 60;
    }

    .fab-notification::after {
      content: '';
      position: absolute;
      bottom: -6px;
      right: 20px;
      width: 12px;
      height: 12px;
      background: white;
      transform: rotate(45deg);
      border-right: 1px solid rgba(13, 36, 62, 0.05);
      border-bottom: 1px solid rgba(13, 36, 62, 0.05);
      box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.02);
    }

    .notif-close-btn {
      position: absolute;
      top: 6px;
      right: 6px;
      width: 20px;
      height: 20px;
      background: #f1f5f9;
      color: #94a3b8;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      font-size: 14px;
    }

    .notif-close-btn:hover {
      background: #e2e8f0;
      color: #64748b;
    }

    @keyframes fab-notif-pop {
      0% { transform: scale(0.5); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    @keyframes fab-notif-float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    .fab-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      color: white;
      padding: 12px 20px;
      border-radius: 50px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      font-weight: 600;
      font-size: 15px;
    }

    .fab-btn:hover {
      transform: translateX(-4px) scale(1.02);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
      color: white;
    }

    /* Colors for each button */
    .messenger-btn {
      background: linear-gradient(135deg, #0084ff 0%, #0066cc 100%);
    }
    .messenger-btn:hover { background: linear-gradient(135deg, #0099ff 0%, #0077dd 100%); }

    .zalo-btn {
      background: linear-gradient(135deg, #0068ff 0%, #0054cc 100%);
    }
    .zalo-btn:hover { background: linear-gradient(135deg, #0080ff 0%, #0068ff 100%); }
    
    .zalo-icon-text {
      font-family: Arial, sans-serif;
      font-weight: 800;
      font-size: 13px;
      letter-spacing: -0.5px;
      background: white;
      color: #0068ff;
      padding: 2px 5px;
      border-radius: 6px;
      line-height: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .fanpage-btn {
      background: linear-gradient(135deg, #1877f2 0%, #145dbf 100%);
    }
    .fanpage-btn:hover { background: linear-gradient(135deg, #1a85ff 0%, #1877f2 100%); }

    .fb-group-btn {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    .fb-group-btn:hover { background: linear-gradient(135deg, #fbbf24 0%, #ea580c 100%); }

    .fab-btn svg {
      width: 24px;
      height: 24px;
    }

    .messenger-btn svg {
      animation: pulse-icon 2s ease-in-out infinite;
    }

    @keyframes pulse-icon {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    /* Mobile responsive */
    @media (max-width: 640px) {
      .floating-action-buttons {
        bottom: 16px;
        right: 16px;
        gap: 12px;
      }
      .fab-btn {
        width: 50px;
        height: 50px;
        padding: 0;
        justify-content: center;
        border-radius: 50%;
      }
      .fab-text {
        display: none;
      }
      .zalo-icon-text {
        font-size: 12px;
        padding: 2px 4px;
      }
    }

    /* Tablet */
    @media (min-width: 641px) and (max-width: 1024px) {
      .floating-action-buttons {
        bottom: 20px;
        right: 20px;
      }
    }
  </style>

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
