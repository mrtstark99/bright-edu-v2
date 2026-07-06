<?php
/**
 * Main Application Entry Point
 */

require_once 'config/config.php';

// Get request URL
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url_parts = explode('/', $url);

// Define routes
$routes = [
    '' => 'pages/home.php',
    'home' => 'pages/home.php',
    'about' => 'pages/about.php',
    'kham-pha' => 'pages/kham-pha.php',
    'services' => 'pages/services.php',
    'schools' => 'pages/schools.php',
    'courses' => 'pages/courses.php',
    'process' => 'pages/process.php',
    'documents' => 'pages/documents.php',
    'cost' => 'pages/cost.php',
    'pricing' => 'pages/pricing.php',
    'scholarships' => 'pages/scholarships.php',
    'visa' => 'pages/visa.php',
    'testimonials' => 'pages/testimonials.php',
    'stories' => 'pages/testimonials.php',
    'blog' => 'pages/blog.php',
    'groups' => 'pages/groups.php',
    'consultation' => 'pages/consultation.php',
    'contact' => 'pages/contact.php',
    'login' => 'auth/login.php',
    'register' => 'auth/register.php',
    'logout' => 'auth/logout.php',
    'forgot-password' => 'auth/forgot-password.php',
    'reset-password' => 'auth/reset-password.php',
    'qa' => 'pages/qa.php'
];

// Admin routes
$admin_routes = [
    'admin' => 'admin/dashboard.php',
    'admin/dashboard' => 'admin/dashboard.php',
    'admin/posts' => 'admin/posts/index.php',
    'admin/posts/create' => 'admin/posts/create.php',
    'admin/posts/edit' => 'admin/posts/edit.php',
    'admin/categories' => 'admin/categories.php',
    'admin/services' => 'admin/services/index.php',
    'admin/services/create' => 'admin/services/create.php',
    'admin/services/edit' => 'admin/services/edit.php',
    'admin/scholarships' => 'admin/scholarships/index.php',
    'admin/scholarships/create' => 'admin/scholarships/create.php',
    'admin/scholarships/edit' => 'admin/scholarships/edit.php',
    'admin/testimonials' => 'admin/testimonials/index.php',
    'admin/testimonials/create' => 'admin/testimonials/create.php',
    'admin/testimonials/edit' => 'admin/testimonials/edit.php',
    'admin/partners' => 'admin/partners/index.php',
    'admin/partners/create' => 'admin/partners/create.php',
    'admin/partners/edit' => 'admin/partners/edit.php',
    'admin/users' => 'admin/users.php',
    'admin/contacts' => 'admin/contacts.php',
    'admin/announcements' => 'admin/announcements.php',
    'admin/groups' => 'admin/groups.php',
    'admin/consultations' => 'admin/consultations/index.php',
    'admin/consultations/slots' => 'admin/consultations/slots.php',
    'admin/leads' => 'admin/leads/index.php',
    'admin/leads/view' => 'admin/leads/view.php',
    'admin/leads/create' => 'admin/leads/create.php',
    'admin/settings' => 'admin/settings.php',
    'admin/profile' => 'admin/profile.php'
];

// API routes
if ($url_parts[0] === 'api') {
    header('Content-Type: application/json');
    $api_file = 'api/' . ($url_parts[1] ?? 'index') . '.php';
    if (file_exists($api_file)) {
        require_once $api_file;
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'API endpoint not found']);
    }
    exit;
}

// Check for blog post slug
if ($url_parts[0] === 'blog' && isset($url_parts[1])) {
    $_GET['slug'] = $url_parts[1];
    require_once 'pages/blog-single.php';
    exit;
}

// Check for service slug
if ($url_parts[0] === 'services' && isset($url_parts[1])) {
    $_GET['slug'] = $url_parts[1];
    require_once 'pages/service-single.php';
    exit;
}

// Handle admin routes
if (strpos($url, 'admin') === 0) {
    requireAuth();
    if (!isAdmin() && !isEditor()) {
        header('HTTP/1.0 403 Forbidden');
        require_once 'pages/403.php';
        exit;
    }
    
    if (isset($admin_routes[$url])) {
        require_once $admin_routes[$url];
    } else {
        header('HTTP/1.0 404 Not Found');
        require_once 'pages/404.php';
    }
    exit;
}

// Handle public routes
if (isset($routes[$url])) {
    require_once $routes[$url];
} else {
    header('HTTP/1.0 404 Not Found');
    require_once 'pages/404.php';
}
?>
