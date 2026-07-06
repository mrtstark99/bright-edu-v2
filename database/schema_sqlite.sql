-- ============================================================
-- Bright Education - SQLite Schema
-- Converted from MySQL - Compatible with SQLite 3
-- ============================================================

PRAGMA journal_mode = WAL;
PRAGMA foreign_keys = ON;

-- ============================================================
-- Table: users
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    username    TEXT NOT NULL UNIQUE,
    email       TEXT NOT NULL UNIQUE,
    password    TEXT NOT NULL,
    full_name   TEXT NOT NULL,
    role        TEXT NOT NULL DEFAULT 'subscriber' CHECK(role IN ('admin','editor','subscriber','user')),
    status      TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive','suspended','banned')),
    avatar      TEXT,
    bio         TEXT,
    phone       TEXT,
    reset_token TEXT,
    reset_expires TEXT,
    created_at  TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at  TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    last_login  TEXT
);

CREATE INDEX IF NOT EXISTS idx_users_email  ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_role   ON users(role);
CREATE INDEX IF NOT EXISTS idx_users_status ON users(status);

-- Trigger: auto-update updated_at on UPDATE
CREATE TRIGGER IF NOT EXISTS trg_users_updated_at
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    UPDATE users SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: categories
-- ============================================================
CREATE TABLE IF NOT EXISTS categories (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    name             TEXT NOT NULL,
    slug             TEXT NOT NULL UNIQUE,
    description      TEXT,
    parent_id        INTEGER DEFAULT 0,
    meta_title       TEXT,
    meta_description TEXT,
    status           TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive')),
    created_at       TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at       TEXT NOT NULL DEFAULT (datetime('now','localtime'))
);

CREATE INDEX IF NOT EXISTS idx_categories_slug      ON categories(slug);
CREATE INDEX IF NOT EXISTS idx_categories_parent_id ON categories(parent_id);

CREATE TRIGGER IF NOT EXISTS trg_categories_updated_at
AFTER UPDATE ON categories
FOR EACH ROW
BEGIN
    UPDATE categories SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: posts
-- ============================================================
CREATE TABLE IF NOT EXISTS posts (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    title            TEXT NOT NULL,
    slug             TEXT NOT NULL UNIQUE,
    excerpt          TEXT,
    content          TEXT,
    featured_image   TEXT,
    category_id      INTEGER,
    author_id        INTEGER NOT NULL,
    status           TEXT NOT NULL DEFAULT 'draft' CHECK(status IN ('draft','published','archived')),
    featured         INTEGER NOT NULL DEFAULT 0,
    views            INTEGER NOT NULL DEFAULT 0,
    meta_title       TEXT,
    meta_description TEXT,
    meta_keywords    TEXT,
    published_at     TEXT,
    created_at       TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at       TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id)   REFERENCES users(id)      ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_posts_slug       ON posts(slug);
CREATE INDEX IF NOT EXISTS idx_posts_status     ON posts(status);
CREATE INDEX IF NOT EXISTS idx_posts_category   ON posts(category_id);
CREATE INDEX IF NOT EXISTS idx_posts_author     ON posts(author_id);
CREATE INDEX IF NOT EXISTS idx_posts_featured   ON posts(featured);
CREATE INDEX IF NOT EXISTS idx_posts_published  ON posts(published_at);

CREATE TRIGGER IF NOT EXISTS trg_posts_updated_at
AFTER UPDATE ON posts
FOR EACH ROW
BEGIN
    UPDATE posts SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: contacts
