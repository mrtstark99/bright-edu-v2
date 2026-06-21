<?php
require_once dirname(__DIR__) . '/config/config.php';
requireEditor();

$db = Database::getInstance();

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'create' || $action === 'edit') {
        $id           = (int)($_POST['group_id'] ?? 0);
        $platform     = in_array($_POST['platform'] ?? '', ['facebook','zalo','youtube','telegram','other'])
                        ? $_POST['platform'] : 'facebook';
        $name         = trim($_POST['name'] ?? '');
        $description  = trim($_POST['description'] ?? '');
        $url          = trim($_POST['url'] ?? '');
        $member_count = trim($_POST['member_count'] ?? '');
        $display_order= (int)($_POST['display_order'] ?? 0);
        $status       = ($_POST['status'] ?? '') === 'inactive' ? 'inactive' : 'active';
        $image        = trim($_POST['current_image'] ?? ''); // existing filepath

        if (!$name || !$url) {
            redirect('/admin/groups', 'Vui lòng điền tên và URL nhóm.', 'danger');
        }

        // Handle remove image
        if (!empty($_POST['remove_image'])) {
            if ($image) deleteFile($image);
            $image = '';
        }

        // Handle new image upload
        if (!empty($_FILES['image']['name'])) {
            $upload = uploadImage($_FILES['image'], 'groups');
            if ($upload['success']) {
                if ($image) deleteFile($image); // xóa ảnh cũ
                $image = $upload['filepath'];
            } else {
                redirect('/admin/groups', 'Lỗi upload ảnh: ' . $upload['message'], 'danger');
            }
        }

        if ($action === 'create') {
            $stmt = $db->prepare("
                INSERT INTO community_groups (platform, name, description, url, member_count, display_order, status, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$platform, $name, $description ?: null, $url, $member_count ?: null, $display_order, $status, $image ?: null]);
            redirect('/admin/groups', 'Đã thêm nhóm mới.', 'success');
        } else {
            $stmt = $db->prepare("
                UPDATE community_groups
                SET platform=?, name=?, description=?, url=?, member_count=?, display_order=?, status=?, image=?,
                    updated_at=datetime('now','localtime')
                WHERE id=?
            ");
            $stmt->execute([$platform, $name, $description ?: null, $url, $member_count ?: null, $display_order, $status, $image ?: null, $id]);
            redirect('/admin/groups', 'Đã cập nhật nhóm.', 'success');
        }
    }

    if ($action === 'delete' && isAdmin()) {
        $id = (int)($_POST['group_id'] ?? 0);
        // Delete associated image
        $stmt = $db->prepare("SELECT image FROM community_groups WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && $row['image']) deleteFile($row['image']);
        $db->prepare("DELETE FROM community_groups WHERE id = ?")->execute([$id]);
        redirect('/admin/groups', 'Đã xóa nhóm.', 'success');
    }
}

// Fetch all groups
$stmt = $db->prepare("SELECT * FROM community_groups ORDER BY display_order ASC, platform ASC, id ASC");
$stmt->execute();
$groups = $stmt->fetchAll();

$platform_meta = [
    'facebook'  => ['label' => 'Facebook',  'color' => 'text-blue-600',  'bg' => 'bg-blue-50',  'icon' => 'bi-facebook'],
    'zalo'      => ['label' => 'Zalo',      'color' => 'text-sky-600',   'bg' => 'bg-sky-50',   'icon' => 'bi-chat-dots-fill'],
    'youtube'   => ['label' => 'YouTube',   'color' => 'text-red-600',   'bg' => 'bg-red-50',   'icon' => 'bi-youtube'],
    'telegram'  => ['label' => 'Telegram',  'color' => 'text-cyan-600',  'bg' => 'bg-cyan-50',  'icon' => 'bi-telegram'],
    'other'     => ['label' => 'Khác',      'color' => 'text-slate-600', 'bg' => 'bg-slate-100','icon' => 'bi-people-fill'],
];

$page_title = 'Quản lý nhóm cộng đồng';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-midnight font-display">Nhóm cộng đồng</h1>
        <p class="text-sm text-muted mt-1">Quản lý danh sách nhóm Facebook, Zalo hiển thị trên trang /groups</p>
    </div>
    <div class="flex gap-3">
        <a href="/groups" target="_blank" class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors">
            <i class="bi bi-box-arrow-up-right"></i> Xem trang
        </a>
        <button onclick="openCreateModal()"
            class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-ink transition-colors shadow-soft">
            <i class="bi bi-plus-circle"></i> Thêm nhóm mới
        </button>
    </div>
</div>

<?php displayFlashMessage(); ?>

<div class="bg-white rounded-3xl shadow-soft border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-4 font-bold">Ảnh</th>
                    <th class="px-5 py-4 font-bold">Nền tảng</th>
                    <th class="px-5 py-4 font-bold">Tên nhóm</th>
                    <th class="px-5 py-4 font-bold">URL</th>
                    <th class="px-5 py-4 font-bold text-center">Thành viên</th>
                    <th class="px-5 py-4 font-bold text-center">Thứ tự</th>
                    <th class="px-5 py-4 font-bold">Trạng thái</th>
                    <th class="px-5 py-4 font-bold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($groups)): ?>
                <tr>
                    <td colspan="8" class="px-5 py-16 text-center">
                        <i class="bi bi-people text-5xl text-slate-200 block mb-3"></i>
                        <p class="text-slate-400 font-medium">Chưa có nhóm nào</p>
                        <button onclick="openCreateModal()" class="mt-4 bg-primary text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-ink transition-colors">Thêm nhóm đầu tiên</button>
                    </td>
                </tr>
                <?php endif; ?>
                <?php foreach ($groups as $g):
                    $m = $platform_meta[$g['platform']] ?? $platform_meta['other'];
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-5 py-3">
                        <?php if ($g['image']): ?>
                        <img src="/uploads/<?php echo htmlspecialchars($g['image']); ?>"
                             alt="" class="w-14 h-10 rounded-lg object-cover border border-slate-100">
                        <?php else: ?>
                        <div class="w-14 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300">
                            <i class="bi bi-image text-lg"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full <?php echo $m['bg'] . ' ' . $m['color']; ?>">
                            <i class="bi <?php echo $m['icon']; ?>"></i> <?php echo $m['label']; ?>
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-midnight"><?php echo htmlspecialchars($g['name']); ?></div>
                        <?php if ($g['description']): ?>
                        <div class="text-xs text-slate-400 mt-0.5 line-clamp-1"><?php echo htmlspecialchars($g['description']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 max-w-[180px]">
                        <a href="<?php echo htmlspecialchars($g['url']); ?>" target="_blank"
                           class="text-xs text-sky-600 hover:underline flex items-center gap-1 truncate">
                            <i class="bi bi-link-45deg flex-shrink-0"></i>
                            <span class="truncate"><?php echo htmlspecialchars($g['url']); ?></span>
                        </a>
                    </td>
                    <td class="px-5 py-4 text-center text-slate-600 text-sm">
                        <?php echo $g['member_count'] ? htmlspecialchars($g['member_count']) : '<span class="text-slate-300">—</span>'; ?>
                    </td>
                    <td class="px-5 py-4 text-center text-slate-500 text-sm"><?php echo $g['display_order']; ?></td>
                    <td class="px-5 py-4">
                        <?php if ($g['status'] === 'active'): ?>
                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-green-50 text-green-700">
                            <i class="bi bi-circle-fill text-[6px]"></i> Hiển thị
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-500">
                            <i class="bi bi-circle text-[6px]"></i> Ẩn
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-1.5">
                            <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($g)); ?>)"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors" title="Sửa">
                                <i class="bi bi-pencil text-sm"></i>
                            </button>
                            <?php if (isAdmin()): ?>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors" title="Xóa"
                                    onclick="return confirm('Xác nhận xóa nhóm này?')">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create/Edit Modal — dùng form submit thật để upload file -->
