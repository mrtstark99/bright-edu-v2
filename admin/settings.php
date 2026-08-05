<?php
require_once dirname(__DIR__) . '/config/config.php';
requireAdmin();

$db = Database::getInstance();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key !== 'csrf_token') {
            $db->prepare("INSERT OR REPLACE INTO settings (setting_key,setting_value,updated_at) VALUES (?,?,datetime('now','localtime'))")
               ->execute([$key, trim($value)]);
        }
    }
    $success = 'Đã lưu cài đặt.';
}

$stmt = $db->prepare("SELECT * FROM settings");
$stmt->execute();
$s = array_column($stmt->fetchAll(), 'setting_value', 'setting_key');

$page_title = 'Cài đặt';
include dirname(__DIR__) . '/includes/admin/header.php';

$si = function($key, $default = '') use ($s) {
    return htmlspecialchars($s[$key] ?? $default);
};
?>

<div class="page-header">
    <div><h1>Cài đặt hệ thống</h1><p>Cấu hình chung của website</p></div>
</div>

<?php if ($success): ?>
<div class="flex items-center gap-3 px-4 py-3 mb-5 rounded-2xl border text-sm font-medium bg-green-50 border-green-200 text-green-800">
    <i class="bi bi-check-circle-fill text-green-500"></i><?php echo $success; ?>
</div>
<?php endif; ?>

<form method="POST">
    <?php echo csrfField(); ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        <!-- Thông tin chung -->
        <div class="a-card">
            <div class="a-card-header"><h2>Thông tin chung</h2></div>
            <div class="a-card-body">
                <div class="a-field"><label class="a-label">Tên website</label>
                    <input type="text" name="site_name" class="a-input" value="<?php echo $si('site_name','Bright Education Japan'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Slogan</label>
                    <input type="text" name="site_slogan" class="a-input" value="<?php echo $si('site_slogan','Du học Nhật Bản'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Mô tả ngắn</label>
                    <textarea name="site_description" class="a-input" rows="3"><?php echo $si('site_description'); ?></textarea>
                </div>
                <div class="a-field"><label class="a-label">Keywords SEO</label>
                    <input type="text" name="site_keywords" class="a-input" value="<?php echo $si('site_keywords'); ?>"
                        placeholder="du học nhật bản, học bổng nhật bản...">
                </div>
                <div class="a-field"><label class="a-label">Mô tả Footer</label>
                    <textarea name="site_footer_desc" class="a-input" rows="3"><?php echo $si('site_footer_desc', 'Đồng hành cùng hàng ngàn học viên Việt Nam trên con đường chinh phục tri thức và xây dựng sự nghiệp tại Nhật Bản.'); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Liên hệ -->
        <div class="a-card">
            <div class="a-card-header"><h2>Thông tin liên hệ</h2></div>
            <div class="a-card-body">
                <div class="a-field"><label class="a-label">Email</label>
                    <input type="email" name="site_email" class="a-input" value="<?php echo $si('site_email','contact@brighteducation.net'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Điện thoại</label>
                    <input type="text" name="site_phone" class="a-input" value="<?php echo $si('site_phone','+84 0971044576'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Điện thoại Nhật Bản</label>
                    <input type="text" name="site_phone_jp" class="a-input" value="<?php echo $si('site_phone_jp','+81 08037316436'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Địa chỉ</label>
                    <textarea name="site_address" class="a-input" rows="3"><?php echo $si('site_address','Số 45 ngõ 207 Quang Trung, Phường Thành Đông, TP Hải Phòng, Việt Nam'); ?></textarea>
                </div>
                <div class="a-field"><label class="a-label">Facebook Page URL</label>
                    <input type="text" name="facebook_url" class="a-input" value="<?php echo $si('facebook_url'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Giờ làm việc</label>
                    <input type="text" name="working_hours" class="a-input" value="<?php echo $si('working_hours', 'Thứ 2 - Thứ 7: 8:00 - 17:30'); ?>">
                </div>
            </div>
        </div>

        <!-- Hiển thị -->
        <div class="a-card">
            <div class="a-card-header"><h2>Cài đặt hiển thị</h2></div>
            <div class="a-card-body">
                <div class="a-field"><label class="a-label">Số bài viết mỗi trang</label>
                    <input type="number" name="posts_per_page" class="a-input" value="<?php echo $si('posts_per_page','10'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Cho phép đăng ký tài khoản</label>
                    <select name="allow_registration" class="a-input">
                        <option value="1" <?php echo $si('allow_registration','1')==='1'?'selected':''; ?>>Có</option>
                        <option value="0" <?php echo $si('allow_registration','1')==='0'?'selected':''; ?>>Không</option>
                    </select>
                </div>
                <div class="a-field"><label class="a-label">Chế độ bảo trì</label>
                    <select name="maintenance_mode" class="a-input">
                        <option value="0" <?php echo $si('maintenance_mode','0')==='0'?'selected':''; ?>>Tắt</option>
                        <option value="1" <?php echo $si('maintenance_mode','0')==='1'?'selected':''; ?>>Bật</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Mạng xã hội & Analytics -->
        <div class="a-card">
            <div class="a-card-header"><h2>Mạng xã hội & Analytics</h2></div>
            <div class="a-card-body">
                <div class="a-field"><label class="a-label">Facebook Messenger ID</label>
                    <input type="text" name="messenger_id" class="a-input" value="<?php echo $si('messenger_id','491649064036887'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Zalo Phone</label>
                    <input type="text" name="zalo_phone" class="a-input" value="<?php echo $si('zalo_phone'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Youtube URL</label>
                    <input type="text" name="youtube_url" class="a-input" value="<?php echo $si('youtube_url'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Tiktok URL</label>
                    <input type="text" name="tiktok_url" class="a-input" value="<?php echo $si('tiktok_url'); ?>">
                </div>
                <div class="a-field"><label class="a-label">Google Analytics ID</label>
                    <input type="text" name="ga_id" class="a-input" value="<?php echo $si('ga_id'); ?>" placeholder="G-XXXXXXXXXX">
                </div>
                <div class="a-field"><label class="a-label">Kiểu nút liên hệ nổi</label>
                    <select name="fab_display_style" class="a-input">
                        <option value="expanded" <?php echo $si('fab_display_style','expanded')==='expanded'?'selected':''; ?>>Luôn hiển thị</option>
                        <option value="collapsed" <?php echo $si('fab_display_style','expanded')==='collapsed'?'selected':''; ?>>Thu gọn</option>
                    </select>
                </div>
            </div>
        </div>

    </div>

    <div class="flex justify-end mb-5">
        <button type="submit" class="btn-adm"><i class="bi bi-save"></i> Lưu cài đặt</button>
    </div>
</form>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
