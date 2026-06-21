<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db  = Database::getInstance();
$id  = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/admin/leads', 'Lead không tồn tại.', 'danger');

$lead = $db->prepare("SELECT l.*, u.full_name AS assigned_name FROM leads l LEFT JOIN users u ON l.assigned_to = u.id WHERE l.id = ?")->execute([$id]) ? null : null;
$stmt = $db->prepare("SELECT l.*, u.full_name AS assigned_name FROM leads l LEFT JOIN users u ON l.assigned_to = u.id WHERE l.id = ?");
$stmt->execute([$id]);
$lead = $stmt->fetch();
if (!$lead) redirect('/admin/leads', 'Lead không tồn tại.', 'danger');

// ── Update lead info ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_lead') {
    $name          = trim($_POST['name'] ?? $lead['name']);
    $email         = trim($_POST['email'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $japanese_level= trim($_POST['japanese_level'] ?? '');
    $intake_period = trim($_POST['intake_period'] ?? '');
    $assigned_to   = (int)($_POST['assigned_to'] ?? 0) ?: null;
    $notes         = trim($_POST['notes'] ?? '');

    $new_status = $_POST['status'] ?? $lead['status'];
    $old_status = $lead['status'];

    $stmt = $db->prepare("
        UPDATE leads SET name=?, email=?, phone=?, japanese_level=?, intake_period=?,
            assigned_to=?, notes=?, status=?, last_contact_at=datetime('now','localtime'),
            updated_at=datetime('now','localtime')
        WHERE id=?
    ");
    $stmt->execute([$name, $email ?: null, $phone ?: null, $japanese_level ?: null, $intake_period ?: null, $assigned_to, $notes ?: null, $new_status, $id]);

    if ($new_status !== $old_status) {
        $labels = ['new'=>'Mới','contacted'=>'Đã liên hệ','consulting'=>'Đang tư vấn','applied'=>'Đã nộp hồ sơ','enrolled'=>'Đã nhập học','lost'=>'Mất khách'];
        $stmt = $db->prepare("INSERT INTO lead_activities (lead_id, type, content, old_status, new_status, created_by) VALUES (?, 'status_change', ?, ?, ?, ?)");
        $stmt->execute([$id, "Đổi trạng thái: {$labels[$old_status]} → {$labels[$new_status]}", $old_status, $new_status, $_SESSION['user_id'] ?? null]);
    }

    redirect("/admin/leads/view?id=$id", 'Đã cập nhật thông tin.', 'success');
}

// ── Add activity ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_activity') {
    $type    = in_array($_POST['type'] ?? '', ['note','call','email','meeting']) ? $_POST['type'] : 'note';
    $content = trim($_POST['content'] ?? '');
    if ($content) {
        $stmt = $db->prepare("INSERT INTO lead_activities (lead_id, type, content, created_by) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $type, $content, $_SESSION['user_id'] ?? null]);
        // Update last_contact_at
        $db->prepare("UPDATE leads SET last_contact_at=datetime('now','localtime'), updated_at=datetime('now','localtime') WHERE id=?")->execute([$id]);
    }
    redirect("/admin/leads/view?id=$id", '', '');
}

// ── Delete activity ───────────────────────────────────────────
if (isset($_GET['del_act']) && isAdmin()) {
    $db->prepare("DELETE FROM lead_activities WHERE id = ? AND lead_id = ?")->execute([(int)$_GET['del_act'], $id]);
    redirect("/admin/leads/view?id=$id", '', '');
}

// Fetch activities
$stmt = $db->prepare("
    SELECT a.*, u.full_name AS author FROM lead_activities a
    LEFT JOIN users u ON a.created_by = u.id
    WHERE a.lead_id = ? ORDER BY a.created_at DESC
");
$stmt->execute([$id]);
$activities = $stmt->fetchAll();

// Users for assign
$stmt = $db->prepare("SELECT id, full_name FROM users WHERE status='active' AND role IN ('admin','editor') ORDER BY full_name");
$stmt->execute();
$users = $stmt->fetchAll();

// Source links
$contact_info = null;
$booking_info = null;
if ($lead['source'] === 'contact') {
    $stmt = $db->prepare("SELECT id FROM lead_activities WHERE lead_id=? AND source_table='contact' LIMIT 1");
    $stmt->execute([$id]);
    $src_act = $stmt->fetch();
    if ($src_act) {
        $stmt = $db->prepare("SELECT * FROM contacts WHERE id=?");
        $stmt->execute([$src_act['source_id'] ?? 0]);
        $contact_info = $stmt->fetch();
    }
}

$status_meta = [
    'new'        => ['label'=>'Mới',             'color'=>'bg-sky-50 text-sky-700 border-sky-200'],
    'contacted'  => ['label'=>'Đã liên hệ',      'color'=>'bg-blue-50 text-blue-700 border-blue-200'],
    'consulting' => ['label'=>'Đang tư vấn',     'color'=>'bg-amber-50 text-amber-700 border-amber-200'],
    'applied'    => ['label'=>'Đã nộp hồ sơ',   'color'=>'bg-purple-50 text-purple-700 border-purple-200'],
    'enrolled'   => ['label'=>'Đã nhập học',     'color'=>'bg-green-50 text-green-700 border-green-200'],
    'lost'       => ['label'=>'Mất khách',       'color'=>'bg-slate-100 text-slate-500 border-slate-200'],
];
$act_meta = [
    'note'          => ['icon'=>'bi-sticky',          'color'=>'text-slate-500', 'label'=>'Ghi chú'],
    'call'          => ['icon'=>'bi-telephone-fill',  'color'=>'text-green-600', 'label'=>'Gọi điện'],
    'email'         => ['icon'=>'bi-envelope-fill',   'color'=>'text-blue-600',  'label'=>'Email'],
    'meeting'       => ['icon'=>'bi-camera-video-fill','color'=>'text-purple-600','label'=>'Cuộc gặp'],
    'status_change' => ['icon'=>'bi-arrow-repeat',    'color'=>'text-amber-600', 'label'=>'Đổi trạng thái'],
    'system'        => ['icon'=>'bi-lightning-fill',  'color'=>'text-slate-400', 'label'=>'Hệ thống'],
];
$sm_lead = $status_meta[$lead['status']] ?? $status_meta['new'];
$initials = mb_strtoupper(mb_substr($lead['name'], 0, 1, 'UTF-8'), 'UTF-8');

$page_title = 'Chi tiết lead — ' . htmlspecialchars($lead['name']);
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';
?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-muted mb-6">
    <a href="/admin/leads" class="hover:text-primary transition-colors">Leads</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="text-midnight font-medium"><?php echo htmlspecialchars($lead['name']); ?></span>
</div>

<?php displayFlashMessage(); ?>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

<!-- ── LEFT: Lead info ── -->
<div class="lg:col-span-2 space-y-5">

    <!-- Profile card -->
    <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="bg-primary px-6 py-8 text-center">
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-3">
                <?php echo $initials; ?>
            </div>
            <h2 class="text-white font-bold text-lg font-display"><?php echo htmlspecialchars($lead['name']); ?></h2>
            <span class="inline-block border text-xs font-bold px-3 py-1 rounded-full mt-2 <?php echo $sm_lead['color']; ?>">
                <?php echo $sm_lead['label']; ?>
            </span>
        </div>

        <div class="p-5 space-y-3 text-sm">
            <?php if ($lead['email']): ?>
            <div class="flex items-center gap-3 text-slate-600">
                <i class="bi bi-envelope text-slate-400 w-4 text-center"></i>
                <a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" class="hover:text-primary transition-colors"><?php echo htmlspecialchars($lead['email']); ?></a>
            </div>
            <?php endif; ?>
            <?php if ($lead['phone']): ?>
            <div class="flex items-center gap-3 text-slate-600">
                <i class="bi bi-telephone text-slate-400 w-4 text-center"></i>
                <a href="tel:<?php echo htmlspecialchars($lead['phone']); ?>" class="hover:text-primary transition-colors"><?php echo htmlspecialchars($lead['phone']); ?></a>
            </div>
            <?php endif; ?>
            <?php if ($lead['japanese_level']): ?>
            <div class="flex items-center gap-3 text-slate-600">
                <i class="bi bi-translate text-slate-400 w-4 text-center"></i>
                <span>Tiếng Nhật: <strong><?php echo htmlspecialchars($lead['japanese_level']); ?></strong></span>
            </div>
            <?php endif; ?>
            <?php if ($lead['intake_period']): ?>
            <div class="flex items-center gap-3 text-slate-600">
                <i class="bi bi-calendar-event text-slate-400 w-4 text-center"></i>
                <span>Kỳ nhập học: <strong><?php echo htmlspecialchars($lead['intake_period']); ?></strong></span>
            </div>
            <?php endif; ?>
            <div class="flex items-center gap-3 text-slate-500 text-xs pt-1 border-t border-slate-100">
                <i class="bi bi-clock text-slate-400 w-4 text-center"></i>
                Tạo: <?php echo formatDate($lead['created_at']); ?> · Cập nhật: <?php echo formatDate($lead['updated_at']); ?>
            </div>
        </div>
    </div>

    <!-- Edit form -->
    <div class="bg-white rounded-3xl shadow-soft border border-slate-100 p-6">
        <h3 class="font-bold text-midnight text-sm mb-4 flex items-center gap-2"><i class="bi bi-pencil-square text-primary"></i> Chỉnh sửa</h3>
        <form method="POST" class="space-y-3">
            <input type="hidden" name="action" value="update_lead">
            <?php echo csrfField(); ?>

            <div>
                <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1">Họ tên</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($lead['name']); ?>" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-primary">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($lead['email'] ?? ''); ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1">SĐT</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($lead['phone'] ?? ''); ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-primary">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1">Tiếng Nhật</label>
                    <select name="japanese_level" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-primary">
                        <?php foreach (['','Chưa học','N5','N4','N3','N2 trở lên'] as $jl): ?>
                        <option value="<?php echo $jl; ?>" <?php echo $lead['japanese_level']===$jl?'selected':''; ?>><?php echo $jl ?: 'Chưa chọn'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1">Trạng thái</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-primary">
                        <?php foreach ($status_meta as $k => $v): ?>
                        <option value="<?php echo $k; ?>" <?php echo $lead['status']===$k?'selected':''; ?>><?php echo $v['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1">Kỳ nhập học</label>
                <input type="text" name="intake_period" value="<?php echo htmlspecialchars($lead['intake_period'] ?? ''); ?>" placeholder="VD: Tháng 4/2026"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1">Phụ trách</label>
                <select name="assigned_to" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-primary">
                    <option value="">Chưa phân công</option>
                    <?php foreach ($users as $u): ?>
                    <option value="<?php echo $u['id']; ?>" <?php echo $lead['assigned_to']==$u['id']?'selected':''; ?>><?php echo htmlspecialchars($u['full_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1">Ghi chú nội bộ</label>
                <textarea name="notes" rows="3" placeholder="Thông tin thêm về khách hàng..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-primary resize-none"><?php echo htmlspecialchars($lead['notes'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-xl font-bold text-sm hover:bg-ink transition-colors">Lưu thay đổi</button>
        </form>
    </div>

    <?php if ($contact_info): ?>
    <!-- Source: contact form -->
    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4 text-sm">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Từ form liên hệ #<?php echo $contact_info['id']; ?></p>
        <p class="text-slate-600 whitespace-pre-wrap text-xs"><?php echo htmlspecialchars($contact_info['message'] ?? '(không có nội dung)'); ?></p>
        <a href="/admin/contacts" class="text-xs text-primary hover:underline mt-2 inline-block">Xem tất cả liên hệ →</a>
    </div>
    <?php endif; ?>
</div>

<!-- ── RIGHT: Activity timeline ── -->
<div class="lg:col-span-3 space-y-5">

    <!-- Add activity -->
    <div class="bg-white rounded-3xl shadow-soft border border-slate-100 p-6">
        <h3 class="font-bold text-midnight text-sm mb-4 flex items-center gap-2"><i class="bi bi-plus-circle text-primary"></i> Ghi nhận tương tác</h3>
        <form method="POST" class="space-y-3">
            <input type="hidden" name="action" value="add_activity">
            <?php echo csrfField(); ?>
            <div class="flex gap-2 flex-wrap">
                <?php
                $act_btns = ['note'=>['Ghi chú','bi-sticky','bg-slate-100 text-slate-700'], 'call'=>['Gọi điện','bi-telephone-fill','bg-green-50 text-green-700'], 'email'=>['Email','bi-envelope-fill','bg-blue-50 text-blue-700'], 'meeting'=>['Cuộc gặp/Zoom','bi-camera-video-fill','bg-purple-50 text-purple-700']];
                foreach ($act_btns as $k => [$lbl, $icon, $cls]):
                ?>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="<?php echo $k; ?>" class="hidden peer" <?php echo $k==='note'?'checked':''; ?>>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full <?php echo $cls; ?> peer-checked:ring-2 peer-checked:ring-primary transition-all">
                        <i class="bi <?php echo $icon; ?>"></i> <?php echo $lbl; ?>
                    </span>
                </label>
                <?php endforeach; ?>
            </div>
            <textarea name="content" rows="3" required placeholder="Nội dung tương tác..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary resize-none"></textarea>
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-ink transition-colors">Thêm</button>
        </form>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-3xl shadow-soft border border-slate-100 p-6">
        <h3 class="font-bold text-midnight text-sm mb-5 flex items-center justify-between">
            <span class="flex items-center gap-2"><i class="bi bi-clock-history text-primary"></i> Lịch sử tương tác</span>
            <span class="text-xs text-slate-400 font-normal"><?php echo count($activities); ?> hoạt động</span>
        </h3>

        <?php if (empty($activities)): ?>
        <div class="text-center py-8 text-slate-400">
            <i class="bi bi-chat-square-dots text-4xl text-slate-200 block mb-2"></i>
            <p class="text-sm">Chưa có hoạt động nào</p>
        </div>
        <?php endif; ?>

        <div class="relative">
            <!-- Vertical line -->
            <?php if (count($activities) > 1): ?>
            <div class="absolute left-4 top-4 bottom-4 w-px bg-slate-100"></div>
            <?php endif; ?>

            <div class="space-y-5">
            <?php foreach ($activities as $act):
                $am = $act_meta[$act['type']] ?? $act_meta['note'];
            ?>
            <div class="flex gap-4 relative">
                <!-- Icon dot -->
                <div class="w-8 h-8 rounded-full bg-slate-50 border-2 border-slate-200 flex items-center justify-center flex-shrink-0 relative z-10">
                    <i class="bi <?php echo $am['icon']; ?> <?php echo $am['color']; ?> text-sm"></i>
                </div>
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400"><?php echo $am['label']; ?></span>
                            <?php if ($act['author']): ?>
                            <span class="text-[11px] text-slate-400 ml-2">· <?php echo htmlspecialchars($act['author']); ?></span>
                            <?php endif; ?>
                            <div class="text-[11px] text-slate-400"><?php echo timeAgo($act['created_at']); ?> · <?php echo formatDate($act['created_at'], 'd/m/Y H:i'); ?></div>
                        </div>
                        <?php if (isAdmin() && $act['type'] !== 'system'): ?>
                        <a href="?id=<?php echo $id; ?>&del_act=<?php echo $act['id']; ?>"
                           class="text-slate-300 hover:text-red-400 transition-colors flex-shrink-0"
                           onclick="return confirm('Xóa hoạt động này?')" title="Xóa">
                            <i class="bi bi-x text-lg"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php if ($act['content']): ?>
                    <div class="mt-1.5 bg-slate-50 rounded-xl px-4 py-3 text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">
                        <?php echo htmlspecialchars($act['content']); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>
</div>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
