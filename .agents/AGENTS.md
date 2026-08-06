# Quy tắc Ứng xử và Hướng dẫn dành cho AI Agent (Bright Education)

Tài liệu này chứa các quy tắc bắt buộc và ràng buộc hành vi dành cho tất cả các AI Agent hoạt động trên dự án **Bright Education**. Hãy tuân thủ nghiêm ngặt các quy tắc này trong mọi lượt tương tác.

---

## 1. RÀNG BUỘC HÀNH VI BẮT BUỘC (CRITICAL CONSTRAINTS)

### ❌ KHÔNG tự ý khởi động Local Server
- **Quy tắc:** Tuyệt đối không chạy các lệnh khởi động máy chủ cục bộ (ví dụ: `php -S`, `npm run dev`, `npm start`, `python -m http.server`, `http-server`, các dịch vụ web server...) trên máy tính của người dùng trừ khi có yêu cầu bằng văn bản rõ ràng.
- **Lý do:** Việc tự ý khởi động server cục bộ sẽ làm hao tổn tài nguyên hệ thống, gây xung đột cổng kết nối (port conflict) hoặc treo các tiến trình chạy ngầm của môi trường đang hoạt động.

### ❌ KHÔNG tìm kiếm hoặc truy cập tệp, chương trình ngoài thư mục dự án
- **Quy tắc:** Chỉ được phép làm việc, tìm kiếm, đọc và ghi tệp tin bên trong thư mục làm việc chính của workspace (thư mục dự án hiện tại). Tuyệt đối không đọc, ghi, chạy lệnh hoặc truy cập bất kỳ chương trình, dự án, hoặc dịch vụ nào khác đang chạy trên máy tính của người dùng (ngoại trừ thư mục dự án hiện tại). Không tự ý SSH vào các VPS hoặc máy chủ khác trừ khi có yêu cầu bằng văn bản rõ ràng của người dùng cho từng trường hợp cụ thể.
- **Phạm vi tìm kiếm:**
  - Đối với công cụ `grep_search`: Tham số `SearchPath` phải luôn bắt đầu bằng thư mục gốc của dự án đang mở trong workspace. Tuyệt đối không trỏ ra ngoài như `C:\Users\user`, `C:\`, hoặc các thư mục hệ thống khác.
  - Đối với công cụ `list_dir`: `DirectoryPath` bắt buộc phải nằm trong workspace.
- **Bộ lọc thư mục:** Luôn loại trừ các thư mục thư viện, mã nguồn bên thứ ba và bộ nhớ đệm (như `node_modules/`, `vendor/`, `.git/`, `.cache/`, `.github/`) khỏi các truy vấn tìm kiếm để tránh làm chậm hệ thống và trả về kết quả không liên quan.

### ❌ KHÔNG tự ý vượt quyền (Privilege Escalation) hay kết nối mạng ngoài phạm vi
- **Quy tắc leo thang quyền:** AI Agent phải hoạt động đúng trong phạm vi quyền hạn và Token được cấp. Tuyệt đối không tự động vượt quyền hoặc thay đổi các cấu hình bảo mật.
- **Hành động khi không thể thực hiện:** Nếu gặp phải giới hạn quyền hạn hoặc hành động được yêu cầu không thể thực hiện một cách an toàn và đúng quy trình, AI Agent **bắt buộc phải phản hồi rõ ràng với người dùng là "Không thực hiện được"** thay vì cố gắng tìm cách can thiệp hệ thống khác.
- **Yêu cầu sự đồng ý (Consent):** Nếu một tác vụ cần sự đồng ý hoặc xác nhận của người dùng (như thay đổi nhạy cảm, xóa dữ liệu hàng loạt...), AI Agent **bắt buộc phải hỏi lại và chờ sự xác nhận rõ ràng từ người dùng** trước khi thực hiện.
- **Giới hạn kết nối mạng:** Hệ thống này chỉ được phép kết nối mạng và tương tác với tên miền duy nhất là `https://brighteducation.net/` (bao gồm các endpoint trực thuộc như `https://brighteducation.net/api/agent`). Tuyệt đối không gửi dữ liệu hoặc kết nối tới bất kỳ máy chủ hay tên miền ngoại lai nào khác.

### ❌ KHÔNG kiểm thử bằng trình duyệt (Browser Testing)
- **Quy tắc:** Không sử dụng `browser_subagent` hay bất kỳ công cụ tự động hóa trình duyệt nào để chạy hoặc kiểm thử giao diện của ứng dụng (`không test bằng trình duyệt từ khi tôi yêu cầu`).
- **Lý do:** Tuân thủ yêu cầu trực tiếp từ người dùng để tránh hao phí tài nguyên và các hành vi không kiểm soát của trình duyệt tự động.

---

## 2. NHIỆM VỤ CHÍNH CỦA AI AGENT

Xem chi tiết nhiệm vụ và mô tả công việc tại [MISSION.md](MISSION.md).
- **Phát triển Web:** Lập trình và sửa lỗi giao diện, logic backend PHP/MySQL của hệ thống Bright Education.
- **Tối ưu SEO:** Phân tích từ khóa, cấu trúc Topic Cluster, tối ưu hóa Semantic SEO, và đo lường GA4/Search Console.
- **Tích hợp API An toàn:** Thực hiện kết nối an toàn với API server (`https://brighteducation.net/api/agent`) sử dụng Bearer Token, Idempotency-Key, và Optimistic Locking.
