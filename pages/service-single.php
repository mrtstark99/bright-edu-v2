<?php
require_once 'config/config.php';

// Get service slug
$slug = $_GET['slug'] ?? '';

// Define details of the 5 services
$services_data = [
    'japanese-language-school' => [
        'title' => 'Du học Trường Nhật ngữ',
        'subtitle' => 'Học tiếng Nhật nền tảng & Trải nghiệm văn hóa',
        'badge' => 'Chương trình Nền tảng',
        'icon' => 'bi-translate',
        'color' => 'indigo',
        'image' => '/assets/images/program_language.jpg',
        'price' => '15.000.000 VNĐ',
        'duration' => '1.5 - 2 năm (Kỳ tuyển sinh: T4, T7, T10, T1)',
        'language' => 'Đạt chứng chỉ N5 trở lên trước khi xuất cảnh',
        'gpa' => 'Tốt nghiệp THPT, GPA tối thiểu 6.0',
        'fee' => '~ 126 - 140 Triệu VNĐ / năm',
        'description' => 'Đây là chương trình tuyển sinh phổ biến nhất dành cho học sinh Việt Nam. Học viên sẽ học tiếng Nhật tập trung tại các trường Nhật ngữ uy tín ở các thành phố lớn (Tokyo, Osaka, Fukuoka, Nagoya...) nhằm nâng cao trình độ, thích nghi với văn hóa và chuẩn bị cho các mục tiêu học lên cao hoặc đi làm lâu dài tại Nhật Bản.',
        'conditions' => [
            'Đã tốt nghiệp THPT trở lên (hoặc Trung cấp, Cao đẳng, Đại học).',
            'Độ tuổi từ 18 - 30 tuổi (Ưu tiên học sinh mới tốt nghiệp THPT trong vòng 3 năm).',
            'Trình độ ngoại ngữ: Đạt N5 hoặc học tối thiểu 150 giờ tiếng Nhật.',
            'Người bảo lãnh tài chính có thu nhập ổn định và có sổ tiết kiệm ngân hàng theo quy định.'
        ],
        'timeline' => [
            [
                'title' => 'Giai đoạn 1: Chuẩn bị tại Việt Nam (4 - 6 tháng)',
                'desc' => 'Học tiếng Nhật cơ bản đạt N5-N4, làm hồ sơ dịch thuật công chứng, đăng ký chọn trường bên Nhật và phỏng vấn đầu vào.'
            ],
            [
                'title' => 'Giai đoạn 2: Nhận COE & Visa (1 - 2 tháng trước bay)',
                'desc' => 'Cục xuất nhập cảnh Nhật Bản cấp COE (Giấy tư cách lưu trú), nộp học phí sang trường bên Nhật, xin Visa tại Đại sứ quán.'
            ],
            [
                'title' => 'Giai đoạn 3: Học tập tại Nhật Bản (1.5 - 2 năm)',
                'desc' => 'Nhập cảnh Nhật Bản, học tiếng Nhật chuyên sâu từ 4-5 tiếng/ngày tại trường Nhật ngữ. Được làm thêm tối đa 28 giờ/tuần.'
            ],
            [
                'title' => 'Giai đoạn 4: Định hướng chuyển tiếp tương lai',
                'desc' => 'Thi cử học lên Đại học / Senmon hoặc chuyển đổi sang visa đi làm lâu dài (Kỹ sư, Tokutei Gino) tại Nhật Bản.'
            ]
        ],
        'features' => [
            'Xử lý hồ sơ chuyên nghiệp nhanh chóng.',
            'Luyện phỏng vấn visa và phỏng vấn trường miễn phí.',
            'Đảm bảo 100% có việc làm thêm ngay sau khi sang Nhật.',
            'Hỗ trợ trọn đời sau khi sang Nhật (tìm nhà, gia hạn visa, học lên).'
        ]
    ],
    'senmon-vocational-school' => [
        'title' => 'Du học Trường Senmon',
        'subtitle' => 'Đào tạo kỹ năng thực chiến & Cam kết việc làm',
        'badge' => 'Thực chiến & Hướng nghiệp',
        'icon' => 'bi-tools',
        'color' => 'amber',
        'image' => '/assets/images/program_senmon.jpg',
        'price' => '30.000.000 VNĐ',
        'duration' => '2 năm đào tạo chuyên sâu',
        'language' => 'Đạt JLPT N3 trở lên (hoặc N2)',
        'gpa' => 'Tốt nghiệp THPT trở lên',
        'fee' => '~ 136 - 150 Triệu VNĐ / năm',
        'description' => 'Trường chuyên môn (Senmon Gakko) tập trung đào tạo kỹ năng làm việc thực tế cho học sinh. Học sinh chỉ cần học 2 năm chuyên môn là có thể làm việc ngay tại Nhật với tấm bằng Kỹ thuật viên (Senmon-shi). Đây là lộ trình ngắn nhất, tiết kiệm chi phí nhất để có visa đi làm chính thức tại Nhật.',
        'conditions' => [
            'Tốt nghiệp THPT trở lên.',
            'Trình độ tiếng Nhật: Đạt chứng chỉ JLPT N3 trở lên hoặc đã hoàn thành khóa học tiếng Nhật trên 6 tháng tại trường Nhật ngữ được công nhận.',
            'Có sức khỏe tốt để đáp ứng các giờ thực hành chuyên sâu và đáp ứng yêu cầu công việc.'
        ],
        'timeline' => [
            [
                'title' => 'Giai đoạn 1: Tuyển sinh và Chọn trường',
                'desc' => 'Bright Education tư vấn định hướng chọn chuyên ngành hot và trường Senmon đối tác phù hợp tại Nhật.'
            ],
            [
                'title' => 'Giai đoạn 2: Xét tuyển & Nhận thư mời',
                'desc' => 'Nộp hồ sơ xét tuyển học bạ, thi viết hoặc phỏng vấn trực tiếp với đại diện trường Senmon.'
            ],
            [
                'title' => 'Giai đoạn 3: Học tập chuyên môn (2 năm)',
                'desc' => 'Học tập lý thuyết kết hợp thực hành chuyên sâu tại trường. Đi thực tập tại các doanh nghiệp liên kết.'
            ],
            [
                'title' => 'Giai đoạn 4: Tốt nghiệp & Đi làm chính thức',
                'desc' => 'Được giới thiệu việc làm trực tiếp từ trường, ký hợp đồng lao động và đổi sang Visa Kỹ sư/Lao động chính thức.'
            ]
        ],
        'features' => [
            'Tư vấn định hướng các ngành học khát nhân lực tại Nhật.',
            'Cam kết giới thiệu việc làm với tỷ lệ đỗ phỏng vấn >95%.',
            'Được hỗ trợ đổi visa lao động ngay sau khi tốt nghiệp.',
            'Cơ hội nhận học bổng miễn giảm từ 10% - 50% học phí.'
        ]
    ],
    'university-program' => [
        'title' => 'Du học Trường Đại học',
        'subtitle' => 'Hệ cử nhân chính quy & Học bổng danh giá',
        'badge' => 'Học thuật Cao cấp',
        'icon' => 'bi-mortarboard',
        'color' => 'rose',
        'image' => '/assets/images/program_university.webp',
        'price' => '30.000.000 VNĐ',
        'duration' => '4 năm học chính quy',
        'language' => 'Đạt JLPT N2 trở lên hoặc điểm thi EJU cao',
        'gpa' => 'Tốt nghiệp THPT, GPA từ 7.0 trở lên',
        'fee' => '~ 140 - 200 Triệu VNĐ / năm',
        'description' => 'Lộ trình học tập chính quy tại các trường đại học công lập và tư thục danh tiếng của Nhật Bản. Dành cho những học viên có năng lực học thuật tốt, mong muốn nhận bằng Cử nhân tiêu chuẩn quốc tế để phát triển sự nghiệp đỉnh cao và định cư lâu dài.',
        'conditions' => [
            'Tốt nghiệp THPT, GPA từ 7.0 trở lên.',
            'Trình độ ngoại ngữ: Tiếng Nhật đạt N2 trở lên hoặc thi EJU (Kỳ thi du học Nhật Bản).',
            'Vượt qua kỳ thi tuyển sinh của trường (Xét hồ sơ học tập, thi viết và phỏng vấn).'
        ],
        'timeline' => [
            [
                'title' => 'Giai đoạn 1: Chuẩn bị & Luyện thi (6 - 12 tháng)',
                'desc' => 'Luyện thi tiếng Nhật đạt N2 trở lên hoặc chuẩn bị kiến thức thi EJU (Toán, Lý, Hóa, Xã hội).'
            ],
            [
                'title' => 'Giai đoạn 2: Ứng tuyển & Phỏng vấn',
                'desc' => 'Nộp hồ sơ ứng tuyển trực tiếp vào các trường Đại học Nhật Bản và phỏng vấn trực tiếp.'
            ],
            [
                'title' => 'Giai đoạn 3: Học Đại học (4 năm)',
                'desc' => 'Học tập chương trình Cử nhân chính quy. Tham gia các câu lạc bộ, hoạt động nghiên cứu khoa học.'
            ],
            [
                'title' => 'Giai đoạn 4: Tốt nghiệp & Phát triển sự nghiệp',
                'desc' => 'Ứng tuyển vào các tập đoàn lớn tại Nhật (Shukatsu) từ năm thứ 3 và đi làm ngay sau tốt nghiệp.'
            ]
        ],
        'features' => [
            'Hỗ trợ đăng ký và luyện thi EJU chuyên sâu.',
            'Tư vấn ứng tuyển các gói học bổng MEXT, JASSO, học bổng trường.',
            'Luyện viết bài luận cá nhân độc đáo thu hút hội đồng tuyển sinh.',
            'Bằng cử nhân chính quy được công nhận trên toàn cầu.'
        ]
    ],
    'scholarship-program' => [
        'title' => 'Chương trình Học bổng',
        'subtitle' => 'Học bổng báo, điều dưỡng, nhà hàng tài trợ 100%',
        'badge' => 'Được tài trợ học phí',
        'icon' => 'bi-award',
        'color' => 'emerald',
        'image' => '/assets/images/program_ssw.jpg',
        'price' => '15.000.000 VNĐ',
        'duration' => '1 - 2 năm (Theo khóa học tiếng và nghề)',
        'language' => 'Đạt JLPT N5 trở lên trước khi bay',
        'gpa' => 'Tốt nghiệp THPT trở lên, GPA khá',
        'fee' => 'Được tài trợ 100% học phí & Ký túc xá',
        'description' => 'Giải pháp tối ưu tài chính trọn gói dành cho học viên có hoàn cảnh khó khăn nhưng có nghị lực vươn lên. Bạn sẽ được các tổ chức và doanh nghiệp lớn tại Nhật Bản tài trợ 100% học phí, hỗ trợ nơi ở và cam kết công việc làm thêm có lương ngay khi sang Nhật.',
        'conditions' => [
            'Tốt nghiệp THPT trở lên. Không yêu cầu GPA quá cao.',
            'Độ tuổi từ 18 - 28 tuổi.',
            'Đạt chứng chỉ JLPT N5 trở lên trước khi xuất cảnh.',
            'Cam kết làm việc tại doanh nghiệp tài trợ sau khi tốt nghiệp (từ 2 - 3 năm).'
        ],
        'timeline' => [
            [
                'title' => 'Giai đoạn 1: Đăng ký & Phỏng vấn học bổng',
                'desc' => 'Nộp hồ sơ xét duyệt năng lực học tập và phỏng vấn trực tiếp với đại diện doanh nghiệp tài trợ của Nhật.'
            ],
            [
                'title' => 'Giai đoạn 2: Học tiếng tại Việt Nam & Nhận COE',
                'desc' => 'Học tiếng Nhật tập trung đạt N5/N4. Nhận giấy báo học bổng chính thức và giấy tư cách lưu trú (COE).'
            ],
            [
                'title' => 'Giai đoạn 3: Học tập & Làm việc tại Nhật Bản',
                'desc' => 'Học tiếng Nhật tại trường. Làm thêm tại doanh nghiệp tài trợ (như viện dưỡng lão, tòa soạn báo, chuỗi nhà hàng).'
            ],
            [
                'title' => 'Giai đoạn 4: Chuyển đổi Visa đi làm chính thức',
                'desc' => 'Tốt nghiệp, chuyển đổi trực tiếp sang Visa Đặc định hoặc Kỹ sư để làm việc lâu dài với mức lương cao.'
            ]
        ],
        'features' => [
            'Miễn hoàn toàn 100% học phí tại Nhật Bản.',
            'Hỗ trợ ký túc xá miễn phí hoặc giá cực ưu đãi.',
            'Cam kết việc làm có lương ngay từ ngày đầu đặt chân đến Nhật.',
            'Bảo đảm việc làm chính thức ổn định lâu dài sau khi tốt nghiệp.'
        ]
    ],
    'english-track-university' => [
        'title' => 'Đại học Hệ Tiếng Anh',
        'subtitle' => 'Chương trình Cử nhân E-Track chuẩn quốc tế',
        'badge' => 'Hội nhập Toàn cầu',
        'icon' => 'bi-globe',
        'color' => 'sky',
        'image' => '/assets/images/whyus_tokyo-optimized.webp',
        'price' => '30.000.000 VNĐ',
        'duration' => '4 năm học chính quy',
        'language' => 'IELTS từ 6.0 trở lên hoặc TOEFL iBT từ 75',
        'gpa' => 'Tốt nghiệp THPT, GPA từ 7.5 trở lên',
        'fee' => '~ 175 Triệu VNĐ / năm (chưa trừ học bổng)',
        'description' => 'Chương trình cử nhân E-Track giảng dạy 100% bằng tiếng Anh tại các trường đại học hàng đầu Nhật Bản. Đây là xu hướng du học hiện đại mở ra cơ hội làm việc trong môi trường đa quốc gia toàn cầu. Sinh viên không bắt buộc phải có chứng chỉ tiếng Nhật đầu vào.',
        'conditions' => [
            'Tốt nghiệp THPT, GPA học tập tốt (từ 7.5 trở lên là lợi thế lớn).',
            'Trình độ tiếng Anh: Đạt chứng chỉ IELTS 6.0 trở lên hoặc TOEFL iBT 75 trở lên (không yêu cầu tiếng Nhật đầu vào).',
            'Vượt qua vòng xét tuyển hồ sơ viết luận và phỏng vấn trực tiếp bằng tiếng Anh với ban giám hiệu.'
        ],
        'timeline' => [
            [
                'title' => 'Giai đoạn 1: Chuẩn bị Hồ sơ & Viết luận',
                'desc' => 'Hoàn thiện hồ sơ học tập dịch thuật tiếng Anh, viết bài luận cá nhân (Personal Statement) chất lượng cao.'
            ],
            [
                'title' => 'Giai đoạn 2: Nộp hồ sơ xét duyệt & Phỏng vấn',
                'desc' => 'Nộp hồ sơ trực tiếp vào trường đại học thông qua hệ thống trực tuyến, phỏng vấn chuyên ngành bằng tiếng Anh.'
            ],
            [
                'title' => 'Giai đoạn 3: Nhận học bổng & Nhập học',
                'desc' => 'Nhận thông báo trúng tuyển cùng kết quả học bổng (giảm học phí 30% - 100%), xin COE, visa và nhập cảnh nhập học.'
            ],
            [
                'title' => 'Giai đoạn 4: Học tập & Hướng nghiệp quốc tế',
                'desc' => 'Học chuyên ngành bằng tiếng Anh song song học tiếng Nhật bổ trợ. Tốt nghiệp làm việc tại các tập đoàn toàn cầu.'
            ]
        ],
        'features' => [
            'Tư vấn viết bài luận tiếng Anh xuất sắc, độc đáo.',
            'Cơ hội nhận học bổng miễn học phí 30% - 100% rất lớn.',
            'Không yêu cầu biết tiếng Nhật trước khi đăng ký du học.',
            'Bằng cấp Cử nhân chuẩn quốc tế được công nhận toàn cầu.'
        ]
    ]
];

