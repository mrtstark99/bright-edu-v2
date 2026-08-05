<?php
require_once dirname(__DIR__) . '/config/config.php';
requireEditor();

$db = Database::getInstance();
$errors = [];
$success = $_SESSION['seo_strategy_success'] ?? '';
unset($_SESSION['seo_strategy_success']);

$month = trim((string)($_GET['month'] ?? $_POST['planning_month'] ?? date('Y-m')));
if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
    $month = date('Y-m');
}
$intentFilter = trim((string)($_GET['intent'] ?? ''));

$intentMeta = [
    'informational' => ['Thông tin', 'Người dùng muốn học, tìm hiểu hoặc giải đáp', 'bg-sky-50 text-sky-700'],
    'navigational' => ['Điều hướng', 'Người dùng muốn tìm một thương hiệu hoặc trang cụ thể', 'bg-slate-100 text-slate-700'],
    'commercial' => ['Thương mại', 'Người dùng đang so sánh trước khi quyết định', 'bg-amber-50 text-amber-700'],
    'transactional' => ['Giao dịch', 'Người dùng sẵn sàng đăng ký hoặc liên hệ', 'bg-green-50 text-green-700'],
];
$roleMeta = ['pillar' => 'Pillar', 'satellite' => 'Vệ tinh', 'standalone' => 'Độc lập'];
$priorityMeta = [
    'high' => ['Cao', 'badge-danger'],
    'medium' => ['Trung bình', 'badge-pending'],
    'low' => ['Thấp', 'badge-inactive'],
];
$keywordStatusMeta = [
    'idea' => ['Ý tưởng', 'badge-inactive'],
    'brief' => ['Đã có brief', 'badge-new'],
    'writing' => ['Đang viết', 'badge-pending'],
    'published' => ['Đã xuất bản', 'badge-active'],
];
$clusterStatusMeta = [
    'planned' => ['Đã lên kế hoạch', 'badge-inactive'],
    'in_progress' => ['Đang triển khai', 'badge-pending'],
    'published' => ['Hoàn thành', 'badge-active'],
];

$normalizeUrl = static function (string $url): ?string {
    $url = trim($url);
    if ($url === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $url)) {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        if (!in_array($host, ['brighteducation.net', 'www.brighteducation.net'], true)) {
            return null;
        }
        $url = $parts['path'] ?? '/';
    }
    $url = '/' . ltrim($url, '/');
    return $url === '/' ? '/' : rtrim($url, '/');
};