<div class="modal fade" id="groupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-medium">
            <div class="modal-header border-b border-slate-100 px-6 py-4">
                <h5 class="modal-title font-bold text-midnight font-display" id="groupModalTitle">Thêm nhóm mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <?php echo csrfField(); ?>
                <input type="hidden" name="action"        id="gf_action"        value="create">
                <input type="hidden" name="group_id"      id="gf_id"            value="">
                <input type="hidden" name="current_image" id="gf_current_image" value="">

                <div class="modal-body px-6 py-5 space-y-4">

                    <!-- Ảnh nhóm -->
                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-400 block mb-2">Ảnh nhóm</label>

                        <!-- Preview ảnh hiện tại (edit mode) -->
                        <div id="gf_existing_wrap" class="hidden mb-3">
                            <div class="relative inline-block">
                                <img id="gf_existing_img" src="" alt="Ảnh hiện tại"
                                     class="h-24 w-auto rounded-xl border border-slate-200 object-cover">
                                <label class="absolute -top-2 -right-2 cursor-pointer">
                                    <input type="checkbox" name="remove_image" id="gf_remove" value="1" class="hidden peer">
                                    <span class="peer-checked:bg-red-500 peer-checked:border-red-500 w-6 h-6 bg-white border-2 border-slate-300 rounded-full flex items-center justify-center shadow-sm transition-all" title="Xóa ảnh này">
                                        <i class="bi bi-x text-slate-500 peer-checked:text-white text-sm"></i>
                                    </span>
                                </label>
                            </div>
                            <p class="text-xs text-slate-400 mt-1.5">Tick <i class="bi bi-x-circle text-red-400"></i> để xóa ảnh, hoặc chọn ảnh mới bên dưới để thay thế.</p>
                        </div>

                        <!-- Drop zone / file picker -->
                        <label for="gf_image_input" id="gf_dropzone"
                               class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-slate-200 rounded-2xl p-6 cursor-pointer hover:border-primary hover:bg-primary-50/30 transition-all">
                            <div id="gf_new_preview_wrap" class="hidden w-full text-center">
                                <img id="gf_new_preview" src="" alt="" class="h-28 w-auto mx-auto rounded-xl object-cover mb-2 border border-slate-200">
                                <p class="text-xs text-slate-400">Ảnh mới đã chọn</p>
                            </div>
                            <div id="gf_dropzone_hint">
                                <i class="bi bi-cloud-arrow-up text-3xl text-slate-300 block text-center mb-1"></i>
                                <p class="text-sm text-slate-400 text-center">Kéo thả hoặc <span class="text-primary font-semibold">chọn ảnh</span></p>
                                <p class="text-xs text-slate-300 text-center mt-0.5">JPG, PNG, WEBP · Tối đa 5MB</p>
                            </div>
                        </label>
                        <input type="file" name="image" id="gf_image_input" accept="image/*" class="hidden">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Nền tảng</label>
                            <select name="platform" id="gf_platform" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                                <option value="facebook">Facebook</option>
                                <option value="zalo">Zalo</option>
                                <option value="youtube">YouTube</option>
                                <option value="telegram">Telegram</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Số thành viên</label>
                            <input type="text" name="member_count" id="gf_members" placeholder="VD: 1.2K"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Tên nhóm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="gf_name" required placeholder="VD: Hội Du học Nhật Bản - Bright Education"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">URL tham gia <span class="text-red-500">*</span></label>
                        <input type="url" name="url" id="gf_url" required placeholder="https://www.facebook.com/groups/..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Mô tả</label>
                        <textarea name="description" id="gf_desc" rows="2" placeholder="Nhóm chia sẻ thông tin du học Nhật Bản..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Thứ tự hiển thị</label>
                            <input type="number" name="display_order" id="gf_order" value="0" min="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-slate-400 block mb-1.5">Trạng thái</label>
                            <select name="status" id="gf_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                                <option value="active">Hiển thị</option>
                                <option value="inactive">Ẩn</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
                    <button type="button" class="bg-slate-100 text-slate-600 px-5 py-2 rounded-xl text-sm font-semibold hover:bg-slate-200" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="bg-primary text-white px-5 py-2 rounded-xl text-sm font-bold hover:bg-ink transition-colors" id="gf_submit">Thêm nhóm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Live preview khi chọn file
