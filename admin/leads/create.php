<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
requireEditor();

$db = Database::getInstance();

$errors = [];
$data = [
    'name'           => '',
    'email'          => '',
    'phone'          => '',
    'japanese_level' => '',
    'intake_period'  => '',
    'source'         => 'manual',
    'status'         => 'new',
    'assigned_to'    => '',
    'notes'          => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name']           = trim($_POST['name'] ?? '');
    $data['email']          = trim($_POST['email'] ?? '');
    $data['phone']          = trim($_POST['phone'] ?? '');
    $data['japanese_level'] = trim($_POST['japanese_level'] ?? '');
    $data['intake_period']  = trim($_POST['intake_period'] ?? '');
    $data['source']         = $_POST['source'] ?? 'manual';
    $data['status']         = $_POST['status'] ?? 'new';
    $data['assigned_to']    = (int)($_POST['assigned_to'] ?? 0) ?: null;
    $data['notes']          = trim($_POST['notes'] ?? '');

    if (!$data['name']) $errors[] = 'Họ tên không được để trống.';
    if (!$data['email'] && !$data['phone']) $errors[] = 'Cần có ít nhất email hoặc số điện thoại.';
    if ($data['email'] && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';

    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO leads (name, email, phone, japanese_level, intake_period, source, status, assigned_to, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['email'] ?: null,
            $data['phone'] ?: null,
            $data['japanese_level'] ?: null,
            $data['intake_period'] ?: null,
            $data['source'],
            $data['status'],
            $data['assigned_to'],
            $data['notes'] ?: null,
        ]);
        $lead_id = (int)$db->lastInsertId();

        // Log creation activity
        $stmt = $db->prepare("INSERT INTO lead_activities (lead_id, type, content, created_by) VALUES (?, 'system', 'Lead được tạo thủ công', ?)");
        $stmt->execute([$lead_id, $_SESSION['user_id'] ?? null]);

        redirect('/admin/leads/view?id=' . $lead_id, 'Đã tạo lead mới thành công.', 'success');
    }
}

// Load editors/admins for assigned_to dropdown
$stmt = $db->prepare("SELECT id, full_name FROM users WHERE role IN ('admin','editor') ORDER BY full_name");
$stmt->execute();
$staff = $stmt->fetchAll();

$status_options = [
    'new'        => 'Mới',
    'contacted'  => 'Đã liên hệ',
    'consulting' => 'Đang tư vấn',
    'applied'    => 'Đã nộp hồ sơ',
    'enrolled'   => 'Đã nhập học',
    'lost'       => 'Mất khách',
];
$source_options = [
    'manual'  => 'Thủ công',
    'contact' => 'Form liên hệ',
    'booking' => 'Đặt lịch',
];

$page_title = 'Thêm Lead mới';
include dirname(dirname(__DIR__)) . '/includes/admin/header.php';
?>

<div class="flex items-center gap-3 mb-6">
    <a href="/admin/leads" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-midnight font-display">Thêm Lead mới</h1>
        <p class="text-sm text-muted mt-0.5">Nhập thủ công thông tin khách hàng tiềm năng</p>
    </div>
</div>

<?php if ($errors): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 mb-5 text-sm">
    <i class="bi bi-exclamation-circle mr-2"></i>
    <?php echo implode(' &nbsp;·&nbsp; ', array_map('htmlspecialchars', $errors)); ?>
</div>
<?php endif; ?>

<form method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left column: core info -->
    <div class="lg:col-span-2 space-y-5">

        <div class="bg-white rounded-3xl p-6 shadow-soft border border-slate-100">
            <h2 class="text-base font-bold text-midnight mb-4 font-display">Thông tin liên hệ</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Họ và tên <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary"
                        placeholder="Nguyễn Văn A" required>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary"
                        placeholder="example@gmail.com">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Số điện thoại</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($data['phone']); ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary"
                        placeholder="0912 345 678">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Trình độ tiếng Nhật</label>
                    <select name="japanese_level" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary">
                        <option value="">Chưa rõ</option>
                        <?php foreach (['N5','N4','N3','N2','N1','Chưa học'] as $lv): ?>
                        <option value="<?php echo $lv; ?>" <?php echo $data['japanese_level']===$lv?'selected':''; ?>><?php echo $lv; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Kỳ nhập học dự kiến</label>
                    <input type="text" name="intake_period" value="<?php echo htmlspecialchars($data['intake_period']); ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary"
                        placeholder="Tháng 4/2025, Tháng 10/2025...">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-soft border border-slate-100">
            <h2 class="text-base font-bold text-midnight mb-4 font-display">Ghi chú nội bộ</h2>
            <textarea name="notes" rows="5"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary resize-none"
                placeholder="Ghi chú về nhu cầu, nguyện vọng, tình trạng tài chính..."></textarea>
        </div>

    </div>

    <!-- Right column: metadata -->
    <div class="space-y-5">

        <div class="bg-white rounded-3xl p-6 shadow-soft border border-slate-100">
            <h2 class="text-base font-bold text-midnight mb-4 font-display">Phân loại</h2>

            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Trạng thái</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary">
                        <?php foreach ($status_options as $k => $v): ?>
                        <option value="<?php echo $k; ?>" <?php echo $data['status']===$k?'selected':''; ?>><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Nguồn</label>
                    <select name="source" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary">
                        <?php foreach ($source_options as $k => $v): ?>
                        <option value="<?php echo $k; ?>" <?php echo $data['source']===$k?'selected':''; ?>><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Phụ trách</label>
                    <select name="assigned_to" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-primary">
                        <option value="">Chưa phân công</option>
                        <?php foreach ($staff as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo $data['assigned_to']==$s['id']?'selected':''; ?>>
                            <?php echo htmlspecialchars($s['full_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit"
            class="w-full bg-primary text-white py-3 rounded-2xl font-bold text-sm hover:bg-ink transition-colors shadow-soft flex items-center justify-center gap-2">
            <i class="bi bi-person-plus"></i> Tạo Lead
        </button>
        <a href="/admin/leads"
            class="w-full block text-center bg-slate-100 text-slate-600 py-3 rounded-2xl font-semibold text-sm hover:bg-slate-200 transition-colors">
            Huỷ
        </a>

    </div>
</form>

<?php include dirname(dirname(__DIR__)) . '/includes/admin/footer.php'; ?>
