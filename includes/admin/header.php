<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin'; ?> — <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Quicksand:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { 
            sans: ['Inter', 'ui-sans-serif', 'system-ui'],
            display: ['Quicksand', 'ui-sans-serif', 'system-ui']
          },
          colors: {
            primary: {
              DEFAULT: '#0d243e',
              50: '#f2f5f9',
              100: '#e1e8f0',
              200: '#c5d3df',
              300: '#9bb7ca',
              400: '#6b92af',
              500: '#487596',
              600: '#345b7b',
              700: '#2a4964',
              800: '#253e54',
              900: '#0d243e',
            },
            sage: { 50: '#f2f5f9', 100: '#e1e8f0', 200: '#c5d3df', 300: '#9bb7ca', 400: '#345b7b', 500: '#0d243e', 600: '#0d243e', 900: '#0d243e' },
            sakura: { 50: '#f2f5f9', 100: '#e1e8f0', 200: '#c5d3df', 300: '#9bb7ca', 400: '#345b7b', 500: '#0d243e', 600: '#0d243e', 900: '#0d243e' },
            sky: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7' },
            amber: { 50: '#fffbeb', 100: '#fef3c7', 500: '#f59e0b', 600: '#d97706' },
            sand: { 50: '#ffffff', 100: '#f8fafc', 200: '#e2e8f0' },
            midnight: '#0d243e', 
            ink: '#111827',
            muted: '#6b7280',
            rice: '#ffffff'
          },
          boxShadow: {
            'soft': '0 4px 20px -2px rgba(1, 53, 103, 0.05)',
            'medium': '0 12px 32px -4px rgba(1, 53, 103, 0.08)',
            'hard': '0 24px 48px -12px rgba(1, 53, 103, 0.12)',
            'tinted': '0 20px 40px -8px rgba(1, 53, 103, 0.15)',
          },
          borderRadius: {
            '4xl': '2rem',
            '5xl': '2.5rem',
            'blob': '40% 60% 70% 30% / 40% 50% 60% 50%',
          }
        }
      }
    }
    </script>
    <style>
        :root {
            --sidebar-w: 232px;
            --admin-max-w: 1200px;
            --admin-gutter: 28px;
            --sidebar-bg: #ffffff;
            --sidebar-border: #f1f5f9;
            --sidebar-accent: #0d243e;
            --sidebar-accent-bg: #f2f5f9;
            --sidebar-text: #64748b;
            --topbar-h: 60px;
            --footer-h: 52px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; background: #f8fafc;
            margin: 0; color: #334155; min-width: 320px;
        }

        /* ── Sidebar ── */
        #admin-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex; flex-direction: column;
            z-index: 200;
            transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .sidebar-logo {
            display: flex; align-items: center; gap: 10px;
            height: var(--topbar-h);
            padding: 0 16px;
            text-decoration: none;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }
        .sidebar-logo-icon {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, #0d243e, #253e54);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: #fff; font-size: 13px; flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(13, 36, 62, 0.25);
        }
        .sidebar-logo-text { line-height: 1.25; }
        .sidebar-logo-name { font-weight: 800; font-size: 15px; color: #0d243e; display: block; font-family: 'Quicksand', sans-serif; }
        .sidebar-logo-sub  { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }

        .sidebar-body { flex: 1; overflow-y: auto; padding: 8px 0 4px; }
        .sidebar-body::-webkit-scrollbar { width: 3px; }
        .sidebar-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        .sidebar-label {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .08em; color: #94a3b8;
            padding: 12px 16px 4px; font-family: 'Quicksand', sans-serif;
        }
        .sidebar-label:first-child { padding-top: 6px; }

        .sidebar-link {
            display: flex; align-items: center; gap: 9px;
            padding: 7px 12px; margin: 1px 8px; border-radius: 9px;
            color: var(--sidebar-text); text-decoration: none;
            font-size: 13px; font-weight: 600;
            transition: all .15s ease;
            border-left: 2px solid transparent;
        }
        .sidebar-link i { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }
        .sidebar-link:hover { background: #f8fafc; color: #0d243e; }
        .sidebar-link.active {
            background: var(--sidebar-accent-bg); color: var(--sidebar-accent);
            border-left-color: var(--sidebar-accent);
        }
        .sidebar-link.active i { color: var(--sidebar-accent); }

        .sidebar-badge {
            margin-left: auto; background: #f43f5e; color: #fff;
            font-size: 10px; font-weight: 700; padding: 1px 6px;
            border-radius: 20px; line-height: 1.5;
        }
        .sidebar-divider {
            height: 1px; background: var(--sidebar-border);
            margin: 6px 16px;
        }

        .sidebar-footer {
            border-top: 1px solid var(--sidebar-border);
            height: var(--footer-h);
            padding: 0 12px;
            background: #fafafa;
            display: flex; align-items: center;
            flex-shrink: 0;
        }
        .sidebar-footer-row {
            display: flex; align-items: center; gap: 8px;
        }
        .sidebar-user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #64748b, #475569);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 12px; flex-shrink: 0;
        }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name { font-size: 13px; font-weight: 700; color: #0d243e; display: block; font-family: 'Quicksand', sans-serif; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-role { font-size: 11px; color: #94a3b8; display: block; font-weight: 500; }
        .sidebar-footer-btn {
            width: 28px; height: 28px; border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            color: #94a3b8; text-decoration: none; font-size: 14px;
            transition: all .15s;
        }
        .sidebar-footer-btn:hover { background: #f1f5f9; color: #0d243e; }

        /* ── Topbar ── */
        #admin-topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: var(--topbar-h); background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(226,232,240,0.8);
            z-index: 100;
        }
        .admin-topbar-inner {
            width: 100%; max-width: var(--admin-max-w); height: 100%;
            margin: 0 auto; padding: 0 var(--admin-gutter);
            display: flex; align-items: center; gap: 12px;
        }
        .topbar-title { font-size: 17px; font-weight: 700; color: #0d243e; flex: 1; font-family: 'Quicksand', sans-serif; }
        .topbar-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 10px;
            color: #64748b; text-decoration: none;
            border: 1px solid #e2e8f0; background: #fff;
            transition: all .2s; font-size: 16px;
        }
        .topbar-btn:hover { background: #f8fafc; color: #0ea5e9; border-color: #cbd5e1; }

        /* ── Main Content ── */
        #admin-main { margin-left: var(--sidebar-w); padding-top: var(--topbar-h); min-height: 100vh; }
        .admin-content {
            width: 100%; max-width: var(--admin-max-w);
            margin: 0 auto; padding: 32px var(--admin-gutter) 44px;
        }

        #admin-footer {
            margin-left: var(--sidebar-w); background: #fff;
            border-top: 1px solid #e2e8f0; color: #94a3b8;
        }
        .admin-footer-inner {
            width: 100%; max-width: var(--admin-max-w); min-height: var(--footer-h);
            margin: 0 auto; padding: 10px var(--admin-gutter);
            font-size: 12px; display: flex; align-items: center;
            justify-content: space-between; gap: 12px;
        }
        .admin-footer-link {
            color: #94a3b8; text-decoration: none; font-size: 11px;
            display: inline-flex; align-items: center; gap: 5px;
            transition: color .2s;
        }
        .admin-footer-link:hover { color: #0d243e; }

        /* ── Utility ── */
        .border-left-primary { border-left: 4px solid #4e73df!important; }
        .border-left-success { border-left: 4px solid #1cc88a!important; }
        .border-left-info    { border-left: 4px solid #36b9cc!important; }
        .border-left-warning { border-left: 4px solid #f6c23e!important; }

        @media (max-width: 767px) {
            :root { --admin-gutter: 16px; }
            #admin-sidebar { transform: translateX(-100%); }
            #admin-sidebar.open { transform: translateX(0); }
            #admin-topbar { left: 0; }
            #admin-main   { margin-left: 0; }
            #admin-footer { margin-left: 0; }
            .admin-content { padding-top: 22px; padding-bottom: 32px; }
            .admin-footer-inner { align-items: flex-start; flex-direction: column; }
        }

        /* ── Shared page components ── */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px; margin-bottom: 24px;
        }
        .page-header h1 {
            font-size: 20px; font-weight: 800; color: #0d243e;
            font-family: 'Quicksand', sans-serif; margin: 0;
        }
        .page-header p {
            font-size: 13px; color: #94a3b8; margin: 2px 0 0;
        }

        /* Card */
        .a-card {
            background: #fff; border-radius: 24px;
            border: 1px solid #eef2f7;
            box-shadow: 0 8px 28px -12px rgba(13,36,62,0.12);
            overflow: hidden;
        }
        .a-card-header {
            padding: 14px 20px; border-bottom: 1px solid #f1f5f9;
            background: #fafafa;
            display: flex; align-items: center; justify-content: space-between;
        }
        .a-card-header h2, .a-card-header h6 {
            font-size: 14px; font-weight: 700; color: #0d243e;
            font-family: 'Quicksand', sans-serif; margin: 0;
        }
        .a-card-body { padding: 20px; }

        /* Table */
        .a-table { width: 100%; font-size: 13px; border-collapse: collapse; }
        .a-table thead tr { background: #f8fafc; }
        .a-table thead th {
            padding: 10px 16px; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            color: #94a3b8; white-space: nowrap;
        }
        .a-table tbody tr { border-top: 1px solid #f1f5f9; transition: background .15s; }
        .a-table tbody tr:hover { background: #f8fafc; }
        .a-table tbody td { padding: 10px 16px; color: #475569; vertical-align: middle; }

        /* Filter bar */
        .a-filter {
            background: #fff; border-radius: 20px; padding: 16px 20px;
            border: 1px solid #eef2f7;
            box-shadow: 0 8px 28px -12px rgba(13,36,62,0.1);
            margin-bottom: 16px;
        }

        /* Buttons */
        .btn-adm {
            display: inline-flex; align-items: center; gap: 7px;
            background: #0d243e; color: #fff;
            padding: 8px 18px; border-radius: 10px;
            font-size: 13px; font-weight: 700; border: none;
            cursor: pointer; text-decoration: none; transition: all .2s;
            white-space: nowrap;
        }
        .btn-adm:hover { background: #111827; color: #fff; }
        .btn-adm-outline {
            display: inline-flex; align-items: center; gap: 7px;
            background: #f1f5f9; color: #475569;
            padding: 8px 18px; border-radius: 10px;
            font-size: 13px; font-weight: 600; border: none;
            cursor: pointer; text-decoration: none; transition: all .2s;
        }
        .btn-adm-outline:hover { background: #e2e8f0; color: #0d243e; }

        /* Icon action buttons */
        .btn-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 8px;
            font-size: 13px; text-decoration: none; transition: all .15s;
            border: none; cursor: pointer;
        }
        .btn-icon-edit  { background: #eff6ff; color: #3b82f6; }
        .btn-icon-edit:hover  { background: #3b82f6; color: #fff; }
        .btn-icon-view  { background: #f0fdf4; color: #16a34a; }
        .btn-icon-view:hover  { background: #16a34a; color: #fff; }
        .btn-icon-del   { background: #fff1f2; color: #f43f5e; }
        .btn-icon-del:hover   { background: #f43f5e; color: #fff; }

        /* Form fields */
        .a-input {
            width: 100%; background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 10px; padding: 8px 14px; font-size: 13px;
            transition: border-color .2s; outline: none; color: #334155;
        }
        .a-input:focus { border-color: #0d243e; }
        .a-label {
            display: block; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            color: #94a3b8; margin-bottom: 5px;
        }
        .a-field { margin-bottom: 14px; }

        /* Status badges */
        .badge { display: inline-flex; align-items: center; border-radius: 20px; font-size: 11px; font-weight: 700; padding: 2px 10px; border: 1px solid transparent; }
        .badge-new       { background: #f0f9ff; color: #0369a1; border-color: #bae6fd; }
        .badge-active    { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .badge-inactive  { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }
        .badge-pending   { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .badge-done      { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .badge-danger    { background: #fff1f2; color: #be123c; border-color: #fecdd3; }

        /* Sticky sidebar panel */
        .sticky-panel { position: sticky; top: 88px; }

        @media (max-width: 639px) {
            .page-header { align-items: flex-start; flex-direction: column; }
            .page-header > a, .page-header > button { width: 100%; justify-content: center; }
            .a-card-header, .a-card-body { padding-left: 16px; padding-right: 16px; }
            .a-table thead th, .a-table tbody td { padding-left: 12px; padding-right: 12px; }
        }
    </style>
</head>
<body>

<!-- ══ SIDEBAR ══ -->
<nav id="admin-sidebar">
    <a class="sidebar-logo" href="/admin/dashboard">
        <div class="sidebar-logo-icon">BE</div>
        <div class="sidebar-logo-text">
            <span class="sidebar-logo-name">Bright Admin</span>
            <span class="sidebar-logo-sub">Control Panel</span>
        </div>
    </a>

    <div class="sidebar-body">

        <!-- Dashboard -->
        <a class="sidebar-link mt-1 <?php echo (str_contains($_SERVER['REQUEST_URI'], '/admin/dashboard') || $_SERVER['REQUEST_URI'] === '/admin') ? 'active' : ''; ?>" href="/admin/dashboard">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="sidebar-divider"></div>

        <!-- Nội dung -->
        <div class="sidebar-label">Nội dung</div>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/posts') ? 'active' : ''; ?>" href="/admin/posts">
            <i class="bi bi-file-richtext"></i> Bài viết
        </a>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/categories') ? 'active' : ''; ?>" href="/admin/categories">
            <i class="bi bi-folder2-open"></i> Danh mục
        </a>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/announcements') ? 'active' : ''; ?>" href="/admin/announcements">
            <i class="bi bi-megaphone"></i> Thông báo
        </a>

        <div class="sidebar-divider"></div>

        <!-- Khách hàng -->
        <?php
        $db = Database::getInstance();
        $stmt_leads = $db->prepare("SELECT COUNT(*) as n FROM leads WHERE status = 'new'");
        $stmt_leads->execute();
        $new_leads = (int)$stmt_leads->fetch()['n'];

        $stmt_consult = $db->prepare("SELECT COUNT(*) as n FROM consultation_bookings WHERE status = 'pending'");
        $stmt_consult->execute();
        $pending_consult = (int)$stmt_consult->fetch()['n'];

        $stmt_contacts = $db->prepare("SELECT COUNT(*) as n FROM contacts WHERE status = 'new'");
        $stmt_contacts->execute();
        $new_contacts = (int)$stmt_contacts->fetch()['n'];
        ?>
        <div class="sidebar-label">Khách hàng</div>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/leads') ? 'active' : ''; ?>" href="/admin/leads">
            <i class="bi bi-person-lines-fill"></i> Leads & CRM
            <?php if ($new_leads > 0): ?><span class="sidebar-badge"><?php echo $new_leads; ?></span><?php endif; ?>
        </a>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/consultations') && !str_contains($_SERVER['REQUEST_URI'], '/slots') ? 'active' : ''; ?>" href="/admin/consultations">
            <i class="bi bi-calendar-check"></i> Lịch tư vấn
            <?php if ($pending_consult > 0): ?><span class="sidebar-badge"><?php echo $pending_consult; ?></span><?php endif; ?>
        </a>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/contacts') ? 'active' : ''; ?>" href="/admin/contacts">
            <i class="bi bi-envelope-open"></i> Form liên hệ
            <?php if ($new_contacts > 0): ?><span class="sidebar-badge"><?php echo $new_contacts; ?></span><?php endif; ?>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Cộng đồng -->
        <div class="sidebar-label">Cộng đồng</div>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/groups') ? 'active' : ''; ?>" href="/admin/groups">
            <i class="bi bi-people"></i> Nhóm cộng đồng
        </a>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/consultations/slots') ? 'active' : ''; ?>" href="/admin/consultations/slots">
            <i class="bi bi-camera-video"></i> Khung giờ Zoom
        </a>

        <!-- Hệ thống (admin only) -->
        <?php if (isAdmin()): ?>
        <div class="sidebar-divider"></div>
        <div class="sidebar-label">Hệ thống</div>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'active' : ''; ?>" href="/admin/users">
            <i class="bi bi-person-badge"></i> Người dùng
        </a>
        <a class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/admin/settings') ? 'active' : ''; ?>" href="/admin/settings">
            <i class="bi bi-sliders"></i> Cài đặt
        </a>
        <?php endif; ?>

    </div>

    <div class="sidebar-footer">
        <div class="sidebar-footer-row">
            <div class="sidebar-user-avatar">
                <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'A'; ?>
            </div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                <span class="sidebar-user-role"><?php echo ucfirst($_SESSION['user_role'] ?? 'admin'); ?></span>
            </div>
            <a href="/admin/profile" class="sidebar-footer-btn" title="Hồ sơ">
                <i class="bi bi-person-gear"></i>
            </a>
            <a href="/" target="_blank" class="sidebar-footer-btn" title="Xem website">
                <i class="bi bi-box-arrow-up-right"></i>
            </a>
            <a href="/logout" class="sidebar-footer-btn" title="Đăng xuất">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>

<!-- ══ TOPBAR ══ -->
<div id="admin-topbar">
  <div class="admin-topbar-inner">
    <button id="sidebar-toggle" class="topbar-btn d-md-none me-1" onclick="document.getElementById('admin-sidebar').classList.toggle('open')">
        <i class="bi bi-list"></i>
    </button>
    <div class="topbar-title"><?php echo $page_title ?? 'Dashboard'; ?></div>
    <a href="/admin/leads" class="topbar-btn" title="Leads mới">
        <i class="bi bi-person-lines-fill"></i>
    </a>
    <a href="/admin/contacts" class="topbar-btn" title="Liên hệ mới">
        <i class="bi bi-envelope"></i>
    </a>
    <a href="/" target="_blank" class="topbar-btn" title="Xem trang web">
        <i class="bi bi-globe2"></i>
    </a>
  </div>
</div>

<!-- ══ MAIN WRAPPER ══ -->
<div id="admin-main">
<div class="admin-content">
