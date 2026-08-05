<?php
/**
 * Helper Functions
 */

// Create SEO friendly slug
function createSlug($string) {
    $slug = mb_strtolower($string, 'UTF-8');
    $slug = preg_replace('/[áàảãạăắằẳẵặâấầẩẫậ]/u', 'a', $slug);
    $slug = preg_replace('/[éèẻẽẹêếềểễệ]/u', 'e', $slug);
    $slug = preg_replace('/[íìỉĩị]/u', 'i', $slug);
    $slug = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/u', 'o', $slug);
    $slug = preg_replace('/[úùủũụưứừửữự]/u', 'u', $slug);
    $slug = preg_replace('/[ýỳỷỹỵ]/u', 'y', $slug);
    $slug = preg_replace('/đ/u', 'd', $slug);
    $slug = preg_replace('/[^a-z0-9-]/u', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Alias for createSlug
function generateSlug($string) {
    return createSlug($string);
}

// Format date
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

// Format date for display
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $current = time();
    $diff = $current - $time;
    
    if ($diff < 60) {
        return 'Vừa xong';
    } elseif ($diff < 3600) {
        return floor($diff/60) . ' phút trước';
    } elseif ($diff < 86400) {
        return floor($diff/3600) . ' giờ trước';
    } elseif ($diff < 604800) {
        return floor($diff/86400) . ' ngày trước';
    } else {
        return formatDate($datetime);
    }
}

// Format number
function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}

// Format money
function formatMoney($amount) {
    return formatNumber($amount) . ' ₫';
}

// Get post featured image URL (handles external Unsplash URLs and local uploaded files)
function getPostImage($path) {
    if (empty($path)) {
        return '';
    }
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    return UPLOAD_URL . $path;
}

// Truncate text
function truncateText($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text, 'UTF-8') <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length, 'UTF-8') . $suffix;
}

// Get excerpt from content
function getExcerpt($content, $length = 160) {
    $content = strip_tags($content);
    $content = preg_replace('/\s+/', ' ', $content);
    return truncateText($content, $length);
}

// Redirect with message
function redirect($url, $message = null, $type = 'success') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header('Location: ' . $url);
    exit;
}

// Get flash message
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

// Display flash message
function displayFlashMessage() {
    $flash = getFlashMessage();
    if (!$flash) return;
    $cfg = [
        'success' => ['bg-green-50 border-green-200 text-green-800', 'bi-check-circle-fill text-green-500'],
        'error'   => ['bg-red-50 border-red-200 text-red-700',       'bi-x-circle-fill text-red-500'],
        'warning' => ['bg-amber-50 border-amber-200 text-amber-800', 'bi-exclamation-triangle-fill text-amber-500'],
        'info'    => ['bg-sky-50 border-sky-200 text-sky-800',       'bi-info-circle-fill text-sky-500'],
    ];
    $t = $cfg[$flash['type']] ?? $cfg['info'];
    echo '<div class="flex items-center gap-3 px-4 py-3 mb-5 rounded-2xl border text-sm font-medium ' . $t[0] . '">';
    echo '<i class="bi ' . $t[1] . ' text-base flex-shrink-0"></i>';
    echo '<span>' . htmlspecialchars($flash['message']) . '</span>';
    echo '</div>';
}

// Pagination
function paginate($total, $page, $per_page, $url) {
    $total_pages = (int)ceil($total / $per_page);
    if ($total_pages <= 1) return '';
    $page = max(1, min($page, $total_pages));

    $btn   = 'inline-flex items-center justify-center min-w-[32px] h-8 px-2.5 rounded-lg text-sm font-semibold transition-colors';
    $norm  = "$btn bg-white border border-slate-200 text-slate-600 hover:bg-slate-50";
    $act   = "$btn bg-primary text-white border border-primary";
    $dis   = "$btn bg-slate-50 text-slate-300 border border-slate-100 pointer-events-none";

    $html = '<nav class="flex items-center gap-1">';
    $html .= $page > 1
        ? '<a class="' . $norm . '" href="' . $url . '?page=' . ($page - 1) . '"><i class="bi bi-chevron-left" style="font-size:11px"></i></a>'
        : '<span class="' . $dis . '"><i class="bi bi-chevron-left" style="font-size:11px"></i></span>';

    for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++) {
        $html .= $i === $page
            ? '<span class="' . $act . '">' . $i . '</span>'
            : '<a class="' . $norm . '" href="' . $url . '?page=' . $i . '">' . $i . '</a>';
    }

    $html .= $page < $total_pages
        ? '<a class="' . $norm . '" href="' . $url . '?page=' . ($page + 1) . '"><i class="bi bi-chevron-right" style="font-size:11px"></i></a>'
        : '<span class="' . $dis . '"><i class="bi bi-chevron-right" style="font-size:11px"></i></span>';

    $html .= '</nav>';
    return $html;
}