document.getElementById('gf_image_input').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('gf_new_preview').src = e.target.result;
        document.getElementById('gf_new_preview_wrap').classList.remove('hidden');
        document.getElementById('gf_dropzone_hint').classList.add('hidden');
    };
    reader.readAsDataURL(file);
});

function resetImageUI() {
    document.getElementById('gf_image_input').value = '';
    document.getElementById('gf_new_preview_wrap').classList.add('hidden');
    document.getElementById('gf_dropzone_hint').classList.remove('hidden');
    document.getElementById('gf_existing_wrap').classList.add('hidden');
    document.getElementById('gf_existing_img').src = '';
    document.getElementById('gf_current_image').value = '';
    if (document.getElementById('gf_remove')) {
        document.getElementById('gf_remove').checked = false;
    }
}

function openCreateModal() {
    document.getElementById('groupModalTitle').textContent = 'Thêm nhóm mới';
    document.getElementById('gf_submit').textContent = 'Thêm nhóm';
    document.getElementById('gf_action').value = 'create';
    document.getElementById('gf_id').value = '';
    document.getElementById('gf_platform').value = 'facebook';
    document.getElementById('gf_name').value = '';
    document.getElementById('gf_url').value = '';
    document.getElementById('gf_desc').value = '';
    document.getElementById('gf_members').value = '';
    document.getElementById('gf_order').value = 0;
    document.getElementById('gf_status').value = 'active';
    resetImageUI();
    new bootstrap.Modal(document.getElementById('groupModal')).show();
}

function openEditModal(g) {
    document.getElementById('groupModalTitle').textContent = 'Sửa nhóm';
    document.getElementById('gf_submit').textContent = 'Lưu thay đổi';
    document.getElementById('gf_action').value = 'edit';
    document.getElementById('gf_id').value = g.id;
    document.getElementById('gf_platform').value = g.platform;
    document.getElementById('gf_name').value = g.name;
    document.getElementById('gf_url').value = g.url;
    document.getElementById('gf_desc').value = g.description || '';
    document.getElementById('gf_members').value = g.member_count || '';
    document.getElementById('gf_order').value = g.display_order;
    document.getElementById('gf_status').value = g.status;
    resetImageUI();

    // Hiển thị ảnh hiện tại nếu có
    if (g.image) {
        document.getElementById('gf_current_image').value = g.image;
        document.getElementById('gf_existing_img').src = '/uploads/' + g.image;
        document.getElementById('gf_existing_wrap').classList.remove('hidden');
    }

    new bootstrap.Modal(document.getElementById('groupModal')).show();
}

// Sync checkbox UI (vì checkbox ẩn dùng label)
document.getElementById('gf_remove')?.addEventListener('change', function() {
    const icon = this.closest('label').querySelector('span i');
    icon.className = this.checked ? 'bi bi-x text-white text-sm' : 'bi bi-x text-slate-500 text-sm';
});
</script>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
