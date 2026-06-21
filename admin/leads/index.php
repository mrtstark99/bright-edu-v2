<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db = Database::getInstance();

// ── Sync action ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'sync') {
    $result = syncAllToLeads();
    redirect('/admin/leads', "Đồng bộ hoàn tất: {$result['created']} lead mới, {$result['merged']} đã gộp.", 'success');
}

// ── Delete ───────────────────────────────────────────────────
if (isset($_GET['delete']) && isAdmin()) {
    $db->prepare("DELETE FROM leads WHERE id = ?")->execute([(int)$_GET['delete']]);
    redirect('/admin/leads', 'Đã xóa lead.', 'success');
}

// ── Filters ──────────────────────────────────────────────────
$status_filter = $_GET['status'] ?? '';
$source_filter = $_GET['source'] ?? '';
$search        = $_GET['search'] ?? '';
$page          = max(1, (int)($_GET['page'] ?? 1));
$per_page      = ADMIN_PER_PAGE;
$offset        = ($page - 1) * $per_page;

$where = []; $params = [];
if ($status_filter) { $where[] = "l.status = ?"; $params[] = $status_filter; }
if ($source_filter) { $where[] = "l.source = ?"; $params[] = $source_filter; }
if ($search) {
    $where[] = "(l.name LIKE ? OR l.email LIKE ? OR l.phone LIKE ?)";
    $s = "%$search%"; array_push($params, $s, $s, $s);
}
$w = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $db->prepare("SELECT COUNT(*) FROM leads l $w");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$stmt = $db->prepare("
    SELECT l.*,
           u.full_name AS assigned_name,
           (SELECT COUNT(*) FROM lead_activities a WHERE a.lead_id = l.id) AS activity_count,
           (SELECT created_at FROM lead_activities a WHERE a.lead_id = l.id ORDER BY created_at DESC LIMIT 1) AS last_activity
    FROM leads l
    LEFT JOIN users u ON l.assigned_to = u.id
    $w
    ORDER BY l.updated_at DESC
    LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$leads = $stmt->fetchAll();

// Stats
$stats_stmt = $db->prepare("SELECT status, COUNT(*) as n FROM leads GROUP BY status");
$stats_stmt->execute();
$stats_raw = $stats_stmt->fetchAll();
$stats = array_column($stats_raw, 'n', 'status');

$status_meta = [
    'new'        => ['label' => 'Mới',          'color' => 'bg-sky-50 text-sky-700 border-sky-200'],
    'contacted'  => ['label' => 'Đã liên hệ',   'color' => 'bg-blue-50 text-blue-700 border-blue-200'],
    'consulting' => ['label' => 'Đang tư vấn',  'color' => 'bg-amber-50 text-amber-700 border-amber-200'],
    'applied'    => ['label' => 'Đã nộp hồ sơ','color' => 'bg-purple-50 text-purple-700 border-purple-200'],
    'enrolled'   => ['label' => 'Đã nhập học',  'color' => 'bg-green-50 text-green-700 border-green-200'],
    'lost'       => ['label' => 'Mất khách',    'color' => 'bg-slate-100 text-slate-500 border-slate-200'],
];
$source_meta = [
    'contact' => ['label' => 'Form liên hệ', 'icon' => 'bi-envelope'],
    'booking' => ['label' => 'Đặt lịch',     'icon' => 'bi-calendar-check'],
    'manual'  => ['label' => 'Thủ công',     'icon' => 'bi-pencil'],
];

$page_title = 'Quản lý Leads / CRM';
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-midnight font-display">Leads & Khách hàng</h1>
        <p class="text-sm text-muted mt-1">Tổng hợp từ form liên hệ, đặt lịch tư vấn và nhập thủ công</p>
    </div>
    <div class="flex gap-3">
        <form method="POST">
            <input type="hidden" name="action" value="sync">
            <button type="submit" class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors">
                <i class="bi bi-arrow-repeat"></i> Đồng bộ dữ liệu
            </button>
        </form>
        <a href="/admin/leads/create" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-ink transition-colors shadow-soft">
            <i class="bi bi-person-plus"></i> Thêm lead
        </a>
    </div>
</div>

<?php displayFlashMessage(); ?>

<!-- Stats -->
<div class="grid grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    <?php
    $total_all = array_sum($stats);
    $stat_cards = [
        ['label'=>'Tổng',         'value'=> $total_all,          'icon'=>'bi-people-fill',       'bg'=>'bg-primary',   'text'=>'text-white'],
        ['label'=>'Mới',          'value'=> $stats['new']??0,    'icon'=>'bi-star-fill',          'bg'=>'bg-sky-500',   'text'=>'text-white'],
        ['label'=>'Đã liên hệ',   'value'=> $stats['contacted']??0, 'icon'=>'bi-telephone-fill', 'bg'=>'bg-blue-500',  'text'=>'text-white'],
        ['label'=>'Đang tư vấn',  'value'=> $stats['consulting']??0,'icon'=>'bi-chat-dots-fill',  'bg'=>'bg-amber-500', 'text'=>'text-white'],
        ['label'=>'Đã nộp HS',    'value'=> $stats['applied']??0,'icon'=>'bi-file-earmark-check', 'bg'=>'bg-purple-500','text'=>'text-white'],
        ['label'=>'Nhập học',     'value'=> $stats['enrolled']??0,'icon'=>'bi-mortarboard-fill',  'bg'=>'bg-green-500', 'text'=>'text-white'],
    ];
    foreach ($stat_cards as $sc):
    ?>
    <div class="<?php echo $sc['bg']; ?> rounded-2xl p-4 flex items-center gap-3">
        <i class="bi <?php echo $sc['icon']; ?> <?php echo $sc['text']; ?> text-xl"></i>
        <div>
            <div class="text-xl font-bold <?php echo $sc['text']; ?>"><?php echo $sc['value']; ?></div>
            <div class="text-[11px] <?php echo $sc['text']; ?> opacity-80"><?php echo $sc['label']; ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filters -->
<div class="bg-white rounded-3xl p-5 shadow-soft border border-slate-100 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tìm kiếm</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tên, email, SĐT..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
        </div>
        <div class="w-44">
            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Trạng thái</label>
            <select name="status" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary">
                <option value="">Tất cả</option>
                <?php foreach ($status_meta as $k => $v): ?>
                <option value="<?php echo $k; ?>" <?php echo $status_filter===$k?'selected':''; ?>><?php echo $v['label']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-44">
            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Nguồn</label>
            <select name="source" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary">
                <option value="">Tất cả nguồn</option>
                <?php foreach ($source_meta as $k => $v): ?>
                <option value="<?php echo $k; ?>" <?php echo $source_filter===$k?'selected':''; ?>><?php echo $v['label']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-ink transition-colors">Tìm</button>
        <?php if ($search||$status_filter||$source_filter): ?>
        <a href="/admin/leads" class="bg-slate-100 text-slate-500 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-200">Xoá lọc</a>
        <?php endif; ?>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-4 font-bold">Khách hàng</th>
                    <th class="px-5 py-4 font-bold">Liên lạc</th>
                    <th class="px-5 py-4 font-bold">Nguồn</th>
                    <th class="px-5 py-4 font-bold">Trạng thái</th>
                    <th class="px-5 py-4 font-bold">Phụ trách</th>
                    <th class="px-5 py-4 font-bold">Hoạt động</th>
                    <th class="px-5 py-4 font-bold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <?php if (empty($leads)): ?>
                <tr><td colspan="7" class="px-5 py-16 text-center text-slate-400">
                    <i class="bi bi-person-x text-5xl text-slate-200 block mb-3"></i>
                    Chưa có lead nào. Nhấn <strong>Đồng bộ dữ liệu</strong> để import từ form liên hệ và đặt lịch.
                </td></tr>
            <?php endif; ?>
            <?php foreach ($leads as $l):
                $sm = $status_meta[$l['status']] ?? ['label'=>$l['status'],'color'=>'bg-slate-100 text-slate-500 border-slate-200'];
                $src = $source_meta[$l['source']] ?? ['label'=>$l['source'],'icon'=>'bi-question'];
                $initials = mb_strtoupper(mb_substr($l['name'], 0, 1, 'UTF-8'), 'UTF-8');
            ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                <?php echo $initials; ?>
                            </div>
                            <div>
                                <a href="/admin/leads/view?id=<?php echo $l['id']; ?>" class="font-bold text-midnight hover:text-primary transition-colors">
                                    <?php echo htmlspecialchars($l['name']); ?>
                                </a>
                                <?php if ($l['japanese_level']): ?>
                                <span class="ml-1 text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded font-medium"><?php echo htmlspecialchars($l['japanese_level']); ?></span>
                                <?php endif; ?>
                                <?php if ($l['intake_period']): ?>
                                <div class="text-xs text-slate-400 mt-0.5"><?php echo htmlspecialchars($l['intake_period']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="text-sm text-slate-600"><?php echo htmlspecialchars($l['email'] ?? '—'); ?></div>
                        <div class="text-xs text-slate-400 mt-0.5"><?php echo htmlspecialchars($l['phone'] ?? '—'); ?></div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 bg-slate-100 px-2 py-1 rounded-lg">
                            <i class="bi <?php echo $src['icon']; ?> text-[11px]"></i>
                            <?php echo $src['label']; ?>
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-block border text-xs font-bold px-2.5 py-1 rounded-full <?php echo $sm['color']; ?>">
                            <?php echo $sm['label']; ?>
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-slate-600">
                        <?php echo $l['assigned_name'] ? htmlspecialchars($l['assigned_name']) : '<span class="text-slate-300 text-xs">Chưa phân công</span>'; ?>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="text-xs text-slate-500">
                            <i class="bi bi-chat-left-text mr-1"></i><?php echo $l['activity_count']; ?> hoạt động
                        </div>
                        <?php if ($l['last_activity']): ?>
                        <div class="text-[11px] text-slate-400 mt-0.5"><?php echo timeAgo($l['last_activity']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex justify-end gap-1.5">
                            <a href="/admin/leads/view?id=<?php echo $l['id']; ?>"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50 text-primary hover:bg-primary hover:text-white transition-colors" title="Xem chi tiết">
                                <i class="bi bi-person-lines-fill text-sm"></i>
                            </a>
                            <?php if (isAdmin()): ?>
                            <a href="?delete=<?php echo $l['id']; ?>"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors"
                               onclick="return confirm('Xóa lead này?')" title="Xóa">
                                <i class="bi bi-trash text-sm"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-slate-100 flex justify-between items-center text-sm text-slate-500">
        <span>Tổng <strong class="text-midnight"><?php echo $total; ?></strong> leads</span>
        <?php echo paginate($total, $page, $per_page, '/admin/leads'); ?>
    </div>
</div>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
