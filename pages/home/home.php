<?php
/**
 * HOME PAGE – Controller
 * Data queries + include sections
 */
require_once __DIR__ . '/../../config/config.php';

$db = Database::getInstance();

// Get featured posts
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name 
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published' AND p.featured = 1
    ORDER BY p.published_at DESC
    LIMIT 3
");
$stmt->execute();
$featured_posts = $stmt->fetchAll();

// Get latest posts
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name 
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published'
    ORDER BY p.published_at DESC
    LIMIT 6
");
$stmt->execute();
$latest_posts = $stmt->fetchAll();

// Get active group consultation slots (upcoming)
$stmt = $db->prepare("
    SELECT * FROM consultation_slots
    WHERE type = 'group' AND status IN ('active','full')
      AND scheduled_date >= date('now','localtime')
    ORDER BY scheduled_date ASC, time_start ASC
    LIMIT 3
");
$stmt->execute();
$zoom_slots = $stmt->fetchAll();

// Get active announcements
$stmt = $db->prepare("
    SELECT * FROM announcements 
    WHERE status = 'active' 
    AND (start_date IS NULL OR start_date <= datetime('now','localtime'))
    AND (end_date IS NULL OR end_date >= datetime('now','localtime'))
    ORDER BY priority DESC, created_at DESC
    LIMIT 1
");
$stmt->execute();
$announcement = $stmt->fetch();

include __DIR__ . '/../../includes/header.php';
?>

  <main id="hero">
    <?php include __DIR__ . '/sections/hero.php'; ?>
    <?php include __DIR__ . '/sections/programs.php'; ?>
    <?php include __DIR__ . '/sections/process_steps.php'; ?>
    <?php include __DIR__ . '/sections/info_portal.php'; ?>
    <?php include __DIR__ . '/sections/cost_calculator.php'; ?>
    <?php include __DIR__ . '/sections/blog_preview.php'; ?>
    <?php include __DIR__ . '/sections/zoom_sessions.php'; ?>
    <?php include __DIR__ . '/sections/contact_form.php'; ?>
    <?php include __DIR__ . '/sections/scrollspy.php'; ?>
  </main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
