<?php
require_once dirname(__DIR__) . '/config/config.php';
requireAuth();
require_once APP_ROOT . '/includes/analytics.php';

$db = Database::getInstance();

$stmt = $db->prepare("SELECT COUNT(*) as total FROM posts WHERE status = 'published'");
$stmt->execute();
$stats['posts'] = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT COUNT(*) as total FROM contacts WHERE status = 'new'");
$stmt->execute();
$stats['contacts'] = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT COUNT(*) as total FROM users WHERE status = 'active'");
$stmt->execute();
$stats['users'] = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT COUNT(*) as total FROM leads WHERE status = 'new'");
$stmt->execute();
$stats['leads'] = $stmt->fetch()['total'];

$stmt = $db->prepare("
    SELECT p.*, u.full_name as author_name, c.name as category_name
    FROM posts p
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.created_at DESC LIMIT 5
");
$stmt->execute();
$recent_posts = $stmt->fetchAll();

$stmt = $db->prepare("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$recent_contacts = $stmt->fetchAll();

$analytics_overview = analyticsDashboardData(28);
$analytics_ga = $analytics_overview['ga']['summary'] ?? [];
$analytics_gsc = $analytics_overview['gsc']['summary'] ?? [];
$analytics_events = $analytics_overview['ga']['events'] ?? [];
$analytics_sessions = (int)round($analytics_ga['sessions'] ?? 0);
$analytics_leads = (int)($analytics_events['generate_lead'] ?? 0);
$analytics_conversion_rate = $analytics_sessions > 0 ? ($analytics_leads / $analytics_sessions) * 100 : 0;

$page_title = 'Dashboard';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Xin chào, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?> 👋</p>
    </div>
    <a href="/admin/posts/create" class="btn-adm"><i class="bi bi-plus-lg"></i> Bài viết mới</a>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <?php
    $cards = [
        ['Bài viết',     $stats['posts'],    'bi-file-richtext',   'bg-sky-500'],
        ['Liên hệ mới',  $stats['contacts'], 'bi-envelope-open',   'bg-amber-500'],
        ['Leads mới',    $stats['leads'],    'bi-person-lines-fill','bg-primary'],
        ['Người dùng',   $stats['users'],    'bi-people',          'bg-slate-500'],
    ];
    foreach ($cards as $c): ?>
    <div class="a-card flex items-center gap-4 p-5">
        <div class="w-11 h-11 rounded-xl <?php echo $c[3]; ?> flex items-center justify-center text-white text-xl flex-shrink-0">
            <i class="bi <?php echo $c[2]; ?>"></i>
        </div>
        <div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider"><?php echo $c[0]; ?></div>
            <div class="text-2xl font-black text-midnight leading-none mt-0.5"><?php echo $c[1]; ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- SEO & Analytics overview -->
<div class="flex items-end justify-between gap-4 mb-3 mt-1">
    <div>
        <h2 class="text-base font-extrabold text-midnight font-display">SEO & Analytics</h2>
        <p class="text-xs text-slate-400 mt-0.5">Dữ liệu organic trong 28 ngày gần nhất</p>
    </div>
    <a href="/admin/analytics" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Báo cáo đầy đủ →</a>
</div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <?php
    $analytics_cards = [
        ['Organic Traffic', $analytics_sessions, 'bi-graph-up-arrow'],
        ['Impressions', (int)round($analytics_gsc['impressions'] ?? 0), 'bi-eye'],
        ['CTR', number_format(((float)($analytics_gsc['ctr'] ?? 0)) * 100, 1, ',', '.') . '%', 'bi-cursor'],
        ['Conversion Rate', number_format($analytics_conversion_rate, 1, ',', '.') . '%', 'bi-bullseye'],
    ];
    foreach ($analytics_cards as $card): ?>
    <a href="/admin/analytics" class="a-card flex items-center gap-4 p-5 hover:-translate-y-0.5 transition-transform">
        <div class="w-11 h-11 rounded-xl bg-slate-100 text-midnight flex items-center justify-center text-lg flex-shrink-0">
            <i class="bi <?php echo $card[2]; ?>"></i>
        </div>
        <div class="min-w-0">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate"><?php echo $card[0]; ?></div>
            <div class="text-xl font-black text-midnight leading-none mt-1"><?php echo is_numeric($card[1]) ? number_format((float)$card[1], 0, ',', '.') : htmlspecialchars((string)$card[1]); ?></div>
        </div>
    </a>
    <?php endforeach; ?>
</div>

<div class="grid lg:grid-cols-2 gap-5">
    <!-- Recent Posts -->
    <div class="a-card">
        <div class="a-card-header">
            <h2>Bài viết gần đây</h2>
            <a href="/admin/posts" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Xem tất cả →</a>
        </div>
        <?php if (empty($recent_posts)): ?>
        <div class="a-card-body text-slate-400 text-sm">Chưa có bài viết nào.</div>
        <?php else: ?>
        <table class="a-table">
            <thead><tr>
                <th>Tiêu đề</th><th>Danh mục</th><th>Ngày</th>
            </tr></thead>
            <tbody>
            <?php foreach ($recent_posts as $post): ?>
            <tr>
                <td class="font-semibold text-midnight max-w-[180px] truncate"><?php echo htmlspecialchars(truncateText($post['title'], 35)); ?></td>
                <td><span class="badge badge-inactive"><?php echo htmlspecialchars($post['category_name'] ?? '—'); ?></span></td>
                <td class="text-xs text-slate-400"><?php echo formatDate($post['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Recent Contacts -->
    <div class="a-card">
        <div class="a-card-header">
            <h2>Liên hệ gần đây</h2>
            <a href="/admin/contacts" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Xem tất cả →</a>
        </div>
        <?php if (empty($recent_contacts)): ?>
        <div class="a-card-body text-slate-400 text-sm">Chưa có liên hệ nào.</div>
        <?php else: ?>
        <table class="a-table">
            <thead><tr>
                <th>Họ tên</th><th>Email</th><th>Ngày</th>
            </tr></thead>
            <tbody>
            <?php foreach ($recent_contacts as $c): ?>
            <tr>
                <td class="font-semibold text-midnight"><?php echo htmlspecialchars($c['name']); ?></td>
                <td class="text-slate-500 text-xs"><?php echo htmlspecialchars($c['email']); ?></td>
                <td class="text-xs text-slate-400"><?php echo formatDate($c['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
