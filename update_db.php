<?php
if (file_exists(__DIR__ . '/database/bright_edu.db')) {
    try {
        $db = new PDO('sqlite:' . __DIR__ . '/database/bright_edu.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Email
        $stmt = $db->prepare("UPDATE settings SET setting_value = 'contact@brighteducation.net' WHERE setting_key = 'site_email'");
        $stmt->execute();

        // Phone 1
        $stmt = $db->prepare("UPDATE settings SET setting_value = '+84 0971044576' WHERE setting_key = 'site_phone'");
        $stmt->execute();

        // Phone 2 (insert if not exists, update if exists)
        $stmt = $db->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = 'site_phone_jp'");
        if ($stmt->fetchColumn() > 0) {
            $stmt = $db->prepare("UPDATE settings SET setting_value = '+81 08037316436' WHERE setting_key = 'site_phone_jp'");
            $stmt->execute();
        } else {
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, field_type, field_label) VALUES ('site_phone_jp', '+81 08037316436', 'text', 'Số điện thoại Nhật Bản')");
            $stmt->execute();
        }

        echo "Database updated successfully.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Database not found.";
}
