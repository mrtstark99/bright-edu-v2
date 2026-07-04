import os
import re

email_old = r"japan@brightconnect\.vn"
email_new = "contact@brighteducation.net"
phone_old = r"0981 456 789"
phone_new = "+84 0971044576"

files_to_check = [
    "admin/settings.php",
    "api/contact.php",
    "database/schema_sqlite.sql",
    "includes/footer.php",
    "pages/contact.php",
    "pages/home.php"
]

for file_path in files_to_check:
    if os.path.exists(file_path):
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        content = re.sub(email_old, email_new, content)
        content = re.sub(phone_old, phone_new, content)
        
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
print("Replaced email and phone 1")
