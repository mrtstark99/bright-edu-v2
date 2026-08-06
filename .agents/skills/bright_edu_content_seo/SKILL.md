---
name: bright_edu_content_seo
description: Hướng dẫn AI soạn thảo nội dung chuẩn SEO, viết theo mô hình Topic Cluster, tối ưu Semantic SEO (LSI) và quản trị vòng đời nội dung.
---

# Kỹ năng Soạn thảo & Tối ưu hóa Nội dung SEO (Bright Education)

Kỹ năng này hướng dẫn trợ lý AI cách phân tích ý định tìm kiếm, xây dựng cấu trúc bài viết chuẩn hóa, tối ưu hóa ngữ nghĩa và quản lý vòng đời nội dung trên hệ thống Bright Education.

## 1. Lập chiến lược Từ khóa & Search Intent

Khi chuẩn bị viết hoặc chỉnh sửa bài viết, AI Agent phải thực hiện Keyword Mapping theo Search Intent:

1. **Ý định thông tin (Information - Blog):** Trả lời các câu hỏi như *Tại sao, Làm thế nào, Hướng dẫn*.
2. **Ý định điều hướng (Navigational):** Người dùng tìm kiếm thương hiệu hoặc dịch vụ cụ thể.
3. **Ý định thương mại (Commercial):** So sánh, đánh giá các trung tâm du học, chi phí.
4. **Ý định giao dịch (Transactional - Trang dịch vụ):** Đăng ký tư vấn du học, nộp hồ sơ.

> [!IMPORTANT]
> **Keyword Mapping:** Đảm bảo 1 URL/bài viết giải quyết 1 ý định tìm kiếm duy nhất để tránh hiện tượng ăn thịt từ khóa (Keyword Cannibalization).

---

## 2. Tiêu chuẩn viết bài chuẩn SEO của Bright Education

### A. Cấu trúc bài viết
- **Inverted Pyramid (Kim tự tháp ngược):** Đưa câu trả lời trực tiếp hoặc thông tin quan trọng nhất lên **100 từ đầu tiên** của bài viết để giữ chân người đọc.
- **Scannability (Tính dễ đọc quét):**
  - Giữ các đoạn văn ngắn từ **2-3 câu** (dưới 50 từ mỗi đoạn).
  - Sử dụng danh sách liệt kê (bullet points, numbered lists) để phân tách ý.
  - Chèn 1 hình ảnh minh họa chất lượng sau mỗi **300 từ**.
- **Mục lục (Table of Contents):** Tự động tạo mục lục ở đầu bài cho các bài viết dài trên 500 từ.

### B. Tối ưu Heading
- **Thẻ H1:** Mỗi bài viết bắt buộc chỉ có **1 thẻ H1 duy nhất** (tiêu đề bài viết), chứa từ khóa chính.
- **Thẻ H2, H3:** Phân cấp logic rõ ràng, chứa từ khóa LSI hoặc các câu hỏi phụ của người dùng.

### C. Tối ưu Metadata
- **Thẻ Title:** Độ dài từ **50 đến 60 ký tự**. Phải chứa từ khóa chính ở đầu và tên thương hiệu ở cuối.
- **Thẻ Meta Description:** Độ dài từ **120 đến 155 ký tự**, tóm tắt cuốn hút và kết thúc bằng một lời kêu gọi hành động (CTA).
- **Cấu trúc URL (Slug):** Độ dài dưới **75 ký tự**, chữ thường, không dấu, ngăn cách bằng gạch ngang `-`, không chứa ký tự đặc biệt.

---

## 3. Semantic SEO (Tối ưu hóa ngữ nghĩa) & E-E-A-T

Để nâng cao sức mạnh chuyên môn của nội dung, AI Agent cần:

- **Từ khóa LSI (Latent Semantic Indexing):** Sử dụng các từ đồng nghĩa, thuật ngữ ngành liên quan để tạo ngữ cảnh ngữ nghĩa phong phú cho Google Bot (sử dụng action `process_draft` để lấy danh sách từ khóa khớp ngữ cảnh).
- **Định dạng Featured Snippet:** Viết các câu định nghĩa ngắn từ **40-50 từ** hoặc các bảng so sánh số liệu ngay dưới các thẻ H2 để tối ưu hóa vị trí Top 0.
- **Entity & Author Authority:** Luôn gắn thẻ liên kết tác giả là chuyên gia (role `admin`/`editor` đã được đăng ký) để tuân thủ tiêu chí E-E-A-T của Google.

---

## 4. Quản trị Vòng đời Nội dung

AI Agent cần thực hiện 2 hoạt động sau để duy trì sức mạnh nội dung của website:

1. **Historical Optimization (Tối ưu hóa bài cũ):** Định kỳ 6-12 tháng quét các bài viết ở trang 2 (vị trí 11-20). Thực hiện bổ sung thông tin mới, cập nhật bảng giá chi phí du học, và chèn thêm FAQ Schema.
2. **Content Pruning (Cắt tỉa nội dung):** Quét các trang không có traffic trong vòng 1 năm qua. Thực hiện gộp (merge) các bài viết ngắn trùng lặp chủ đề vào bài viết chính (Pillar Page), hoặc xóa và chuyển hướng 301 về danh mục chính.
