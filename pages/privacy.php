<?php
require_once 'config/config.php';

$page_title = 'Chính sách bảo mật | Bright Education';
$page_description = 'Chính sách thu thập, sử dụng và bảo vệ thông tin cá nhân của học viên khi liên hệ Bright Education.';
include 'includes/header.php';
?>

<main class="pt-20 bg-slate-50 min-h-screen">
  <section class="mx-auto max-w-4xl px-5 py-14 lg:py-20">
    <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-500" aria-label="Breadcrumb">
      <a href="/" class="hover:text-primary">Trang chủ</a><i class="bi bi-chevron-right"></i><span>Chính sách bảo mật</span>
    </nav>
    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-10">
      <h1 class="mb-6 text-3xl font-black text-primary">Chính sách bảo mật</h1>
      <div class="prose max-w-none text-slate-600">
        <p>Bright Education chỉ thu thập thông tin cần thiết để tư vấn lộ trình học tập, xử lý yêu cầu liên hệ và cung cấp dịch vụ mà bạn đăng ký.</p>
        <h2>Thông tin được thu thập</h2>
        <p>Thông tin có thể bao gồm họ tên, số điện thoại, email, trình độ tiếng Nhật, kỳ nhập học dự kiến và nội dung bạn chủ động cung cấp.</p>
        <h2>Mục đích sử dụng</h2>
        <p>Thông tin được dùng để phản hồi yêu cầu, xếp lịch tư vấn, cải thiện dịch vụ và thực hiện nghĩa vụ pháp lý. Chúng tôi không bán thông tin cá nhân.</p>
        <h2>Bảo vệ và thời gian lưu trữ</h2>
        <p>Bright Education áp dụng các biện pháp kỹ thuật và quy trình phù hợp để hạn chế truy cập trái phép. Dữ liệu chỉ được lưu trong thời gian cần thiết cho mục đích đã nêu.</p>
        <h2>Quyền của bạn</h2>
        <p>Bạn có thể yêu cầu xem, chỉnh sửa hoặc xóa thông tin bằng cách liên hệ <a href="mailto:<?php echo htmlspecialchars(getSetting('site_email', 'contact@brighteducation.net')); ?>"><?php echo htmlspecialchars(getSetting('site_email', 'contact@brighteducation.net')); ?></a>.</p>
      </div>
    </article>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
