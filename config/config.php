<?php
/**
 * Application Configuration
 */

session_start();

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Application settings
define('APP_NAME', 'Bright Education');
define('APP_URL', 'http://localhost:4000');
define('APP_ROOT', dirname(dirname(__FILE__)));
define('UPLOAD_PATH', APP_ROOT . '/uploads/');
define('UPLOAD_URL', APP_URL . '/uploads/');

// Security settings
define('SECURE_AUTH_KEY', 'b8c7d3f2a1e4h6g9j5k8l2m4n7p0q3r6s9t2v5w8x1y4z7');
define('SESSION_LIFETIME', 7200); // 2 hours
define('CSRF_TOKEN_NAME', 'csrf_token');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// Email settings
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');
define('SMTP_FROM_EMAIL', 'noreply@brightconnect.vn');
define('SMTP_FROM_NAME', 'Bright Education');

// SEO settings
define('DEFAULT_META_TITLE', 'Bright Education | Du học Nhật Bản');
define('DEFAULT_META_DESC', 'Bright Education đồng hành cùng học viên Việt Nam du học Nhật Bản');
define('DEFAULT_META_KEYWORDS', 'du học nhật bản, bright education, học bổng nhật bản');

// Pagination
define('POSTS_PER_PAGE', 12);
define('ADMIN_PER_PAGE', 20);

// Upload limits
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOC_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx']);

// Include database config
require_once 'database.php';

// Include helper functions
require_once APP_ROOT . '/includes/functions.php';
require_once APP_ROOT . '/includes/security.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_ROOT . '/models/',
        APP_ROOT . '/controllers/',
        APP_ROOT . '/core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