// Upload image
function uploadImage($file, $folder = 'images') {
    $result = validateFileUpload($file, ALLOWED_IMAGE_TYPES);
    
    if (!$result['success']) {
        return $result;
    }
    
    $upload_dir = UPLOAD_PATH . $folder . '/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $filepath = $upload_dir . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
    
    return [
        'success' => true,
        'filename' => $filename,
        'filepath' => $folder . '/' . $filename,
        'url' => UPLOAD_URL . $folder . '/' . $filename
    ];
}

// Delete file
function deleteFile($filepath) {
    $full_path = UPLOAD_PATH . $filepath;
    if (file_exists($full_path)) {
        return unlink($full_path);
    }
    return false;
}

// Get settings
function getSetting($key, $default = null) {
    static $settings = null;
    
    if ($settings === null) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT setting_key, setting_value, setting_type FROM settings");
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $settings = [];
        foreach ($results as $row) {
            $value = $row['setting_value'];
            
            switch ($row['setting_type']) {
                case 'json':
                    $value = json_decode($value, true);
                    break;
                case 'boolean':
                    $value = (bool)$value;
                    break;
                case 'number':
                    $value = is_numeric($value) ? (int)$value : $value;
                    break;
            }
            
            $settings[$row['setting_key']] = $value;
        }
    }
    
    return $settings[$key] ?? $default;
}

// Update setting
function updateSetting($key, $value, $type = 'text') {
    $db = Database::getInstance();
    
    if ($type === 'json') {
        $value = json_encode($value);
    }
    
    $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
    return $stmt->execute([$value, $key]);
}

// Send email
function sendEmail($to, $subject, $body, $from_email = null, $from_name = null) {
    // In production, use PHPMailer or similar library
    // This is a simple example
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . ($from_name ?? SMTP_FROM_NAME) . ' <' . ($from_email ?? SMTP_FROM_EMAIL) . '>' . "\r\n";
    
    return mail($to, $subject, $body, $headers);
}

// Get client IP
// ── CRM Lead helpers ──────────────────────────────────────────

function createOrUpdateLead(string $source, int $sourceId, string $name, string $email, string $phone, string $japaneseLevel = '', string $intakePeriod = ''): int {
    $db = Database::getInstance();

    // Check activity already imported to avoid duplicates
    $stmt = $db->prepare("SELECT lead_id FROM lead_activities WHERE source_table = ? AND source_id = ? LIMIT 1");
    $stmt->execute([$source, $sourceId]);
    $existing_act = $stmt->fetch();
    if ($existing_act) return (int)$existing_act['lead_id'];

    // Find lead by email, then phone
    $lead_id = null;
    if ($email) {
        $stmt = $db->prepare("SELECT id FROM leads WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        if ($row) $lead_id = (int)$row['id'];
    }
    if (!$lead_id && $phone) {
        $stmt = $db->prepare("SELECT id FROM leads WHERE phone = ? LIMIT 1");
        $stmt->execute([$phone]);
        $row = $stmt->fetch();
        if ($row) $lead_id = (int)$row['id'];
    }

    if (!$lead_id) {
        $stmt = $db->prepare("INSERT INTO leads (name, email, phone, japanese_level, intake_period, source, status) VALUES (?, ?, ?, ?, ?, ?, 'new')");
        $stmt->execute([$name, $email ?: null, $phone ?: null, $japaneseLevel ?: null, $intakePeriod ?: null, $source]);
        $lead_id = (int)$db->lastInsertId();
    }

    // Log activity
    $labels = ['contact' => 'Đăng ký liên hệ qua form', 'booking' => 'Đặt lịch tư vấn Zoom'];
    $content = $labels[$source] ?? 'Nguồn: ' . $source;
    $stmt = $db->prepare("INSERT INTO lead_activities (lead_id, type, content, source_table, source_id) VALUES (?, 'system', ?, ?, ?)");
    $stmt->execute([$lead_id, $content, $source, $sourceId]);

    return $lead_id;
}

function syncAllToLeads(): array {
    $db = Database::getInstance();
    $created = 0; $merged = 0;

    // Sync contacts
    $stmt = $db->prepare("SELECT * FROM contacts ORDER BY id ASC");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $c) {
        $before = createOrUpdateLead('contact', $c['id'], $c['name'], $c['email'] ?? '', $c['phone'] ?? '', $c['japanese_level'] ?? '', $c['intake_period'] ?? '');
        // Check if new lead was created (no prior activity)
        $check = $db->prepare("SELECT COUNT(*) FROM lead_activities WHERE source_table='contact' AND source_id=?");
        $check->execute([$c['id']]);
        if ($check->fetchColumn() == 1) $created++; else $merged++;
    }

    // Sync consultation_bookings
    $stmt = $db->prepare("SELECT * FROM consultation_bookings ORDER BY id ASC");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $b) {
        createOrUpdateLead('booking', $b['id'], $b['name'], $b['email'] ?? '', $b['phone'] ?? '', $b['japanese_level'] ?? '');
        $check = $db->prepare("SELECT COUNT(*) FROM lead_activities WHERE source_table='booking' AND source_id=?");
        $check->execute([$b['id']]);
        if ($check->fetchColumn() == 1) $created++; else $merged++;
    }

    return ['created' => $created, 'merged' => $merged];
}

function getClientIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
