<?php
require_once dirname(__DIR__) . '/config/config.php';

// Log logout event
if (isset($_SESSION['user_email'])) {
    logSecurityEvent('logout', 'User logged out: ' . $_SESSION['user_email']);
}

// Destroy session
session_destroy();

// Redirect to login
redirect('/login', 'Bạn đã đăng xuất thành công.', 'success');
