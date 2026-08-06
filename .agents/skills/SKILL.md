---
name: bright_edu_server_capabilities
description: Mô tả thông tin cấu hình máy chủ, bảng tra cứu hành động theo quyền hạn (Scopes) và quy tắc ứng xử bắt buộc dành cho các AI Agent trên hệ thống Bright Education.
---

# Quyền hạn & Cấu hình Máy chủ dành cho AI Agent (Bright Education)

Tài liệu này cung cấp mô tả tổng quan về cấu trúc máy chủ, bảng phân quyền chi tiết (Scopes) cho từng hành động API, và các quy tắc ứng xử bắt buộc đối với các trợ lý AI khi làm việc trên dự án.

## 1. Thông tin Cấu hình Máy chủ & Kết nối (Server Environment & Connection)

- **Địa chỉ gọi API:** `https://brighteducation.net/api/agent`
- **Cơ sở dữ liệu:** Hệ thống sử dụng cơ sở dữ liệu SQLite tại đường dẫn `database/bright_edu.db`.
- **Nhật ký Hệ thống (Audit Logs):** Mọi hành vi làm thay đổi dữ liệu của AI Agent (tạo nháp, sửa bài, gửi duyệt...) đều được tự động ghi lại vào bảng `audit_logs` trên máy chủ với tiền tố `agent_` để Quản trị viên dễ dàng giám sát nguồn gốc thay đổi.

### Quy chuẩn kết nối kỹ thuật:
- **Phương thức xác thực:** Chỉ chấp nhận `Authorization: Bearer <MÃ_TOKEN>` được truyền qua HTTP Header. Tuyệt đối không truyền mã qua URL query string.
- **HTTP Headers yêu cầu:**
  ```http
  Authorization: Bearer <MÃ_TOKEN_CỦA_AI>
  Content-Type: application/json
  Idempotency-Key: <MÃ_UUID_NGẪU_NHIÊN> (Bắt buộc cho phương thức POST)
  ```

### Ví dụ kết nối nhanh bằng cURL:
```bash
curl -X GET "https://brighteducation.net/api/agent?action=seo" \
     -H "Authorization: Bearer your_ai_token_here" \
     -H "Content-Type: application/json"
```

### Ví dụ kết nối bằng Python (requests):
```python
import requests
import uuid

url = "https://brighteducation.net/api/agent?action=create_draft"
headers = {
    "Authorization": "Bearer your_ai_token_here",
    "Content-Type": "application/json",
    "Idempotency-Key": str(uuid.uuid4())
}
payload = {
    "title": "Chi phí du học Nhật Bản 2026",
    "content": "<h2>Học phí và sinh hoạt phí</h2>...",
    "category_id": 1
}

response = requests.post(url, json=payload, headers=headers)
print(response.json())
```

---

## 2. Bảng phân quyền chi tiết (Scopes & API Actions)

Mỗi khóa Token được gán một danh sách các quyền hạn (Scopes) ngăn cách bởi dấu phẩy. AI Agent chỉ có thể gọi các hành động (Actions) tương ứng với Scope đó:

| API Action (`?action=`) | Phương thức | Ý nghĩa chức năng | Quyền hạn yêu cầu (Scope) |
| :--- | :--- | :--- | :--- |
| **`seo`** | `GET` | Xem kế hoạch từ khóa, KPI chiến dịch và cụm chủ đề | `seo:read` hoặc `admin` |
| **`analytics`** | `GET` | Xem báo cáo đo lường tổng hợp GA4 và Search Console | `analytics:read` hoặc `admin` |
| **`page_performance`** | `GET` | Xem dữ liệu hiệu suất trang gộp chi tiết theo từng URL | `analytics:read` hoặc `admin` |
| **`opportunities`** | `GET` | Nhận danh sách các cơ hội SEO tự động (Opportunities Engine) | `analytics:read` or `admin` |
| **`posts`** | `GET` | Xem toàn bộ danh sách bài viết hiện có trên hệ thống | `posts:read` hoặc `admin` |
| **`categories`** | `GET` | Xem danh sách các danh mục bài viết đang hoạt động | `posts:read` hoặc `admin` |
| **`list_revisions`** | `GET` | Xem lịch sử các phiên bản cũ của một bài viết cụ thể | `posts:read` hoặc `admin` |
| **`validate_post`** | `POST` | Kiểm toán điểm chất lượng SEO và rà quét mã độc XSS | `posts:draft` hoặc `admin` |
| **`process_draft`** | `POST` | Gợi ý cấu trúc Heading (TOC), từ khóa LSI phù hợp | `posts:draft` hoặc `admin` |
| **`create_draft`** | `POST` | Tạo bài viết mới ở trạng thái bản thảo AI (`ai_draft`) | `posts:draft` hoặc `admin` |
| **`update_post`** | `POST` | Cập nhật nội dung bài viết cũ (yêu cầu khóa lạc quan) | `posts:draft` hoặc `admin` |
| **`submit_for_review`** | `POST` | Gửi bài viết cho biên tập viên duyệt (`pending_review`) | `posts:draft` hoặc `admin` |
| **`restore_revision`** | `POST` | Khôi phục bài viết về một phiên bản lịch sử trước đó | `posts:draft` hoặc `admin` |
| **`approve_post`** | `POST` | Duyệt bài viết chuyển trạng thái sang `approved` | `posts:publish` hoặc `admin` |
| **`publish_post`** | `POST` | Xuất bản bài viết hiển thị công khai trên website | `posts:publish` hoặc `admin` |

---

## 3. Quy tắc ứng xử bắt buộc dành cho AI Agent

Khi hoạt động trên hệ thống này, các AI Agent phải tuân thủ nghiêm ngặt các quy định sau:

1. **Tuân thủ quy trình duyệt bài (Draft Flow):**
   Không tự ý xuất bản bài viết trực tiếp lên trang web trừ khi được người dùng yêu cầu rõ ràng và Token của bạn có quyền `posts:publish`. Luôn đưa bài viết về trạng thái `ai_draft` hoặc `pending_review` để con người phê duyệt cuối cùng.
2. **Kiểm toán chất lượng trước khi lưu:**
   Luôn gọi hành động `validate_post` để kiểm tra mật độ từ khóa, độ dài Title/Meta, và rà quét an toàn mã độc trước khi gọi lưu dữ liệu.
3. **Phòng ngừa ghi đè chéo (Optimistic Locking):**
   Trước khi sửa bất cứ bài viết nào, phải đọc thời gian cập nhật gần nhất (`updated_at`) và gửi kèm giá trị đó qua biến `expected_updated_at`. Nếu nhận lỗi `409 Conflict`, tuyệt đối không cố gắng ghi đè mà phải cảnh báo cho người dùng hoặc thực hiện đối chiếu mã nguồn thủ công.
4. **Luôn sử dụng Idempotency-Key:**
   Đảm bảo mọi request POST (ghi dữ liệu) đều có header `Idempotency-Key` với giá trị dạng UUID duy nhất cho mỗi giao dịch để tránh tạo bài viết trùng lặp khi mạng bị chập chờn.
