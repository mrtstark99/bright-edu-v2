<?php
require_once 'config/config.php';

$page_title = 'Câu chuyện của chúng tôi - Bright Education';
include 'includes/header.php';
?>

<main class="pt-20">
  <!-- Page Header -->
  <section class="bg-primary pt-20 pb-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-mesh opacity-20"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full mix-blend-screen blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-sage-500/20 rounded-full mix-blend-screen blur-[80px] pointer-events-none"></div>
    
    <div class="mx-auto max-w-7xl px-4 sm:px-5 relative z-10 text-center reveal">
      <h1 class="text-4xl md:text-[3.5rem] font-bold text-white font-display mb-6 tracking-tight">Câu chuyện của chúng tôi</h1>
      <p class="text-lg text-white/80 max-w-2xl mx-auto leading-relaxed">Từ những bỡ ngỡ ban đầu của một du học sinh đến sự ra đời của Bright Education.</p>
    </div>
  </section>

  <!-- Content Section -->
  <section class="py-16 sm:py-24 bg-white relative reveal -mt-10 rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.05)] z-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-5">
      <div class="max-w-5xl mx-auto items-start">
        
        <!-- The Story -->
        <div class="space-y-10">
          <!-- The Horizontal Card Area -->
          <div class="relative pt-16 sm:pt-12 sm:pl-12 mb-20 mx-auto max-w-5xl">
             
             <!-- The Dark Card Background -->
             <div class="bg-midnight rounded-tl-[3rem] sm:rounded-tl-[5rem] rounded-br-[3rem] sm:rounded-br-[5rem] rounded-tr-xl rounded-bl-xl shadow-2xl relative p-8 pt-28 sm:p-12 sm:pl-48 text-white">
                
                <!-- The Overlapping Circular Portrait -->
                <div class="absolute -top-16 left-1/2 -translate-x-1/2 sm:-top-10 sm:-left-10 sm:translate-x-0 w-40 h-40 sm:w-52 sm:h-52 rounded-full border-[8px] sm:border-[12px] border-midnight bg-midnight shadow-lg overflow-hidden z-10 group">
                   <img src="/assets/images/about_team.jpg" alt="Hoàng Minh Hiếu" class="w-full h-full object-cover grayscale-[20%] group-hover:grayscale-0 transition-all duration-500 group-hover:scale-105">
                </div>

                <!-- Text Content & Buttons -->
                <div class="relative z-20 text-center sm:text-left">
                   <span class="text-sage-400 font-bold tracking-widest uppercase text-xs mb-3 block">Hành trình thực tế</span>
                   <h2 class="text-3xl sm:text-4xl font-bold text-white font-display tracking-tight leading-[1.3] mb-6">
                     Chào bạn, tôi là <span class="text-primary">Hoàng Minh Hiếu</span>
                   </h2>
                   
                   <div class="space-y-4 text-[15px] sm:text-[15px] text-white/80 leading-relaxed mb-8">
                     <p>
                        Hành trình của tôi bắt đầu từ tháng 10 năm 2019, khi lần đầu tiên đặt chân đến Nhật Bản với tư cách là một du học sinh. Giống như rất nhiều bạn trẻ khác, tôi cũng từng trải qua những bỡ ngỡ về rào cản ngôn ngữ, văn hóa, và những khó khăn khi tự lo liệu các thủ tục hành chính, chọn trường học và tìm việc làm thêm.
                     </p>
                     <p>
                        Chính từ những vấp ngáp và trải nghiệm tự đúc kết được trong suốt quãng thời gian sinh sống và học tập tại xứ sở hoa anh đào, tôi nhận ra rằng: một sự chuẩn bị kỹ lưỡng và nguồn thông tin minh bạch là chìa khóa quan trọng nhất để có một chuyến đi thuận lợi.
                     </p>
                   </div>
                   
                   <!-- Action Button (Like 'READ MORE') -->
                   <a href="/contact" class="inline-flex items-center justify-center bg-white text-midnight font-bold text-[13px] tracking-widest uppercase px-8 py-3.5 rounded hover:bg-slate-100 transition-colors shadow-lg group/btn">
                      Trò chuyện cùng tôi <i class="bi bi-arrow-right ml-2 transform group-hover/btn:translate-x-1 transition-transform"></i>
                   </a>
                </div>

             </div>
          </div>

          <!-- Timeline -->
          <div class="pt-6 border-t border-slate-100">
            <h3 class="text-xl font-bold text-midnight font-display mb-6">Lý lịch học tập & Làm việc</h3>
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
               
               <!-- Item 1 -->
               <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                  <div class="flex items-center justify-center w-6 h-6 rounded-full border border-white bg-slate-200 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                     <div class="w-2 h-2 bg-primary rounded-full"></div>
                  </div>
                  <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] bg-white p-4 rounded-xl border border-slate-100 shadow-sm text-left md:text-right group-odd:md:text-left">
                     <h4 class="font-bold text-primary text-base tracking-wide">10/2019 - 03/2021</h4>
                     <div class="mt-1">
                        <span class="font-bold text-midnight text-[15px] group/tooltip relative inline-block cursor-help border-b border-dashed border-slate-300">
                           学校法人 愛光学園 山手日本語学校
                           <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[200px] sm:max-w-xs px-3 py-2 bg-midnight text-white text-[12px] font-normal leading-tight rounded-lg shadow-lg opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-300 z-50 pointer-events-none text-center">
                              Aiko Gakuen Yamate Japanese Language School
                              <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-midnight rotate-45"></div>
                           </div>
                        </span>
                     </div>
                     <div class="text-[13px] font-bold text-primary mt-2 mb-1">Rèn luyện năng lực tiếng Nhật</div>
                     <p class="text-[13px] text-muted">Bắt đầu hành trình tại Nhật Bản. Trải nghiệm những ngày tháng đầu tiên đầy thử thách về ngôn ngữ và văn hóa.</p>
                  </div>
               </div>

               <!-- Item 2 -->
               <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                  <div class="flex items-center justify-center w-6 h-6 rounded-full border border-white bg-slate-200 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                     <div class="w-2 h-2 bg-sage-500 rounded-full"></div>
                  </div>
                  <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] bg-white p-4 rounded-xl border border-slate-100 shadow-sm text-left md:text-right group-odd:md:text-left">
                     <h4 class="font-bold text-sage-600 text-base tracking-wide">04/2021 - 03/2023</h4>
                     <div class="mt-1">
                        <span class="font-bold text-midnight text-[15px] group/tooltip relative inline-block cursor-help border-b border-dashed border-slate-300">
                           学校法人 愛光学園 山手ビジネスカレッジ
                           <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[200px] sm:max-w-xs px-3 py-2 bg-midnight text-white text-[12px] font-normal leading-tight rounded-lg shadow-lg opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-300 z-50 pointer-events-none text-center">
                              Aiko Gakuen Yamate Business College
                              <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-midnight rotate-45"></div>
                           </div>
                        </span>
                     </div>
                     <div class="text-[13px] font-bold text-sage-600 mt-2 mb-1">Khoa kinh tế - quản trị kinh doanh</div>
                     <p class="text-[13px] text-muted">Hoàn thành khóa tiếng và bước vào môi trường học thuật chuyên sâu, chuẩn bị hành trang cho môi trường làm việc.</p>
                  </div>
               </div>

               <!-- Item 3 -->
               <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                  <div class="flex items-center justify-center w-6 h-6 rounded-full border border-white bg-slate-200 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                     <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                  </div>
                  <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] bg-white p-4 rounded-xl border border-slate-100 shadow-sm text-left md:text-right group-odd:md:text-left">
                     <h4 class="font-bold text-amber-600 text-base tracking-wide">04/2023 - 03/2026</h4>
                     <div class="mt-1">
                        <span class="font-bold text-midnight text-[15px] group/tooltip relative inline-block cursor-help border-b border-dashed border-slate-300">
                           学校法人 愛光学園 山手日本語学校
                           <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[200px] sm:max-w-xs px-3 py-2 bg-midnight text-white text-[12px] font-normal leading-tight rounded-lg shadow-lg opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-300 z-50 pointer-events-none text-center">
                              Aiko Gakuen Yamate Japanese Language School
                              <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-midnight rotate-45"></div>
                           </div>
                        </span>
                     </div>
                     <div class="text-[13px] font-bold text-amber-600 mt-2 mb-1">Vai trò: Giáo viên chuyên nhiệm</div>
                     <p class="text-[13px] text-muted">Nhận lời mời từ BGH ở lại làm việc tại <span class="group/tooltip relative inline-block cursor-help border-b border-dashed border-slate-300 font-medium text-midnight">愛光学園<span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max px-3 py-2 bg-midnight text-white text-[12px] font-normal leading-tight rounded-lg shadow-lg opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-300 z-50 pointer-events-none text-center">Aiko Gakuen<span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-midnight rotate-45"></span></span></span>. Trực tiếp dẫn dắt, quản lý học sinh VN: từ tuyển sinh, xử lý hồ sơ thị thực đến giáo dục học tập và đời sống.</p>
                  </div>
               </div>

               <!-- Item 4 -->
               <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                  <div class="flex items-center justify-center w-6 h-6 rounded-full border border-white bg-slate-200 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                     <div class="w-2 h-2 bg-sakura-500 rounded-full"></div>
                  </div>
                  <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] bg-white p-4 rounded-xl border border-slate-100 shadow-sm text-left md:text-right group-odd:md:text-left">
                     <h4 class="font-bold text-sakura-600 text-base tracking-wide">Tháng 04/2026</h4>
                     <div class="mt-1">
                        <span class="font-bold text-midnight text-[15px]">
                           Thành lập Bright Education & BrightHome
                        </span>
                     </div>
                     <p class="text-[13px] text-muted mt-2">Phát triển hệ sinh thái hỗ trợ toàn diện dựa trên kinh nghiệm thực chiến từ môi trường giáo dục Nhật Bản để giúp đỡ thế hệ du học sinh tiếp theo.</p>
                  </div>
               </div>

            </div>
          </div>

          <!-- Vision -->
          <div class="pt-8 border-t border-slate-100">
            <h3 class="text-xl font-bold text-midnight font-display mb-4">Tầm nhìn & Định hướng</h3>
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 relative">
               <i class="bi bi-quote absolute top-2 right-4 text-4xl text-slate-200"></i>
               <p class="text-[15px] sm:text-base text-muted leading-relaxed relative z-10">
                 Trải qua trọn vẹn vòng lặp của một du học sinh, tôi thấu hiểu rõ những điểm mù và rủi ro trong quá trình chuẩn bị hồ sơ. Đó là lý do Bright Education ra đời. Chúng tôi không hứa hẹn những điều viển vông hay vẽ ra những màu hồng phi thực tế.
               </p>
               <p class="mt-4 text-[15px] sm:text-base text-muted leading-relaxed relative z-10">
                 Mục tiêu cốt lõi của Bright Education rất đơn giản: cung cấp thông tin trung thực, hướng dẫn thủ tục chính xác, và chia sẻ kinh nghiệm thực chiến để hành trình đến Nhật của bạn trở nên an toàn, tiết kiệm và vững vàng nhất.
               </p>
            </div>
          </div>

          <!-- Industry Experience -->
          <div class="pt-8 border-t border-slate-100">
            <div class="flex items-center gap-3 mb-6">
              <div class="h-9 w-9 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <i class="bi bi-briefcase-fill text-base"></i>
              </div>
              <h3 class="text-xl font-bold text-midnight font-display">Kinh nghiệm trong ngành</h3>
            </div>

            <!-- Stats Bento Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
              <div class="bg-primary text-white rounded-2xl p-5 flex flex-col justify-between shadow-md">
                <div class="text-4xl font-black font-display tracking-tight">7+</div>
                <div class="text-[12px] font-semibold text-white/70 mt-2 uppercase tracking-wider">Năm tại Nhật Bản</div>
              </div>
              <div class="bg-slate-50 rounded-2xl p-5 flex flex-col justify-between border border-slate-100">
                <div class="text-4xl font-black font-display tracking-tight text-primary">3+</div>
                <div class="text-[12px] font-semibold text-muted mt-2 uppercase tracking-wider">Năm tư vấn du học</div>
              </div>
              <div class="bg-slate-50 rounded-2xl p-5 flex flex-col justify-between border border-slate-100">
                <div class="text-4xl font-black font-display tracking-tight text-primary">100+</div>
                <div class="text-[12px] font-semibold text-muted mt-2 uppercase tracking-wider">Học viên được hỗ trợ</div>
              </div>
              <div class="bg-slate-900 text-white rounded-2xl p-5 flex flex-col justify-between shadow-md">
                <div class="text-4xl font-black font-display tracking-tight">60+</div>
                <div class="text-[12px] font-semibold text-white/70 mt-2 uppercase tracking-wider">Trường Nhật ngữ & Senmon liên kết</div>
              </div>
            </div>

            <!-- Expertise List -->
            <div class="grid sm:grid-cols-2 gap-3">
              <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4 border border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="bi bi-file-earmark-check-fill text-primary text-sm"></i>
                </div>
                <div>
                  <div class="font-bold text-midnight text-[14px]">Xử lý hồ sơ COE & Visa</div>
                  <p class="text-[13px] text-muted mt-0.5 leading-relaxed">Trực tiếp chuẩn bị và nộp hồ sơ xin tư cách lưu trú cho hàng chục học sinh tại Nhật.</p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4 border border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="bi bi-mortarboard-fill text-primary text-sm"></i>
                </div>
                <div>
                  <div class="font-bold text-midnight text-[14px]">Giảng dạy & Tư vấn học thuật</div>
                  <p class="text-[13px] text-muted mt-0.5 leading-relaxed">3 năm làm giáo viên chuyên nhiệm tại trường Nhật ngữ Yamate, trực tiếp hướng dẫn học viên Việt Nam.</p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4 border border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="bi bi-house-fill text-primary text-sm"></i>
                </div>
                <div>
                  <div class="font-bold text-midnight text-[14px]">Hỗ trợ hòa nhập cuộc sống</div>
                  <p class="text-[13px] text-muted mt-0.5 leading-relaxed">Kinh nghiệm thực tế tìm nhà, đăng ký bảo hiểm, mở tài khoản ngân hàng và xin việc làm thêm tại Nhật.</p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4 border border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="bi bi-translate text-primary text-sm"></i>
                </div>
                <div>
                  <div class="font-bold text-midnight text-[14px]">Thông dịch & Hỗ trợ song ngữ</div>
                  <p class="text-[13px] text-muted mt-0.5 leading-relaxed">Hỗ trợ trực tiếp các thủ tục hành chính bằng tiếng Nhật, từ phòng xuất nhập cảnh đến bệnh viện.</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-primary"></div>
    <div class="absolute inset-0 bg-[radial-gradient(#ffffff20_1px,transparent_1px)] [background-size:20px_20px] opacity-30"></div>
    <div class="mx-auto max-w-4xl px-4 sm:px-5 relative z-10 text-center reveal">
      <h2 class="text-3xl md:text-4xl font-bold text-white font-display mb-6">Bạn đã sẵn sàng?</h2>
      <p class="mb-10 text-lg text-white/80 max-w-2xl mx-auto">Hãy để những trải nghiệm thực tế của chúng tôi giúp bạn có một khởi đầu vững chắc nhất tại Nhật Bản.</p>
      <a href="/contact" class="inline-flex items-center justify-center rounded-2xl bg-white text-primary px-8 py-4 text-[15px] font-bold hover:bg-slate-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
        Trò chuyện cùng tôi <i class="bi bi-arrow-right ml-2"></i>
      </a>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
