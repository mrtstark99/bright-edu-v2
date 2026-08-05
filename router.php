<?php
/**
 * Router for PHP Built-in Server
 * Replaces .htaccess rewrite rules when running: php -S localhost:8000
 *
 * Usage: php -S localhost:8000 -t /path/to/project router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files (images, CSS, JS, fonts) directly
$staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico',
                     'svg', 'woff', 'woff2', 'ttf', 'eot', 'map', 'pdf'];
$ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));

if (in_array($ext, $staticExtensions)) {
    $filePath = __DIR__ . $uri;
    if (file_exists($filePath)) {
        return false; // Let PHP built-in server serve the static file
    }
}

// Block sensitive directories/files
$blocked = ['/config/', '/database/', '/includes/', '/.htaccess'];
foreach ($blocked as $b) {
    if (strpos($uri, $b) === 0) {
        http_response_code(403);
        echo '403 Forbidden';
        return true;
    }
}

// Route sitemap.xml to sitemap.php
if ($uri === '/sitemap.xml') {
    require __DIR__ . '/sitemap.php';
    return true;
}

// Serve robots.txt directly
if ($uri === '/robots.txt') {
    $filePath = __DIR__ . '/robots.txt';
    if (file_exists($filePath)) {
        header('Content-Type: text/plain; charset=utf-8');
        readfile($filePath);
        return true;
    }
}

// If it's a real PHP file, serve it directly
if ($ext === 'php') {
    $filePath = __DIR__ . $uri;
    if (file_exists($filePath)) {
        return false; // Serve the PHP file directly
    }
}

// Route everything else through index.php
$_GET['url'] = ltrim($uri, '/');
require __DIR__ . '/index.php';
return true;
