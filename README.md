# Bright Education - Hệ thống Website Du học Nhật Bản

## Cài đặt

1. Import database từ file `database/bright_edu.sql` vào MySQL
2. Cấu hình database trong file `config/database.php`
3. Truy cập website tại `http://localhost`

## Tài khoản Admin mặc định

- Email: `admin@brightconnect.vn`
- Password: `Admin@123`

## Cấu trúc thư mục

```
/
├── admin/           # Trang quản trị
├── api/            # API endpoints
├── auth/           # Xác thực (login, logout)
├── config/         # Cấu hình hệ thống
├── database/       # File SQL
├── includes/       # Header, footer, functions
├── pages/          # Các trang public
├── uploads/        # Thư mục upload files
└── index.php       # Entry point với routing
```

## Tính năng chính

### Public
- Trang chủ với thông tin du học
- Blog với danh mục
- Form liên hệ
- Responsive design với TailwindCSS

### Admin
- Dashboard với thống kê
- Quản lý bài viết (CRUD)
- Quản lý danh mục
- Quản lý liên hệ
- Quản lý người dùng (Admin only)
- TinyMCE editor cho nội dung

## Bảo mật

- CSRF protection
- Password hashing (bcrypt)
- SQL injection prevention (PDO)
- XSS protection
- Rate limiting cho login
- File upload validation
- Session security

## SEO

- SEO-friendly URLs
- Meta tags customization
- Sitemap support ready
- Schema.org ready

## Yêu cầu hệ thống

- PHP 7.4+
- MySQL 5.7+
- Apache với mod_rewrite

## Lưu ý phát triển

- Tắt error reporting trong production (config.php)
- Cấu hình SMTP cho gửi email (config.php)
- Thay đổi SECURE_AUTH_KEY trong production
- Enable HTTPS trong .htaccess cho production
