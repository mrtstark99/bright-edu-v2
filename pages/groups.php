<?php
require_once 'config/config.php';

$db = Database::getInstance();

$stmt = $db->prepare("
    SELECT * FROM community_groups
    WHERE status = 'active'
    ORDER BY display_order ASC, platform ASC, id ASC
");
$stmt->execute();
$all_groups = $stmt->fetchAll();

// Group by platform
$grouped = [];
foreach ($all_groups as $g) {
    $grouped[$g['platform']][] = $g;
}

$platform_meta = [
    'facebook'  => ['label' => 'Facebook', 'color' => '#1877f2', 'bg' => '#e7f0fd', 'icon' => 'bi-facebook'],
    'zalo'      => ['label' => 'Zalo',     'color' => '#0068ff', 'bg' => '#e5f0ff', 'icon' => 'bi-chat-dots-fill'],
    'youtube'   => ['label' => 'YouTube',  'color' => '#ff0000', 'bg' => '#ffe5e5', 'icon' => 'bi-youtube'],
    'telegram'  => ['label' => 'Telegram', 'color' => '#229ed9', 'bg' => '#e3f4fb', 'icon' => 'bi-telegram'],
    'other'     => ['label' => 'Khác',     'color' => '#6b7280', 'bg' => '#f3f4f6', 'icon' => 'bi-people-fill'],
];

$page_title = 'Cộng đồng Bright Education';
$page_description = 'Tham gia cộng đồng du học Nhật Bản trên Facebook, Zalo của Bright Education để nhận thông tin mới nhất';
include 'includes/header.php';
?>

<main class="pt-20 bg-slate-50 min-h-screen">

  <!-- Hero -->
  <section class="relative overflow-hidden bg-primary py-14 sm:py-16 lg:py-20">
    <div class="absolute -right-24 -top-32 h-80 w-80 rounded-full bg-white/[.06]"></div>
    <div class="absolute -bottom-24 -left-16 h-56 w-56 rounded-full bg-primary-400/10"></div>
    <div class="relative mx-auto max-w-7xl px-5 lg:px-8">
      <p class="text-[11px] font-extrabold uppercase tracking-[.2em] text-primary-300">Bright community</p>
      <h1 class="mt-3 text-4xl font-black tracking-tight text-white font-display sm:text-5xl">Cộng đồng Bright Education</h1>
      <nav class="mt-6 flex items-center gap-2 text-xs font-semibold text-white/65" aria-label="Breadcrumb">
        <a href="/" class="transition hover:text-white">Trang chủ</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-white">Cộng đồng</span>
      </nav>
    </div>
  </section>

  <!-- Groups -->
  <section class="max-w-5xl mx-auto px-6 lg:px-8 py-14">

    <?php if (empty($all_groups)): ?>
    <div class="text-center py-20 text-slate-400">
      <i class="bi bi-people text-6xl block mb-4 text-slate-200"></i>
      <p class="font-semibold text-lg">Chưa có nhóm nào được thêm</p>
      <p class="text-sm mt-2">Vui lòng quay lại sau.</p>
    </div>

    <?php else:
      // Order platforms: facebook first, then zalo, then rest
      $order = ['facebook','zalo','youtube','telegram','other'];
      $sorted_platforms = array_filter($order, fn($p) => isset($grouped[$p]));
    ?>

    <?php foreach ($sorted_platforms as $platform):
      $meta   = $platform_meta[$platform];
      $groups = $grouped[$platform];
    ?>
    <div class="mb-12 reveal">
      <!-- Platform header -->
      <div class="flex items-center gap-3 mb-6">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-lg flex-shrink-0"
             style="background:<?php echo $meta['color']; ?>">
          <i class="bi <?php echo $meta['icon']; ?>"></i>
        </div>
        <h2 class="text-xl font-bold text-midnight font-display"><?php echo $meta['label']; ?></h2>
        <div class="flex-1 h-px bg-slate-200 ml-2"></div>
      </div>

      <!-- Cards grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($groups as $g): ?>
        <div class="bg-white rounded-3xl border border-slate-100 shadow-soft hover:shadow-medium transition-all hover:-translate-y-1 flex flex-col overflow-hidden">

          <!-- Ảnh nhóm hoặc color bar -->
          <?php if (!empty($g['image'])): ?>
          <div class="relative h-36 overflow-hidden bg-slate-100">
            <img src="/uploads/<?php echo htmlspecialchars($g['image']); ?>"
                 alt="<?php echo htmlspecialchars($g['name']); ?>"
                 loading="lazy" decoding="async"
                 class="w-full h-full object-cover">
            <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full text-white shadow"
                  style="background:<?php echo $meta['color']; ?>">
              <i class="bi <?php echo $meta['icon']; ?>"></i> <?php echo $meta['label']; ?>
            </span>
          </div>
          <?php else: ?>
          <div class="h-2 w-full" style="background:<?php echo $meta['color']; ?>"></div>
          <?php endif; ?>

          <div class="p-6 flex flex-col flex-1">
            <div class="flex items-start gap-3 mb-3">
              <?php if (empty($g['image'])): ?>
              <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-white text-xl flex-shrink-0 shadow-sm"
                   style="background:<?php echo $meta['color']; ?>">
                <i class="bi <?php echo $meta['icon']; ?>"></i>
              </div>
              <?php endif; ?>
              <div class="flex-1 min-w-0">
                <h3 class="font-bold text-midnight text-[15px] leading-snug"><?php echo htmlspecialchars($g['name']); ?></h3>
                <?php if ($g['member_count']): ?>
                <span class="inline-flex items-center gap-1 text-xs text-slate-500 mt-0.5">
                  <i class="bi bi-people"></i> <?php echo htmlspecialchars($g['member_count']); ?> thành viên
                </span>
                <?php endif; ?>
              </div>
            </div>

            <?php if ($g['description']): ?>
            <p class="text-sm text-muted leading-relaxed flex-1 mb-5">
              <?php echo htmlspecialchars($g['description']); ?>
            </p>
            <?php else: ?>
            <div class="flex-1 mb-5"></div>
            <?php endif; ?>

            <a href="<?php echo htmlspecialchars($g['url']); ?>" target="_blank" rel="noopener noreferrer"
               class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90 hover:shadow-md"
               style="background:<?php echo $meta['color']; ?>">
              <i class="bi bi-box-arrow-up-right text-xs"></i>
              Tham gia ngay
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

  </section>

  <!-- CTA: Đặt lịch tư vấn -->
  <section class="bg-primary py-14 reveal">
    <div class="max-w-3xl mx-auto px-6 text-center">
      <h2 class="text-2xl lg:text-3xl font-bold font-display text-white mb-3">Muốn tư vấn trực tiếp?</h2>
      <p class="text-primary-100 mb-7">Đặt lịch gặp chuyên viên qua Zoom — miễn phí, cá nhân hóa theo hồ sơ của bạn.</p>
      <a href="/consultation" class="inline-flex items-center gap-2 bg-white text-primary font-bold px-8 py-3.5 rounded-full hover:shadow-hard transition-all hover:-translate-y-0.5">
        <i class="bi bi-calendar-check-fill"></i> Đặt lịch tư vấn miễn phí
      </a>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
