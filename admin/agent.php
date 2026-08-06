<?php
/**
 * AI Agent Management Panel (Upgraded Version)
 * Allows administrators to generate API keys with custom Scopes, IP allowlists, and track requests.
 */

require_once dirname(__DIR__) . '/config/config.php';
requireAdmin();

$db = Database::getInstance();
$errors = [];
$success = $_SESSION['agent_success'] ?? '';
$new_token = $_SESSION['new_agent_token'] ?? null;

unset($_SESSION['agent_success'], $_SESSION['new_agent_token']);

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'generate') {
            $name = sanitizeInput($_POST['token_name'] ?? '');
            $author_id = (int)($_POST['default_author_id'] ?? 0);
            $allowed_ips = sanitizeInput($_POST['allowed_ips'] ?? '');
            
            // Collect scopes
            $selectedScopes = $_POST['scopes'] ?? [];
            if (empty($selectedScopes)) {
                $errors[] = 'Vui lòng chọn ít nhất một quyền hạn (Scope) cho AI Agent.';
            }
            
            if ($name === '') {
                $errors[] = 'Vui lòng nhập tên nhận diện cho AI Agent.';
            }
            if ($author_id <= 0) {
                $errors[] = 'Vui lòng chọn tác giả mặc định.';
            }

            if (!$errors) {
                $permissions = implode(',', $selectedScopes);
                
                // Generate secure random raw token
                $rawToken = 'ai_agent_' . bin2hex(random_bytes(24));
                $tokenHash = hash('sha256', $rawToken);

                $stmt = $db->prepare("
                    INSERT INTO ai_agent_tokens (token_name, token_hash, default_author_id, permissions, allowed_ips) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$name, $tokenHash, $author_id, $permissions, $allowed_ips === '' ? null : $allowed_ips]);

                $_SESSION['agent_success'] = 'Đã tạo thành công Token cho AI Agent.';
                $_SESSION['new_agent_token'] = [
                    'name' => $name,
                    'raw' => $rawToken
                ];

                header('Location: /admin/agent');
                exit;
            }
        } elseif ($action === 'revoke') {
            $id = (int)($_POST['revoke_token_id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM ai_agent_tokens WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['agent_success'] = 'Đã thu hồi quyền truy cập của Token thành công.';
                header('Location: /admin/agent');
                exit;
            }
        }
    }
}

// Fetch users for Author list (Admins & Editors only)
$stmtUsers = $db->prepare("SELECT id, full_name, role FROM users WHERE role IN ('admin', 'editor') AND status = 'active' ORDER BY full_name");
$stmtUsers->execute();
$authors = $stmtUsers->fetchAll();