-- ============================================================
CREATE TABLE IF NOT EXISTS contacts (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    name           TEXT NOT NULL,
    email          TEXT NOT NULL,
    phone          TEXT,
    subject        TEXT,
    message        TEXT,
    intake_period  TEXT,
    japanese_level TEXT,
    status         TEXT NOT NULL DEFAULT 'new' CHECK(status IN ('new','read','replied','processing','completed','archived')),
    assigned_to    INTEGER,
    notes          TEXT,
    ip_address     TEXT,
    user_agent     TEXT,
    created_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_contacts_status   ON contacts(status);
CREATE INDEX IF NOT EXISTS idx_contacts_created  ON contacts(created_at);
CREATE INDEX IF NOT EXISTS idx_contacts_assigned ON contacts(assigned_to);

CREATE TRIGGER IF NOT EXISTS trg_contacts_updated_at
AFTER UPDATE ON contacts
FOR EACH ROW
BEGIN
    UPDATE contacts SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: services
-- ============================================================
CREATE TABLE IF NOT EXISTS services (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    name           TEXT,
    title          TEXT,
    slug           TEXT UNIQUE,
    description    TEXT,
    content        TEXT,
    icon           TEXT,
    price          REAL,
    display_order  INTEGER NOT NULL DEFAULT 0,
    order_position INTEGER NOT NULL DEFAULT 0,
    status         TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive')),
    created_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at     TEXT NOT NULL DEFAULT (datetime('now','localtime'))
);

CREATE INDEX IF NOT EXISTS idx_services_status ON services(status);
CREATE INDEX IF NOT EXISTS idx_services_order  ON services(display_order);

CREATE TRIGGER IF NOT EXISTS trg_services_updated_at
AFTER UPDATE ON services
FOR EACH ROW
BEGIN
    UPDATE services SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: scholarships
-- ============================================================
CREATE TABLE IF NOT EXISTS scholarships (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    title        TEXT NOT NULL,
    description  TEXT,
    amount       TEXT,
    requirements TEXT,
    deadline     TEXT,
    status       TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive','expired')),
    created_at   TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at   TEXT NOT NULL DEFAULT (datetime('now','localtime'))
);

CREATE INDEX IF NOT EXISTS idx_scholarships_status   ON scholarships(status);
CREATE INDEX IF NOT EXISTS idx_scholarships_deadline ON scholarships(deadline);

CREATE TRIGGER IF NOT EXISTS trg_scholarships_updated_at
AFTER UPDATE ON scholarships
FOR EACH ROW
BEGIN
    UPDATE scholarships SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: testimonials
-- ============================================================
CREATE TABLE IF NOT EXISTS testimonials (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    student_name  TEXT NOT NULL,
    course        TEXT,
    location      TEXT,
    content       TEXT NOT NULL,
    avatar        TEXT,
    display_order INTEGER NOT NULL DEFAULT 0,
    status        TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive')),
    created_at    TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at    TEXT NOT NULL DEFAULT (datetime('now','localtime'))
);

CREATE INDEX IF NOT EXISTS idx_testimonials_status ON testimonials(status);
CREATE INDEX IF NOT EXISTS idx_testimonials_order  ON testimonials(display_order);

CREATE TRIGGER IF NOT EXISTS trg_testimonials_updated_at
AFTER UPDATE ON testimonials
FOR EACH ROW
BEGIN
    UPDATE testimonials SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: partners
-- ============================================================
CREATE TABLE IF NOT EXISTS partners (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    name          TEXT NOT NULL,
    location      TEXT,
    description   TEXT,
    logo          TEXT,
    website       TEXT,
    display_order INTEGER NOT NULL DEFAULT 0,
    status        TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive')),
    created_at    TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at    TEXT NOT NULL DEFAULT (datetime('now','localtime'))
);

CREATE INDEX IF NOT EXISTS idx_partners_status ON partners(status);
CREATE INDEX IF NOT EXISTS idx_partners_order  ON partners(display_order);

CREATE TRIGGER IF NOT EXISTS trg_partners_updated_at
AFTER UPDATE ON partners
FOR EACH ROW
BEGIN
    UPDATE partners SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: announcements
-- ============================================================
CREATE TABLE IF NOT EXISTS announcements (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    title        TEXT NOT NULL,
    content      TEXT,
    type         TEXT NOT NULL DEFAULT 'info' CHECK(type IN ('info','warning','success','danger')),
    target_users TEXT NOT NULL DEFAULT 'all' CHECK(target_users IN ('all','admin','user','editor')),
    priority     INTEGER NOT NULL DEFAULT 0,
    start_date   TEXT,
    end_date     TEXT,
    created_by   INTEGER,
    status       TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive')),
    created_at   TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at   TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_announcements_status   ON announcements(status);
CREATE INDEX IF NOT EXISTS idx_announcements_priority ON announcements(priority);

CREATE TRIGGER IF NOT EXISTS trg_announcements_updated_at
AFTER UPDATE ON announcements
FOR EACH ROW
BEGIN
    UPDATE announcements SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: settings
-- ============================================================
CREATE TABLE IF NOT EXISTS settings (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key   TEXT NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type  TEXT NOT NULL DEFAULT 'text' CHECK(setting_type IN ('text','json','boolean','number')),
    description   TEXT,
    updated_at    TEXT NOT NULL DEFAULT (datetime('now','localtime'))
);

CREATE INDEX IF NOT EXISTS idx_settings_key ON settings(setting_key);

CREATE TRIGGER IF NOT EXISTS trg_settings_updated_at
AFTER UPDATE ON settings
FOR EACH ROW
BEGIN
    UPDATE settings SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
END;

-- ============================================================
-- Table: audit_logs
-- ============================================================
CREATE TABLE IF NOT EXISTS audit_logs (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id    INTEGER,
    action     TEXT NOT NULL,
    table_name TEXT,
    record_id  INTEGER,
    old_values TEXT,
    new_values TEXT,
    ip_address TEXT,
    user_agent TEXT,
    created_at TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_audit_logs_user    ON audit_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_audit_logs_action  ON audit_logs(action);
CREATE INDEX IF NOT EXISTS idx_audit_logs_created ON audit_logs(created_at);

-- ============================================================
-- Table: sessions
-- ============================================================
CREATE TABLE IF NOT EXISTS sessions (
    id            TEXT PRIMARY KEY,
    user_id       INTEGER,
    ip_address    TEXT,
    user_agent    TEXT,
    payload       TEXT NOT NULL,
    last_activity INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_sessions_user          ON sessions(user_id);
CREATE INDEX IF NOT EXISTS idx_sessions_last_activity ON sessions(last_activity);

-- ============================================================
-- Default Data
-- ============================================================

-- Default categories
INSERT OR IGNORE INTO categories (name, slug, description) VALUES
('Du học Nhật Bản', 'du-hoc-nhat-ban', 'Thông tin về du học Nhật Bản'),
('Học bổng',        'hoc-bong',        'Các chương trình học bổng du học'),
('Kinh nghiệm',     'kinh-nghiem',     'Chia sẻ kinh nghiệm du học'),
('Tin tức',         'tin-tuc',         'Tin tức mới nhất về du học');

-- Default services
INSERT OR IGNORE INTO services (name, slug, description, order_position) VALUES
('Tư vấn định hướng', 'tu-van-dinh-huong', 'Tư vấn định hướng du học phù hợp', 1),
('Chuẩn bị hồ sơ',   'chuan-bi-ho-so',   'Hỗ trợ chuẩn bị hồ sơ du học',    2),
('Xin COE & Visa',    'xin-coe-visa',     'Hỗ trợ xin COE và visa du học',    3),
('Hỗ trợ sau khi đến','ho-tro-sau-khi-den','Hỗ trợ học viên sau khi đến Nhật', 4);

-- Default settings
INSERT OR IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name',         'Bright Education',           'text',    'Tên website'),
('site_slogan',       'Du học Nhật Bản',            'text',    'Slogan website'),
('site_email',        'contact@brighteducation.net',     'text',    'Email liên hệ'),
('site_phone',        '+84 0971044576',               'text',    'Số điện thoại'),
('site_phone_jp',     '+81 08037316436',              'text',    'Số điện thoại Nhật Bản'),
('site_address',      '207 Quang Trung, Thành Đông, Hải Phòng','text',   'Địa chỉ'),
('facebook_url',      'https://facebook.com/brighteducation', 'text', 'Facebook URL'),
('youtube_url',       'https://youtube.com/brighteducation',  'text', 'Youtube URL'),
('messenger_id',      '491649064036887',            'text',    'Messenger Page ID'),
('maintenance_mode',  '0',                          'boolean', 'Chế độ bảo trì'),
('posts_per_page',    '12',                         'number',  'Số bài viết mỗi trang'),
('allow_registration','0',                          'boolean', 'Cho phép đăng ký');

-- ============================================================
-- Table: qa_questions
-- ============================================================
CREATE TABLE IF NOT EXISTS qa_questions (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id        INTEGER,
    author_name    TEXT NOT NULL,
    content        TEXT NOT NULL,
    likes_count    INTEGER NOT NULL DEFAULT 0,
    status         TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','hidden')),
    created_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_qa_questions_status ON qa_questions(status);
CREATE INDEX IF NOT EXISTS idx_qa_questions_created ON qa_questions(created_at);

-- ============================================================
-- Table: qa_answers
-- ============================================================
CREATE TABLE IF NOT EXISTS qa_answers (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    question_id    INTEGER NOT NULL,
    user_id        INTEGER,
    author_name    TEXT NOT NULL,
    content        TEXT NOT NULL,
    likes_count    INTEGER NOT NULL DEFAULT 0,
    status         TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','hidden')),
    created_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    updated_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    FOREIGN KEY (question_id) REFERENCES qa_questions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_qa_answers_question ON qa_answers(question_id);
CREATE INDEX IF NOT EXISTS idx_qa_answers_status ON qa_answers(status);
