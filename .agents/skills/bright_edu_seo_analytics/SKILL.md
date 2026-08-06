---
name: bright_edu_seo_analytics
description: Hướng dẫn AI đo lường chỉ số KPIs, lấy dữ liệu Google Analytics 4 & Search Console gộp, và vận hành Opportunity Engine tối ưu hóa chuyển đổi.
---

# Kỹ năng Đo lường & Phân tích Cơ hội SEO (Bright Education)

Kỹ năng này hướng dẫn trợ lý AI cách thu thập dữ liệu đo lường, ghép nối chỉ số GA4 và Search Console, đồng thời vận hành chu kỳ tối ưu dựa trên Opportunities Engine của hệ thống Bright Education.

## 1. Chu kỳ vận hành SEO tiêu chuẩn
AI Agent cần hoạt động theo vòng lặp 5 bước sau để cải thiện thứ hạng và CRO (tối ưu hóa tỷ lệ chuyển đổi):
```text
Đo dữ liệu → Tìm điểm nghẽn → Triển khai → Kiểm tra sau 28 ngày → Điều chỉnh
```

- **Mục tiêu:** Tập trung tối ưu hóa các trang sẵn có đang có vị trí sát top hoặc có CTR thấp trước khi tạo thêm nội dung mới.
- **Không làm tất cả cùng lúc:** Chỉ ưu tiên các trang/từ khóa có khả năng chuyển đổi hoặc lưu lượng hiển thị cao.

---

## 2. Hệ thống đo lường Chỉ số SEO (KPIs)

Khi thu thập và phân tích dữ liệu, AI Agent phải phân loại theo 4 nhóm chỉ số:

| Nhóm chỉ số | Các chỉ số chính | Phương pháp thu thập |
| :--- | :--- | :--- |
| **1. Hiển thị & Traffic** | Organic Traffic, Impressions, Vị trí (Rankings) | `GET /api/agent?action=seo` |
| **2. Tương tác người dùng**| Click-Through Rate (CTR), Engagement Rate, Avg Engagement Time | `GET /api/agent?action=page_performance` |
| **3. Sức mạnh kỹ thuật** | Core Web Vitals (LCP < 2.5s, INP, CLS < 0.1), Backlinks | Phân tích Search Console & Crawl audit |
| **4. Chỉ số kinh doanh** | Conversion Rate (Tỷ lệ gửi form/leads), Organic ROI | `GET /api/agent?action=page_performance` |

---

## 3. Ghép nối dữ liệu và Tìm điểm nghẽn qua API

Để xác định điểm nghẽn, AI cần gọi hành động `page_performance`:
```http
GET /api/agent?action=page_performance&days=28
```
Dữ liệu trả về sẽ gộp thông tin GA4 và Search Console trên từng Landing Page tương đối:
- **GSC:** `clicks`, `impressions`, `ctr`, `position`, `top_queries` (các từ khóa hàng đầu mang lại clicks).
- **GA4 (Kênh Organic Search):** `organic_sessions`, `engagement_rate`, `avg_engagement_time`, `leads` (số sự kiện chính), `conversion_rate`.

---

## 4. Vận hành Động cơ cơ hội (Opportunity Engine)

Thay vì phân tích thủ công, AI Agent nên truy vấn hành động cơ hội:
```http
GET /api/agent?action=opportunities&days=28
```
Hệ thống sẽ trả về danh sách các cơ hội tối ưu hóa được chấm điểm ưu tiên (`priority_score` từ 0 - 100) dựa trên các quy tắc:

### Cơ hội 1: Tỷ lệ CTR thấp (`type = low_ctr`)
- **Điều kiện:** Vị trí trung bình trong Top 10 (`position` $\le 10$), lượt hiển thị lớn (`impressions` > 200) nhưng tỷ lệ nhấp chuột thấp (`ctr` < 2%).
- **Hành động khuyến nghị:** `rewrite_title_description` (viết lại Title/Meta Description gây tò mò, bổ sung CTA, trả lời trực tiếp Search Intent).

### Cơ hội 2: Vị trí gần Top (`type = striking_distance`)
- **Điều kiện:** Vị trí trung bình nằm trong khoảng từ 4 đến 15 (`position` từ 4 - 15) và có hiển thị tốt (`impressions` > 100).
- **Hành động khuyến nghị:** `optimize_content_and_links` (tối ưu hóa cấu trúc headings, chèn FAQ Schema, bổ sung liên kết nội bộ từ các trang liên quan).

### Cơ hội 3: Chuyển đổi thấp (`type = low_conversion`)
- **Điều kiện:** Lượng truy cập Organic tốt (`sessions` > 50) nhưng không ghi nhận lượt gửi Form / Leads (`leads` = 0).
- **Hành động khuyến nghị:** `improve_cta_and_forms` (đưa CTA lên màn hình đầu tiên, rút gọn form đăng ký, thêm chính sách cam kết).

### Cơ hội 4: Tương tác kém (`type = low_engagement`)
- **Điều kiện:** Lượng truy cập Organic tốt (`sessions` > 30) nhưng tỷ lệ tương tác thấp (`engagement_rate` < 40%).
- **Hành động khuyến nghị:** `improve_readability_and_structure` (sử dụng đoạn văn ngắn, thêm bảng so sánh, bôi đậm ý chính, thiết lập mục lục Table of Contents).
