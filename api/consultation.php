<?php
require_once dirname(__DIR__) . '/config/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$db = Database::getInstance();

$name          = trim($_POST['name'] ?? '');
$email         = trim($_POST['email'] ?? '');
$phone         = trim($_POST['phone'] ?? '');
$booking_type  = in_array($_POST['booking_type'] ?? '', ['group','individual']) ? $_POST['booking_type'] : 'individual';
$slot_id       = !empty($_POST['slot_id']) ? (int)$_POST['slot_id'] : null;
$japanese_level= trim($_POST['japanese_level'] ?? '');
$topic         = trim($_POST['topic'] ?? '');
$message       = trim($_POST['message'] ?? '');
$preferred_date= trim($_POST['preferred_date'] ?? '');
$preferred_time= trim($_POST['preferred_time'] ?? '');
$ip            = getClientIP();

// Validation
if (!$name || !$email || !$phone) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ họ tên, email và số điện thoại.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Địa chỉ email không hợp lệ.']);
    exit;
}

// Group booking: verify slot
if ($booking_type === 'group') {
    if (!$slot_id) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn buổi tư vấn nhóm.']);
        exit;
    }
    $stmt = $db->prepare("SELECT * FROM consultation_slots WHERE id = ? AND type = 'group' AND status = 'active'");
    $stmt->execute([$slot_id]);
    $slot = $stmt->fetch();
    if (!$slot) {
        echo json_encode(['success' => false, 'message' => 'Buổi tư vấn không tồn tại hoặc đã đầy chỗ.']);
        exit;
    }
    if ($slot['current_participants'] >= $slot['max_participants']) {
        echo json_encode(['success' => false, 'message' => 'Buổi tư vấn này đã đủ số lượng người tham gia.']);
        exit;
    }
    // Prevent duplicate booking for same slot + email
    $stmt = $db->prepare("SELECT id FROM consultation_bookings WHERE slot_id = ? AND email = ? AND status != 'cancelled'");
    $stmt->execute([$slot_id, $email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email này đã đăng ký buổi tư vấn nhóm này rồi.']);
        exit;
    }
}

// Individual: validate preferred_date
if ($booking_type === 'individual' && !$preferred_date) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn ngày mong muốn.']);
    exit;
}

try {
    $db->beginTransaction();

    // Insert booking
    $status = ($booking_type === 'group') ? 'confirmed' : 'pending';
    $stmt = $db->prepare("
        INSERT INTO consultation_bookings
            (slot_id, name, email, phone, japanese_level, booking_type, topic, preferred_date, preferred_time, message, status, ip_address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $slot_id, $name, $email, $phone, $japanese_level,
        $booking_type, $topic, $preferred_date, $preferred_time,
        $message, $status, $ip
    ]);

    // Increment participant count for group bookings
    if ($booking_type === 'group' && $slot_id) {
        $db->exec("UPDATE consultation_slots SET current_participants = current_participants + 1,
            status = CASE WHEN current_participants + 1 >= max_participants THEN 'full' ELSE status END
            WHERE id = $slot_id");
    }

    $booking_id = (int)$db->lastInsertId();
    createOrUpdateLead('booking', $booking_id, $name, $email, $phone, $japanese_level ?? '');

    $db->commit();

    $msg = $booking_type === 'group'
        ? 'Đăng ký thành công! Link Zoom sẽ được gửi đến email ' . htmlspecialchars($email) . ' trước buổi học 30 phút.'
        : 'Yêu cầu đặt lịch đã được ghi nhận! Chuyên viên sẽ liên hệ xác nhận qua email hoặc điện thoại trong vòng 24 giờ.';

    echo json_encode(['success' => true, 'message' => $msg]);

} catch (Exception $e) {
    $db->rollBack();
    error_log('Consultation booking error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.']);
}
