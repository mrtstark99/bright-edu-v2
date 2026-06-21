<?php
require_once dirname(__DIR__) . '/config/config.php';

$db = Database::getInstance();
$error = '';
$success = '';

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    
    if (empty($full_name) || empty($email)) {
        $error = 'Họ tên và email không được để trống.';
    } else {
        // Update basic info
        $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
        if ($stmt->execute([$full_name, $email, $_SESSION['user_id']])) {
            $_SESSION['user_name'] = $full_name;
            $_SESSION['user_email'] = $email;
            $success = 'Đã cập nhật thông tin thành công.';
        }
        
        // Update password if provided
        if (!empty($current_password) && !empty($new_password)) {
            if (verifyPassword($current_password, $user['password'])) {
                $hashed = hashPassword($new_password);
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed, $_SESSION['user_id']]);
                $success = 'Đã cập nhật mật khẩu thành công.';
            } else {
                $error = 'Mật khẩu hiện tại không đúng.';
                $success = '';
            }
        }
    }
    
    // Refresh user data
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}

$page_title = 'Hồ sơ cá nhân';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-midnight font-display">Hồ sơ cá nhân</h1>
</div>

<?php if ($error): ?>
<div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 font-medium border border-red-100 flex items-center gap-3">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span><?php echo $error; ?></span>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="bg-sage-50 text-sage-600 p-4 rounded-xl mb-6 font-medium border border-sage-100 flex items-center gap-3">
    <i class="bi bi-check-circle-fill"></i>
    <span><?php echo $success; ?></span>
</div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden text-center p-8 sticky top-6">
            <div class="w-32 h-32 rounded-full bg-gradient-to-tr from-sky-500 to-sage-500 flex items-center justify-center text-white font-bold text-4xl mx-auto shadow-lg mb-6">
                <?php echo strtoupper(substr($user['full_name'] ?: $user['username'], 0, 1)); ?>
            </div>
            <h2 class="text-xl font-bold text-midnight font-display mb-1"><?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?></h2>
            <p class="text-slate-500 mb-4"><?php echo htmlspecialchars($user['email']); ?></p>
            
            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 uppercase tracking-wider mb-6">
                <?php echo htmlspecialchars($user['role']); ?>
            </div>
            
            <div class="border-t border-slate-100 pt-6">
                <p class="text-sm text-slate-500 text-left mb-2">
                    <span class="font-bold text-slate-700">Tên đăng nhập:</span> @<?php echo htmlspecialchars($user['username']); ?>
                </p>
                <p class="text-sm text-slate-500 text-left mb-2">
                    <span class="font-bold text-slate-700">Ngày tham gia:</span> <?php echo formatDate($user['created_at']); ?>
                </p>
                <p class="text-sm text-slate-500 text-left">
                    <span class="font-bold text-slate-700">Trạng thái:</span> 
                    <span class="text-green-600 font-medium">Hoạt động</span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h6 class="m-0 font-bold text-midnight font-display text-lg">Cập nhật thông tin</h6>
            </div>
            <div class="p-6">
                <form method="POST" action="">
                    <div class="space-y-6">
                        <div>
                            <label for="full_name" class="block text-sm font-bold text-slate-700 mb-2">Họ và tên</label>
                            <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Địa chỉ Email</label>
                            <input type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="pt-6 border-t border-slate-100">
                            <h6 class="font-bold text-midnight font-display text-base mb-6">Đổi mật khẩu (tùy chọn)</h6>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu hiện tại</label>
                                    <input type="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="current_password" name="current_password" placeholder="Nhập để xác nhận đổi mật khẩu">
                                </div>
                                
                                <div>
                                    <label for="new_password" class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu mới</label>
                                    <input type="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors" id="new_password" name="new_password" placeholder="Bỏ trống nếu không muốn đổi">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="bg-midnight hover:bg-slate-800 text-white font-semibold py-3 px-6 rounded-xl transition-colors inline-flex items-center gap-2">
                            <i class="bi bi-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