$redirectToMonth = static function (string $month): void {
    header('Location: /admin/seo-strategy?month=' . rawurlencode($month));
    exit;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.';
    } else {
        $action = (string)($_POST['action'] ?? '');
        try {
            if ($action === 'save_cluster') {
                $id = (int)($_POST['id'] ?? 0);
                $name = trim((string)($_POST['name'] ?? ''));
                $pillarTitle = trim((string)($_POST['pillar_title'] ?? ''));
                $pillarUrl = $normalizeUrl((string)($_POST['pillar_url'] ?? ''));
                $description = trim((string)($_POST['description'] ?? ''));
                $status = (string)($_POST['status'] ?? 'planned');

                if ($name === '') $errors[] = 'Tên cụm chủ đề không được để trống.';
                if ($pillarTitle === '') $errors[] = 'Tiêu đề Pillar không được để trống.';
                if ($pillarUrl === null) $errors[] = 'Pillar URL phải thuộc website brighteducation.net.';
                if (!isset($clusterStatusMeta[$status])) $errors[] = 'Trạng thái cụm chủ đề không hợp lệ.';

                if (!$errors) {
                    if ($id > 0) {
                        $db->prepare("UPDATE seo_topic_clusters SET planning_month=?,name=?,pillar_title=?,pillar_url=?,description=?,status=?,updated_at=datetime('now','localtime') WHERE id=?")
                            ->execute([$month, $name, $pillarTitle, $pillarUrl, $description, $status, $id]);
                        $_SESSION['seo_strategy_success'] = 'Đã cập nhật Topic Cluster.';
                    } else {
                        $db->prepare("INSERT INTO seo_topic_clusters (planning_month,name,pillar_title,pillar_url,description,status) VALUES (?,?,?,?,?,?)")
                            ->execute([$month, $name, $pillarTitle, $pillarUrl, $description, $status]);
                        $_SESSION['seo_strategy_success'] = 'Đã tạo Topic Cluster mới.';
                    }
                    $redirectToMonth($month);
                }
            } elseif ($action === 'save_keyword') {
                $id = (int)($_POST['id'] ?? 0);
                $keyword = mb_strtolower(trim((string)($_POST['keyword'] ?? '')), 'UTF-8');
                $intent = (string)($_POST['intent'] ?? 'informational');
                $targetUrl = $normalizeUrl((string)($_POST['target_url'] ?? ''));
                $clusterId = (int)($_POST['cluster_id'] ?? 0) ?: null;
                $contentRole = (string)($_POST['content_role'] ?? 'satellite');
                $isLongTail = isset($_POST['is_long_tail']) ? 1 : 0;
                $priority = (string)($_POST['priority'] ?? 'medium');
                $conversionScore = max(0, min(100, (int)($_POST['conversion_score'] ?? 0)));
                $searchVolume = max(0, (int)($_POST['search_volume'] ?? 0));
                $difficulty = max(0, min(100, (int)($_POST['difficulty'] ?? 0)));
                $status = (string)($_POST['status'] ?? 'idea');
                $notes = trim((string)($_POST['notes'] ?? ''));

                if ($keyword === '') $errors[] = 'Từ khóa không được để trống.';
                if (!isset($intentMeta[$intent])) $errors[] = 'Search Intent không hợp lệ.';
                if ($targetUrl === null) $errors[] = 'Target URL phải thuộc website brighteducation.net.';
                if (!isset($roleMeta[$contentRole])) $errors[] = 'Vai trò nội dung không hợp lệ.';
                if (!isset($priorityMeta[$priority])) $errors[] = 'Độ ưu tiên không hợp lệ.';
                if (!isset($keywordStatusMeta[$status])) $errors[] = 'Trạng thái từ khóa không hợp lệ.';

                if ($targetUrl !== null && $targetUrl !== '') {
                    $stmt = $db->prepare("SELECT keyword,intent FROM seo_keyword_map WHERE target_url=? AND intent<>? AND id<>? LIMIT 1");
                    $stmt->execute([$targetUrl, $intent, $id]);
                    $conflict = $stmt->fetch();
                    if ($conflict) {
                        $conflictLabel = $intentMeta[$conflict['intent']][0] ?? $conflict['intent'];
                        $errors[] = 'URL ' . $targetUrl . ' đang phục vụ intent “' . $conflictLabel . '” qua từ khóa “' . $conflict['keyword'] . '”. Một URL không thể nhận hai intent khác nhau.';
                    }
                }

                if ($clusterId !== null) {
                    $stmt = $db->prepare('SELECT COUNT(*) FROM seo_topic_clusters WHERE id=?');
                    $stmt->execute([$clusterId]);
                    if ((int)$stmt->fetchColumn() === 0) $errors[] = 'Topic Cluster không tồn tại.';
                }

                if (!$errors) {
                    $values = [$month, $keyword, $intent, $targetUrl, $clusterId, $contentRole, $isLongTail, $priority, $conversionScore, $searchVolume, $difficulty, $status, $notes];
                    if ($id > 0) {
                        $values[] = $id;
                        $db->prepare("UPDATE seo_keyword_map SET planning_month=?,keyword=?,intent=?,target_url=?,cluster_id=?,content_role=?,is_long_tail=?,priority=?,conversion_score=?,search_volume=?,difficulty=?,status=?,notes=?,updated_at=datetime('now','localtime') WHERE id=?")
                            ->execute($values);
                        $_SESSION['seo_strategy_success'] = 'Đã cập nhật Keyword Mapping.';
                    } else {
                        $db->prepare("INSERT INTO seo_keyword_map (planning_month,keyword,intent,target_url,cluster_id,content_role,is_long_tail,priority,conversion_score,search_volume,difficulty,status,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)")
                            ->execute($values);
                        $_SESSION['seo_strategy_success'] = 'Đã thêm từ khóa vào kế hoạch.';
                    }
                    $redirectToMonth($month);
                }
            } elseif ($action === 'delete_keyword') {
                $db->prepare('DELETE FROM seo_keyword_map WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]);
                $_SESSION['seo_strategy_success'] = 'Đã xóa từ khóa.';
                $redirectToMonth($month);
            } elseif ($action === 'delete_cluster') {
                $db->prepare('DELETE FROM seo_topic_clusters WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]);
                $_SESSION['seo_strategy_success'] = 'Đã xóa Topic Cluster; các từ khóa liên quan được giữ lại.';
                $redirectToMonth($month);
            } elseif ($action === 'copy_previous_month') {
                $sourceMonth = date('Y-m', strtotime($month . '-01 -1 month'));
                $stmt = $db->prepare('SELECT * FROM seo_topic_clusters WHERE planning_month=? ORDER BY id');
                $stmt->execute([$sourceMonth]);
                $sourceClusters = $stmt->fetchAll();
                $clusterMap = [];
                foreach ($sourceClusters as $cluster) {
                    $db->prepare("INSERT OR IGNORE INTO seo_topic_clusters (planning_month,name,pillar_title,pillar_url,description,status) VALUES (?,?,?,?,?,'planned')")
                        ->execute([$month, $cluster['name'], $cluster['pillar_title'], $cluster['pillar_url'], $cluster['description']]);
                    $find = $db->prepare('SELECT id FROM seo_topic_clusters WHERE planning_month=? AND name=?');
                    $find->execute([$month, $cluster['name']]);
                    $clusterMap[(int)$cluster['id']] = (int)$find->fetchColumn();
                }

                $stmt = $db->prepare('SELECT * FROM seo_keyword_map WHERE planning_month=? ORDER BY id');
                $stmt->execute([$sourceMonth]);
                $copiedKeywords = 0;
                $skippedConflicts = 0;
                foreach ($stmt->fetchAll() as $keywordRow) {
                    if (!empty($keywordRow['target_url'])) {
                        $conflictCheck = $db->prepare('SELECT COUNT(*) FROM seo_keyword_map WHERE target_url=? AND intent<>?');
                        $conflictCheck->execute([$keywordRow['target_url'], $keywordRow['intent']]);
                        if ((int)$conflictCheck->fetchColumn() > 0) {
                            $skippedConflicts++;
                            continue;
                        }
                    }
                    $newClusterId = $keywordRow['cluster_id'] ? ($clusterMap[(int)$keywordRow['cluster_id']] ?? null) : null;
                    $insert = $db->prepare("INSERT OR IGNORE INTO seo_keyword_map (planning_month,keyword,intent,target_url,cluster_id,content_role,is_long_tail,priority,conversion_score,search_volume,difficulty,status,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,'idea',?)");
                    $insert->execute([$month, $keywordRow['keyword'], $keywordRow['intent'], $keywordRow['target_url'], $newClusterId, $keywordRow['content_role'], $keywordRow['is_long_tail'], $keywordRow['priority'], $keywordRow['conversion_score'], $keywordRow['search_volume'], $keywordRow['difficulty'], $keywordRow['notes']]);
                    $copiedKeywords += $insert->rowCount() > 0 ? 1 : 0;
                }
                $_SESSION['seo_strategy_success'] = 'Đã sao chép kế hoạch từ ' . $sourceMonth . ': ' . count($sourceClusters) . ' cụm và ' . $copiedKeywords . ' từ khóa mới'
                    . ($skippedConflicts > 0 ? '; bỏ qua ' . $skippedConflicts . ' mapping xung đột intent.' : '.');
                $redirectToMonth($month);
            }
        } catch (PDOException $exception) {
            if (str_contains($exception->getMessage(), 'UNIQUE constraint failed')) {
                $errors[] = 'Tên cụm hoặc từ khóa này đã tồn tại trong tháng ' . $month . '.';
            } else {
                $errors[] = 'Không thể lưu dữ liệu. Vui lòng thử lại.';
            }
        }
    }
}

$editCluster = null;
if (isset($_GET['edit_cluster'])) {
    $stmt = $db->prepare('SELECT * FROM seo_topic_clusters WHERE id=?');
    $stmt->execute([(int)$_GET['edit_cluster']]);
    $editCluster = $stmt->fetch() ?: null;
}
$editKeyword = null;
if (isset($_GET['edit_keyword'])) {
    $stmt = $db->prepare('SELECT * FROM seo_keyword_map WHERE id=?');
    $stmt->execute([(int)$_GET['edit_keyword']]);
    $editKeyword = $stmt->fetch() ?: null;
}

$stmt = $db->prepare("SELECT c.*, COUNT(k.id) keyword_count, SUM(CASE WHEN k.content_role='satellite' THEN 1 ELSE 0 END) satellite_count FROM seo_topic_clusters c LEFT JOIN seo_keyword_map k ON k.cluster_id=c.id WHERE c.planning_month=? GROUP BY c.id ORDER BY c.id DESC");
$stmt->execute([$month]);
$clusters = $stmt->fetchAll();

$keywordSql = "SELECT k.*, c.name cluster_name FROM seo_keyword_map k LEFT JOIN seo_topic_clusters c ON c.id=k.cluster_id WHERE k.planning_month=?";
$keywordParams = [$month];
if (isset($intentMeta[$intentFilter])) {
    $keywordSql .= ' AND k.intent=?';
    $keywordParams[] = $intentFilter;
}
$keywordSql .= " ORDER BY CASE k.priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END, k.is_long_tail DESC, k.conversion_score DESC, k.id DESC";
$stmt = $db->prepare($keywordSql);
$stmt->execute($keywordParams);
$keywords = $stmt->fetchAll();

$allMonthKeywordsStmt = $db->prepare('SELECT * FROM seo_keyword_map WHERE planning_month=?');
$allMonthKeywordsStmt->execute([$month]);
$allMonthKeywords = $allMonthKeywordsStmt->fetchAll();
$totalKeywords = count($allMonthKeywords);
$mappedCount = count(array_filter($allMonthKeywords, static fn($row) => trim((string)$row['target_url']) !== ''));
$longTailCount = count(array_filter($allMonthKeywords, static fn($row) => (int)$row['is_long_tail'] === 1));
$highIntentCount = count(array_filter($allMonthKeywords, static fn($row) => in_array($row['intent'], ['commercial', 'transactional'], true)));
$publishedCount = count(array_filter($allMonthKeywords, static fn($row) => $row['status'] === 'published'));

$page_title = 'SEO Strategy';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<style>
  .intent-card { min-height:112px; }
  .strategy-stat { font-size:26px; line-height:1; font-weight:850; color:#0d243e; letter-spacing:-.03em; }
  .keyword-cell { min-width:230px; max-width:380px; }
  .progress-track { height:6px; background:#e2e8f0; border-radius:999px; overflow:hidden; }
  .progress-fill { height:100%; background:#0d243e; border-radius:inherit; }
</style>

<div class="page-header">
  <div>
    <h1>Nghiên cứu & Chiến lược SEO</h1>
    <p>Search Intent, Keyword Mapping và Topic Cluster theo chu kỳ hàng tháng</p>
  </div>
  <div class="flex flex-wrap gap-2">
    <input type="month" class="a-input !w-auto !py-2" value="<?php echo htmlspecialchars($month); ?>" onchange="location.href='/admin/seo-strategy?month='+this.value">
    <form method="POST" onsubmit="return confirm('Sao chép toàn bộ kế hoạch của tháng trước sang tháng này?')">
      <?php echo csrfField(); ?><input type="hidden" name="action" value="copy_previous_month"><input type="hidden" name="planning_month" value="<?php echo htmlspecialchars($month); ?>">
      <button class="btn-adm-outline" type="submit"><i class="bi bi-copy"></i> Sao chép tháng trước</button>
    </form>
  </div>
</div>

<?php if ($errors): ?>
<div class="px-4 py-3 mb-5 rounded-2xl border text-sm font-medium bg-red-50 border-red-200 text-red-700">
  <?php foreach ($errors as $error): ?><div><i class="bi bi-x-circle-fill mr-2"></i><?php echo htmlspecialchars($error); ?></div><?php endforeach; ?>
</div>
<?php elseif ($success): ?>
<div class="flex items-center gap-3 px-4 py-3 mb-5 rounded-2xl border text-sm font-medium bg-green-50 border-green-200 text-green-800"><i class="bi bi-check-circle-fill text-green-500"></i><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-5">
  <?php
  $stats = [
    ['Tổng từ khóa', $totalKeywords, 'bi-key'],
    ['Đã mapping URL', $mappedCount, 'bi-link-45deg'],
    ['Long-tail', $longTailCount, 'bi-stars'],
    ['Ý định mua cao', $highIntentCount, 'bi-bag-check'],
    ['Đã xuất bản', $publishedCount, 'bi-check2-circle'],
  ];
  foreach ($stats as $stat): ?>
  <div class="a-card p-5">
    <div class="flex items-center justify-between text-slate-400 text-[11px] font-bold uppercase tracking-wider"><span><?php echo $stat[0]; ?></span><i class="bi <?php echo $stat[2]; ?>"></i></div>
    <div class="strategy-stat mt-5"><?php echo number_format($stat[1], 0, ',', '.'); ?></div>
  </div>
  <?php endforeach; ?>
</div>

<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
  <?php foreach ($intentMeta as $intentKey => $meta):
    $intentCount = count(array_filter($allMonthKeywords, static fn($row) => $row['intent'] === $intentKey));
  ?>
  <a href="/admin/seo-strategy?month=<?php echo rawurlencode($month); ?>&intent=<?php echo $intentKey; ?>" class="a-card intent-card p-5 block hover:-translate-y-0.5 transition-transform">
    <div class="flex items-start justify-between gap-3">
      <span class="badge <?php echo $meta[2]; ?>"><?php echo $meta[0]; ?></span>
      <span class="text-xl font-black text-midnight"><?php echo $intentCount; ?></span>
    </div>
    <p class="text-xs text-slate-500 leading-relaxed mt-4"><?php echo $meta[1]; ?></p>
  </a>
  <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 xl:grid-cols-5 gap-5 mb-5">
  <div class="xl:col-span-2">
    <div class="a-card sticky-panel">
      <div class="a-card-header"><h2><?php echo $editKeyword ? 'Chỉnh sửa Keyword Mapping' : 'Thêm Keyword Mapping'; ?></h2></div>
      <form method="POST" class="a-card-body">
        <?php echo csrfField(); ?><input type="hidden" name="action" value="save_keyword"><input type="hidden" name="planning_month" value="<?php echo htmlspecialchars($month); ?>">
        <?php if ($editKeyword): ?><input type="hidden" name="id" value="<?php echo (int)$editKeyword['id']; ?>"><?php endif; ?>
        <div class="a-field"><label class="a-label">Từ khóa <span class="text-red-500">*</span></label><input class="a-input" name="keyword" required value="<?php echo htmlspecialchars($editKeyword['keyword'] ?? ''); ?>" placeholder="chi phí du học nhật bản 2026"></div>
        <div class="grid sm:grid-cols-2 gap-3">
          <div class="a-field"><label class="a-label">Search Intent</label><select class="a-input" name="intent"><?php foreach ($intentMeta as $key => $meta): ?><option value="<?php echo $key; ?>" <?php echo ($editKeyword['intent'] ?? 'informational') === $key ? 'selected' : ''; ?>><?php echo $meta[0]; ?></option><?php endforeach; ?></select></div>
          <div class="a-field"><label class="a-label">Vai trò nội dung</label><select class="a-input" name="content_role"><?php foreach ($roleMeta as $key => $label): ?><option value="<?php echo $key; ?>" <?php echo ($editKeyword['content_role'] ?? 'satellite') === $key ? 'selected' : ''; ?>><?php echo $label; ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="a-field"><label class="a-label">Target URL</label><input class="a-input" name="target_url" value="<?php echo htmlspecialchars($editKeyword['target_url'] ?? ''); ?>" placeholder="/blog/chi-phi-du-hoc-nhat-ban"></div>
        <div class="a-field"><label class="a-label">Topic Cluster</label><select class="a-input" name="cluster_id"><option value="">— Chưa phân cụm —</option><?php foreach ($clusters as $cluster): ?><option value="<?php echo (int)$cluster['id']; ?>" <?php echo (int)($editKeyword['cluster_id'] ?? 0) === (int)$cluster['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cluster['name']); ?></option><?php endforeach; ?></select></div>
        <div class="grid grid-cols-3 gap-3">
          <div class="a-field"><label class="a-label">Conversion</label><input class="a-input" type="number" min="0" max="100" name="conversion_score" value="<?php echo (int)($editKeyword['conversion_score'] ?? 0); ?>"></div>
          <div class="a-field"><label class="a-label">Volume</label><input class="a-input" type="number" min="0" name="search_volume" value="<?php echo (int)($editKeyword['search_volume'] ?? 0); ?>"></div>
          <div class="a-field"><label class="a-label">Difficulty</label><input class="a-input" type="number" min="0" max="100" name="difficulty" value="<?php echo (int)($editKeyword['difficulty'] ?? 0); ?>"></div>
        </div>
        <div class="grid sm:grid-cols-2 gap-3">
          <div class="a-field"><label class="a-label">Ưu tiên</label><select class="a-input" name="priority"><?php foreach ($priorityMeta as $key => $meta): ?><option value="<?php echo $key; ?>" <?php echo ($editKeyword['priority'] ?? 'medium') === $key ? 'selected' : ''; ?>><?php echo $meta[0]; ?></option><?php endforeach; ?></select></div>
          <div class="a-field"><label class="a-label">Trạng thái</label><select class="a-input" name="status"><?php foreach ($keywordStatusMeta as $key => $meta): ?><option value="<?php echo $key; ?>" <?php echo ($editKeyword['status'] ?? 'idea') === $key ? 'selected' : ''; ?>><?php echo $meta[0]; ?></option><?php endforeach; ?></select></div>
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-600 mb-4"><input type="checkbox" name="is_long_tail" value="1" <?php echo (int)($editKeyword['is_long_tail'] ?? 0) === 1 ? 'checked' : ''; ?>> Long-tail keyword có khả năng chuyển đổi cao</label>
        <div class="a-field"><label class="a-label">Ghi chú / Content Angle</label><textarea class="a-input" name="notes" rows="3" placeholder="Góc nội dung, đối tượng, CTA..."><?php echo htmlspecialchars($editKeyword['notes'] ?? ''); ?></textarea></div>
        <button class="btn-adm w-full justify-center" type="submit"><i class="bi bi-save"></i> <?php echo $editKeyword ? 'Cập nhật mapping' : 'Thêm vào kế hoạch'; ?></button>
        <?php if ($editKeyword): ?><a href="/admin/seo-strategy?month=<?php echo rawurlencode($month); ?>" class="btn-adm-outline w-full justify-center mt-2">Hủy chỉnh sửa</a><?php endif; ?>
      </form>
    </div>
  </div>

  <div class="xl:col-span-3">
    <div class="a-card overflow-hidden">
      <div class="a-card-header">
        <h2>Keyword Map (<?php echo count($keywords); ?>)</h2>
        <?php if ($intentFilter): ?><a href="/admin/seo-strategy?month=<?php echo rawurlencode($month); ?>" class="text-xs font-semibold text-sky-600">Xóa lọc intent</a><?php endif; ?>
      </div>
      <div class="overflow-x-auto">
        <table class="a-table min-w-[1050px]">
          <thead><tr><th>Từ khóa & URL</th><th>Intent</th><th>Cluster</th><th>Long-tail</th><th>Điểm</th><th>Ưu tiên</th><th>Trạng thái</th><th></th></tr></thead>
          <tbody>
          <?php if (!$keywords): ?><tr><td colspan="8" class="!py-12 text-center text-slate-400">Chưa có từ khóa cho tháng này.</td></tr><?php endif; ?>
          <?php foreach ($keywords as $row): $intent = $intentMeta[$row['intent']]; $priority = $priorityMeta[$row['priority']]; $statusMeta = $keywordStatusMeta[$row['status']]; ?>
          <tr>
            <td class="keyword-cell">
              <div class="font-semibold text-midnight"><?php echo htmlspecialchars($row['keyword']); ?></div>
              <div class="text-[11px] text-slate-400 mt-1 font-mono"><?php echo $row['target_url'] ? htmlspecialchars($row['target_url']) : 'Chưa mapping URL'; ?></div>
            </td>
            <td><span class="badge <?php echo $intent[2]; ?>"><?php echo $intent[0]; ?></span><div class="text-[10px] text-slate-400 mt-1"><?php echo $roleMeta[$row['content_role']] ?? ''; ?></div></td>
            <td class="text-xs text-slate-500 max-w-[150px]"><?php echo htmlspecialchars($row['cluster_name'] ?? '—'); ?></td>
            <td><?php echo (int)$row['is_long_tail'] ? '<span class="badge badge-active">Có</span>' : '<span class="text-slate-300">—</span>'; ?></td>
            <td><div class="text-xs text-slate-500">CV <strong class="text-midnight"><?php echo (int)$row['conversion_score']; ?></strong> · KD <?php echo (int)$row['difficulty']; ?></div><div class="text-[10px] text-slate-400 mt-1">Vol. <?php echo number_format((int)$row['search_volume'], 0, ',', '.'); ?></div></td>
            <td><span class="badge <?php echo $priority[1]; ?>"><?php echo $priority[0]; ?></span></td>
            <td><span class="badge <?php echo $statusMeta[1]; ?>"><?php echo $statusMeta[0]; ?></span></td>
            <td><div class="flex justify-end gap-1.5"><a href="?month=<?php echo rawurlencode($month); ?>&edit_keyword=<?php echo (int)$row['id']; ?>" class="btn-icon btn-icon-edit" title="Sửa"><i class="bi bi-pencil"></i></a><form method="POST" onsubmit="return confirm('Xóa từ khóa này khỏi kế hoạch?')"><?php echo csrfField(); ?><input type="hidden" name="action" value="delete_keyword"><input type="hidden" name="planning_month" value="<?php echo htmlspecialchars($month); ?>"><input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>"><button class="btn-icon btn-icon-del" title="Xóa"><i class="bi bi-trash"></i></button></form></div></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">
  <div>
    <div class="a-card">
      <div class="a-card-header"><h2><?php echo $editCluster ? 'Chỉnh sửa Topic Cluster' : 'Tạo Topic Cluster'; ?></h2></div>
      <form method="POST" class="a-card-body">
        <?php echo csrfField(); ?><input type="hidden" name="action" value="save_cluster"><input type="hidden" name="planning_month" value="<?php echo htmlspecialchars($month); ?>">
        <?php if ($editCluster): ?><input type="hidden" name="id" value="<?php echo (int)$editCluster['id']; ?>"><?php endif; ?>
        <div class="a-field"><label class="a-label">Tên cụm chủ đề</label><input class="a-input" required name="name" value="<?php echo htmlspecialchars($editCluster['name'] ?? ''); ?>" placeholder="Du học Nhật Bản"></div>
        <div class="a-field"><label class="a-label">Tiêu đề trang Pillar</label><input class="a-input" required name="pillar_title" value="<?php echo htmlspecialchars($editCluster['pillar_title'] ?? ''); ?>" placeholder="Cẩm nang du học Nhật Bản toàn diện"></div>
        <div class="a-field"><label class="a-label">Pillar URL</label><input class="a-input" name="pillar_url" value="<?php echo htmlspecialchars($editCluster['pillar_url'] ?? ''); ?>" placeholder="/du-hoc-nhat-ban"></div>
        <div class="a-field"><label class="a-label">Trạng thái</label><select class="a-input" name="status"><?php foreach ($clusterStatusMeta as $key => $meta): ?><option value="<?php echo $key; ?>" <?php echo ($editCluster['status'] ?? 'planned') === $key ? 'selected' : ''; ?>><?php echo $meta[0]; ?></option><?php endforeach; ?></select></div>
        <div class="a-field"><label class="a-label">Mô tả chiến lược</label><textarea class="a-input" name="description" rows="3"><?php echo htmlspecialchars($editCluster['description'] ?? ''); ?></textarea></div>
        <button class="btn-adm w-full justify-center" type="submit"><i class="bi bi-diagram-3"></i> <?php echo $editCluster ? 'Cập nhật cụm' : 'Tạo cụm chủ đề'; ?></button>
        <?php if ($editCluster): ?><a href="/admin/seo-strategy?month=<?php echo rawurlencode($month); ?>" class="btn-adm-outline w-full justify-center mt-2">Hủy</a><?php endif; ?>
      </form>
    </div>
  </div>

  <div class="xl:col-span-2">
    <div class="a-card overflow-hidden">
      <div class="a-card-header"><h2>Cấu trúc Topic Cluster (<?php echo count($clusters); ?>)</h2></div>
      <div class="grid md:grid-cols-2 gap-4 p-5">
        <?php if (!$clusters): ?><div class="md:col-span-2 text-center text-slate-400 py-10">Chưa có Topic Cluster cho tháng này.</div><?php endif; ?>
        <?php foreach ($clusters as $cluster): $clusterStatus = $clusterStatusMeta[$cluster['status']]; ?>
        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-5">
          <div class="flex items-start justify-between gap-3"><div><span class="badge <?php echo $clusterStatus[1]; ?>"><?php echo $clusterStatus[0]; ?></span><h3 class="font-bold text-midnight mt-3"><?php echo htmlspecialchars($cluster['name']); ?></h3></div><div class="flex gap-1.5"><a href="?month=<?php echo rawurlencode($month); ?>&edit_cluster=<?php echo (int)$cluster['id']; ?>" class="btn-icon btn-icon-edit"><i class="bi bi-pencil"></i></a><form method="POST" onsubmit="return confirm('Xóa cụm này? Các từ khóa vẫn được giữ lại nhưng sẽ mất liên kết cụm.')"><?php echo csrfField(); ?><input type="hidden" name="action" value="delete_cluster"><input type="hidden" name="planning_month" value="<?php echo htmlspecialchars($month); ?>"><input type="hidden" name="id" value="<?php echo (int)$cluster['id']; ?>"><button class="btn-icon btn-icon-del"><i class="bi bi-trash"></i></button></form></div></div>
          <div class="mt-4 rounded-xl bg-white border border-slate-100 p-3"><div class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Pillar Page</div><div class="text-sm font-semibold text-midnight mt-1"><?php echo htmlspecialchars($cluster['pillar_title']); ?></div><code class="text-[10px] text-slate-400"><?php echo htmlspecialchars($cluster['pillar_url'] ?: 'Chưa mapping URL'); ?></code></div>
          <div class="flex items-center justify-between text-xs text-slate-500 mt-4"><span><?php echo (int)$cluster['keyword_count']; ?> từ khóa</span><span><?php echo (int)$cluster['satellite_count']; ?> bài vệ tinh</span></div>
          <div class="progress-track mt-2"><div class="progress-fill" style="width:<?php echo min(100, (int)$cluster['satellite_count'] * 20); ?>%"></div></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