// Check if slug is valid
if (!isset($services_data[$slug])) {
    // Redirect to services catalog if slug not found
    header('Location: /services');
    exit;
}

$data = $services_data[$slug];
$page_title = $data['title'] . ' - Bright Education';
$page_description = seoDescription(
    $data['subtitle'] . '. Nhận tư vấn lộ trình, hồ sơ và chi phí miễn phí từ Bright Education.'
);
$page_image = !empty($data['image']) ? APP_URL . $data['image'] : APP_URL . '/assets/images/favicon.png';
$og_type = 'website';
$service_faqs = [
    [
        'question' => 'Chương trình ' . $data['title'] . ' kéo dài bao lâu?',
        'answer' => $data['duration'] . '. Bright Education sẽ xây dựng mốc chuẩn bị hồ sơ phù hợp với kỳ nhập học của bạn.'
    ],
    [
        'question' => 'Điều kiện đăng ký ' . $data['title'] . ' là gì?',
        'answer' => implode(' ', array_slice($data['conditions'], 0, 2))
    ],
    [
        'question' => 'Chi phí dự kiến cho ' . $data['title'] . ' là bao nhiêu?',
        'answer' => 'Phí dịch vụ tại Việt Nam là ' . $data['price'] . '; học phí tham khảo tại Nhật là ' . $data['fee'] . '. Chi phí thực tế phụ thuộc trường và kỳ nhập học.'
    ]
];
$faq_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(function ($faq) {
        return [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']]
        ];
    }, $service_faqs)
];
$cluster_keywords = [
    'japanese-language-school' => 'trường Nhật ngữ',
    'senmon-vocational-school' => 'việc làm',
    'university-program' => 'đại học',
    'scholarship-program' => 'học bổng',
    'english-track-university' => 'đại học'
];
$cluster_keyword = $cluster_keywords[$slug] ?? 'du học Nhật Bản';
$db_service = Database::getInstance();
$stmt_cluster = $db_service->prepare("SELECT title, slug, excerpt, content FROM posts WHERE status = 'published' AND (title LIKE ? OR content LIKE ?) ORDER BY published_at DESC LIMIT 3");
$stmt_cluster->execute(['%' . $cluster_keyword . '%', '%' . $cluster_keyword . '%']);
$cluster_posts = $stmt_cluster->fetchAll();
if (empty($cluster_posts)) {
    $stmt_cluster = $db_service->prepare("SELECT title, slug, excerpt, content FROM posts WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
    $stmt_cluster->execute();
    $cluster_posts = $stmt_cluster->fetchAll();
}
include 'includes/header.php';
?>

<main class="pt-20 bg-slate-50 min-h-screen">
  
  <!-- Detail Hero Section -->
  <section class="bg-primary text-white pt-16 pb-24 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="mx-auto max-w-7xl px-5 lg:px-8 relative z-10">
      
      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs font-semibold text-white/60 mb-6 uppercase tracking-wider" aria-label="Breadcrumb">
        <a href="/" class="hover:text-white transition-colors">Trang chủ</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <a href="/services" class="hover:text-white transition-colors">Chương trình du học</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-white"><?php echo $data['title']; ?></span>
      </nav>

      <!-- Badge & Title -->
      <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3.5 py-1 text-xs font-bold text-white/90 mb-4 border border-white/10 uppercase tracking-widest">
        <i class="bi bi-star-fill text-[10px] text-amber-400"></i> <?php echo $data['badge']; ?>
      </span>
      <h1 class="text-3xl sm:text-[2.75rem] font-black font-display tracking-tight leading-tight mb-4"><?php echo $data['title']; ?></h1>
      <p class="text-base sm:text-lg text-white/85 max-w-3xl leading-relaxed"><?php echo $data['subtitle']; ?></p>
    </div>
  </section>

  <!-- Detail Content & Sidebar -->
  <section class="py-16 -mt-10 relative z-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Main Detail Content (8 cols) -->
        <div class="lg:col-span-8 space-y-8">
          
          <!-- Image and Overview Box -->
          <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-soft">
            <div class="relative h-[250px] sm:h-[350px] overflow-hidden">
              <img src="<?php echo $data['image']; ?>" alt="<?php echo $data['title']; ?>" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            </div>
            <div class="p-6 sm:p-8">
              <h2 class="text-xl font-bold text-primary font-display mb-4">Giới Thiệu Chương Trình</h2>
              <p class="text-slate-600 leading-relaxed text-[15px]"><?php echo $data['description']; ?></p>
            </div>
          </div>

          <!-- Conditions Section -->
          <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">
            <h2 class="text-xl font-bold text-primary font-display mb-5 flex items-center gap-2">
              <i class="bi bi-shield-check text-green-500"></i> Điều Kiện Tuyển Sinh
            </h2>
            <ul class="space-y-3">
              <?php foreach ($data['conditions'] as $condition): ?>
                <li class="flex items-start gap-3 text-slate-600 text-sm sm:text-[14.5px]">
                  <i class="bi bi-check-circle-fill text-primary shrink-0 mt-0.5 text-base"></i>
                  <span><?php echo $condition; ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <!-- Timeline Section -->
          <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">
            <h2 class="text-xl font-bold text-primary font-display mb-6 flex items-center gap-2">
              <i class="bi bi-calendar3 text-indigo-500"></i> Lộ Trình Đào Tạo & Thực Hiện
            </h2>
            <div class="relative border-l border-slate-200 ml-4 pl-6 space-y-8">
              <?php foreach ($data['timeline'] as $index => $step): ?>
                <div class="relative">
                  <!-- Bullet Circle -->
                  <span class="absolute -left-[35px] top-1 w-5 h-5 rounded-full bg-primary border-4 border-white shadow-soft"></span>
                  <h3 class="font-bold text-slate-800 text-[15px] mb-1.5"><?php echo $step['title']; ?></h3>
                  <p class="text-slate-500 text-xs sm:text-sm leading-relaxed"><?php echo $step['desc']; ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Features Section -->
          <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">
            <h2 class="text-xl font-bold text-primary font-display mb-5 flex items-center gap-2">
              <i class="bi bi-patch-check text-orange-500"></i> Cam Kết Từ Bright Education
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <?php foreach ($data['features'] as $feat): ?>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                  <i class="bi bi-star-fill text-amber-500 mt-0.5 shrink-0"></i>
                  <span class="text-xs sm:text-[13px] font-semibold text-slate-700 leading-relaxed"><?php echo $feat; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Cost Comparison Table Section -->
          <?php
          // Rate & calculations
          $rate = 175;
          $flight_fee = 10000000;
          
          $service_fee_bright = 0;
          $service_fee_market = 0;
          $tuition_jpy = 0;
          $dorm_jpy = 0;
          $tuition_note = '';
          $comparison_items = [];

          if ($slug === 'japanese-language-school' || $slug === 'scholarship-program') {
              $service_fee_bright = 15000000;
              $service_fee_market = 40000000;
              $tuition_jpy = $slug === 'scholarship-program' ? 0 : 750000;
              $dorm_jpy = $slug === 'scholarship-program' ? 0 : 150000;
              $tuition_note = $slug === 'scholarship-program' ? 'Được doanh nghiệp bảo trợ tài trợ 100% học phí & KTX' : 'Học phí 1 năm học tại Nhật (~750.000 JPY) + Ký túc xá (~150.000 JPY) (Lưu ý: Thay đổi tùy theo từng trường)';
              
              $comparison_items = [
                  ['item' => 'Xử lý hồ sơ & Dịch thuật', 'desc' => 'Làm việc trực tiếp với nhà trường; rà soát, dịch thuật giấy tờ và hoàn thiện hồ sơ nhập học; tư vấn điều kiện tuyển sinh, hướng dẫn chuẩn bị bài thi và phỏng vấn đầu vào', 'market' => 10000000, 'bright' => 15000000],
                  ['item' => 'Chứng thực bằng cấp', 'desc' => 'Thực hiện xác minh, chứng thực bằng cấp và học bạ theo đúng hình thức nhà trường yêu cầu', 'market' => 1500000, 'bright' => 0],
                  ['item' => 'Hỗ trợ du học sinh', 'desc' => 'Theo dõi tiến độ nhập học, kết nối gia đình với nhà trường và hướng dẫn ổn định học tập, sinh hoạt sau khi sang Nhật', 'market' => 10000000, 'bright' => 0],
                  ['item' => 'Chứng minh tài chính', 'desc' => 'Tư vấn nguồn bảo lãnh, chuẩn hóa giấy tờ thu nhập và hồ sơ năng lực tài chính theo yêu cầu', 'market' => 6000000, 'bright' => 0],
                  ['item' => 'Xử lý hồ sơ xin COE', 'desc' => 'Lập biểu mẫu và nội dung giải trình tư cách lưu trú; phối hợp với trường nộp, bổ sung hồ sơ và theo dõi kết quả tại Cục XNC Nhật Bản', 'market' => 10000000, 'bright' => 0],
                  ['item' => 'Chuyển phát hồ sơ sang Nhật', 'desc' => 'Đóng gói, kiểm tra và chuyển phát bảo đảm các giấy tờ gốc cần nộp cho nhà trường', 'market' => 1000000, 'bright' => 0],
                  ['item' => 'Chi phí xin visa', 'desc' => 'Khai đơn, sắp xếp bộ hồ sơ và hướng dẫn thủ tục nộp visa tại ĐSQ/LSQ Nhật Bản', 'market' => 1500000, 'bright' => 0]
              ];
          } else {
              // Senmon, University, English-track
              $service_fee_bright = 30000000;
              
              if ($slug === 'senmon-vocational-school') {
                  $service_fee_market = 50000000;
                  $tuition_jpy = 750000;
                  $dorm_jpy = 150000;
                  $tuition_note = 'Học phí 1 năm học tại Nhật (~750.000 JPY) + Ký túc xá (~150.000 JPY) (Lưu ý: Thay đổi tùy theo từng trường)';
                  
                  $comparison_items = [
                      ['item' => 'Xử lý hồ sơ & Dịch thuật', 'desc' => 'Làm việc trực tiếp với trường Senmon; rà soát, dịch thuật giấy tờ và hoàn thiện hồ sơ nhập học; tư vấn yêu cầu tuyển sinh, hướng dẫn thi và phỏng vấn đầu vào', 'market' => 15000000, 'bright' => 15000000],
                      ['item' => 'Chứng thực bằng cấp', 'desc' => 'Thực hiện xác minh, chứng thực bằng cấp và học bạ theo đúng hình thức nhà trường yêu cầu', 'market' => 1500000, 'bright' => 0],
                      ['item' => 'Tư vấn chọn ngành & trường', 'desc' => 'Đánh giá năng lực, sở thích và mục tiêu nghề nghiệp để xây dựng danh sách ngành, trường phù hợp', 'market' => 15000000, 'bright' => 0],
                      ['item' => 'Chứng minh tài chính', 'desc' => 'Tư vấn nguồn bảo lãnh, chuẩn hóa giấy tờ thu nhập và hồ sơ năng lực tài chính theo yêu cầu', 'market' => 6000000, 'bright' => 0],
                      ['item' => 'Xử lý hồ sơ xin COE', 'desc' => 'Lập biểu mẫu và nội dung giải trình tư cách lưu trú; phối hợp với trường nộp, bổ sung hồ sơ và theo dõi kết quả tại Cục XNC Nhật Bản', 'market' => 10000000, 'bright' => 15000000],
                      ['item' => 'Chuyển phát hồ sơ sang Nhật', 'desc' => 'Đóng gói, kiểm tra và chuyển phát bảo đảm các giấy tờ gốc cần nộp cho nhà trường', 'market' => 1000000, 'bright' => 0],
                      ['item' => 'Chi phí xin visa', 'desc' => 'Khai đơn, sắp xếp bộ hồ sơ và hướng dẫn thủ tục nộp visa tại ĐSQ/LSQ Nhật Bản', 'market' => 1500000, 'bright' => 0]
                  ];
              } elseif ($slug === 'university-program') {
                  $service_fee_market = 60000000;
                  $tuition_jpy = 750000;
                  $dorm_jpy = 150000;
                  $tuition_note = 'Học phí 1 năm học tại Nhật (~750.000 JPY) + Ký túc xá (~150.000 JPY) (Lưu ý: Thay đổi tùy theo từng trường)';
                  
                  $comparison_items = [
                      ['item' => 'Xử lý hồ sơ & Dịch thuật', 'desc' => 'Làm việc trực tiếp với trường đại học; rà soát, dịch thuật giấy tờ và hoàn thiện hồ sơ nhập học; tư vấn yêu cầu tuyển sinh, hướng dẫn thi EJU và phỏng vấn đầu vào', 'market' => 15000000, 'bright' => 15000000],
                      ['item' => 'Chứng thực bằng cấp', 'desc' => 'Thực hiện xác minh, chứng thực bằng cấp và học bạ theo đúng hình thức nhà trường yêu cầu', 'market' => 1500000, 'bright' => 0],
                      ['item' => 'Tư vấn chọn ngành & trường', 'desc' => 'Phân tích học lực, điểm EJU dự kiến, ngân sách và mục tiêu nghề nghiệp để xây dựng danh sách nguyện vọng phù hợp', 'market' => 25000000, 'bright' => 0],
                      ['item' => 'Chứng minh tài chính', 'desc' => 'Tư vấn nguồn bảo lãnh, chuẩn hóa giấy tờ thu nhập và hồ sơ năng lực tài chính theo yêu cầu', 'market' => 6000000, 'bright' => 0],
                      ['item' => 'Xử lý hồ sơ xin COE', 'desc' => 'Lập biểu mẫu và nội dung giải trình tư cách lưu trú; phối hợp với trường nộp, bổ sung hồ sơ và theo dõi kết quả tại Cục XNC Nhật Bản', 'market' => 10000000, 'bright' => 15000000],
                      ['item' => 'Chuyển phát hồ sơ sang Nhật', 'desc' => 'Đóng gói, kiểm tra và chuyển phát bảo đảm các giấy tờ gốc cần nộp cho nhà trường', 'market' => 1000000, 'bright' => 0],
                      ['item' => 'Chi phí xin visa', 'desc' => 'Khai đơn, sắp xếp bộ hồ sơ và hướng dẫn thủ tục nộp visa tại ĐSQ/LSQ Nhật Bản', 'market' => 1500000, 'bright' => 0]
                  ];
              } elseif ($slug === 'english-track-university') {
                  $service_fee_market = 60000000;
                  $tuition_jpy = 750000;
                  $dorm_jpy = 150000;
                  $tuition_note = 'Học phí 1 năm học tại Nhật (~750.000 JPY) + Ký túc xá (~150.000 JPY) (Lưu ý: Thay đổi tùy theo từng trường)';
                  
                  $comparison_items = [
                      ['item' => 'Xử lý hồ sơ & Dịch thuật', 'desc' => 'Làm việc trực tiếp với chương trình đại học E-Track; rà soát, dịch thuật giấy tờ và hoàn thiện hồ sơ nhập học; tư vấn yêu cầu tuyển sinh, hướng dẫn bài thi và phỏng vấn đầu vào', 'market' => 15000000, 'bright' => 15000000],
                      ['item' => 'Hoàn thiện bài luận & hồ sơ học thuật', 'desc' => 'Xây dựng câu chuyện cá nhân, biên tập bài luận, CV và portfolio bằng tiếng Anh theo định hướng của từng trường', 'market' => 25000000, 'bright' => 0],
                      ['item' => 'Chứng thực bằng cấp', 'desc' => 'Thực hiện xác minh, chứng thực bằng cấp và học bạ theo đúng hình thức nhà trường yêu cầu', 'market' => 1500000, 'bright' => 0],
                      ['item' => 'Chứng minh tài chính', 'desc' => 'Tư vấn nguồn bảo lãnh, chuẩn hóa giấy tờ thu nhập và hồ sơ năng lực tài chính theo yêu cầu', 'market' => 6000000, 'bright' => 0],
                      ['item' => 'Xử lý hồ sơ xin COE', 'desc' => 'Lập biểu mẫu và nội dung giải trình tư cách lưu trú; phối hợp với trường nộp, bổ sung hồ sơ và theo dõi kết quả tại Cục XNC Nhật Bản', 'market' => 10000000, 'bright' => 15000000],
                      ['item' => 'Chuyển phát hồ sơ sang Nhật', 'desc' => 'Đóng gói, kiểm tra và chuyển phát bảo đảm các giấy tờ gốc cần nộp cho nhà trường', 'market' => 1000000, 'bright' => 0],
                      ['item' => 'Chi phí xin visa', 'desc' => 'Khai đơn, sắp xếp bộ hồ sơ và hướng dẫn thủ tục nộp visa tại ĐSQ/LSQ Nhật Bản', 'market' => 1500000, 'bright' => 0]
                  ];
              }
          }

          $tuition_fee_vnd = ($tuition_jpy + $dorm_jpy) * $rate;
          $total_bright = $service_fee_bright + $flight_fee + 1500000 + 800000 + 12000000 + $tuition_fee_vnd;
          $total_market = $service_fee_market + $flight_fee + 1500000 + 800000 + 12000000 + $tuition_fee_vnd;
          ?>

          <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">
            <h2 class="text-xl font-bold text-primary font-display mb-2 flex items-center gap-2">
              <i class="bi bi-cash-coin text-amber-500"></i> Bảng So Sánh Chi Phí Thực Tế (Tỷ giá 175)
            </h2>
            <p class="text-[13px] text-slate-500 mb-6 leading-relaxed">
              Bảng đối chiếu minh bạch các khoản phí xử lý hồ sơ tại Việt Nam và các chi phí chuẩn bị ban đầu. Bright Education cam kết <strong>không phát sinh chi phí ngoài hợp đồng</strong>.
            </p>

            <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-soft">
              <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm font-sans" style="font-family: 'Inter', sans-serif;">
                  <thead>
                    <tr class="border-b-2 border-slate-200 bg-white">
                      <th class="py-3 px-4 font-bold text-midnight w-[30%]">Danh Mục Chi Phí</th>
                      <th class="py-3 px-4 font-bold text-midnight hidden md:table-cell w-[35%]">Chi Tiết</th>
                      <th class="py-3 px-4 font-bold text-slate-400 text-right w-[17.5%]">Giá Thị Trường (VNĐ)</th>
                      <th class="py-3 px-4 font-bold text-primary text-right w-[17.5%]">Bright Education (VNĐ)</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    <?php foreach ($comparison_items as $item): ?>
                      <tr class="bg-white hover:bg-slate-50/50 transition-colors">
                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?php echo $item['item']; ?></td>
                        <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell"><?php echo $item['desc']; ?></td>
                        <td class="py-3.5 px-4 text-slate-500 text-right">~<?php echo number_format($item['market'], 0, ',', '.'); ?>đ</td>
                        <td class="py-3.5 px-4 text-right font-semibold">
                          <?php if ($item['bright'] === 0): ?>
                            <span class="text-emerald-600">Miễn phí</span>
                          <?php else: ?>
                            <span class="text-slate-700"><?php echo number_format($item['bright'], 0, ',', '.') . 'đ'; ?></span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    
                    <tr class="font-bold border-t-2 border-slate-200 bg-white">
                      <td class="py-3.5 px-4 text-emerald-700">PHÍ DỊCH VỤ TRỌN GÓI</td>
                      <td class="py-3.5 px-4 hidden md:table-cell text-xs text-slate-500 font-normal">Toàn bộ hồ sơ tại Việt Nam (không phát sinh)</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~<?php echo number_format($service_fee_market, 0, ',', '.'); ?>đ</td>
                      <td class="py-3.5 px-4 text-emerald-700 text-right font-bold"><?php echo number_format($service_fee_bright, 0, ',', '.'); ?>đ</td>
                    </tr>

                    <tr class="font-bold bg-slate-100 border-t border-b border-slate-200">
                      <td colspan="4" class="py-2.5 px-4 text-xs text-slate-600 uppercase tracking-wider">
                        <i class="bi bi-info-circle-fill text-amber-500 mr-1"></i> Các chi phí khác (Chưa nằm trong phí dịch vụ)
                      </td>
                    </tr>

                    <tr class="bg-slate-50 hover:bg-slate-100/60 transition-colors">
                      <td class="py-3.5 px-4 text-slate-600 font-medium">Vé Máy Bay</td>
                      <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell">Vé máy bay một chiều sang Nhật (Bright đặt hộ hoặc tự túc)</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~<?php echo number_format($flight_fee, 0, ',', '.'); ?>đ</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~<?php echo number_format($flight_fee, 0, ',', '.'); ?>đ</td>
                    </tr>

                    <tr class="bg-slate-50 hover:bg-slate-100/60 transition-colors">
                      <td class="py-3.5 px-4 text-slate-600 font-medium">Khám lao phổi</td>
                      <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell">Khám lao phổi theo mẫu chỉ định của Cục xuất nhập cảnh</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~1.500.000đ</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~1.500.000đ</td>
                    </tr>

                    <tr class="bg-slate-50 hover:bg-slate-100/60 transition-colors">
                      <td class="py-3.5 px-4 text-slate-600 font-medium">Đăng ký thi chứng chỉ tiếng Nhật</td>
                      <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell">Lệ phí đăng ký thi JLPT, NAT-TEST hoặc TOPJ tại Việt Nam</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~800.000đ</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~800.000đ</td>
                    </tr>

                    <tr class="bg-slate-50 hover:bg-slate-100/60 transition-colors">
                      <td class="py-3.5 px-4 text-slate-600 font-medium">Học tiếng Nhật tại Việt Nam</td>
                      <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell">Khóa học tiếng Nhật từ cơ bản đến hoàn thành trình độ N4 trước khi bay</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~12.000.000đ</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~12.000.000đ</td>
                    </tr>

                    <tr class="bg-slate-50 hover:bg-slate-100/60 transition-colors">
                      <td class="py-3.5 px-4 text-slate-600 font-medium">Học phí & KTX tại Nhật</td>
                      <td class="py-3.5 px-4 text-xs text-slate-500 hidden md:table-cell"><?php echo $tuition_note; ?></td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~<?php echo number_format($tuition_fee_vnd, 0, ',', '.'); ?>đ</td>
                      <td class="py-3.5 px-4 text-slate-500 text-right">~<?php echo number_format($tuition_fee_vnd, 0, ',', '.'); ?>đ</td>
                    </tr>

                    <tr class="font-black text-sm border-t-2 border-slate-200 bg-midnight text-white">
                      <td class="py-4 px-4">TỔNG CHI PHÍ NĂM ĐẦU</td>
                      <td class="py-4 px-4 hidden md:table-cell text-xs text-white/60">Dự phòng ngân sách chuẩn bị năm đầu</td>
                      <td class="py-4 px-4 text-right text-slate-300">~<?php echo number_format($total_market, 0, ',', '.'); ?>đ</td>
                      <td class="py-4 px-4 text-right text-orange-400"><?php echo number_format($total_bright, 0, ',', '.'); ?>đ</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="mt-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-500 space-y-1">
              <p><strong>* Ghi chú quan trọng:</strong></p>
              <p>1. Học viên nộp trực tiếp học phí và ký túc xá trực tiếp vào tài khoản ngân hàng của trường học bên Nhật. Bright Education tuyệt đối không thu hộ khoản này.</p>
              <p>2. Toàn bộ tính toán quy đổi trên đây dựa trên tỷ giá thực tế <strong>1 JPY = 175 VNĐ</strong>.</p>
            </div>
          </div>

          <!-- Frequently Asked Questions -->
          <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft" aria-labelledby="service-faq-title">
            <h2 id="service-faq-title" class="text-2xl font-bold text-primary font-display mb-6">Câu hỏi thường gặp</h2>
            <div class="space-y-4">
              <?php foreach ($service_faqs as $faq): ?>
              <details class="group rounded-2xl border border-slate-200 p-5">
                <summary class="cursor-pointer list-none font-bold text-slate-800 flex items-center justify-between gap-4">
                  <?php echo htmlspecialchars($faq['question']); ?>
                  <i class="bi bi-plus-lg text-primary group-open:rotate-45 transition-transform"></i>
                </summary>
                <p class="mt-3 text-sm leading-7 text-slate-600"><?php echo htmlspecialchars($faq['answer']); ?></p>
              </details>
              <?php endforeach; ?>
            </div>
          </section>

          <?php if (!empty($cluster_posts)): ?>
          <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft" aria-labelledby="service-guides-title">
            <h2 id="service-guides-title" class="text-2xl font-bold text-primary font-display mb-2">Kiến thức liên quan</h2>
            <p class="mb-6 text-sm text-slate-500">Hướng dẫn giúp bạn chuẩn bị tốt hơn cho lộ trình <?php echo htmlspecialchars($data['title']); ?>.</p>
            <div class="grid gap-4 sm:grid-cols-3">
              <?php foreach ($cluster_posts as $cluster_post): ?>
              <article class="rounded-2xl border border-slate-200 p-4">
                <h3 class="font-bold leading-6 text-slate-800"><?php echo htmlspecialchars($cluster_post['title']); ?></h3>
                <p class="mt-2 text-xs leading-5 text-slate-500"><?php echo htmlspecialchars(getExcerpt($cluster_post['excerpt'] ?: $cluster_post['content'], 85)); ?></p>
                <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-primary hover:underline" href="/blog/<?php echo rawurlencode($cluster_post['slug']); ?>">Xem hướng dẫn <i class="bi bi-arrow-right"></i></a>
              </article>
              <?php endforeach; ?>
            </div>
          </section>
          <?php endif; ?>

        </div>

        <!-- Right Column: Sidebar (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
          
          <!-- Summary Box -->
          <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft space-y-5">
            <h2 class="text-base font-bold text-primary font-display border-b border-slate-100 pb-3">Thông Tin Lộ Trình</h2>
            
            <div class="space-y-4 text-xs sm:text-[13px]">
              <div>
                <span class="block text-slate-400 font-medium mb-1">Thời gian học tập:</span>
                <span class="font-bold text-slate-800"><?php echo $data['duration']; ?></span>
              </div>
              <div>
                <span class="block text-slate-400 font-medium mb-1">Yêu cầu tiếng Nhật:</span>
                <span class="font-bold text-slate-800"><?php echo $data['language']; ?></span>
              </div>
              <div>
                <span class="block text-slate-400 font-medium mb-1">Yêu cầu học vấn:</span>
                <span class="font-bold text-slate-800"><?php echo $data['gpa']; ?></span>
              </div>
              <div>
                <span class="block text-slate-400 font-medium mb-1">Học phí trung bình tại Nhật:</span>
                <span class="font-bold text-primary font-display"><?php echo $data['fee']; ?></span>
              </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
              <span class="block text-center text-slate-400 text-xs font-semibold mb-2">Phí dịch vụ trọn gói tại Việt Nam</span>
              <div class="text-center font-black text-2xl text-orange-600"><?php echo $data['price']; ?></div>
            </div>
          </div>

          <!-- Lead Capture Form in Sidebar -->
          <div class="bg-gradient-to-br from-primary to-slate-900 rounded-3xl p-6 text-white shadow-soft relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-40 h-40 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
            <h2 class="text-base font-bold font-display mb-2 relative z-10">Đăng Ký Tư Vấn 1-1</h2>
            <p class="text-xs text-white/70 mb-5 relative z-10 leading-relaxed">Nhận lộ trình cá nhân hóa và thông tin chi tiết các ưu đãi học bổng mới nhất.</p>
            
            <form method="POST" action="/api/contact.php" id="sidebar-intake-form" class="space-y-3.5 relative z-10">
              <?php echo csrfField(); ?>
              <input type="hidden" name="message" value="<?php echo $data['title']; ?>">
              
              <div>
                <input type="text" name="name" required placeholder="Họ và tên" class="w-full px-4 py-2.5 bg-white/10 hover:bg-white/15 focus:bg-white focus:text-slate-800 focus:ring-4 focus:ring-primary/20 rounded-xl outline-none text-xs font-semibold transition-all placeholder:text-white/60">
              </div>
              <div>
                <input type="tel" name="phone" required placeholder="Số điện thoại" class="w-full px-4 py-2.5 bg-white/10 hover:bg-white/15 focus:bg-white focus:text-slate-800 focus:ring-4 focus:ring-primary/20 rounded-xl outline-none text-xs font-semibold transition-all placeholder:text-white/60">
              </div>
              <div>
                <input type="email" name="email" required placeholder="Địa chỉ Email" class="w-full px-4 py-2.5 bg-white/10 hover:bg-white/15 focus:bg-white focus:text-slate-800 focus:ring-4 focus:ring-primary/20 rounded-xl outline-none text-xs font-semibold transition-all placeholder:text-white/60">
              </div>
              
              <div class="grid grid-cols-2 gap-2">
                <div>
                  <select name="japanese_level" class="w-full px-3 py-2.5 bg-white/10 hover:bg-white/15 focus:bg-white focus:text-slate-800 rounded-xl outline-none text-xs font-semibold transition-all text-white/80 appearance-none">
                    <option value="Chưa học" class="text-slate-800">Tiếng Nhật: Chưa</option>
                    <option value="N5" class="text-slate-800">Đã học xong N5</option>
                    <option value="N4" class="text-slate-800">Đã học xong N4</option>
                    <option value="N3" class="text-slate-800">Đã có N3</option>
                    <option value="N2 trở lên" class="text-slate-800">Đã có N2 trở lên</option>
                  </select>
                </div>
                <div>
                  <select name="intake_period" class="w-full px-3 py-2.5 bg-white/10 hover:bg-white/15 focus:bg-white focus:text-slate-800 rounded-xl outline-none text-xs font-semibold transition-all text-white/80 appearance-none">
                    <option value="Tháng 4" class="text-slate-800">Kỳ học: T4</option>
                    <option value="Tháng 7" class="text-slate-800">Kỳ học: T7</option>
                    <option value="Tháng 10" class="text-slate-800">Kỳ học: T10</option>
                    <option value="Tháng 1" class="text-slate-800">Kỳ học: T1</option>
                    <option value="Đang cân nhắc" class="text-slate-800">Đang cân nhắc</option>
                  </select>
                </div>
              </div>

              <button type="submit" class="w-full mt-2 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-soft hover:shadow-medium transition-all flex items-center justify-center gap-1.5">
                Đăng Ký Nhận Lộ Trình <i class="bi bi-arrow-right"></i>
              </button>
            </form>
          </div>

          <!-- Hotline Call -->
          <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-soft text-center space-y-3">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto">
              <i class="bi bi-telephone-fill text-lg"></i>
            </div>
            <h5 class="font-bold text-slate-800 text-sm">Hỗ Trợ Trực Tiếp 24/7</h5>
            <p class="text-xs text-slate-400">Liên hệ trực tiếp với chuyên viên tư vấn của Bright Education.</p>
            <div class="text-lg font-black text-primary font-display">+84 0971044576</div>
          </div>

        </div>

      </div>
    </div>
  </section>

</main>

<script>
// Handle Sidebar Form Submission via Ajax
document.getElementById('sidebar-intake-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const submitBtn = this.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;
  
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Đang xử lý...';
  
  fetch('/api/contact.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
    if (data.success) {
      alert('Cảm ơn bạn! Thông tin đăng ký lộ trình du học đã được gửi thành công. Bright Education sẽ liên hệ lại với bạn sớm nhất có thể.');
      this.reset();
    } else {
      alert('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại.'));
    }
  })
  .catch(error => {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
    alert('Có lỗi kết nối. Vui lòng kiểm tra lại mạng Internet và thử lại sau.');
  });
});
</script>

<?php include 'includes/footer.php'; ?>
