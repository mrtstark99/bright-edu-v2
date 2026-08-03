<?php
require_once dirname(__DIR__) . '/config/config.php';

// Check if registration is allowed
if (getSetting('allow_registration', '0') !== '1') {
    redirect('/login', 'Chức năng đăng ký hiện đang bị đóng.', 'danger');
}

// Redirect if already logged in
if (isLoggedIn()) {
    if (isAdmin() || isEditor()) {
        redirect('/admin/dashboard');
    } else {
        redirect('/');
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $csrf_token = $_POST[CSRF_TOKEN_NAME] ?? '';
    
    // Verify CSRF token
    if (!verifyCSRFToken($csrf_token)) {
        $error = 'Invalid security token. Please refresh and try again.';
    } else if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ các thông tin bắt buộc.';
    } else if (!validateEmail($email)) {
        $error = 'Địa chỉ email không hợp lệ.';
    } else if ($password !== $password_confirm) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } else if (strlen($password) < 6) {
        $error = 'Mật khẩu phải chứa ít nhất 6 ký tự.';
    } else {
        $db = Database::getInstance();
        
        // Check if email or username exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = 'Email hoặc Tên đăng nhập đã được sử dụng.';
        } else {
            // Register user
            $hashed_password = hashPassword($password);
            
            $stmt = $db->prepare("INSERT INTO users (full_name, username, email, password, role, status) VALUES (?, ?, ?, ?, 'subscriber', 'active')");
            if ($stmt->execute([$full_name, $username, $email, $hashed_password])) {
                // Log security event
                logSecurityEvent('register_success', 'New user registered: ' . $email);
                
                $success = 'Đăng ký tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.';
            } else {
                $error = 'Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại sau.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - <?php echo APP_NAME; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { 
            sans: ['Inter', 'ui-sans-serif', 'system-ui'],
            display: ['Quicksand', 'ui-sans-serif', 'system-ui']
          },
          colors: {
            primary: {
              DEFAULT: '#0d243e',
              50: '#f2f5f9',
              100: '#e1e8f0',
              200: '#c5d3df',
              300: '#9bb7ca',
              400: '#6b92af',
              500: '#487596',
              600: '#345b7b',
              700: '#2a4964',
              800: '#253e54',
              900: '#0d243e',
            },
            sage: { 50: '#f2f5f9', 100: '#e1e8f0', 200: '#c5d3df', 300: '#9bb7ca', 400: '#345b7b', 500: '#0d243e', 600: '#0d243e', 900: '#0d243e' },
            midnight: '#0d243e', 
          }
        }
      }
    }
    </script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-5%] w-96 h-96 bg-pink-100/50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[30rem] h-[30rem] bg-blue-100/50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    </div>

    <div class="w-full max-w-lg">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-midnight font-display tracking-tight">Bright Education</h1>
            <p class="text-slate-500 mt-2 font-medium">Tạo tài khoản mới</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 sm:p-10">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-800">Đăng ký</h2>
                <p class="text-sm text-slate-500 mt-1">Vui lòng điền thông tin để tạo tài khoản</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm font-medium border border-red-100 flex items-start gap-3">
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium border border-green-100 flex items-start gap-3">
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
                <div class="text-center">
                    <a href="/login" class="inline-block mt-4 bg-midnight text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-slate-800 transition-colors">Đến trang Đăng nhập</a>
                </div>
            <?php else: ?>
                <form method="POST" action="" class="space-y-5">
                    <?php echo csrfField(); ?>
                    
                    <div>
                        <label for="full_name" class="block text-sm font-bold text-slate-700 mb-2">Họ và tên</label>
                        <input type="text" id="full_name" name="full_name" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sage-500 focus:ring-1 focus:ring-sage-500 transition-colors"
                               value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required autofocus>
                    </div>
                    
                    <div>
                        <label for="username" class="block text-sm font-bold text-slate-700 mb-2">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sage-500 focus:ring-1 focus:ring-sage-500 transition-colors"
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Địa chỉ Email</label>
                        <input type="email" id="email" name="email" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sage-500 focus:ring-1 focus:ring-sage-500 transition-colors"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sage-500 focus:ring-1 focus:ring-sage-500 transition-colors"
                                   required>
                        </div>
                        <div>
                            <label for="password_confirm" class="block text-sm font-bold text-slate-700 mb-2">Xác nhận mật khẩu</label>
                            <input type="password" id="password_confirm" name="password_confirm" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-sage-500 focus:ring-1 focus:ring-sage-500 transition-colors"
                                   required>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-midnight hover:bg-slate-800 text-white font-semibold py-3.5 px-4 rounded-xl transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-midnight/20 mt-4">
                        Tạo tài khoản
                    </button>
                    
                    <div class="text-center mt-6">
                        <span class="text-sm text-slate-500">Đã có tài khoản?</span>
                        <a href="/login" class="text-sm font-bold text-sage-600 hover:text-sage-700 ml-1 transition-colors">Đăng nhập</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-8 text-sm font-medium text-slate-400">
            &copy; <?php echo date('Y'); ?> Bright Education. All rights reserved.
        </div>
    </div>
</body>
</html>
