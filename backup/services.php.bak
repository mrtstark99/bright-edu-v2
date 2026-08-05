<?php
require_once 'config/config.php';

$db = Database::getInstance();

// Get services
$stmt = $db->prepare("SELECT * FROM services WHERE status = 'active' ORDER BY order_position");
$stmt->execute();
$services = $stmt->fetchAll();

$page_title = 'Dịch vụ - Bright Education';
include 'includes/header.php';
?>

<main class="pt-20">
  <!-- Page Header -->
  <section class="bg-primary pt-20 pb-24 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="mx-auto max-w-7xl px-5 lg:px-8 relative z-10 text-center">
      <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold text-white/80 uppercase tracking-widest mb-6">
        <i class="bi bi-briefcase"></i> Dịch vụ & Bảng giá
      </span>
      <h1 class="text-4xl md:text-[3.25rem] font-bold text-white font-display mb-5 tracking-tight">Dịch vụ & Bảng giá</h1>
      <p class="text-lg text-white/75 max-w-2xl mx-auto leading-relaxed">Chọn gói dịch vụ phù hợp để chúng tôi đồng hành cùng bạn trên con đường chinh phục giấc mơ du học Nhật Bản một cách trọn vẹn và an tâm nhất.</p>
    </div>
  </section>

  <section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display mb-4">Minh bạch 100% — Không phát sinh chi phí ẩn</h2>
        <p class="text-muted text-lg">Tất cả học viên đều được lo trọn gói xử lý hồ sơ, dịch thuật, công chứng, xin COE, luyện phỏng vấn và xin Visa. Chỉ thu phí khi hành trình du học chắc chắn bắt đầu.</p>
      </div>

      <!-- Services Tabs -->
      <?php
      $service_types = [
          'ngon-ngu' => 'Du học Ngôn ngữ',
          'dai-hoc' => 'Du học Đại học',
          'truong-nghe' => 'Trường nghề',
          'dac-dinh' => 'Đặc định'
      ];
      ?>
      <div class="flex flex-wrap justify-center gap-4 mb-12" role="tablist">
          <?php $is_first = true; foreach($service_types as $id => $title): ?>
              <button onclick="switchServiceTab('<?php echo $id; ?>')" id="tab-<?php echo $id; ?>" role="tab" aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>" class="service-tab-btn px-6 sm:px-8 py-3.5 rounded-full font-bold text-[15px] transition-all duration-300 border-2 <?php echo $is_first ? 'bg-primary text-white border-primary shadow-md transform -translate-y-0.5' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-300 hover:text-primary hover:bg-slate-50'; ?>">
                  <?php echo $title; ?>
              </button>
          <?php $is_first = false; endforeach; ?>
      </div>

      <!-- Tab Panels -->
      <div class="tab-content-container relative">
          <?php $is_first = true; foreach($service_types as $id => $title): ?>
              <div id="panel-<?php echo $id; ?>" role="tabpanel" class="service-tab-panel transition-opacity duration-500 <?php echo $is_first ? 'block opacity-100' : 'hidden opacity-0'; ?>">
                  <div class="text-center mb-10">
                      <h3 class="text-2xl font-bold text-primary mb-2">Bảng giá <?php echo $title; ?></h3>
                      <p class="text-muted">Chi tiết các hạng mục và chi phí tham khảo cho chương trình <?php echo $title; ?></p>
                  </div>
                  <?php include 'includes/pricing_table.php'; ?>
              </div>
          <?php $is_first = false; endforeach; ?>
      </div>
    </div>
  </section>

  <section class="py-16 sm:py-24 bg-slate-50 relative border-t border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display mb-4">Bảng Tổng Chi Phí & Hạng Mục Xử Lý Hồ Sơ</h2>
        <p class="text-muted text-lg">Chi tiết các hạng mục đã bao gồm trong gói dịch vụ và các khoản chi phí bên thứ 3 mà bạn cần chuẩn bị cho hành trình du học.</p>
      </div>

      <div class="space-y-12">
        <!-- Table 1 -->
        <div class="rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-soft transition-all hover:shadow-medium">
          <div class="bg-slate-50 border-b border-slate-200 px-8 py-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center flex-shrink-0">
              <i class="bi bi-shield-check text-primary text-xl"></i>
            </div>
            <div>
              <h4 class="text-primary font-bold text-lg mb-1">I. Các hạng mục thuộc trách nhiệm của trung tâm</h4>
              <p class="text-muted text-sm">Cam kết không phát sinh chi phí ngoài gói dịch vụ đã chọn.</p>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider font-bold text-slate-400">
                  <th class="py-4 px-8 w-16 text-center">STT</th>
                  <th class="py-4 px-6">Nội Dung Hạng Mục</th>
                  <th class="py-4 px-6 w-40 text-center">Bright Education</th>
                  <th class="py-4 px-6 w-40 text-center text-amber-600">Giá Thị Trường</th>
                  <th class="py-4 px-8">Ghi Chú</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-[15px]">
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">01</td>
                  <td class="py-5 px-6 font-semibold text-primary">Giới thiệu Nhật Bản, tư vấn thủ tục và lộ trình du học</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-slate-400">-</td>
                  <td class="py-5 px-8 text-muted text-sm">Phù hợp với năng lực và nguyện vọng.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">02</td>
                  <td class="py-5 px-6 font-semibold text-primary">Tư vấn chọn trường và kiểm tra tiếng Nhật đầu vào</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-slate-400">-</td>
                  <td class="py-5 px-8 text-muted text-sm">Đánh giá chính xác trình độ hiện tại.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">03</td>
                  <td class="py-5 px-6 font-semibold text-primary">Liên hệ trường Nhật ngữ và sắp xếp lịch phỏng vấn</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-slate-400">-</td>
                  <td class="py-5 px-8 text-muted text-sm">Trực tiếp kết nối, không qua trung gian.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">04</td>
                  <td class="py-5 px-6 font-semibold text-primary">Luyện phỏng vấn với trường và hướng dẫn xin Visa</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-slate-400">-</td>
                  <td class="py-5 px-8 text-muted text-sm">Luyện tập 1-1 chuyên sâu cho từng học viên.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">05</td>
                  <td class="py-5 px-6 font-semibold text-primary">Xét duyệt ban đầu, nhận định phương án hồ sơ</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-slate-400">-</td>
                  <td class="py-5 px-8 text-muted text-sm">Đưa ra giải pháp tối ưu cho từng trường hợp.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">06</td>
                  <td class="py-5 px-6 font-semibold text-primary">Hoàn thiện hồ sơ, dịch thuật và công chứng</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-amber-600">2.000.000 - 10.000.000đ</td>
                  <td class="py-5 px-8 text-muted text-sm">Xử lý toàn bộ giấy tờ, kể cả hồ sơ khó.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">07</td>
                  <td class="py-5 px-6 font-semibold text-primary">Tư vấn và xử lý hồ sơ chứng minh tài chính</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-amber-600">2.500.000 - 3.000.000đ</td>
                  <td class="py-5 px-8 text-muted text-sm">Hỗ trợ sổ tiết kiệm, chứng minh thu nhập.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">08</td>
                  <td class="py-5 px-6 font-semibold text-primary">Xin xác thực bằng tốt nghiệp cao nhất</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-amber-600">500.000 - 900.000đ</td>
                  <td class="py-5 px-8 text-muted text-sm">Tại các cơ quan có thẩm quyền Việt Nam.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">09</td>
                  <td class="py-5 px-6 font-semibold text-primary">Phí chuyển phát nhanh hồ sơ sang trường</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-amber-600">500.000 - 1.500.000đ</td>
                  <td class="py-5 px-8 text-muted text-sm">Gửi phát nhanh quốc tế đảm bảo an toàn.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-primary transition-colors">10</td>
                  <td class="py-5 px-6 font-semibold text-primary">Xử lý hồ sơ và lệ phí xin Visa tại Đại sứ quán</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center gap-1.5 bg-slate-50 text-primary py-1.5 px-4 rounded-full text-xs font-bold whitespace-nowrap"><i class="bi bi-check2"></i> Đã bao gồm</span></td>
                  <td class="py-5 px-6 text-center font-medium text-amber-600">900.000 - 2.500.000đ</td>
                  <td class="py-5 px-8 text-muted text-sm">Bao gồm cả lệ phí nộp trực tiếp cho ĐSQ.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Table 2 -->
        <div class="rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-soft transition-all hover:shadow-medium">
          <div class="bg-slate-50 border-b border-slate-200 px-8 py-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center flex-shrink-0">
              <i class="bi bi-wallet2 text-amber-500 text-xl"></i>
            </div>
            <div>
              <h4 class="text-primary font-bold text-lg mb-1">II. Chi phí bên thứ 3 (Khách hàng tự chi trả theo thực tế)</h4>
              <p class="text-muted text-sm">Các khoản bắt buộc nộp cho nhà nước hoặc đối tác độc lập.</p>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider font-bold text-slate-400">
                  <th class="py-4 px-8 w-16 text-center">STT</th>
                  <th class="py-4 px-6">Nội Dung Hạng Mục</th>
                  <th class="py-4 px-6 w-64 text-center">Chi Phí Ước Tính (VNĐ)</th>
                  <th class="py-4 px-8">Ghi Chú</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-[15px]">
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-amber-500 transition-colors">01</td>
                  <td class="py-5 px-6 font-semibold text-primary">Làm Hộ chiếu (Passport)</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center justify-center bg-amber-50 text-amber-700 py-1.5 px-4 rounded-xl text-[13px] font-bold whitespace-nowrap min-w-[120px]">~ 200.000</span></td>
                  <td class="py-5 px-8 text-muted text-sm">Đóng trực tiếp cho cơ quan Quản lý XNC Việt Nam.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-amber-500 transition-colors">02</td>
                  <td class="py-5 px-6 font-semibold text-primary">Học tiếng Nhật tại Việt Nam</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center justify-center bg-amber-50 text-amber-700 py-1.5 px-4 rounded-xl text-[13px] font-bold whitespace-nowrap min-w-[120px]">10.000.000 - 13.000.000</span></td>
                  <td class="py-5 px-8 text-muted text-sm">Tùy thuộc vào thời gian học và trung tâm đào tạo.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-amber-500 transition-colors">03</td>
                  <td class="py-5 px-6 font-semibold text-primary">Lệ phí thi năng lực tiếng Nhật (JLPT, Nat-test, J-test...)</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center justify-center bg-amber-50 text-amber-700 py-1.5 px-4 rounded-xl text-[13px] font-bold whitespace-nowrap min-w-[120px]">750.000 - 800.000</span></td>
                  <td class="py-5 px-8 text-muted text-sm">Thi để lấy chứng chỉ nộp kèm hồ sơ.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-amber-500 transition-colors">04</td>
                  <td class="py-5 px-6 font-semibold text-primary">Khám lao phổi</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center justify-center bg-amber-50 text-amber-700 py-1.5 px-4 rounded-xl text-[13px] font-bold whitespace-nowrap min-w-[120px]">700.000 - 1.000.000</span></td>
                  <td class="py-5 px-8 text-muted text-sm">Bắt buộc khám tại bệnh viện do Đại sứ quán chỉ định.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-amber-500 transition-colors">05</td>
                  <td class="py-5 px-6 font-semibold text-primary">Vé máy bay một chiều sang Nhật</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center justify-center bg-amber-50 text-amber-700 py-1.5 px-4 rounded-xl text-[13px] font-bold whitespace-nowrap min-w-[120px]">7.000.000 - 15.000.000</span></td>
                  <td class="py-5 px-8 text-muted text-sm">Dao động tùy thời điểm đặt vé và hãng hàng không.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-amber-500 transition-colors">06</td>
                  <td class="py-5 px-6 font-semibold text-primary">Học phí trường tiếng Nhật (Năm đầu tiên)</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center justify-center bg-amber-50 text-amber-700 py-1.5 px-4 rounded-xl text-[13px] font-bold whitespace-nowrap min-w-[120px]">130.000.000 - 160.000.000</span></td>
                  <td class="py-5 px-8 text-muted text-sm">Đóng trực tiếp vào tài khoản của trường bên Nhật.</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-amber-500 transition-colors">07</td>
                  <td class="py-5 px-6 font-semibold text-primary">Chi phí nhà ở đầu vào tại Nhật (Ký túc xá / Thuê ngoài)</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center justify-center bg-amber-50 text-amber-700 py-1.5 px-4 rounded-xl text-[13px] font-bold whitespace-nowrap min-w-[120px]">15.000.000 - 43.000.000</span></td>
                  <td class="py-5 px-8 text-muted text-sm">Tiền nhà 3-6 tháng, phí đầu vào (Shikikin, Reikin).</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="py-5 px-8 text-center text-slate-300 font-medium group-hover:text-amber-500 transition-colors">08</td>
                  <td class="py-5 px-6 font-semibold text-primary">Sinh hoạt phí mang theo (Tiền mặt phòng thân)</td>
                  <td class="py-5 px-6 text-center"><span class="inline-flex items-center justify-center bg-amber-50 text-amber-700 py-1.5 px-4 rounded-xl text-[13px] font-bold whitespace-nowrap min-w-[120px]">15.000.000 - 25.000.000</span></td>
                  <td class="py-5 px-8 text-muted text-sm">Dành cho chi tiêu tháng đầu tiên (tương đương 10 - 15 man Yên).</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Interactive Calculator Section -->
        <div class="mt-20 border-t border-slate-200 pt-16" id="calculator">
          <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-sky-100 mb-4">
              <i class="bi bi-calculator text-sky-500 text-xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-primary font-display">Dự Toán Chi Phí Tương Tác</h3>
            <p class="text-muted mt-2 max-w-2xl mx-auto">Hãy tùy chỉnh các lựa chọn dưới đây để xem tổng chi phí gia đình cần chuẩn bị cho lộ trình du học của bạn.</p>
          </div>

          <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Steps -->
            <div class="w-full lg:w-2/3 space-y-8">
              
              <!-- Step 1: Dịch vụ Bright -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h4 class="text-lg font-bold text-primary mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-slate-100 text-primary flex items-center justify-center text-sm">1</span> 
                  Chọn gói Dịch vụ Bright Education
                </h4>
                <div class="grid sm:grid-cols-3 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_package" value="15000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Tiêu Chuẩn</div>
                      <div class="text-sm text-primary font-semibold mb-2">15.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Xử lý hồ sơ cơ bản</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_package" value="20000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col relative overflow-hidden">
                      <div class="absolute top-0 right-0 bg-amber-400 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded-bl-lg">Khuyên dùng</div>
                      <div class="font-bold text-primary mb-1">An Tâm</div>
                      <div class="text-sm text-primary font-semibold mb-2">20.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Đón sân bay, dẫn đi làm giấy tờ tại Nhật</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_package" value="30000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Trọn Vẹn (VIP)</div>
                      <div class="text-sm text-primary font-semibold mb-2">30.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Cam kết giới thiệu việc làm, đồng hành 24 tháng</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Step 2: Khóa học tiếng Nhật tại VN -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h4 class="text-lg font-bold text-primary mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-slate-100 text-primary flex items-center justify-center text-sm">2</span> 
                  Chương trình học Tiếng Nhật tại Việt Nam
                </h4>
                <div class="grid sm:grid-cols-3 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_course" value="0" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Tự học / Đã có N4</div>
                      <div class="text-sm text-primary font-semibold mb-2">0đ</div>
                      <div class="text-xs text-muted mt-auto">Dành cho học sinh đã đủ trình độ</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_course" value="10000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Cơ bản 3 tháng</div>
                      <div class="text-sm text-primary font-semibold mb-2">10.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Chương trình chuẩn N5</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_course" value="15000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Chuyên sâu 6 tháng</div>
                      <div class="text-sm text-primary font-semibold mb-2">15.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Luyện thi JLPT N4</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Step 3: Trường Nhật Ngữ -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h4 class="text-lg font-bold text-primary mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-slate-100 text-primary flex items-center justify-center text-sm">3</span> 
                  Lựa chọn Trường Nhật Ngữ (Năm đầu tiên)
                </h4>
                <div class="grid sm:grid-cols-2 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_school" value="110000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col justify-between">
                      <div>
                        <div class="font-bold text-primary">Trường ở tỉnh xa</div>
                        <div class="text-xs text-muted mt-1">Hokkaido, Ibaraki, Oita... Học phí và sinh hoạt phí đều rất rẻ.</div>
                      </div>
                      <div class="text-sm text-primary font-bold mt-3">~ 110 Triệu VNĐ</div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_school" value="125000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col justify-between">
                      <div>
                        <div class="font-bold text-primary">Thành phố cỡ trung</div>
                        <div class="text-xs text-muted mt-1">Fukuoka, Chiba, Saitama... Dễ tìm việc làm, chi phí vừa phải.</div>
                      </div>
                      <div class="text-sm text-primary font-bold mt-3">~ 125 Triệu VNĐ</div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_school" value="135000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col justify-between relative overflow-hidden">
                      <div class="absolute top-0 right-0 bg-amber-400 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded-bl-lg">Phổ biến</div>
                      <div>
                        <div class="font-bold text-primary pr-12">Ngoại ô Tokyo / Osaka</div>
                        <div class="text-xs text-muted mt-1">Cách trung tâm 30-40p tàu. Cân bằng tốt giữa chi phí và cơ hội.</div>
                      </div>
                      <div class="text-sm text-primary font-bold mt-3">~ 135 Triệu VNĐ</div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_school" value="145000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col justify-between">
                      <div>
                        <div class="font-bold text-primary">Trung tâm Tokyo / Osaka</div>
                        <div class="text-xs text-muted mt-1">Sầm uất, nhiều cơ hội việc làm lương cao nhưng học phí đắt.</div>
                      </div>
                      <div class="text-sm text-primary font-bold mt-3">~ 145 Triệu VNĐ</div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Step 4: KTX & Tiền mặt -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h4 class="text-lg font-bold text-primary mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-slate-100 text-primary flex items-center justify-center text-sm">4</span> 
                  Chi phí sinh hoạt ban đầu tại Nhật
                </h4>
                <div class="grid sm:grid-cols-3 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_living" value="30000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Tiết Kiệm</div>
                      <div class="text-sm text-primary font-semibold mb-2">~ 30.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">KTX chung 4 người + 10 Man tiền mặt phòng thân</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_living" value="45000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Cơ Bản</div>
                      <div class="text-sm text-primary font-semibold mb-2">~ 45.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">KTX tiêu chuẩn 2 người + 12 Man tiền mặt phòng thân</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_living" value="60000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Thoải Mái</div>
                      <div class="text-sm text-primary font-semibold mb-2">~ 60.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Thuê phòng riêng + 15 Man tiền mặt phòng thân</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Step 5: Thủ tục khác -->
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h4 class="text-lg font-bold text-primary mb-4 flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-slate-100 text-primary flex items-center justify-center text-sm">5</span> 
                  Chi phí thủ tục khác tại VN
                </h4>
                <p class="text-sm text-muted mb-4">Gồm: Khám lao phổi, Thi JLPT, Hộ chiếu, Vé máy bay</p>
                <div class="grid sm:grid-cols-3 gap-4">
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_other" value="8650000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Thấp Nhất</div>
                      <div class="text-sm text-primary font-semibold mb-2">8.650.000đ</div>
                      <div class="text-xs text-muted mt-auto">Tổng các mức thấp nhất (Săn vé giá rẻ)</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_other" value="13000000" class="peer sr-only" checked>
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Trung Bình</div>
                      <div class="text-sm text-primary font-semibold mb-2">13.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Chi tiêu hợp lý, vé máy bay phổ thông</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="calc_other" value="17000000" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-primary peer-checked:bg-slate-50 transition-all hover:border-slate-300 h-full flex flex-col">
                      <div class="font-bold text-primary mb-1">Dự Tính An Toàn</div>
                      <div class="text-sm text-primary font-semibold mb-2">17.000.000đ</div>
                      <div class="text-xs text-muted mt-auto">Tổng mức cao nhất, bay thẳng giờ đẹp</div>
                      <div class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                  </label>
                </div>
              </div>

            </div>

            <!-- Right Column: Sticky Receipt -->
            <div class="w-full lg:w-1/3">
              <div class="sticky top-24 bg-primary text-white rounded-3xl p-8 shadow-xl border-t-4 border-white/20 relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute -left-16 -bottom-16 w-40 h-40 bg-slate-500/10 rounded-full blur-2xl"></div>
                
                <h4 class="text-xl font-bold font-display mb-6 border-b border-white/10 pb-4 flex items-center gap-2 relative z-10">
                  <i class="bi bi-receipt text-white/60"></i> Phiếu Dự Toán
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
                  <div class="text-sm text-white/60 font-bold tracking-widest uppercase mb-1">Tổng Cần Chuẩn Bị</div>
                  <div class="text-4xl font-black text-white font-display tracking-tight break-words">
                    <span id="summary_total">223.000.000</span><span class="text-xl ml-1 text-white/70 font-medium">VNĐ</span>
                  </div>
                  <p class="text-xs text-white/50 mt-3 italic">*Bảng dự toán mang tính tham khảo. Chi phí thực tế phụ thuộc tỷ giá Yên và nhu cầu tiêu dùng.</p>
                </div>

                <a href="/contact" class="w-full mt-8 block text-center bg-white text-primary rounded-xl py-4 font-bold transition-colors hover:bg-white/90 shadow-lg relative z-10">
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
            updateSummary();
          });

          // Service Tab Switching Logic
          function switchServiceTab(tabId) {
            // Update Buttons
            document.querySelectorAll('.service-tab-btn').forEach(btn => {
              if (btn.id === 'tab-' + tabId) {
                btn.setAttribute('aria-selected', 'true');
                btn.classList.add('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'transform', '-translate-y-0.5');
                btn.classList.remove('bg-white', 'text-slate-500', 'border-slate-200', 'hover:border-slate-300', 'hover:text-primary', 'hover:bg-slate-50');
              } else {
                btn.setAttribute('aria-selected', 'false');
                btn.classList.remove('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'transform', '-translate-y-0.5');
                btn.classList.add('bg-white', 'text-slate-500', 'border-slate-200', 'hover:border-slate-300', 'hover:text-primary', 'hover:bg-slate-50');
              }
            });

            // Update Panels
            document.querySelectorAll('.service-tab-panel').forEach(panel => {
              if (panel.id === 'panel-' + tabId) {
                panel.classList.remove('hidden');
                // Small delay to allow display:block to apply before animating opacity
                setTimeout(() => {
                  panel.classList.remove('opacity-0');
                  panel.classList.add('opacity-100');
                }, 50);
              } else {
                panel.classList.remove('opacity-100');
                panel.classList.add('opacity-0', 'hidden');
              }
            });
          }
        </script>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
