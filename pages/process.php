<?php
$page_title = 'Quy trình du học | Bright Education';
include 'includes/header.php';
?>

<main class="bg-slate-50 min-h-screen">
  <!-- Hero Section -->
  <section class="relative bg-midnight pt-32 pb-40 overflow-hidden">
    <!-- Geometric Background elements -->
    <div class="absolute inset-0 bg-[radial-gradient(#ffffff15_1px,transparent_1px)] [background-size:20px_20px] opacity-30"></div>
    <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px] mix-blend-screen pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[600px] h-[600px] bg-sage-500/20 rounded-full blur-[100px] mix-blend-screen pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 text-center reveal">
      <span class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-sage-300 text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-md">Roadmap</span>
      <h1 class="text-4xl md:text-6xl font-bold text-white font-display tracking-tight mb-6">
        Lộ Trình Du Học <span class="text-sage-300">Trọn Vẹn</span>
      </h1>
      <p class="text-lg text-white/80 max-w-2xl mx-auto leading-relaxed">
        Hành trình đến Nhật Bản của bạn sẽ không còn là một ẩn số. Tại Bright Education, chúng tôi chuẩn hóa mọi bước đi để đảm bảo sự an tâm tuyệt đối từ ngày đầu tiên cho đến khi bạn hoàn toàn vững vàng tại xứ sở hoa anh đào.
      </p>
    </div>
  </section>

  <!-- Process Timeline Section -->
  <section class="py-24 relative z-20 -mt-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
      
      <!-- Timeline Container -->
      <div class="relative before:absolute before:inset-0 before:ml-6 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-1 before:bg-gradient-to-b before:from-primary before:via-sage-400 before:to-rose-500 before:opacity-20 before:rounded-full">
        
        <?php
        $steps = [
            [
                'id' => '01',
                'title' => 'Tìm hiểu & Tư vấn 1-1',
                'desc' => 'Chúng tôi bắt đầu bằng việc lắng nghe. Chuyên viên sẽ khảo sát nhu cầu, năng lực hiện tại và mục tiêu của bạn để thiết kế một lộ trình học tập và tài chính cá nhân hóa hoàn toàn miễn phí.',
                'icon' => 'bi-chat-heart',
                'color' => 'primary'
            ],
            [
                'id' => '02',
                'title' => 'Thanh toán phí dịch vụ',
                'desc' => 'Tiến hành thanh toán phí dịch vụ. Bright Education không thu phí cọc, chỉ thu 1 lần duy nhất trọn bộ gói dịch vụ và cam kết tuyệt đối không phát sinh thêm bất kỳ chi phí nào khác.',
                'icon' => 'bi-credit-card',
                'color' => 'sky-500'
            ],
            [
                'id' => '03',
                'title' => 'Chọn trường & Định hướng',
                'desc' => 'Dựa trên lộ trình đã thống nhất, Bright Education sẽ giới thiệu các trường Nhật ngữ phù hợp với nguyện vọng vùng miền và ngành học tương lai, sau đó tiến hành ký kết hợp đồng minh bạch.',
                'icon' => 'bi-building-check',
                'color' => 'sage-500'
            ],
            [
                'id' => '04',
                'title' => 'Học tiếng Nhật & Phỏng vấn',
                'desc' => 'Bạn sẽ được tham gia các lớp đào tạo tiếng Nhật nền tảng, đồng thời được rèn luyện kỹ năng phỏng vấn thực chiến với đại diện trường và Cục Xuất nhập cảnh Nhật Bản.',
                'icon' => 'bi-journal-text',
                'color' => 'sakura-500'
            ],
            [
                'id' => '05',
                'title' => 'Xử lý hồ sơ chuyên sâu',
                'desc' => 'Đội ngũ xử lý hồ sơ giàu kinh nghiệm sẽ hỗ trợ bạn thu thập, dịch thuật, công chứng giấy tờ và hoàn thiện các thủ tục minh chứng tài chính phức tạp một cách chính xác nhất.',
                'icon' => 'bi-folder-check',
                'color' => 'amber-500'
            ],
            [
                'id' => '06',
                'title' => 'Trình cục xin COE',
                'desc' => 'Hồ sơ hoàn chỉnh của bạn sẽ được gửi sang Nhật để đệ trình lên Cục Xuất nhập cảnh. Trong thời gian này, chúng tôi sẽ theo sát và cập nhật tình trạng liên tục cho bạn.',
                'icon' => 'bi-send-check',
                'color' => 'indigo-500'
            ],
            [
                'id' => '07',
                'title' => 'Nhận COE & Đóng học phí',
                'desc' => 'Khi có kết quả đỗ Giấy chứng nhận Tư cách lưu trú (COE), chúng tôi sẽ hướng dẫn chi tiết quy trình chuyển khoản học phí trực tiếp từ ngân hàng Việt Nam sang tài khoản của trường.',
                'icon' => 'bi-bank',
                'color' => 'emerald-500'
            ],
            [
                'id' => '08',
                'title' => 'Xin Visa du học',
                'desc' => 'Với bản gốc COE và giấy báo nhập học, chúng tôi sẽ hỗ trợ bạn chuẩn bị hồ sơ và nộp đơn xin Visa tại Đại sứ quán/Lãnh sự quán Nhật Bản tại Việt Nam.',
                'icon' => 'bi-passport',
                'color' => 'cyan-500'
            ],
            [
                'id' => '09',
                'title' => 'Xuất cảnh & Nhập học',
                'desc' => 'Hỗ trợ đặt vé máy bay, tổ chức buổi hướng dẫn trước xuất cảnh (Pre-departure). Khi đến Nhật, sẽ có người đón tại sân bay và hỗ trợ các thủ tục hành chính ban đầu để bạn sớm ổn định.',
                'icon' => 'bi-airplane-engines',
                'color' => 'rose-500'
            ]
        ];

        foreach ($steps as $index => $step) {
            $isEven = $index % 2 !== 0;
            $colorClass = "text-" . $step['color'];
            $bgClass = "bg-" . $step['color'];
            $borderClass = "border-" . $step['color'];
            ?>
            <!-- Timeline Item -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mb-16 sm:mb-24 last:mb-0 reveal">
              <!-- Icon/Marker -->
              <div class="flex items-center justify-center w-14 h-14 rounded-full border-4 border-slate-50 <?php echo $bgClass; ?> text-white shadow-xl shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-transform duration-500 group-hover:scale-110">
                 <i class="bi <?php echo $step['icon']; ?> text-xl"></i>
              </div>
              
              <!-- Content Card -->
              <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3.5rem)]">
                 <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-soft hover:shadow-xl transition-all duration-500 border border-slate-100 group-hover:-translate-y-2 relative overflow-hidden">
                    <!-- Step Number Watermark -->
                    <div class="absolute -right-4 -bottom-6 text-9xl font-bold font-display text-slate-50 select-none pointer-events-none transition-transform duration-700 group-hover:scale-110 group-hover:text-slate-100/60"><?php echo $step['id']; ?></div>
                    
                    <div class="relative z-10">
                      <span class="inline-block py-1.5 px-4 rounded-full bg-slate-50 border border-slate-100 <?php echo $colorClass; ?> text-[11px] font-bold tracking-widest uppercase mb-4 shadow-sm">Bước <?php echo $step['id']; ?></span>
                      <h3 class="text-2xl sm:text-3xl font-bold text-midnight font-display mb-4 leading-tight"><?php echo $step['title']; ?></h3>
                      <p class="text-[15px] sm:text-base text-muted leading-relaxed"><?php echo $step['desc']; ?></p>
                    </div>
                 </div>
              </div>
            </div>
            <?php
        }
        ?>
        
      </div>
    </div>
  </section>

  <!-- Final CTA -->
  <section class="py-24 bg-white relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center reveal">
       <div class="bg-midnight rounded-[3rem] p-12 sm:p-16 relative overflow-hidden shadow-2xl">
          <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-transparent"></div>
          <div class="relative z-10">
             <h2 class="text-3xl sm:text-4xl font-bold text-white font-display mb-6">Sẵn sàng để bắt đầu?</h2>
             <p class="text-white/80 text-lg mb-10 max-w-2xl mx-auto">Chặng đường 9 bước có vẻ dài, nhưng với Bright Education đồng hành, bạn sẽ luôn biết mình đang ở đâu và cần làm gì tiếp theo.</p>
             <a href="/contact" class="inline-flex items-center justify-center bg-white text-primary font-bold text-[14px] tracking-widest uppercase px-10 py-4 rounded-xl hover:bg-slate-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 group">
                Nhận lộ trình miễn phí ngay <i class="bi bi-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
             </a>
          </div>
       </div>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
