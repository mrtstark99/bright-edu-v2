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
        ");
        try { $this->conn->exec("ALTER TABLE community_groups ADD COLUMN image TEXT"); } catch (\Exception $e) {}

        // --- Migration for updating email and phone ---
        $this->conn->exec("UPDATE settings SET setting_value = 'contact@brighteducation.net' WHERE setting_key = 'site_email' AND setting_value = 'japan@brightconnect.vn'");
        $this->conn->exec("UPDATE settings SET setting_value = '+84 0971044576' WHERE setting_key = 'site_phone' AND setting_value = '0981 456 789'");
        $stmt = $this->conn->query("SELECT COUNT(*) FROM settings WHERE setting_key = 'site_phone_jp'");
        if ((int)$stmt->fetchColumn() === 0) {
            $this->conn->exec("INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES ('site_phone_jp', '+81 08037316436', 'text', 'Số điện thoại Nhật Bản')");
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
