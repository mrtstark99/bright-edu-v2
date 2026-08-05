<?php
/**
 * Dynamic XML Sitemap Generator
 */
require_once 'config/config.php';

header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// 1. Static Pages
$static_pages = [
    '',
    '/about',
    '/services',
    '/schools',
    '/courses',
    '/process',
    '/documents',
    '/cost',
    '/blog',
    '/qa',
    '/contact',
    '/privacy',
    '/terms'
];

foreach ($static_pages as $page) {
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . APP_URL . $page . '</loc>' . PHP_EOL;
    echo '    <changefreq>daily</changefreq>' . PHP_EOL;
    echo '    <priority>' . (empty($page) ? '1.0' : '0.8') . '</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}

// 2. Services (5 core programs)
$services = [
    'japanese-language-school',
    'senmon-vocational-school',
    'university-program',
    'scholarship-program',
    'english-track-university'
];
foreach ($services as $slug) {
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . APP_URL . '/services/' . $slug . '</loc>' . PHP_EOL;
    echo '    <changefreq>weekly</changefreq>' . PHP_EOL;
    echo '    <priority>0.7</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}

// 3. Blog Posts from SQLite Database
try {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT slug, updated_at, created_at FROM posts WHERE status = 'published' ORDER BY updated_at DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();
    foreach ($posts as $post) {
        $lastmod = !empty($post['updated_at']) ? date('Y-m-d', strtotime($post['updated_at'])) : date('Y-m-d', strtotime($post['created_at']));
        echo '  <url>' . PHP_EOL;
        echo '    <loc>' . APP_URL . '/blog/' . htmlspecialchars($post['slug']) . '</loc>' . PHP_EOL;
        echo '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
        echo '    <changefreq>weekly</changefreq>' . PHP_EOL;
        echo '    <priority>0.6</priority>' . PHP_EOL;
        echo '  </url>' . PHP_EOL;
    }
} catch (Exception $e) {
    // Fail silently or log error
}

echo '</urlset>';
?>
