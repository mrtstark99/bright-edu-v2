<?php
/**
 * AI Agent Integration API Endpoint (Upgraded Version)
 * Implements Token Scopes, Bearer Auth, Rate Limiting, Idempotency, Revision Control,
 * Page Performance analysis, Opportunity calculation, HTML sanitization, and workflow reviews.
 */

require_once dirname(__DIR__) . '/config/config.php';

// Force JSON response header
header('Content-Type: application/json; charset=utf-8');

// Generate a unique Request ID for tracking
$requestId = bin2hex(random_bytes(16));
header('X-Request-ID: ' . $requestId);

// 1. Enforce BEARER-ONLY authentication (reject query-string tokens)
if (isset($_GET['token'])) {
    sendErrorResponse(400, 'Security Constraint: Token parameters in query strings are disabled. Use Authorization Bearer headers instead.');
}

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
if (!$authHeader && function_exists('apache_request_headers')) {
    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
}

$token = '';
if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
    $token = trim($matches[1]);
}

if (!$token) {
    sendErrorResponse(401, 'Unauthorized: Token is missing from Authorization Header.');
}

// 2. Validate Token in DB
$tokenHash = hash('sha256', $token);
$db = Database::getInstance();

$stmt = $db->prepare("SELECT * FROM ai_agent_tokens WHERE token_hash = ? LIMIT 1");
$stmt->execute([$tokenHash]);
$agent = $stmt->fetch();

if (!$agent) {
    sendErrorResponse(401, 'Unauthorized: Invalid token.');
}

// Verify Expiry & Revocation status
if (!empty($agent['expires_at']) && strtotime($agent['expires_at']) < time()) {
    sendErrorResponse(401, 'Unauthorized: Token has expired.');
}
if (!empty($agent['revoked_at'])) {
    sendErrorResponse(401, 'Unauthorized: Token has been revoked.');
}

// 3. IP Allowlist Verification
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
if (!empty($agent['allowed_ips'])) {
    $allowedIps = array_map('trim', explode(',', $agent['allowed_ips']));
    if (!in_array($clientIp, $allowedIps, true)) {
        sendErrorResponse(403, 'Forbidden: IP address ' . $clientIp . ' is not in the allowlist.');
    }
}

// 4. Rate Limiting Check
$currentMinute = date('Y-m-d H:i');
$db->prepare("INSERT OR IGNORE INTO ai_agent_rate_limits (token_id, minute_bucket, request_count) VALUES (?, ?, 0)")
   ->execute([$agent['id'], $currentMinute]);
$db->prepare("UPDATE ai_agent_rate_limits SET request_count = request_count + 1 WHERE token_id = ? AND minute_bucket = ?")
   ->execute([$agent['id'], $currentMinute]);

$stmtLimit = $db->prepare("SELECT request_count FROM ai_agent_rate_limits WHERE token_id = ? AND minute_bucket = ?");
$stmtLimit->execute([$agent['id'], $currentMinute]);
$reqCount = (int)$stmtLimit->fetchColumn();

if ($reqCount > 60) {
    sendErrorResponse(429, 'Too Many Requests: Rate limit exceeded (60 requests per minute).');
}