// Fetch current active tokens
$stmtTokens = $db->prepare("
    SELECT t.*, u.full_name as author_name 
    FROM ai_agent_tokens t 
    LEFT JOIN users u ON t.default_author_id = u.id 
    ORDER BY t.created_at DESC
");
$stmtTokens->execute();
$tokens = $stmtTokens->fetchAll();

$page_title = 'Cấu hình AI Agent';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<div class="page-header mb-6">
    <div>
        <h1 class="text-2xl font-bold text-midnight font-display">Cấu hình AI Agent</h1>
        <p class="text-sm text-slate-500">Thiết lập token bảo mật với cơ chế phân quyền (Scope), giới hạn IP và chống ghi đè cho các trợ lý AI.</p>
    </div>
</div>

<?php if ($success): ?>
<div class="flex items-center gap-3 px-4 py-3 mb-6 rounded-2xl border text-sm font-medium bg-green-50 border-green-200 text-green-800 animate-fade-in">
    <i class="bi bi-check-circle-fill text-green-500 text-base"></i>
    <span><?php echo $success; ?></span>
</div>
<?php endif; ?>

<?php if ($errors): ?>
<div class="bg-red-50 text-red-700 border border-red-200 p-4 rounded-2xl mb-6 text-sm font-medium">
    <ul class="m-0 list-disc pl-5">
        <?php foreach ($errors as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php if ($new_token): ?>
<!-- Display Newly Generated Token Alert -->
<div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 mb-8 shadow-soft border-l-4 border-l-amber-500">
    <div class="flex gap-4 items-start">
        <div class="bg-amber-100 text-amber-800 p-3 rounded-2xl shrink-0">
            <i class="bi bi-shield-lock-fill text-2xl"></i>
        </div>
        <div class="flex-1">
            <h4 class="text-amber-900 font-bold font-display text-lg mb-1">Mã Token cho Agent: <?php echo htmlspecialchars($new_token['name']); ?></h4>
            <p class="text-xs text-amber-800 mb-4">
                <strong>QUAN TRỌNG:</strong> Đây là lần duy nhất mã Token này hiển thị. Hãy sao chép và lưu trữ an toàn ngay lập tức. Hệ thống sẽ KHÔNG thể phục hồi mã thô này sau khi tải lại trang.
            </p>
            
            <div class="flex items-center gap-2 bg-white border border-amber-200 rounded-2xl p-3 max-w-2xl font-mono text-sm break-all select-all shadow-inner">
                <span class="text-slate-800 flex-1 font-semibold" id="raw-token-str"><?php echo htmlspecialchars($new_token['raw']); ?></span>
                <button onclick="copyTokenToClipboard()" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold py-2 px-4 rounded-xl transition-colors shrink-0 flex items-center gap-2 shadow-sm">
                    <i class="bi bi-copy"></i> Sao chép
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function copyTokenToClipboard() {
    var tokenText = document.getElementById('raw-token-str').innerText;
    navigator.clipboard.writeText(tokenText).then(function() {
        alert('Đã sao chép token vào bộ nhớ tạm!');
    }, function() {
        alert('Lỗi khi sao chép. Vui lòng chọn thủ công.');
    });
}
</script>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-8">
    <!-- Left Column: Manage Access -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Token Generator -->
        <div class="a-card">
            <div class="a-card-header">
                <h2>Tạo Token kết nối mới</h2>
            </div>
            <div class="a-card-body">
                <form method="POST">
                    <?php echo csrfField(); ?>
                    <input type="hidden" name="action" value="generate">

                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="a-field">
                            <label class="a-label">Tên Agent nhận diện (ví dụ: Cursor IDE, n8n Automation)</label>
                            <input type="text" name="token_name" class="a-input" placeholder="Nhập tên nhận diện..." required>
                        </div>
                        
                        <div class="a-field">
                            <label class="a-label">Tác giả mặc định khi viết bài</label>
                            <select name="default_author_id" class="a-input" required>
                                <option value="">-- Chọn tác giả --</option>
                                <?php foreach ($authors as $auth): ?>
                                    <option value="<?php echo $auth['id']; ?>">
                                        <?php echo htmlspecialchars($auth['full_name']); ?> (<?php echo htmlspecialchars($auth['role']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="a-field">
                        <label class="a-label">IP Allowlist (Không bắt buộc, nhập danh sách IP cách nhau bởi dấu phẩy)</label>
                        <input type="text" name="allowed_ips" class="a-input" placeholder="Ví dụ: 192.168.1.1, 45.123.54.12">
                    </div>

                    <!-- Scope selection checkboxes -->
                    <div class="a-field">
                        <label class="a-label mb-2">Quyền hạn truy cập (Scopes) <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-slate-50 border border-slate-200 rounded-2xl p-4">
                            <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="scopes[]" value="seo:read" class="mt-0.5" checked>
                                <div>
                                    <strong class="text-midnight block">seo:read</strong>
                                    Xem kế hoạch từ khóa, KPI và cụm chủ đề SEO.
                                </div>
                            </label>
                            <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="scopes[]" value="seo:write" class="mt-0.5">
                                <div>
                                    <strong class="text-midnight block">seo:write</strong>
                                    Thêm, sửa và xóa từ khóa trong kế hoạch SEO.
                                </div>
                            </label>
                            <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="scopes[]" value="analytics:read" class="mt-0.5" checked>
                                <div>
                                    <strong class="text-midnight block">analytics:read</strong>
                                    Xem dữ liệu GA4, Search Console, Opportunity Engine.
                                </div>
                            </label>
                            <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="scopes[]" value="posts:read" class="mt-0.5" checked>
                                <div>
                                    <strong class="text-midnight block">posts:read</strong>
                                    Xem danh sách bài viết, danh mục và lịch sử chỉnh sửa.
                                </div>
                            </label>
                            <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="scopes[]" value="posts:draft" class="mt-0.5" checked>
                                <div>
                                    <strong class="text-midnight block">posts:draft</strong>
                                    Tạo nháp (draft), cập nhật nội dung và gửi duyệt bài viết.
                                </div>
                            </label>
                            <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="scopes[]" value="posts:publish" class="mt-0.5">
                                <div>
                                    <strong class="text-midnight block">posts:publish</strong>
                                    Phê duyệt (approve) và Xuất bản trực tiếp (publish) bài viết.
                                </div>
                            </label>
                            <label class="flex items-start gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="scopes[]" value="admin" class="mt-0.5">
                                <div>
                                    <strong class="text-midnight block text-red-600">admin</strong>
                                    Bỏ qua mọi kiểm tra scope, cấp toàn quyền tuyệt đối.
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="btn-adm shadow-sm">
                            <i class="bi bi-key-fill"></i> Tạo mã Token
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Token Listing -->
        <div class="a-card">
            <div class="a-card-header">
                <h2>Các khóa truy cập đang hoạt động</h2>
            </div>
            <div class="a-card-body p-0">
                <?php if (empty($tokens)): ?>
                    <div class="p-6 text-center text-slate-500 text-sm">
                        <i class="bi bi-shield-slash text-3xl block mb-2 text-slate-300"></i>
                        Chưa có token nào hoạt động.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="a-table">
                            <thead>
                                <tr>
                                    <th>Tên Agent / Tác giả</th>
                                    <th>Quyền hạn (Scopes)</th>
                                    <th>IP Allowlist</th>
                                    <th>Thống kê gọi</th>
                                    <th>Hoạt động cuối</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tokens as $tk): ?>
                                    <tr>
                                        <td>
                                            <div class="font-bold text-midnight"><?php echo htmlspecialchars($tk['token_name']); ?></div>
                                            <div class="text-[11px] text-slate-400">Tác giả: <?php echo htmlspecialchars($tk['author_name'] ?? 'Không rõ'); ?></div>
                                        </td>
                                        <td>
                                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                                <?php foreach (explode(',', $tk['permissions']) as $sc): ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-medium <?php echo $sc === 'admin' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-100'; ?>">
                                                        <?php echo htmlspecialchars($sc); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td class="text-xs font-mono">
                                            <?php if ($tk['allowed_ips']): ?>
                                                <span class="text-slate-700" title="<?php echo htmlspecialchars($tk['allowed_ips']); ?>">
                                                    <?php echo truncateText($tk['allowed_ips'], 15); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400 italic">Không giới hạn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="text-xs font-medium text-slate-700">Calls: <span class="font-mono text-midnight font-bold"><?php echo (int)$tk['request_count']; ?></span></div>
                                            <?php if ($tk['last_ip']): ?>
                                                <div class="text-[10px] text-slate-400 font-mono">IP: <?php echo htmlspecialchars($tk['last_ip']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($tk['last_used_at']): ?>
                                                <span class="text-xs font-medium text-slate-600" title="Chi tiết: <?php echo htmlspecialchars($tk['last_user_agent']); ?>">
                                                    <?php echo date('d/m/Y H:i', strtotime($tk['last_used_at'])); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-xs text-slate-400 italic">Chưa kết nối</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn thu hồi Token này không? Trợ lý AI đang liên kết sẽ mất quyền kết nối ngay lập tức.');" class="inline-block">
                                                <?php echo csrfField(); ?>
                                                <input type="hidden" name="action" value="revoke">
                                                <input type="hidden" name="revoke_token_id" value="<?php echo $tk['id']; ?>">
                                                <button type="submit" class="btn-icon btn-icon-del" title="Thu hồi khóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Right Column: Integration Documentation -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden sticky top-6">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h5 class="m-0 font-bold text-midnight font-display text-lg flex items-center gap-2">
                    <i class="bi bi-shield-check text-emerald-500"></i> Hướng dẫn kết nối an toàn
                </h5>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <div>
                    <h6 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">1. Xác thực HTTP header</h6>
                    <p class="text-xs text-slate-500 leading-relaxed mb-3">
                        Gửi khóa Token thông qua Header. Hệ thống không cho phép truyền token trực tiếp trên URL (?token=) để ngăn rò rỉ log:
                    </p>
                    <pre class="bg-slate-900 text-slate-300 rounded-xl p-3 font-mono text-[10px] overflow-x-auto shadow-inner">Authorization: Bearer &lt;TOKEN_KEY&gt;</pre>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <h6 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">2. Lấy dữ liệu hiệu suất trang gộp</h6>
                    <p class="text-xs text-slate-500 leading-relaxed mb-2">
                        Endpoint gộp Search Console (Page+Query) với GA4 (Organic search landing page details) theo từng URL:
                    </p>
                    <pre class="bg-slate-900 text-slate-300 rounded-xl p-3 font-mono text-[10px] overflow-x-auto shadow-inner">GET /api/agent?action=page_performance</pre>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <h6 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">3. Opportunity Engine</h6>
                    <p class="text-xs text-slate-500 leading-relaxed mb-2">
                        Đọc danh sách cơ hội SEO được tính toán tự động dựa trên quy tắc (Top 10 CTR thấp, Traffic cao Leads thấp, Vị trí sát top 10...):
                    </p>
                    <pre class="bg-slate-900 text-slate-300 rounded-xl p-3 font-mono text-[10px] overflow-x-auto shadow-inner">GET /api/agent?action=opportunities</pre>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <h6 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">4. Kiểm duyệt bài viết phân tầng</h6>
                    <p class="text-xs text-slate-500 leading-relaxed mb-2">
                        Quy trình duyệt bài viết của AI:
                    </p>
                    <ol class="text-xs text-slate-500 list-decimal pl-4 mb-3 space-y-1">
                        <li>AI tạo bài nháp: <code>action=create_draft</code> (lưu dạng nháp AI).</li>
                        <li>AI hoặc Editor gửi duyệt: <code>action=submit_for_review</code>.</li>
                        <li>Admin duyệt hoặc xuất bản: <code>action=publish_post</code>.</li>
                    </ol>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <h6 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">5. Chống ghi đè & Gửi trùng</h6>
                    <p class="text-xs text-slate-500 leading-relaxed mb-2">
                        Khi gọi API ghi dữ liệu, bạn nên thêm các cơ chế kiểm soát:
                    </p>
                    <ul class="text-xs text-slate-500 list-disc pl-4 space-y-2">
                        <li><strong>Chống gửi trùng (Idempotency):</strong> Thêm header <code>Idempotency-Key: &lt;KEY_DUY_NHẤT&gt;</code> vào request.</li>
                        <li><strong>Chống ghi đè (Optimistic Locking):</strong> Gửi <code>expected_updated_at</code> khi cập nhật. Nếu bài viết đã bị thay đổi trên server từ trước đó, hệ thống sẽ trả về lỗi <code>409 Conflict</code>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
