<?php
/**
 * Security Functions
 */

// Generate CSRF token
function generateCSRFToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    if (!isset($_SESSION[CSRF_TOKEN_NAME]) || !hash_equals($_SESSION[CSRF_TOKEN_NAME], $token)) {
        return false;
    }
    return true;
}

// Get CSRF token field
function csrfField() {
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . generateCSRFToken() . '">';
}

// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Check authentication
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Check if user is editor
function isEditor() {
    return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'editor']);
}

// Require authentication
function requireAuth() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: /login');
        exit;
    }
}

// Require admin access
function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        header('HTTP/1.0 403 Forbidden');
        die('Access denied');
    }
}

// Require editor access
function requireEditor() {
    requireAuth();
    if (!isEditor()) {
        header('HTTP/1.0 403 Forbidden');
        die('Access denied');
    }
}

// Rate limiting for login attempts
function checkLoginAttempts($email) {
    $db = Database::getInstance();
    $lockout_key = 'login_attempts_' . md5($email);
    
    if (!isset($_SESSION[$lockout_key])) {
        $_SESSION[$lockout_key] = [
            'attempts' => 0,
            'last_attempt' => time()
        ];
    }
    
    $data = &$_SESSION[$lockout_key];
    
    // Reset attempts if lockout time has passed
    if (time() - $data['last_attempt'] > LOGIN_LOCKOUT_TIME) {
        $data['attempts'] = 0;
    }
    
    // Check if locked out
    if ($data['attempts'] >= MAX_LOGIN_ATTEMPTS) {
        $remaining = LOGIN_LOCKOUT_TIME - (time() - $data['last_attempt']);
        if ($remaining > 0) {
            return [
                'locked' => true,
                'remaining' => $remaining
            ];
        }
        $data['attempts'] = 0;
    }
    
    return ['locked' => false];
}

// Record login attempt
function recordLoginAttempt($email) {
    $lockout_key = 'login_attempts_' . md5($email);
    $_SESSION[$lockout_key]['attempts']++;
    $_SESSION[$lockout_key]['last_attempt'] = time();
}

// Clear login attempts
function clearLoginAttempts($email) {
    $lockout_key = 'login_attempts_' . md5($email);
    unset($_SESSION[$lockout_key]);
}

// XSS Protection
function xss_clean($data) {
    // Fix &entity\n;
    $data = str_replace(['&amp;','&lt;','&gt;'], ['&amp;amp;','&amp;lt;','&amp;gt;'], $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
    
    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
    
    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
    
    return $data;
}

// SQL Injection Protection (using PDO prepared statements)
function escapeSQL($string) {
    return addslashes($string);
}

// File upload security
function validateFileUpload($file, $allowed_types, $max_size = MAX_FILE_SIZE) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Invalid file upload'];
    }
    
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return ['success' => false, 'message' => 'No file sent'];
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['success' => false, 'message' => 'File size limit exceeded'];
        default:
            return ['success' => false, 'message' => 'Unknown upload error'];
    }
    
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File is too large'];
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    // Additional check for image files
    if (in_array($ext, ALLOWED_IMAGE_TYPES)) {
        $image = @getimagesize($file['tmp_name']);
        if ($image === false) {
            return ['success' => false, 'message' => 'Invalid image file'];
        }
    }
    
    return ['success' => true];
}

// Log security events
function logSecurityEvent($action, $details = '') {
    $db = Database::getInstance();
    $user_id = $_SESSION['user_id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $stmt = $db->prepare("INSERT INTO audit_logs (user_id, action, ip_address, user_agent, new_values) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $action, $ip, $user_agent, json_encode(['details' => $details])]);
}