// Update token audit metrics
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$db->prepare("
    UPDATE ai_agent_tokens 
    SET last_used_at = datetime('now','localtime'), last_ip = ?, last_user_agent = ?, request_count = request_count + 1 
    WHERE id = ?
")->execute([$clientIp, $userAgent, $agent['id']]);

// 5. Idempotency Key Check (For write actions only)
$idempotencyKey = $_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? $_SERVER['REDIRECT_HTTP_IDEMPOTENCY_KEY'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idempotencyKey !== '') {
    $stmtIdem = $db->prepare("SELECT response_payload FROM api_idempotency_keys WHERE idempotency_key = ?");
    $stmtIdem->execute([$idempotencyKey]);
    $cachedResp = $stmtIdem->fetchColumn();
    if ($cachedResp) {
        header('X-Cache-Lookup: HIT - Idempotent Request');
        echo $cachedResp;
        exit;
    }
}

// Helper to log actions
$logAgentAction = static function (string $actionName, ?int $recordId, array $oldVals = [], array $newVals = []) use ($db, $agent, $clientIp, $userAgent): void {
    $stmt = $db->prepare("
        INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
        VALUES (NULL, ?, 'posts', ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        'agent_' . $actionName,
        $recordId,
        $oldVals ? json_encode($oldVals, JSON_UNESCAPED_UNICODE) : null,
        $newVals ? json_encode($newVals, JSON_UNESCAPED_UNICODE) : null,
        $clientIp,
        $userAgent
    ]);
};

// 6. Enforce Token Permissions (Scopes)
$scopes = array_map('trim', explode(',', strtolower($agent['permissions'])));
$hasScope = static function (string $required) use ($scopes): bool {
    return in_array('admin', $scopes, true) || in_array($required, $scopes, true);
};

// Parse inputs
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

try {
    ob_start(); // Buffer to capture response and cache for Idempotency
    
    switch ($action) {
        case 'seo':
            // Scope validation: seo:read
            if (!$hasScope('seo:read')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [seo:read].');
            }

            $stmtClusters = $db->prepare("SELECT * FROM seo_topic_clusters ORDER BY name");
            $stmtClusters->execute();
            $clusters = $stmtClusters->fetchAll();

            $stmtKeywords = $db->prepare("
                SELECT k.*, c.name as cluster_name 
                FROM seo_keyword_map k 
                LEFT JOIN seo_topic_clusters c ON k.cluster_id = c.id 
                ORDER BY k.planning_month DESC, k.priority DESC, k.keyword ASC
            ");
            $stmtKeywords->execute();
            $keywords = $stmtKeywords->fetchAll();

            foreach ($keywords as &$kw) {
                if ($kw['status'] === 'brief') {
                    $kw['status'] = 'planned';
                }
            }
            unset($kw);

            $targets = [
                'organic_sessions' => (float)getSetting('kpi_organic_sessions_target', 0),
                'impressions' => (float)getSetting('kpi_impressions_target', 0),
                'position' => (float)getSetting('kpi_position_target', 0),
                'ctr' => (float)getSetting('kpi_ctr_target', 0),
                'engagement_rate' => (float)getSetting('kpi_engagement_rate_target', 0),
                'avg_engagement_time' => (float)getSetting('kpi_avg_engagement_time_target', 0),
                'conversion_rate' => (float)getSetting('kpi_conversion_rate_target', 0),
                'roi' => (float)getSetting('kpi_roi_target', 0),
            ];

            sendSuccessResponse([
                'clusters' => $clusters,
                'keywords' => $keywords,
                'targets' => $targets
            ]);
            break;

        case 'analytics':
            // Scope validation: analytics:read
            if (!$hasScope('analytics:read')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [analytics:read].');
            }

            require_once APP_ROOT . '/includes/analytics.php';
            $days = (int)($_GET['days'] ?? 28);
            $forceRefresh = isset($_GET['refresh']) && $_GET['refresh'] === '1';
            $report = analyticsDashboardData($days, $forceRefresh);

            sendSuccessResponse($report);
            break;

        case 'page_performance':
            // Scope: analytics:read
            if (!$hasScope('analytics:read')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [analytics:read].');
            }

            require_once APP_ROOT . '/includes/analytics.php';
            $days = (int)($_GET['days'] ?? 28);
            $forceRefresh = isset($_GET['refresh']) && $_GET['refresh'] === '1';
            $report = analyticsPagePerformanceData($days, $forceRefresh);

            sendSuccessResponse($report);
            break;

        case 'opportunities':
            // Scope: analytics:read
            if (!$hasScope('analytics:read')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [analytics:read].');
            }

            require_once APP_ROOT . '/includes/analytics.php';
            $days = (int)($_GET['days'] ?? 28);
            $perfData = analyticsPagePerformanceData($days, false);
            
            $opportunities = [];
            foreach ($perfData['performance'] ?? [] as $item) {
                $url = $item['url'];
                $gsc = $item['gsc'] ?? [];
                $ga4 = $item['ga4'] ?? [];
                
                $clicks = $gsc['clicks'] ?? 0;
                $impressions = $gsc['impressions'] ?? 0;
                $ctr = $gsc['ctr'] ?? 0;
                $position = $gsc['position'] ?? 0;
                $sessions = $ga4['organic_sessions'] ?? 0;
                $engagement = $ga4['engagement_rate'] ?? 1.0;
                $leads = $ga4['leads'] ?? 0;
                $leadRate = $ga4['conversion_rate'] ?? 0;

                // Rule 1: low_ctr (in Top 10 but low click through rate)
                if ($position > 0 && $position <= 10 && $impressions > 200 && $ctr < 0.02) {
                    $score = min(100, (int)round((1 - $ctr) * ($impressions / 200) + 20));
                    $opportunities[] = [
                        'type' => 'low_ctr',
                        'page' => $url,
                        'position' => $position,
                        'impressions' => $impressions,
                        'ctr' => $ctr,
                        'priority_score' => $score,
                        'recommended_action' => 'rewrite_title_description',
                        'details' => 'Trang xếp hạng tốt trong Top 10 nhưng thu hút ít nhấp chuột. Cần viết lại thẻ tiêu đề H1 và Meta Description.'
                    ];
                }

                // Rule 2: striking_distance (vị trí từ 4-15)
                if ($position >= 4 && $position <= 15 && $impressions > 100) {
                    $score = min(100, (int)round((16 - $position) * 5 + ($impressions / 400)));
                    $opportunities[] = [
                        'type' => 'striking_distance',
                        'page' => $url,
                        'position' => $position,
                        'impressions' => $impressions,
                        'ctr' => $ctr,
                        'priority_score' => $score,
                        'recommended_action' => 'optimize_content_and_links',
                        'details' => 'Bài viết gần Top 3. Nên tối ưu hóa cấu trúc Heading, bổ sung FAQ và thêm các liên kết nội bộ từ các trang liên quan.'
                    ];
                }

                // Rule 3: low_conversion (Sessions cao nhưng ít Leads)
                if ($sessions > 50 && $leads === 0) {
                    $score = min(100, (int)round($sessions / 2));
                    $opportunities[] = [
                        'type' => 'low_conversion',
                        'page' => $url,
                        'sessions' => $sessions,
                        'leads' => $leads,
                        'priority_score' => $score,
                        'recommended_action' => 'improve_cta_and_forms',
                        'details' => 'Trang có traffic organic tốt nhưng tỷ lệ gửi form kém. Cần đưa CTA lên đầu trang và tối ưu hóa biểu mẫu.'
                    ];
                }

                // Rule 4: low_engagement (Traffic cao nhưng thoát nhanh)
                if ($sessions > 30 && $engagement < 0.40) {
                    $score = min(100, (int)round((1 - $engagement) * 100));
                    $opportunities[] = [
                        'type' => 'low_engagement',
                        'page' => $url,
                        'sessions' => $sessions,
                        'engagement_rate' => $engagement,
                        'priority_score' => $score,
                        'recommended_action' => 'improve_readability_and_structure',
                        'details' => 'Người dùng vào trang nhưng thoát nhanh. Tránh mở đầu dài dòng, thêm bảng so sánh, mục lục và chia nhỏ heading.'
                    ];
                }
            }

            // Sort opportunities by priority_score descending
            usort($opportunities, static fn($a, $b) => $b['priority_score'] <=> $a['priority_score']);

            sendSuccessResponse($opportunities);
            break;

        case 'posts':
            // Scope validation: posts:read
            if (!$hasScope('posts:read')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:read].');
            }

            $stmtPosts = $db->prepare("
                SELECT p.id, p.title, p.slug, p.excerpt, p.status, p.featured, p.views, p.published_at, p.created_at, p.updated_at,
                       c.name as category_name, u.full_name as author_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.author_id = u.id 
                ORDER BY p.created_at DESC
            ");
            $stmtPosts->execute();
            $posts = $stmtPosts->fetchAll();

            sendSuccessResponse($posts);
            break;

        case 'categories':
            // Scope validation: posts:read
            if (!$hasScope('posts:read')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:read].');
            }

            $stmtCats = $db->prepare("SELECT id, name, slug, description FROM categories ORDER BY name");
            $stmtCats->execute();
            $categories = $stmtCats->fetchAll();

            sendSuccessResponse($categories);
            break;

        case 'create_draft':
            // Scope validation: posts:draft
            if (!$hasScope('posts:draft')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:draft].');
            }

            $title = trim($input['title'] ?? '');
            $content = $input['content'] ?? '';
            $category_id = (int)($input['category_id'] ?? 0);

            if ($title === '' || $content === '' || $category_id <= 0) {
                sendErrorResponse(400, 'Missing required fields: title, content, category_id');
            }

            // Verify category exists
            $stmtCatCheck = $db->prepare("SELECT COUNT(*) FROM categories WHERE id = ?");
            $stmtCatCheck->execute([$category_id]);
            if ((int)$stmtCatCheck->fetchColumn() === 0) {
                sendErrorResponse(400, 'Invalid category_id: Category does not exist.');
            }

            // Sanitize content
            $content = sanitizePostHtml($content);

            // Determine slug
            $slug = trim($input['slug'] ?? '');
            if ($slug === '') {
                $slug = createSlug($title);
            }
            $stmtSlug = $db->prepare("SELECT id FROM posts WHERE slug = ?");
            $stmtSlug->execute([$slug]);
            if ($stmtSlug->fetch()) {
                $slug .= '-' . time();
            }

            $excerpt = trim($input['excerpt'] ?? '');
            if ($excerpt === '') {
                $excerpt = getExcerpt($content);
            }

            $status = 'ai_draft'; // Default review status for AI
            $featured = (int)($input['featured'] ?? 0);
            $meta_title = trim($input['meta_title'] ?? seoTitle($title));
            $meta_description = trim($input['meta_description'] ?? seoDescription($content));
            $meta_keywords = trim($input['meta_keywords'] ?? '');
            $featured_image = $input['featured_image'] ?? null;

            // Determine author assignment
                        $author_id = $agent['default_author_id'];
            if (!$author_id) {
                $stmtAuth = $db->prepare("SELECT id FROM users WHERE role = 'admin' AND status = 'active' LIMIT 1");
                $stmtAuth->execute();
                $author_id = (int)$stmtAuth->fetchColumn() ?: 1;
            }

            // Tiếp nhận ngày xuất bản (lên lịch)
            $published_at = isset($input['published_at']) && trim($input['published_at']) !== '' ? trim($input['published_at']) : null;
            if (($status === 'published' || $status === 'scheduled') && !$published_at) {
                $published_at = date('Y-m-d H:i:s');
            }

            $sqlInsert = "
                INSERT INTO posts (title, slug, excerpt, content, featured_image, category_id, author_id, status, featured, meta_title, meta_description, meta_keywords, published_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmtInsert = $db->prepare($sqlInsert);
            $exec = $stmtInsert->execute([$title, $slug, $excerpt, $content, $featured_image, $category_id, $author_id, $status, $featured, $meta_title, $meta_description, $meta_keywords, $published_at]);

            if ($exec) {
                $newId = $db->lastInsertId();
                
                // Write audit log
                $newVals = ['id' => $newId, 'title' => $title, 'status' => $status];
                $logAgentAction('create_draft', $newId, [], $newVals);

                // Write initial revision record
                $db->prepare("
                    INSERT INTO post_revisions (post_id, title, slug, excerpt, content, meta_title, meta_description, meta_keywords, author_id, action, changed_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'created', ?)
                ")->execute([$newId, $title, $slug, $excerpt, $content, $meta_title, $meta_description, $meta_keywords, $author_id, 'agent_token_' . $agent['id']]);

                sendSuccessResponse([
                    'id' => $newId,
                    'slug' => $slug,
                    'status' => $status,
                    'url' => '/blog/' . $slug
                ], 'Draft created successfully.');
            } else {
                sendErrorResponse(500, 'Failed to insert draft post record.');
            }
            break;

        case 'update_post':
            // Scope validation: posts:draft
            if (!$hasScope('posts:draft')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:draft].');
            }

            $postId = (int)($input['id'] ?? 0);
            if ($postId <= 0) {
                sendErrorResponse(400, 'Missing valid post id.');
            }

            $stmtPost = $db->prepare("SELECT * FROM posts WHERE id = ?");
            $stmtPost->execute([$postId]);
            $post = $stmtPost->fetch();

            if (!$post) {
                sendErrorResponse(404, 'Post not found.');
            }

            // 7. Optimistic Locking Check
            if (isset($input['expected_updated_at'])) {
                if ($post['updated_at'] !== $input['expected_updated_at']) {
                    sendErrorResponse(409, 'Conflict: Post has been modified by another editor. Please reload and try again.');
                }
            }

            $title = trim($input['title'] ?? $post['title']);
            $content = sanitizePostHtml($input['content'] ?? $post['content']);
            $category_id = isset($input['category_id']) ? (int)$input['category_id'] : (int)$post['category_id'];
            $excerpt = isset($input['excerpt']) ? trim($input['excerpt']) : $post['excerpt'];
            
            // Check status validation
            $status = $input['status'] ?? $post['status'];
            $allowedStatuses = ['draft','published','archived','ai_draft','pending_review','changes_requested','approved','scheduled'];
            if (!in_array($status, $allowedStatuses, true)) {
                $status = $post['status'];
            }

            // AI draft workflow validation (regular agent cannot publish directly unless posts:publish scope)
            if ($status === 'published' && !$hasScope('posts:publish')) {
                sendErrorResponse(403, 'Forbidden: Missing scope [posts:publish] required to publish articles directly. Choose status [pending_review] instead.');
            }

            // Validate Category
            $stmtCatCheck = $db->prepare("SELECT COUNT(*) FROM categories WHERE id = ?");
            $stmtCatCheck->execute([$category_id]);
            if ((int)$stmtCatCheck->fetchColumn() === 0) {
                sendErrorResponse(400, 'Invalid category_id.');
            }

            $featured = isset($input['featured']) ? (int)$input['featured'] : (int)$post['featured'];
            $meta_title = isset($input['meta_title']) ? trim($input['meta_title']) : $post['meta_title'];
            $meta_description = isset($input['meta_description']) ? trim($input['meta_description']) : $post['meta_description'];
            $meta_keywords = isset($input['meta_keywords']) ? trim($input['meta_keywords']) : $post['meta_keywords'];
            $featured_image = isset($input['featured_image']) ? $input['featured_image'] : $post['featured_image'];

            // Handle slug changes
            $slug = $post['slug'];
            if ($title !== $post['title'] && (!isset($input['slug']) || trim($input['slug']) === '')) {
                $slug = createSlug($title);
                $stmtSlugCheck = $db->prepare("SELECT id FROM posts WHERE slug = ? AND id <> ?");
                $stmtSlugCheck->execute([$slug, $postId]);
                if ($stmtSlugCheck->fetch()) {
                    $slug .= '-' . time();
                }
            } elseif (isset($input['slug']) && trim($input['slug']) !== '') {
                $slug = createSlug(trim($input['slug']));
            }

            $published_at = $post['published_at'];
            if (isset($input['published_at'])) {
                $published_at = trim($input['published_at']) !== '' ? trim($input['published_at']) : null;
            }
            if (($status === 'published' || $status === 'scheduled') && !$published_at) {
                $published_at = date('Y-m-d H:i:s');
            }

            // 6. Write Revision Record BEFORE modifying post
            $db->prepare("
                INSERT INTO post_revisions (post_id, title, slug, excerpt, content, meta_title, meta_description, meta_keywords, author_id, action, changed_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'updated', ?)
            ")->execute([$postId, $post['title'], $post['slug'], $post['excerpt'], $post['content'], $post['meta_title'], $post['meta_description'], $post['meta_keywords'], $post['author_id'], 'agent_token_' . $agent['id']]);

            $sqlUpdate = "
                UPDATE posts 
                SET title = ?, slug = ?, excerpt = ?, content = ?, featured_image = ?, category_id = ?, status = ?, featured = ?, 
                    meta_title = ?, meta_description = ?, meta_keywords = ?, published_at = ?, updated_at = datetime('now','localtime') 
                WHERE id = ?
            ";
            $stmtUpdate = $db->prepare($sqlUpdate);
            $exec = $stmtUpdate->execute([$title, $slug, $excerpt, $content, $featured_image, $category_id, $status, $featured, $meta_title, $meta_description, $meta_keywords, $published_at, $postId]);

            if ($exec) {
                $logAgentAction('update_post', $postId, $post, ['title' => $title, 'status' => $status]);
                sendSuccessResponse([
                    'id' => $postId,
                    'slug' => $slug,
                    'status' => $status,
                    'url' => '/blog/' . $slug
                ], 'Post updated successfully.');
            } else {
                sendErrorResponse(500, 'Failed to update post record.');
            }
            break;

        case 'submit_for_review':
            // Scope: posts:draft
            if (!$hasScope('posts:draft')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:draft].');
            }

            $postId = (int)($input['id'] ?? 0);
            if ($postId <= 0) {
                sendErrorResponse(400, 'Missing valid post id.');
            }

            $stmtPost = $db->prepare("SELECT status FROM posts WHERE id = ?");
            $stmtPost->execute([$postId]);
            $oldStatus = $stmtPost->fetchColumn();

            if (!$oldStatus) {
                sendErrorResponse(404, 'Post not found.');
            }

            $db->prepare("UPDATE posts SET status = 'pending_review', updated_at = datetime('now','localtime') WHERE id = ?")
               ->execute([$postId]);

            $logAgentAction('submit_for_review', $postId, ['status' => $oldStatus], ['status' => 'pending_review']);
            sendSuccessResponse(['id' => $postId, 'status' => 'pending_review'], 'Post submitted for review successfully.');
            break;

        case 'approve_post':
            // Scope: posts:publish
            if (!$hasScope('posts:publish')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:publish].');
            }

            $postId = (int)($input['id'] ?? 0);
            if ($postId <= 0) {
                sendErrorResponse(400, 'Missing valid post id.');
            }

            $stmtPost = $db->prepare("SELECT status FROM posts WHERE id = ?");
            $stmtPost->execute([$postId]);
            $oldStatus = $stmtPost->fetchColumn();

            if (!$oldStatus) {
                sendErrorResponse(404, 'Post not found.');
            }

            $db->prepare("UPDATE posts SET status = 'approved', updated_at = datetime('now','localtime') WHERE id = ?")
               ->execute([$postId]);

            $logAgentAction('approve_post', $postId, ['status' => $oldStatus], ['status' => 'approved']);
            sendSuccessResponse(['id' => $postId, 'status' => 'approved'], 'Post approved successfully.');
            break;

        case 'publish_post':
            // Scope: posts:publish
            if (!$hasScope('posts:publish')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:publish].');
            }

            $postId = (int)($input['id'] ?? 0);
            if ($postId <= 0) {
                sendErrorResponse(400, 'Missing valid post id.');
            }

            $stmtPost = $db->prepare("SELECT status, published_at FROM posts WHERE id = ?");
            $stmtPost->execute([$postId]);
            $post = $stmtPost->fetch();

            if (!$post) {
                sendErrorResponse(404, 'Post not found.');
            }

            $published_at = $post['published_at'] ?: date('Y-m-d H:i:s');

            $db->prepare("UPDATE posts SET status = 'published', published_at = ?, updated_at = datetime('now','localtime') WHERE id = ?")
               ->execute([$published_at, $postId]);

            $logAgentAction('publish_post', $postId, ['status' => $post['status']], ['status' => 'published']);
            sendSuccessResponse(['id' => $postId, 'status' => 'published'], 'Post published successfully.');
            break;

        case 'list_revisions':
            // Scope: posts:read
            if (!$hasScope('posts:read')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:read].');
            }

            $postId = (int)($_GET['post_id'] ?? 0);
            if ($postId <= 0) {
                sendErrorResponse(400, 'Missing post_id parameters.');
            }

            $stmtRev = $db->prepare("SELECT id, post_id, title, slug, action, changed_by, created_at FROM post_revisions WHERE post_id = ? ORDER BY created_at DESC");
            $stmtRev->execute([$postId]);
            $revisions = $stmtRev->fetchAll();

            sendSuccessResponse($revisions);
            break;

        case 'restore_revision':
            // Scope: posts:draft
            if (!$hasScope('posts:draft')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:draft].');
            }

            $revId = (int)($input['revision_id'] ?? 0);
            if ($revId <= 0) {
                sendErrorResponse(400, 'Missing valid revision_id.');
            }

            $stmtRev = $db->prepare("SELECT * FROM post_revisions WHERE id = ?");
            $stmtRev->execute([$revId]);
            $rev = $stmtRev->fetch();

            if (!$rev) {
                sendErrorResponse(404, 'Revision not found.');
            }

            $postId = $rev['post_id'];
            $stmtPost = $db->prepare("SELECT * FROM posts WHERE id = ?");
            $stmtPost->execute([$postId]);
            $post = $stmtPost->fetch();

            if (!$post) {
                sendErrorResponse(404, 'Associated post not found.');
            }

            // Save current post state as a new revision before reverting
            $db->prepare("
                INSERT INTO post_revisions (post_id, title, slug, excerpt, content, meta_title, meta_description, meta_keywords, author_id, action, changed_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'updated', ?)
            ")->execute([$postId, $post['title'], $post['slug'], $post['excerpt'], $post['content'], $post['meta_title'], $post['meta_description'], $post['meta_keywords'], $post['author_id'], 'restore_rollback_agent']);

            // Restore
            $sqlUpdate = "
                UPDATE posts 
                SET title = ?, slug = ?, excerpt = ?, content = ?, meta_title = ?, meta_description = ?, meta_keywords = ?, updated_at = datetime('now','localtime') 
                WHERE id = ?
            ";
            $db->prepare($sqlUpdate)->execute([$rev['title'], $rev['slug'], $rev['excerpt'], $rev['content'], $rev['meta_title'], $rev['meta_description'], $rev['meta_keywords'], $postId]);

            $logAgentAction('restore_revision', $postId, $post, ['title' => $rev['title']]);
            sendSuccessResponse(['id' => $postId, 'restored_revision' => $revId], 'Post restored to revision successfully.');
            break;

        case 'process_draft':
            // Scope: posts:draft
            if (!$hasScope('posts:draft')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:draft].');
            }

            $rawContent = $input['content'] ?? '';
            $title = trim($input['title'] ?? '');

            if ($title === '') {
                sendErrorResponse(400, 'Title is required for processing draft.');
            }

            $suggestedSlug = createSlug($title);
            $suggestedTitle = seoTitle($title);
            $suggestedDescription = seoDescription($rawContent, $title);
            $tocResult = buildPostTableOfContents($rawContent);

            // Clean Vietnamese word count estimation
            $cleanText = preg_replace('/\s+/', ' ', trim(strip_tags($rawContent)));
            $wordCount = $cleanText === '' ? 0 : count(explode(' ', $cleanText));
            $readingTime = (int)max(1, ceil($wordCount / 200));

            $h2Count = preg_match_all('/<h2[^>]*>/i', $rawContent);
            $h3Count = preg_match_all('/<h3[^>]*>/i', $rawContent);

            $stmtKeywordsList = $db->prepare("SELECT keyword, intent, target_url FROM seo_keyword_map");
            $stmtKeywordsList->execute();
            $allKeywords = $stmtKeywordsList->fetchAll();

            $matchedKeywords = [];
            foreach ($allKeywords as $kw) {
                $kwText = $kw['keyword'];
                if (mb_stripos($rawContent, $kwText) !== false || mb_stripos($title, $kwText) !== false) {
                    $matchedKeywords[] = $kw;
                }
            }

            sendSuccessResponse([
                'slug' => $suggestedSlug,
                'seo_title' => $suggestedTitle,
                'seo_description' => $suggestedDescription,
                'toc' => $tocResult['items'],
                'metrics' => [
                    'word_count' => $wordCount,
                    'reading_time_minutes' => $readingTime,
                    'h2_count' => $h2Count,
                    'h3_count' => $h3Count,
                ],
                'matched_keywords' => $matchedKeywords,
            ]);
            break;

        case 'validate_post':
            // Scope: posts:draft
            if (!$hasScope('posts:draft')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:draft].');
            }

            $rawContent = $input['content'] ?? '';
            $title = trim($input['title'] ?? '');
            $metaTitle = trim($input['meta_title'] ?? '');
            $metaDesc = trim($input['meta_description'] ?? '');
            $slug = trim($input['slug'] ?? '');

            $errorsList = [];
            $warningsList = [];
            $passed = true;

            // Content audits
            $cleanText = preg_replace('/\s+/', ' ', trim(strip_tags($rawContent)));
            $wordCount = $cleanText === '' ? 0 : count(explode(' ', $cleanText));
            
            if ($wordCount < 300) {
                $warningsList[] = 'Độ dài bài viết khá ngắn (' . $wordCount . ' từ). Các bài viết chi tiết chuẩn SEO thường trên 600 từ.';
            }

            $h2Count = preg_match_all('/<h2[^>]*>/i', $rawContent);
            if ($h2Count === 0) {
                $warningsList[] = 'Bài viết không có thẻ H2. Cần phân chia bố cục rõ ràng để tăng tính đọc hiểu.';
            }

            // Script/XSS injection audits
            if (preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $rawContent)) {
                $errorsList[] = 'An toàn bảo mật: Phát hiện mã <script> độc hại trong bài viết. Vui lòng gỡ bỏ.';
                $passed = false;
            }

            if (preg_match('/on\w+\s*=\s*["\'](.*?)["\']/is', $rawContent)) {
                $errorsList[] = 'An toàn bảo mật: Phát hiện các mã xử lý sự kiện Javascript (inline event handlers) trong HTML.';
                $passed = false;
            }

            // SEO Metadata audits
            if ($title === '') {
                $errorsList[] = 'SEO: Tiêu đề bài viết bị trống.';
                $passed = false;
            }

            if (mb_strlen($metaTitle) > 60) {
                $warningsList[] = 'SEO: Thẻ Meta Title dài hơn 60 ký tự (' . mb_strlen($metaTitle) . ' ký tự). Có thể bị hiển thị cắt bỏ trên Google Search.';
            }

            if (mb_strlen($metaDesc) > 155) {
                $warningsList[] = 'SEO: Thẻ Meta Description dài hơn 155 ký tự (' . mb_strlen($metaDesc) . ' ký tự). Có thể bị hiển thị cắt bỏ.';
            }

            if ($slug !== '') {
                // Check if slug contains special characters
                if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
                    $errorsList[] = 'SEO: Slug chứa ký tự đặc biệt hoặc chữ hoa. Chỉ cho phép chữ thường, số và gạch ngang.';
                    $passed = false;
                }
            }

            sendSuccessResponse([
                'passed' => $passed,
                'score' => max(0, 100 - (count($errorsList) * 20) - (count($warningsList) * 5)),
                'errors' => $errorsList,
                'warnings' => $warningsList,
                'checks' => [
                    'word_count' => $wordCount,
                    'h2_count' => $h2Count,
                    'has_scripts' => preg_match('/<script/i', $rawContent) ? true : false,
                ]
            ]);
            break;

        case 'create_keyword':
            // Scope validation: seo:write
            if (!$hasScope('seo:write')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [seo:write].');
            }

            $planning_month = trim($input['planning_month'] ?? '');
            $keyword = trim($input['keyword'] ?? '');

            if ($planning_month === '' || $keyword === '') {
                sendErrorResponse(400, 'Missing required fields: planning_month, keyword');
            }

            // Check if already exists
            $stmtCheck = $db->prepare("SELECT id FROM seo_keyword_map WHERE planning_month = ? AND keyword = ?");
            $stmtCheck->execute([$planning_month, $keyword]);
            if ($stmtCheck->fetch()) {
                sendErrorResponse(400, 'Conflict: Keyword already exists in this planning month.');
            }

            $intent = $input['intent'] ?? 'informational';
            if (!in_array($intent, ['informational', 'navigational', 'commercial', 'transactional'], true)) {
                $intent = 'informational';
            }

            $content_role = $input['content_role'] ?? 'satellite';
            if (!in_array($content_role, ['pillar', 'satellite', 'standalone'], true)) {
                $content_role = 'satellite';
            }

            $priority = $input['priority'] ?? 'medium';
            if (!in_array($priority, ['high', 'medium', 'low'], true)) {
                $priority = 'medium';
            }

            $notes = $input['notes'] ?? null;
            $cluster_id = isset($input['cluster_id']) ? (int)$input['cluster_id'] : null;

            $stmtInsert = $db->prepare("
                INSERT INTO seo_keyword_map (planning_month, keyword, intent, content_role, priority, notes, cluster_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $exec = $stmtInsert->execute([$planning_month, $keyword, $intent, $content_role, $priority, $notes, $cluster_id]);

            if ($exec) {
                $newId = $db->lastInsertId();
                sendSuccessResponse([
                    'id' => (int)$newId,
                    'planning_month' => $planning_month,
                    'keyword' => $keyword,
                    'status' => 'idea'
                ], 'Keyword created successfully.');
            } else {
                sendErrorResponse(500, 'Failed to insert keyword record.');
            }
            break;

        case 'update_keyword':
            // Scope validation: seo:write
            if (!$hasScope('seo:write')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [seo:write].');
            }

            $id = (int)($input['id'] ?? 0);
            if ($id <= 0) {
                sendErrorResponse(400, 'Missing valid keyword id.');
            }

            // Verify keyword exists
            $stmtCheck = $db->prepare("SELECT * FROM seo_keyword_map WHERE id = ?");
            $stmtCheck->execute([$id]);
            $keywordRow = $stmtCheck->fetch();
            if (!$keywordRow) {
                sendErrorResponse(404, 'Keyword not found.');
            }

            $fieldsToUpdate = [];
            $params = [];

            if (isset($input['status'])) {
                $status = $input['status'];
                if ($status === 'planned') {
                    $status = 'brief';
                }
                if (!in_array($status, ['idea', 'brief', 'writing', 'published'], true)) {
                    sendErrorResponse(400, 'Invalid status. Allowed values: idea, planned, writing, published');
                }
                $fieldsToUpdate[] = "status = ?";
                $params[] = $status;
            }

            if (isset($input['target_url'])) {
                $fieldsToUpdate[] = "target_url = ?";
                $params[] = trim($input['target_url']);
            }

            if (isset($input['priority'])) {
                $priority = $input['priority'];
                if (!in_array($priority, ['high', 'medium', 'low'], true)) {
                    sendErrorResponse(400, 'Invalid priority. Allowed values: high, medium, low');
                }
                $fieldsToUpdate[] = "priority = ?";
                $params[] = $priority;
            }

            if (empty($fieldsToUpdate)) {
                sendErrorResponse(400, 'No fields to update.');
            }

            $params[] = $id;

            $sql = "UPDATE seo_keyword_map SET " . implode(", ", $fieldsToUpdate) . ", updated_at = datetime('now','localtime') WHERE id = ?";
            $stmtUpdate = $db->prepare($sql);
            $exec = $stmtUpdate->execute($params);

            if ($exec) {
                // Return updated row
                $stmtCheck->execute([$id]);
                $updatedRow = $stmtCheck->fetch();
                // Map brief back to planned in output
                if ($updatedRow['status'] === 'brief') {
                    $updatedRow['status'] = 'planned';
                }
                // Cast types
                $updatedRow['id'] = (int)$updatedRow['id'];
                if ($updatedRow['cluster_id'] !== null) {
                    $updatedRow['cluster_id'] = (int)$updatedRow['cluster_id'];
                }
                sendSuccessResponse($updatedRow, 'Keyword updated successfully.');
            } else {
                sendErrorResponse(500, 'Failed to update keyword.');
            }
            break;

        case 'delete_keyword':
            // Scope validation: seo:write
            if (!$hasScope('seo:write')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [seo:write].');
            }

            $id = (int)($input['id'] ?? 0);
            if ($id <= 0) {
                sendErrorResponse(400, 'Missing valid keyword id.');
            }

            // Verify keyword exists
            $stmtCheck = $db->prepare("SELECT id FROM seo_keyword_map WHERE id = ?");
            $stmtCheck->execute([$id]);
            if (!$stmtCheck->fetch()) {
                sendErrorResponse(404, 'Keyword not found.');
            }

            $stmtDelete = $db->prepare("DELETE FROM seo_keyword_map WHERE id = ?");
            $exec = $stmtDelete->execute([$id]);

            if ($exec) {
                sendSuccessResponse(['id' => $id], 'Keyword deleted successfully.');
            } else {
                sendErrorResponse(500, 'Failed to delete keyword.');
            }
            break;

        case 'upload_image':
            // Scope validation: posts:draft or seo:write
            if (!$hasScope('posts:draft') && !$hasScope('seo:write')) {
                sendErrorResponse(403, 'Forbidden: Missing required scope [posts:draft] or [seo:write].');
            }

            // Case 1: Upload a file via multipart form-data ($_FILES)
            if (isset($_FILES['image'])) {
                $upload_res = uploadImage($_FILES['image'], 'images');
                if ($upload_res['success']) {
                    sendSuccessResponse([
                        'filename' => $upload_res['filename'],
                        'filepath' => $upload_res['filepath'],
                        'url' => $upload_res['url']
                    ], 'Image uploaded successfully.');
                } else {
                    sendErrorResponse(400, 'Image upload failed: ' . $upload_res['message']);
                }
            } 
            // Case 2: Save a remote image via image_url
            elseif (!empty($input['image_url'])) {
                $url = trim($input['image_url']);
                if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                    sendErrorResponse(400, 'Invalid image_url format.');
                }

                $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
                if (!in_array($ext, ALLOWED_IMAGE_TYPES, true)) {
                    $ext = 'jpg';
                }

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $imgData = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode !== 200 || !$imgData) {
                    sendErrorResponse(400, 'Failed to download image from the provided image_url.');
                }

                $upload_dir = UPLOAD_PATH . 'images/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $filename = uniqid() . '_' . time() . '.' . $ext;
                $filepath = $upload_dir . $filename;

                if (file_put_contents($filepath, $imgData) === false) {
                    sendErrorResponse(500, 'Failed to save downloaded image on the server.');
                }

                sendSuccessResponse([
                    'filename' => $filename,
                    'filepath' => 'images/' . $filename,
                    'url' => UPLOAD_URL . 'images/' . $filename
                ], 'Image downloaded and saved successfully.');
            } else {
                sendErrorResponse(400, 'Missing image file or image_url.');
            }
            break;

        default:
            sendErrorResponse(400, 'Invalid action parameter.');
            break;
    }

    // Capture response and save for Idempotency
    $respPayload = ob_get_clean();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idempotencyKey !== '') {
        $db->prepare("INSERT OR REPLACE INTO api_idempotency_keys (idempotency_key, response_payload) VALUES (?, ?)")
           ->execute([$idempotencyKey, $respPayload]);
    }
    echo $respPayload;

} catch (Exception $e) {
    ob_end_clean(); // Clear buffer in case of failure
    sendErrorResponse(500, 'An internal server error occurred while processing your request.');
}

