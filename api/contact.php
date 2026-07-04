<?php
require_once '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get and validate input
$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$phone = sanitizeInput($_POST['phone'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');
$intake_period = sanitizeInput($_POST['intake_period'] ?? '');
$japanese_level = sanitizeInput($_POST['japanese_level'] ?? '');
$csrf_token = $_POST[CSRF_TOKEN_NAME] ?? '';

// Verify CSRF token
if (!verifyCSRFToken($csrf_token)) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}

// Validate required fields
if (empty($name) || empty($email) || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
    exit;
}

// Validate email
if (!validateEmail($email)) {
    echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
    exit;
}

try {
    $db = Database::getInstance();
    
    $sql = "INSERT INTO contacts (name, email, phone, message, intake_period, japanese_level, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $name,
        $email,
        $phone,
        $message,
        $intake_period,
        $japanese_level,
        getClientIP(),
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    
    $contact_id = (int)$db->lastInsertId();
    createOrUpdateLead('contact', $contact_id, $name, $email, $phone, $japanese_level, $intake_period);

    // Send email notification to admin (optional)
    $admin_email = getSetting('site_email', 'contact@brighteducation.net');
    $subject = "Liên hệ mới từ website - $name";
    $body = "
        <h3>Thông tin liên hệ mới:</h3>
        <p><strong>Họ tên:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Điện thoại:</strong> $phone</p>
        <p><strong>Kỳ nhập học:</strong> $intake_period</p>
        <p><strong>Trình độ tiếng Nhật:</strong> $japanese_level</p>
        <p><strong>Nội dung:</strong><br>$message</p>
    ";
    
    // sendEmail($admin_email, $subject, $body);
    
    echo json_encode(['success' => true, 'message' => 'Thông tin đã được gửi thành công']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.']);
}
?>
