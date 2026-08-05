<?php
require_once dirname(__DIR__) . '/config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    if (isAdmin() || isEditor()) {
        redirect('/admin/dashboard');
    } else {
        redirect('/');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST[CSRF_TOKEN_NAME] ?? '';
    
    // Verify CSRF token
    if (!verifyCSRFToken($csrf_token)) {
        $error = 'Invalid security token. Please refresh and try again.';
    } else if (empty($email) || empty($password)) {
        $error = 'Email và mật khẩu không được để trống.';
    } else {
        // Check login attempts
        $lockout = checkLoginAttempts($email);
        if ($lockout['locked']) {
            $minutes = ceil($lockout['remaining'] / 60);
            $error = "Tài khoản đã bị khóa. Vui lòng thử lại sau $minutes phút.";
        } else {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT id, username, email, password, full_name, role, status FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && verifyPassword($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    $error = 'Tài khoản của bạn đã bị vô hiệu hóa.';
                    recordLoginAttempt($email);
                } else {
                    // Clear login attempts
                    clearLoginAttempts($email);
                    
                    // Set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_role'] = $user['role'];
                    
                    // Log successful login
                    logSecurityEvent('login_success', 'User logged in: ' . $email);
                    
                    // Redirect
                    $redirect = $_SESSION['redirect_after_login'] ?? '';
                    unset($_SESSION['redirect_after_login']);
                    if (empty($redirect)) {
                        $redirect = (in_array($user['role'], ['admin', 'editor'])) ? '/admin/dashboard' : '/';
                    }
                    redirect($redirect);
                }
            } else {
                $error = 'Email hoặc mật khẩu không đúng.';
                recordLoginAttempt($email);
                logSecurityEvent('login_failed', 'Failed login attempt: ' . $email);
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
    <title>Đăng nhập - <?php echo APP_NAME; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
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
            sakura: { 50: '#f2f5f9', 100: '#e1e8f0', 200: '#c5d3df', 300: '#9bb7ca', 400: '#345b7b', 500: '#0d243e', 600: '#0d243e', 900: '#0d243e' },
            sky: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7' },
            amber: { 50: '#fffbeb', 100: '#fef3c7', 500: '#f59e0b', 600: '#d97706' },
            sand: { 50: '#ffffff', 100: '#f8fafc', 200: '#e2e8f0' },
            midnight: '#0d243e', 
            ink: '#111827',
            muted: '#6b7280',
            rice: '#ffffff'
          },
          boxShadow: {
            'soft': '0 4px 20px -2px rgba(1, 53, 103, 0.05)',
            'medium': '0 12px 32px -4px rgba(1, 53, 103, 0.08)',
            'hard': '0 24px 48px -12px rgba(1, 53, 103, 0.12)',
            'tinted': '0 20px 40px -8px rgba(1, 53, 103, 0.15)',
          },
          borderRadius: {
            '4xl': '2rem',
            '5xl': '2.5rem',
            'blob': '40% 60% 70% 30% / 40% 50% 60% 50%',
          }
        }
      }
    }
    </script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased">
    
    <!-- Decorative Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-5%] w-96 h-96 bg-sakura-100/50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[30rem] h-[30rem] bg-sage-100/50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    </div>

    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <a href="/">
                <img src="/assets/images/logo.svg" alt="Bright Education" class="h-16 w-auto mx-auto transition-transform hover:scale-105">
            </a>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-8 sm:p-10">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-800">Đăng nhập</h2>
                <p class="text-sm text-slate-500 mt-1">Vui lòng nhập thông tin để tiếp tục</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm font-medium border border-red-100 flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="space-y-6">
                <?php echo csrfField(); ?>
                
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Địa chỉ Email</label>
                    <input type="email" id="email" name="email" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-sage-500 focus:ring-1 focus:ring-sage-500 transition-colors placeholder-slate-400"
                           placeholder="Email"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required autofocus>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu</label>
                    <input type="password" id="password" name="password" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-sage-500 focus:ring-1 focus:ring-sage-500 transition-colors placeholder-slate-400"
                           placeholder="••••••••"
                           required>
                </div>
                
                <div>
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                </div>
                
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-300 text-sage-500 focus:ring-sage-500 transition-colors">
                        <span class="text-sm font-medium text-slate-600 group-hover:text-midnight transition-colors">Ghi nhớ tôi</span>
                    </label>
                    
                    <a href="/forgot-password" class="text-sm font-bold text-sage-600 hover:text-sage-700 transition-colors">Quên mật khẩu?</a>
                </div>
                
                <button type="submit" class="w-full bg-midnight hover:bg-slate-800 text-white font-semibold py-3.5 px-4 rounded-xl transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-midnight/20">
                    Đăng nhập hệ thống
                </button>
 
                <div class="text-center mt-6">
                    <span class="text-sm text-slate-500">Chưa có tài khoản?</span>
                    <a href="/register" class="text-sm font-bold text-sage-600 hover:text-sage-700 ml-1 transition-colors">Đăng ký ngay</a>
                </div>
            </form>
        </div>
        
        <!-- Back to Home Link -->
        <div class="text-center mt-6">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-midnight transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại trang chủ
            </a>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8 text-sm font-medium text-slate-400">
            &copy; <?php echo date('Y'); ?> Bright Education. All rights reserved.
        </div>
    </div>
</body>
</html>