// Global API payload helpers to structure JSON output
function sendErrorResponse(int $statusCode, string $message): void {
    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'error' => $message
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function sendSuccessResponse($data, string $message = ''): void {
    $resp = ['success' => true, 'data' => $data];
    if ($message !== '') {
        $resp['message'] = $message;
    }
    $payload = json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    // Save idempotency key if present in POST request
    $idempotencyKey = $_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? $_SERVER['REDIRECT_HTTP_IDEMPOTENCY_KEY'] ?? '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idempotencyKey !== '') {
        $db = Database::getInstance();
        $db->prepare("INSERT OR REPLACE INTO api_idempotency_keys (idempotency_key, response_payload) VALUES (?, ?)")
           ->execute([$idempotencyKey, $payload]);
    }
    
    echo $payload;
    exit;
}

function sanitizePostHtml(string $html): string {
    // Strip scripts
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
    // Strip inline event listeners
    $html = preg_replace('/on\w+\s*=\s*["\']?(.*?)["\']?/is', '', $html);
    // Strip javascript: URLs in href (allowing quotes, backslashes, or no quotes)
    $html = preg_replace('/href\s*=\s*[\"\'\\\\]*\s*javascript:.*?([\"\'\\\\]|$)/is', 'href="#"', $html);
    return $html;
}
