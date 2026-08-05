<?php
require_once 'config/config.php';

$page_title = 'Điều khoản sử dụng | Bright Education';
$page_description = 'Điều khoản truy cập website, sử dụng nội dung và dịch vụ tư vấn du học Nhật Bản của Bright Education.';
include 'includes/header.php';
?>

<main class="pt-20 bg-slate-50 min-h-screen">
  <section class="mx-auto max-w-4xl px-5 py-14 lg:py-20">
    <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-500" aria-label="Breadcrumb">
      <a href="/" class="hover:text-primary">Trang chủ</a><i class="bi bi-chevron-right"></i><span>Điều khoản sử dụng</span>
    </nav>
    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-10">
      <h1 class="mb-6 text-3xl font-black text-primary">Điều khoản sử dụng</h1>
      <div class="prose max-w-none text-slate-600">
        <p>Khi truy cập website Bright Education, bạn đồng ý sử dụng thông tin và các tính năng trên website theo những điều khoản dưới đây.</p>
        <h2>Phạm vi thông tin</h2>
        <p>Nội dung trên website mang tính tham khảo và có thể được cập nhật theo quy định tuyển sinh, visa, học phí và chính sách của các đơn vị liên quan.</p>
        <h2>Trách nhiệm của người dùng</h2>
        <p>Bạn chịu trách nhiệm về tính chính xác của thông tin đã cung cấp và không được sử dụng website cho hoạt động trái pháp luật, gây gián đoạn hoặc xâm phạm quyền của người khác.</p>
        <h2>Quyền sở hữu</h2>
        <p>Nội dung, hình ảnh và nhận diện do Bright Education sở hữu hoặc được phép sử dụng không được sao chép cho mục đích thương mại khi chưa có chấp thuận.</p>
        <h2>Liên hệ</h2>
        <p>Nếu có câu hỏi về điều khoản, vui lòng liên hệ qua <a href="/contact">trang liên hệ</a>.</p>
      </div>
    </article>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
