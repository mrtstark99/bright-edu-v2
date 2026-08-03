import sqlite3
import os

db_path = "database/bright_edu.db"
if os.path.exists(db_path):
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute("UPDATE settings SET setting_value = 'contact@brighteducation.net' WHERE setting_key = 'site_email'")
    c.execute("UPDATE settings SET setting_value = '+84 0971044576' WHERE setting_key = 'site_phone'")
    
    c.execute("SELECT COUNT(*) FROM settings WHERE setting_key = 'site_phone_jp'")
    if c.fetchone()[0] > 0:
        c.execute("UPDATE settings SET setting_value = '+81 08037316436' WHERE setting_key = 'site_phone_jp'")
    else:
        c.execute("INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES ('site_phone_jp', '+81 08037316436', 'text', 'Số điện thoại Nhật Bản')")
    
    conn.commit()
    conn.close()
    print("Database updated")
else:
    print("DB not found")
