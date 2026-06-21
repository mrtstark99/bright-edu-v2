<?php
require_once 'config/config.php';

$page_title = 'Liên hệ - Bright Education';
include 'includes/header.php';
?>

<main class="pt-20 bg-slate-50 min-h-[calc(100vh-100px)] flex items-center">
  <!-- Split Screen Layout -->
  <section class="max-w-7xl mx-auto px-4 sm:px-5 py-12 lg:py-16 w-full reveal">
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col lg:flex-row border border-slate-100">
      
      <!-- Left: Contact Info (Primary Background) -->
      <div class="lg:w-2/5 bg-primary p-10 lg:p-14 text-white relative overflow-hidden flex flex-col justify-between">
        <!-- Decorative Background -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full mix-blend-screen blur-[60px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-sage-500/20 rounded-full mix-blend-screen blur-[80px] pointer-events-none"></div>
        
        <div class="relative z-10">
          <h1 class="text-3xl lg:text-4xl font-bold font-display mb-4">Kết nối với <br/>Bright Education</h1>
          <p class="text-white/80 text-[15px] leading-relaxed mb-12">
            Hãy để lại thông tin, chuyên viên của chúng tôi sẽ liên hệ lại với bạn trong vòng 24 giờ để tư vấn lộ trình du học phù hợp nhất.
          </p>
          
          <div class="space-y-8">
            <div class="flex items-start gap-4 group">
              <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-white/20 transition-colors border border-white/5 shadow-soft">
                <i class="bi bi-telephone-fill text-sage-200 text-lg"></i>
              </div>
              <div>
                <h3 class="text-[11px] font-bold uppercase tracking-wider text-white/60 mb-1">Hotline</h3>
                <p class="text-[16px] font-semibold text-white"><?php echo getSetting('site_phone', '0981 456 789'); ?></p>
              </div>
            </div>
            
            <div class="flex items-start gap-4 group">
              <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-white/20 transition-colors border border-white/5 shadow-soft">
                <i class="bi bi-envelope-fill text-sakura-200 text-lg"></i>
              </div>
              <div>
                <h3 class="text-[11px] font-bold uppercase tracking-wider text-white/60 mb-1">Email</h3>
                <p class="text-[16px] font-semibold text-white"><?php echo getSetting('site_email', 'japan@brightconnect.vn'); ?></p>
              </div>
            </div>
            
            <div class="flex items-start gap-4 group">
              <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-white/20 transition-colors border border-white/5 shadow-soft">
                <i class="bi bi-geo-alt-fill text-sage-200 text-lg"></i>
              </div>
              <div>
                <h3 class="text-[11px] font-bold uppercase tracking-wider text-white/60 mb-1">Văn phòng</h3>
                <p class="text-[14px] font-medium text-white/90 leading-relaxed">
                  <span class="block"><?php echo getSetting('site_address', '207 Quang Trung, Thành Đông, Hải Phòng'); ?></span>
                </p>
              </div>
            </div>
            
            <div class="flex items-start gap-4 group">
              <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-white/20 transition-colors border border-white/5 shadow-soft">
                <i class="bi bi-clock-fill text-sakura-200 text-lg"></i>
              </div>
              <div>
                <h3 class="text-[11px] font-bold uppercase tracking-wider text-white/60 mb-1">Giờ làm việc</h3>
                <p class="text-[14px] font-medium text-white/90 leading-relaxed">
                  <span class="block mb-1"><?php echo getSetting('working_hours', 'Thứ 2 - Thứ 6: 8:00 - 18:00'); ?></span>
                </p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Bottom Social Links -->
        <div class="relative z-10 mt-16 pt-8 border-t border-white/10 flex gap-4">
          <a href="<?php echo getSetting('facebook_url', '#'); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white shadow-soft">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="<?php echo getSetting('youtube_url', '#'); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white shadow-soft">
            <i class="bi bi-youtube"></i>
          </a>
          <a href="<?php echo getSetting('tiktok_url', '#'); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white shadow-soft">
            <i class="bi bi-tiktok"></i>
          </a>
        </div>
      </div>
      
      <!-- Right: Contact Form -->
      <div class="lg:w-3/5 p-10 lg:p-14 bg-white">
        <h2 class="text-2xl font-bold text-midnight font-display mb-2">Gửi thông tin tư vấn</h2>
        <p class="text-sm text-muted mb-8">Điền thông tin bên dưới để đăng ký nhận lộ trình và bảng giá chi tiết.</p>
        
        <form method="POST" action="/api/contact.php" id="contact-form" class="space-y-6">
          <?php echo csrfField(); ?>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-[12px] font-bold text-midnight uppercase tracking-wider">Họ và tên <span class="text-sakura-600">*</span></label>
              <input type="text" name="name" required placeholder="Nguyễn Văn A" class="w-full px-4 py-3.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-midnight placeholder:text-slate-400">
            </div>
            
            <div class="space-y-2">
              <label class="text-[12px] font-bold text-midnight uppercase tracking-wider">Số điện thoại <span class="text-sakura-600">*</span></label>
              <input type="tel" name="phone" required placeholder="0981 xxx xxx" class="w-full px-4 py-3.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-midnight placeholder:text-slate-400">
            </div>
          </div>
          
          <div class="space-y-2">
            <label class="text-[12px] font-bold text-midnight uppercase tracking-wider">Email <span class="text-sakura-600">*</span></label>
            <input type="email" name="email" required placeholder="email@example.com" class="w-full px-4 py-3.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-midnight placeholder:text-slate-400">
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-[12px] font-bold text-midnight uppercase tracking-wider">Kỳ nhập học dự kiến</label>
              <div class="relative">
                <select name="intake_period" class="w-full px-4 py-3.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-midnight appearance-none">
                  <option>Tháng 4 năm 2025</option>
                  <option>Tháng 7 năm 2025</option>
                  <option>Tháng 10 năm 2025</option>
                  <option>Khác / Đang cân nhắc</option>
                </select>
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                  <i class="bi bi-chevron-down text-sm"></i>
                </div>
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="text-[12px] font-bold text-midnight uppercase tracking-wider">Trình độ tiếng Nhật</label>
              <div class="relative">
                <select name="japanese_level" class="w-full px-4 py-3.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-midnight appearance-none">
                  <option>Chưa học</option>
                  <option>N5</option>
                  <option>N4</option>
                  <option>N3</option>
                  <option>N2 trở lên</option>
                </select>
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                  <i class="bi bi-chevron-down text-sm"></i>
                </div>
              </div>
            </div>
          </div>
          
          <div class="space-y-2">
            <label class="text-[12px] font-bold text-midnight uppercase tracking-wider">Nội dung tư vấn</label>
            <textarea name="message" rows="4" placeholder="Bạn có thắc mắc gì về quy trình, học phí hay chọn trường không?" class="w-full px-4 py-3.5 bg-slate-50 rounded-xl border border-slate-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none text-sm font-medium text-midnight placeholder:text-slate-400 resize-none"></textarea>
          </div>
          
          <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-ink transition-colors shadow-medium hover:shadow-hard flex items-center justify-center gap-2 group">
            Gửi yêu cầu tư vấn
            <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
          </button>
          
          <p class="text-[11px] text-center text-slate-400 mt-4">
            Bằng việc gửi thông tin, bạn đồng ý với <a href="#" class="underline hover:text-primary">Chính sách bảo mật</a> của chúng tôi.
          </p>
        </form>
      </div>
      
    </div>
  </section>
</main>

<script>
document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/api/contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong vòng 24 giờ.');
            this.reset();
        } else {
            alert('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại.'));
        }
    })
    .catch(error => {
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
    });
});
</script>

<?php include 'includes/footer.php'; ?>
