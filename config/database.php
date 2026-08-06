<?php
/**
 * Database Configuration - SQLite
 * Không cần MySQL, chỉ cần PHP với extension pdo_sqlite
 */

// Đường dẫn đến file SQLite database
define('DB_PATH', dirname(dirname(__FILE__)) . '/database/bright_edu.db');

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $this->conn = new PDO('sqlite:' . DB_PATH);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Bật foreign key enforcement (SQLite tắt mặc định)
            $this->conn->exec('PRAGMA foreign_keys = ON');
            // Tăng performance với WAL mode
            $this->conn->exec('PRAGMA journal_mode = WAL');
            $this->conn->exec('PRAGMA synchronous = NORMAL');

            // Tự động khởi tạo schema nếu DB mới (chưa có bảng users)
            $this->initializeIfEmpty();

            // Áp dụng migration cho các bảng mới
            $this->applyMigrations();

        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Khởi tạo schema SQLite lần đầu (nếu DB chưa có bảng)
     */
    private function initializeIfEmpty() {
        $stmt = $this->conn->query(
            "SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='users'"
        );
        $tableExists = (int)$stmt->fetchColumn();
        $stmt->closeCursor();

        if (!$tableExists) {
            $schemaFile = dirname(dirname(__FILE__)) . '/database/schema_sqlite.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $this->conn->exec($sql);
            }
        }
    }

    private function applyMigrations() {
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS consultation_slots (
                id                  INTEGER PRIMARY KEY AUTOINCREMENT,
                type                TEXT NOT NULL DEFAULT 'group' CHECK(type IN ('group','individual')),
                title               TEXT NOT NULL,
                description         TEXT,
                zoom_link           TEXT,
                scheduled_date      TEXT NOT NULL,
                time_start          TEXT NOT NULL,
                time_end            TEXT NOT NULL,
                max_participants    INTEGER NOT NULL DEFAULT 30,
                current_participants INTEGER NOT NULL DEFAULT 0,
                is_free             INTEGER NOT NULL DEFAULT 1,
                status              TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','full','cancelled','completed')),
                created_by          INTEGER,
                created_at          TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                updated_at          TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
            );

            CREATE TABLE IF NOT EXISTS consultation_bookings (
                id              INTEGER PRIMARY KEY AUTOINCREMENT,
                slot_id         INTEGER,
                name            TEXT NOT NULL,
                email           TEXT NOT NULL,
                phone           TEXT NOT NULL,
                japanese_level  TEXT,
                booking_type    TEXT NOT NULL DEFAULT 'individual' CHECK(booking_type IN ('group','individual')),
                topic           TEXT,
                preferred_date  TEXT,
                preferred_time  TEXT,
                message         TEXT,
                status          TEXT NOT NULL DEFAULT 'pending' CHECK(status IN ('pending','confirmed','cancelled','completed','no_show')),
                zoom_link       TEXT,
                admin_notes     TEXT,
                ip_address      TEXT,
                created_at      TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                updated_at      TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                FOREIGN KEY (slot_id) REFERENCES consultation_slots(id) ON DELETE SET NULL
            );

            CREATE INDEX IF NOT EXISTS idx_consultation_slots_date   ON consultation_slots(scheduled_date);
            CREATE INDEX IF NOT EXISTS idx_consultation_slots_status ON consultation_slots(status);
            CREATE INDEX IF NOT EXISTS idx_consultation_slots_type   ON consultation_slots(type);
            CREATE INDEX IF NOT EXISTS idx_consultation_bookings_slot   ON consultation_bookings(slot_id);
            CREATE INDEX IF NOT EXISTS idx_consultation_bookings_status ON consultation_bookings(status);
            CREATE INDEX IF NOT EXISTS idx_consultation_bookings_type   ON consultation_bookings(booking_type);

            CREATE TABLE IF NOT EXISTS community_groups (
                id             INTEGER PRIMARY KEY AUTOINCREMENT,
                platform       TEXT NOT NULL DEFAULT 'facebook' CHECK(platform IN ('facebook','zalo','youtube','telegram','other')),
                name           TEXT NOT NULL,
                description    TEXT,
                url            TEXT NOT NULL,
                member_count   TEXT,
                display_order  INTEGER NOT NULL DEFAULT 0,
                status         TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active','inactive')),
                created_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                updated_at     TEXT NOT NULL DEFAULT (datetime('now','localtime'))
            );
            CREATE INDEX IF NOT EXISTS idx_community_groups_status   ON community_groups(status);
            CREATE INDEX IF NOT EXISTS idx_community_groups_platform ON community_groups(platform);
            CREATE INDEX IF NOT EXISTS idx_community_groups_order    ON community_groups(display_order);
        ");
        // Leads CRM
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS leads (
                id              INTEGER PRIMARY KEY AUTOINCREMENT,
                name            TEXT NOT NULL,
                email           TEXT,
                phone           TEXT,
                japanese_level  TEXT,
                intake_period   TEXT,
                source          TEXT NOT NULL DEFAULT 'manual'
                                CHECK(source IN ('contact','booking','manual')),
                status          TEXT NOT NULL DEFAULT 'new'
                                CHECK(status IN ('new','contacted','consulting','applied','enrolled','lost')),
                assigned_to     INTEGER REFERENCES users(id) ON DELETE SET NULL,
                notes           TEXT,
                last_contact_at TEXT,
                created_at      TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                updated_at      TEXT NOT NULL DEFAULT (datetime('now','localtime'))
            );
            CREATE INDEX IF NOT EXISTS idx_leads_email  ON leads(email);
            CREATE INDEX IF NOT EXISTS idx_leads_status ON leads(status);
            CREATE INDEX IF NOT EXISTS idx_leads_source ON leads(source);

            CREATE TABLE IF NOT EXISTS lead_activities (
                id           INTEGER PRIMARY KEY AUTOINCREMENT,
                lead_id      INTEGER NOT NULL REFERENCES leads(id) ON DELETE CASCADE,
                type         TEXT NOT NULL DEFAULT 'note'
                             CHECK(type IN ('note','call','email','meeting','status_change','system')),
                content      TEXT,
                old_status   TEXT,
                new_status   TEXT,
                source_table TEXT,
                source_id    INTEGER,
                created_by   INTEGER REFERENCES users(id) ON DELETE SET NULL,
                created_at   TEXT NOT NULL DEFAULT (datetime('now','localtime'))
            );
            CREATE INDEX IF NOT EXISTS idx_lead_act_lead   ON lead_activities(lead_id);
            CREATE INDEX IF NOT EXISTS idx_lead_act_src    ON lead_activities(source_table, source_id);

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

            CREATE TABLE IF NOT EXISTS analytics_cache (
                cache_key  TEXT PRIMARY KEY,
                payload    TEXT NOT NULL,
                expires_at TEXT NOT NULL,
                updated_at TEXT NOT NULL DEFAULT (datetime('now','localtime'))
            );
            CREATE INDEX IF NOT EXISTS idx_analytics_cache_expires ON analytics_cache(expires_at);

            CREATE TABLE IF NOT EXISTS seo_topic_clusters (
                id             INTEGER PRIMARY KEY AUTOINCREMENT,
                planning_month TEXT NOT NULL,
                name           TEXT NOT NULL,
                pillar_title   TEXT NOT NULL,
                pillar_url     TEXT,
                description    TEXT,
                status         TEXT NOT NULL DEFAULT 'planned'
                               CHECK(status IN ('planned','in_progress','published')),
                created_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                updated_at     TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                UNIQUE(planning_month, name)
            );
            CREATE INDEX IF NOT EXISTS idx_seo_clusters_month ON seo_topic_clusters(planning_month);

            CREATE TABLE IF NOT EXISTS seo_keyword_map (
                id               INTEGER PRIMARY KEY AUTOINCREMENT,
                planning_month   TEXT NOT NULL,
                keyword          TEXT NOT NULL,
                intent           TEXT NOT NULL
                                 CHECK(intent IN ('informational','navigational','commercial','transactional')),
                target_url       TEXT,
                cluster_id       INTEGER,
                content_role     TEXT NOT NULL DEFAULT 'satellite'
                                 CHECK(content_role IN ('pillar','satellite','standalone')),
                is_long_tail     INTEGER NOT NULL DEFAULT 0,
                priority         TEXT NOT NULL DEFAULT 'medium'
                                 CHECK(priority IN ('high','medium','low')),
                conversion_score INTEGER NOT NULL DEFAULT 0 CHECK(conversion_score BETWEEN 0 AND 100),
                search_volume    INTEGER NOT NULL DEFAULT 0,
                difficulty       INTEGER NOT NULL DEFAULT 0 CHECK(difficulty BETWEEN 0 AND 100),
                status           TEXT NOT NULL DEFAULT 'idea'
                                 CHECK(status IN ('idea','brief','writing','published')),
                notes            TEXT,
                created_at       TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                updated_at       TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                FOREIGN KEY (cluster_id) REFERENCES seo_topic_clusters(id) ON DELETE SET NULL,
                UNIQUE(planning_month, keyword)
            );
            CREATE INDEX IF NOT EXISTS idx_seo_keywords_month ON seo_keyword_map(planning_month);
            CREATE INDEX IF NOT EXISTS idx_seo_keywords_intent ON seo_keyword_map(intent);
            CREATE INDEX IF NOT EXISTS idx_seo_keywords_url ON seo_keyword_map(target_url);
            CREATE TABLE IF NOT EXISTS ai_agent_tokens (
                id                INTEGER PRIMARY KEY AUTOINCREMENT,
                token_name        TEXT NOT NULL,
                token_hash        TEXT NOT NULL UNIQUE,
                permissions       TEXT NOT NULL DEFAULT 'read_seo,read_analytics,manage_content',
                default_author_id INTEGER,
                last_used_at      TEXT,
                created_at        TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                FOREIGN KEY (default_author_id) REFERENCES users(id) ON DELETE SET NULL
            );
            CREATE INDEX IF NOT EXISTS idx_ai_agent_tokens_hash ON ai_agent_tokens(token_hash);
        ");
        try { $this->conn->exec("ALTER TABLE community_groups ADD COLUMN image TEXT"); } catch (\Exception $e) {}
        try { $this->conn->exec("ALTER TABLE qa_questions ADD COLUMN image TEXT"); } catch (\Exception $e) {}
        try { $this->conn->exec("ALTER TABLE qa_questions ADD COLUMN tags TEXT"); } catch (\Exception $e) {}
        try { $this->conn->exec("ALTER TABLE qa_questions ADD COLUMN bg_style TEXT"); } catch (\Exception $e) {}

        // --- Migration for AI Agent system upgrades ---
        try { $this->conn->exec("ALTER TABLE ai_agent_tokens ADD COLUMN expires_at TEXT"); } catch (\Exception $e) {}
        try { $this->conn->exec("ALTER TABLE ai_agent_tokens ADD COLUMN revoked_at TEXT"); } catch (\Exception $e) {}
        try { $this->conn->exec("ALTER TABLE ai_agent_tokens ADD COLUMN last_ip TEXT"); } catch (\Exception $e) {}
        try { $this->conn->exec("ALTER TABLE ai_agent_tokens ADD COLUMN last_user_agent TEXT"); } catch (\Exception $e) {}
        try { $this->conn->exec("ALTER TABLE ai_agent_tokens ADD COLUMN request_count INTEGER DEFAULT 0"); } catch (\Exception $e) {}
        try { $this->conn->exec("ALTER TABLE ai_agent_tokens ADD COLUMN allowed_ips TEXT"); } catch (\Exception $e) {}

        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS post_revisions (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                post_id     INTEGER NOT NULL,
                title       TEXT NOT NULL,
                slug        TEXT NOT NULL,
                excerpt     TEXT,
                content     TEXT,
                meta_title  TEXT,
                meta_description TEXT,
                meta_keywords TEXT,
                author_id   INTEGER,
                action      TEXT, -- 'created', 'updated', 'restored'
                changed_by  TEXT, -- token ID or username
                created_at  TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS ai_agent_rate_limits (
                token_id      INTEGER NOT NULL,
                minute_bucket TEXT NOT NULL,
                request_count INTEGER NOT NULL DEFAULT 0,
                PRIMARY KEY (token_id, minute_bucket)
            );

            CREATE TABLE IF NOT EXISTS api_idempotency_keys (
                idempotency_key TEXT PRIMARY KEY,
                response_payload TEXT NOT NULL,
                created_at TEXT NOT NULL DEFAULT (datetime('now','localtime'))
            );
        ");

        $stmtCheck = $this->conn->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='posts'");
        $postsSql = $stmtCheck->fetchColumn();
        $stmtCheck->closeCursor();
        if ($postsSql && strpos($postsSql, 'pending_review') === false) {
            $this->conn->exec("
                PRAGMA foreign_keys = OFF;
                DROP TABLE IF EXISTS posts_new;
                CREATE TABLE posts_new (
                    id               INTEGER PRIMARY KEY AUTOINCREMENT,
                    title            TEXT NOT NULL,
                    slug             TEXT NOT NULL UNIQUE,
                    excerpt          TEXT,
                    content          TEXT,
                    featured_image   TEXT,
                    category_id      INTEGER,
                    author_id        INTEGER NOT NULL,
                    status           TEXT NOT NULL DEFAULT 'draft' CHECK(status IN ('draft','published','archived','ai_draft','pending_review','changes_requested','approved','scheduled')),
                    featured         INTEGER NOT NULL DEFAULT 0,
                    views            INTEGER NOT NULL DEFAULT 0,
                    meta_title       TEXT,
                    meta_description TEXT,
                    meta_keywords    TEXT,
                    published_at     TEXT,
                    created_at       TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                    updated_at       TEXT NOT NULL DEFAULT (datetime('now','localtime')),
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
                    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
                );
                INSERT INTO posts_new (id, title, slug, excerpt, content, featured_image, category_id, author_id, status, featured, views, meta_title, meta_description, meta_keywords, published_at, created_at, updated_at)
                SELECT id, title, slug, excerpt, content, featured_image, category_id, author_id, status, featured, views, meta_title, meta_description, meta_keywords, published_at, created_at, updated_at FROM posts;
                DROP TABLE posts;
                ALTER TABLE posts_new RENAME TO posts;
                
                CREATE INDEX IF NOT EXISTS idx_posts_slug       ON posts(slug);
                CREATE INDEX IF NOT EXISTS idx_posts_status     ON posts(status);
                CREATE INDEX IF NOT EXISTS idx_posts_category   ON posts(category_id);
                CREATE INDEX IF NOT EXISTS idx_posts_author     ON posts(author_id);
                CREATE INDEX IF NOT EXISTS idx_posts_featured   ON posts(featured);
                CREATE INDEX IF NOT EXISTS idx_posts_published  ON posts(published_at);
                
                DROP TRIGGER IF EXISTS trg_posts_updated_at;
                CREATE TRIGGER trg_posts_updated_at
                AFTER UPDATE ON posts
                FOR EACH ROW
                BEGIN
                    UPDATE posts SET updated_at = datetime('now','localtime') WHERE id = OLD.id;
                END;
                PRAGMA foreign_keys = ON;
            ");
        }

        // --- Migration for updating email and phone ---
        $this->conn->exec("UPDATE settings SET setting_value = 'contact@brighteducation.net' WHERE setting_key = 'site_email' AND setting_value = 'japan@brightconnect.vn'");
        $this->conn->exec("UPDATE settings SET setting_value = '+84 0971044576' WHERE setting_key = 'site_phone' AND setting_value = '0981 456 789'");
        $stmt = $this->conn->query("SELECT COUNT(*) FROM settings WHERE setting_key = 'site_phone_jp'");
        if ((int)$stmt->fetchColumn() === 0) {
            $this->conn->exec("INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES ('site_phone_jp', '+81 08037316436', 'text', 'Số điện thoại Nhật Bản')");
        }
        $this->conn->exec("UPDATE settings SET setting_value = 'Số 45 ngõ 207 Quang Trung, Phường Thành Đông, TP Hải Phòng, Việt Nam' WHERE setting_key = 'site_address' AND setting_value = '207 Quang Trung, Thành Đông, Hải Phòng'");
        $stmt = $this->conn->query("SELECT COUNT(*) FROM settings WHERE setting_key = 'legal_entity_name'");
        if ((int)$stmt->fetchColumn() === 0) {
            $this->conn->exec("INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES ('legal_entity_name', 'VICTORIA UNIVERSAL CO.,LTD', 'text', 'Tên pháp nhân hoạt động')");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }

    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    public function commit() {
        return $this->conn->commit();
    }

    public function rollBack() {
        return $this->conn->rollBack();
    }

    public function exec($sql) {
        return $this->conn->exec($sql);
    }
}
