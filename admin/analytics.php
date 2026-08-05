<?php
require_once dirname(__DIR__) . '/config/config.php';
requireAuth();
require_once APP_ROOT . '/includes/analytics.php';

$db = Database::getInstance();
$errors = [];
$success = $_SESSION['analytics_success'] ?? '';
unset($_SESSION['analytics_success']);

$upsertSetting = static function (string $key, string $value, string $type = 'text') use ($db): void {
    $stmt = $db->prepare("INSERT OR REPLACE INTO settings (setting_key, setting_value, setting_type, updated_at) VALUES (?, ?, ?, datetime('now','localtime'))");
    $stmt->execute([$key, $value, $type]);
};

$normalizeLookerUrl = static function (string $url): string {
    $url = trim($url);
    if ($url === '') {
        return '';
    }
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return '';
    }
    $parts = parse_url($url);
    $host = strtolower($parts['host'] ?? '');
    if (!in_array($host, ['lookerstudio.google.com', 'datastudio.google.com'], true)) {
        return '';
    }
    if (str_contains($url, '/reporting/') && !str_contains($url, '/embed/reporting/')) {
        $url = str_replace('/reporting/', '/embed/reporting/', $url);
    }
    return $url;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isAdmin()) {
        http_response_code(403);
        exit('Chỉ quản trị viên được thay đổi cấu hình đo lường.');
    }
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.';
    } else {
        $gaId = strtoupper(trim((string)($_POST['ga_id'] ?? '')));
        $propertyId = preg_replace('/\D+/', '', (string)($_POST['ga_property_id'] ?? ''));
        $gscVerification = trim((string)($_POST['gsc_verification'] ?? ''));
        $gscSiteUrl = trim((string)($_POST['gsc_site_url'] ?? ''));
        $lookerInput = trim((string)($_POST['looker_embed_url'] ?? ''));
        $lookerUrl = $normalizeLookerUrl($lookerInput);

        if ($gaId !== '' && !preg_match('/^G-[A-Z0-9]+$/', $gaId)) {
            $errors[] = 'Measurement ID phải có dạng G-XXXXXXXXXX.';
        }
        if ($propertyId !== '' && !preg_match('/^\d+$/', $propertyId)) {
            $errors[] = 'GA4 Property ID chỉ gồm chữ số.';
        }
        if ($gscSiteUrl !== ''
            && !str_starts_with($gscSiteUrl, 'sc-domain:')
            && !filter_var($gscSiteUrl, FILTER_VALIDATE_URL)) {
            $errors[] = 'Search Console property phải là sc-domain:domain.com hoặc URL đầy đủ.';
        }
        if ($lookerInput !== '' && $lookerUrl === '') {
            $errors[] = 'Looker Studio URL không hợp lệ hoặc không thuộc tên miền Google.';
        }

        $credentialJson = trim((string)($_POST['service_account_json'] ?? ''));
        if (!empty($_FILES['service_account_file']['tmp_name'])) {
            if ((int)($_FILES['service_account_file']['size'] ?? 0) > 65536) {
                $errors[] = 'File Service Account vượt quá 64 KB.';
            } else {
                $credentialJson = trim((string)file_get_contents($_FILES['service_account_file']['tmp_name']));
            }
        }
        $encryptedCredentials = null;
        if ($credentialJson !== '') {
            $credentials = json_decode($credentialJson, true);
            if (!is_array($credentials)
                || ($credentials['type'] ?? '') !== 'service_account'
                || empty($credentials['client_email'])
                || empty($credentials['private_key'])
                || ($credentials['token_uri'] ?? '') !== 'https://oauth2.googleapis.com/token'
                || !str_ends_with((string)$credentials['client_email'], '.gserviceaccount.com')) {
                $errors[] = 'Service Account JSON không đúng định dạng của Google Cloud.';
            } else {
                $encryptedCredentials = analyticsEncryptSecret(json_encode($credentials, JSON_UNESCAPED_SLASHES));
                if ($encryptedCredentials === '') {
                    $errors[] = 'Không thể mã hóa credentials trên máy chủ.';
                }
            }
        }

        if (!$errors) {
            $upsertSetting('ga_id', $gaId);
            $upsertSetting('ga_property_id', $propertyId);
            $upsertSetting('gsc_verification', $gscVerification);
            $upsertSetting('gsc_site_url', $gscSiteUrl);
            $upsertSetting('looker_embed_url', $lookerUrl);

            $numericKeys = [
                'seo_monthly_cost', 'organic_lead_value',
                'kpi_organic_sessions_target', 'kpi_impressions_target',
                'kpi_position_target',
                'kpi_ctr_target', 'kpi_engagement_rate_target',
                'kpi_avg_engagement_time_target', 'kpi_conversion_rate_target',
                'kpi_roi_target',
            ];
            foreach ($numericKeys as $key) {
                $value = max(0, (float)($_POST[$key] ?? 0));
                $upsertSetting($key, (string)$value, 'text');
            }

            if (!empty($_POST['remove_credentials'])) {
                $upsertSetting('google_service_account_enc', '');
            } elseif ($encryptedCredentials !== null) {
                $upsertSetting('google_service_account_enc', $encryptedCredentials);
            }

            analyticsClearCache();
            $_SESSION['analytics_success'] = 'Đã lưu cấu hình đo lường. Dữ liệu Google sẽ được tải lại.';
            header('Location: /admin/analytics');
            exit;
        }
    }
}

$days = (int)($_GET['days'] ?? 28);
$days = in_array($days, [7, 28, 90], true) ? $days : 28;
$forceRefresh = isset($_GET['refresh']) && $_GET['refresh'] === '1';
$report = analyticsDashboardData($days, $forceRefresh);
$ga = $report['ga']['summary'] ?? [];
$gsc = $report['gsc']['summary'] ?? [];
$events = $report['ga']['events'] ?? [];
$keywords = $report['gsc']['keywords'] ?? [];
$credentials = analyticsGetCredentials();
$lookerUrl = trim((string)getSetting('looker_embed_url', ''));

$organicSessions = (int)round($ga['sessions'] ?? 0);
$impressions = (int)($gsc['impressions'] ?? 0);
$ctr = (float)($gsc['ctr'] ?? 0) * 100;
$position = (float)($gsc['position'] ?? 0);
$engagementRate = (float)($ga['engagementRate'] ?? 0) * 100;
$avgEngagement = (float)($ga['averageEngagementTimePerSession'] ?? 0);
$leadCount = (int)($events['generate_lead'] ?? 0);
$conversionRate = $organicSessions > 0 ? ($leadCount / $organicSessions) * 100 : 0;
$monthlyCost = (float)getSetting('seo_monthly_cost', 0);
$leadValue = (float)getSetting('organic_lead_value', 0);
$periodCost = $monthlyCost > 0 ? ($monthlyCost / 30) * $days : 0;
$estimatedRevenue = $leadCount * $leadValue;
$organicRoi = $periodCost > 0 ? (($estimatedRevenue - $periodCost) / $periodCost) * 100 : null;

$formatNumber = static fn($value): string => number_format((float)$value, 0, ',', '.');
$formatPercent = static fn($value): string => number_format((float)$value, 1, ',', '.') . '%';
$formatDuration = static function (float $seconds): string {
    $seconds = max(0, (int)round($seconds));
    return $seconds >= 60 ? floor($seconds / 60) . 'm ' . ($seconds % 60) . 's' : $seconds . 's';
};
$targetText = static function (string $key, string $suffix = ''): string {
    $target = (float)getSetting($key, 0);
    return $target > 0 ? 'Mục tiêu: ' . number_format($target, $suffix === '%' ? 1 : 0, ',', '.') . $suffix : 'Chưa đặt mục tiêu';
};

$metricCards = [
    ['Organic Traffic', $formatNumber($organicSessions), $targetText('kpi_organic_sessions_target'), 'bi-graph-up-arrow', 'sky'],
    ['Impressions', $formatNumber($impressions), $targetText('kpi_impressions_target'), 'bi-eye', 'indigo'],
    ['Vị trí trung bình', $position > 0 ? number_format($position, 1, ',', '.') : '—', (float)getSetting('kpi_position_target', 0) > 0 ? 'Mục tiêu: Top ' . number_format((float)getSetting('kpi_position_target', 0), 0, ',', '.') : 'Chưa đặt mục tiêu', 'bi-trophy', 'amber'],
    ['CTR', $formatPercent($ctr), $targetText('kpi_ctr_target', '%'), 'bi-cursor', 'violet'],
    ['Engagement Rate', $formatPercent($engagementRate), $targetText('kpi_engagement_rate_target', '%'), 'bi-activity', 'emerald'],
    ['Avg. Engagement Time', $formatDuration($avgEngagement), $targetText('kpi_avg_engagement_time_target', 's'), 'bi-stopwatch', 'cyan'],
    ['Conversion Rate', $formatPercent($conversionRate), $targetText('kpi_conversion_rate_target', '%'), 'bi-bullseye', 'rose'],
    ['Organic ROI', $organicRoi === null ? '—' : $formatPercent($organicRoi), $targetText('kpi_roi_target', '%'), 'bi-cash-coin', 'green'],
];

$eventLabels = [
    'click_to_call' => ['Click gọi điện', 'bi-telephone'],
    'copy_email' => ['Copy email', 'bi-clipboard'],
    'scroll_depth' => ['Mốc cuộn trang', 'bi-arrow-down-circle'],
    'lead_form_start' => ['Bắt đầu điền form', 'bi-pencil-square'],
    'form_submit_attempt' => ['Nhấn gửi form', 'bi-send'],
    'generate_lead' => ['Lead thành công', 'bi-person-check'],
];

$page_title = 'Đo lường & Analytics';
include dirname(__DIR__) . '/includes/admin/header.php';
?>

<style>
  .metric-card { min-height: 142px; }
  .metric-icon { width: 42px; height: 42px; border-radius: 14px; display:flex; align-items:center; justify-content:center; }
  .metric-value { font-size: 27px; line-height: 1; font-weight: 800; color:#0d243e; letter-spacing:-.03em; }
  .status-dot { width:9px; height:9px; border-radius:999px; display:inline-block; }
  .looker-frame { width:100%; min-height:760px; border:0; background:#f8fafc; }
  .setup-step { display:flex; gap:12px; align-items:flex-start; }
  .setup-step-num { flex:0 0 auto; width:26px; height:26px; border-radius:9px; background:#0d243e; color:#fff; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; }
</style>

<div class="page-header">
  <div>
    <h1>Đo lường & Analytics</h1>
    <p>GA4, Search Console, chuyển đổi và hiệu quả SEO trong <?php echo $days; ?> ngày gần nhất</p>
  </div>
  <div class="flex flex-wrap items-center gap-2">
    <select class="a-input !w-auto !py-2" onchange="location.href='/admin/analytics?days='+this.value">
      <?php foreach ([7, 28, 90] as $period): ?>
      <option value="<?php echo $period; ?>" <?php echo $days === $period ? 'selected' : ''; ?>><?php echo $period; ?> ngày</option>
      <?php endforeach; ?>
    </select>
    <a href="/admin/analytics?days=<?php echo $days; ?>&refresh=1" class="btn-adm"><i class="bi bi-arrow-clockwise"></i> Làm mới</a>
  </div>
</div>

<?php if ($success): ?>
<div class="flex items-center gap-3 px-4 py-3 mb-5 rounded-2xl border text-sm font-medium bg-green-50 border-green-200 text-green-800">
  <i class="bi bi-check-circle-fill text-green-500"></i><?php echo htmlspecialchars($success); ?>
</div>
<?php endif; ?>

<?php if ($errors): ?>
<div class="px-4 py-3 mb-5 rounded-2xl border text-sm bg-red-50 border-red-200 text-red-700">
  <?php foreach ($errors as $error): ?><div><i class="bi bi-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($report['errors'])): ?>
<div class="a-card mb-5 border-amber-200 !bg-amber-50/60">
  <div class="a-card-body flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    <div>
      <div class="font-bold text-amber-900 mb-1"><i class="bi bi-plug mr-2"></i>Dashboard đang chờ kết nối dữ liệu Google</div>
      <div class="text-sm text-amber-800"><?php echo htmlspecialchars(implode(' · ', $report['errors'])); ?></div>
    </div>
    <?php if (isAdmin()): ?><a href="#analytics-config" class="btn-adm whitespace-nowrap">Mở cấu hình</a><?php endif; ?>
  </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
  <?php foreach ($metricCards as $card): ?>
  <div class="a-card metric-card p-5 flex flex-col justify-between">
    <div class="flex items-start justify-between gap-4">
      <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400"><?php echo htmlspecialchars($card[0]); ?></div>
      <div class="metric-icon bg-<?php echo $card[4]; ?>-50 text-<?php echo $card[4]; ?>-600"><i class="bi <?php echo $card[3]; ?>"></i></div>
    </div>
    <div>
      <div class="metric-value"><?php echo htmlspecialchars($card[1]); ?></div>
      <div class="text-xs text-slate-400 mt-2"><?php echo htmlspecialchars($card[2]); ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">
  <div class="a-card xl:col-span-1">
    <div class="a-card-header">
      <h2>Custom Events (CRO)</h2>
      <span class="text-xs text-slate-400"><?php echo $days; ?> ngày</span>
    </div>
    <div class="divide-y divide-slate-100">
      <?php foreach ($eventLabels as $eventName => $eventMeta): ?>
      <div class="flex items-center gap-3 px-5 py-3.5">
        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center"><i class="bi <?php echo $eventMeta[1]; ?>"></i></div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-semibold text-midnight"><?php echo htmlspecialchars($eventMeta[0]); ?></div>
          <code class="text-[10px] text-slate-400"><?php echo htmlspecialchars($eventName); ?></code>
        </div>
        <div class="text-lg font-black text-midnight"><?php echo $formatNumber($events[$eventName] ?? 0); ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="a-card xl:col-span-2 overflow-hidden">
    <div class="a-card-header">
      <h2>Top từ khóa Organic</h2>
      <span class="text-xs text-slate-400">Nguồn: Google Search Console</span>
    </div>
    <div class="overflow-x-auto">
      <table class="a-table min-w-[680px]">
        <thead><tr><th>Từ khóa</th><th>Clicks</th><th>Impressions</th><th>CTR</th><th>Vị trí TB</th></tr></thead>
        <tbody>
        <?php if (!$keywords): ?>
          <tr><td colspan="5" class="!py-10 text-center text-slate-400">Chưa có dữ liệu từ khóa hoặc Search Console chưa được kết nối.</td></tr>
        <?php else: foreach ($keywords as $row): ?>
          <tr>
            <td class="font-semibold text-midnight max-w-[320px]"><?php echo htmlspecialchars($row['keyword']); ?></td>
            <td><?php echo $formatNumber($row['clicks']); ?></td>
            <td><?php echo $formatNumber($row['impressions']); ?></td>
            <td><?php echo $formatPercent($row['ctr'] * 100); ?></td>
            <td><span class="badge badge-inactive"><?php echo number_format($row['position'], 1, ',', '.'); ?></span></td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="a-card mb-5">
  <div class="a-card-header">
    <h2>Looker Studio Dashboard</h2>
    <?php if ($lookerUrl): ?><a href="<?php echo htmlspecialchars(str_replace('/embed/reporting/', '/reporting/', $lookerUrl)); ?>" target="_blank" rel="noopener" class="text-xs font-semibold text-sky-600">Mở toàn màn hình <i class="bi bi-box-arrow-up-right"></i></a><?php endif; ?>
  </div>
  <?php if ($lookerUrl): ?>
    <iframe class="looker-frame" src="<?php echo htmlspecialchars($lookerUrl); ?>" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="Bright Education Looker Studio report"></iframe>
  <?php else: ?>
    <div class="a-card-body text-center py-12">
      <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl mx-auto mb-4"><i class="bi bi-bar-chart-line"></i></div>
      <div class="font-bold text-midnight">Chưa gắn báo cáo Looker Studio</div>
      <p class="text-sm text-slate-500 mt-2 max-w-xl mx-auto">Tạo báo cáo từ nguồn GA4 và Search Console, bật “Embed report”, sau đó dán URL vào cấu hình bên dưới.</p>
    </div>
  <?php endif; ?>
</div>

<?php if (isAdmin()): ?>
<div class="a-card mb-5" id="analytics-config">
  <div class="a-card-header">
    <div>
      <h2>Cấu hình hệ thống đo lường</h2>
      <p class="text-xs text-slate-400 mt-1">Credentials được mã hóa; private key không bao giờ hiển thị lại.</p>
    </div>
    <div class="flex items-center gap-2 text-xs font-semibold <?php echo $credentials ? 'text-green-700' : 'text-amber-700'; ?>">
      <span class="status-dot <?php echo $credentials ? 'bg-green-500' : 'bg-amber-500'; ?>"></span>
      <?php echo $credentials ? htmlspecialchars($credentials['client_email']) : 'Chưa có Service Account'; ?>
    </div>
  </div>
  <form method="POST" enctype="multipart/form-data" class="a-card-body">
    <?php echo csrfField(); ?>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <div>
        <h3 class="font-bold text-midnight mb-4">Google Analytics & Search Console</h3>
        <div class="grid sm:grid-cols-2 gap-4">
          <div class="a-field"><label class="a-label">GA4 Measurement ID</label><input class="a-input" name="ga_id" value="<?php echo htmlspecialchars((string)getSetting('ga_id', '')); ?>" placeholder="G-XXXXXXXXXX"></div>
          <div class="a-field"><label class="a-label">GA4 Property ID</label><input class="a-input" name="ga_property_id" value="<?php echo htmlspecialchars((string)getSetting('ga_property_id', '')); ?>" inputmode="numeric" placeholder="123456789"></div>
          <div class="a-field sm:col-span-2"><label class="a-label">Search Console property</label><input class="a-input" name="gsc_site_url" value="<?php echo htmlspecialchars((string)getSetting('gsc_site_url', '')); ?>" placeholder="sc-domain:brighteducation.net"></div>
          <div class="a-field sm:col-span-2"><label class="a-label">Search Console verification content</label><input class="a-input" name="gsc_verification" value="<?php echo htmlspecialchars((string)getSetting('gsc_verification', '')); ?>" placeholder="Mã content của thẻ google-site-verification"></div>
          <div class="a-field sm:col-span-2"><label class="a-label">Looker Studio embed URL</label><input class="a-input" type="url" name="looker_embed_url" value="<?php echo htmlspecialchars($lookerUrl); ?>" placeholder="https://lookerstudio.google.com/embed/reporting/..."></div>
          <div class="a-field sm:col-span-2">
            <label class="a-label">Service Account JSON</label>
            <input class="a-input" type="file" name="service_account_file" accept="application/json,.json">
            <textarea class="a-input mt-2 font-mono text-xs" name="service_account_json" rows="3" placeholder="Hoặc dán nội dung JSON mới tại đây. Để trống để giữ credentials hiện tại."></textarea>
            <?php if ($credentials): ?><label class="mt-2 inline-flex items-center gap-2 text-xs text-red-600"><input type="checkbox" name="remove_credentials" value="1"> Xóa credentials hiện tại</label><?php endif; ?>
          </div>
        </div>
      </div>

      <div>
        <h3 class="font-bold text-midnight mb-4">Mục tiêu KPI & Organic ROI</h3>
        <div class="grid sm:grid-cols-2 gap-4">
          <div class="a-field"><label class="a-label">Chi phí SEO / tháng (VND)</label><input class="a-input" type="number" min="0" name="seo_monthly_cost" value="<?php echo htmlspecialchars((string)getSetting('seo_monthly_cost', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">Giá trị trung bình / lead (VND)</label><input class="a-input" type="number" min="0" name="organic_lead_value" value="<?php echo htmlspecialchars((string)getSetting('organic_lead_value', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">Organic Traffic mục tiêu</label><input class="a-input" type="number" min="0" name="kpi_organic_sessions_target" value="<?php echo htmlspecialchars((string)getSetting('kpi_organic_sessions_target', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">Impressions mục tiêu</label><input class="a-input" type="number" min="0" name="kpi_impressions_target" value="<?php echo htmlspecialchars((string)getSetting('kpi_impressions_target', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">Vị trí từ khóa mục tiêu (Top)</label><input class="a-input" type="number" min="0" name="kpi_position_target" value="<?php echo htmlspecialchars((string)getSetting('kpi_position_target', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">CTR mục tiêu (%)</label><input class="a-input" type="number" min="0" step="0.1" name="kpi_ctr_target" value="<?php echo htmlspecialchars((string)getSetting('kpi_ctr_target', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">Engagement Rate mục tiêu (%)</label><input class="a-input" type="number" min="0" step="0.1" name="kpi_engagement_rate_target" value="<?php echo htmlspecialchars((string)getSetting('kpi_engagement_rate_target', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">Avg. Engagement Time (giây)</label><input class="a-input" type="number" min="0" name="kpi_avg_engagement_time_target" value="<?php echo htmlspecialchars((string)getSetting('kpi_avg_engagement_time_target', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">Conversion Rate mục tiêu (%)</label><input class="a-input" type="number" min="0" step="0.1" name="kpi_conversion_rate_target" value="<?php echo htmlspecialchars((string)getSetting('kpi_conversion_rate_target', '0')); ?>"></div>
          <div class="a-field"><label class="a-label">Organic ROI mục tiêu (%)</label><input class="a-input" type="number" min="0" step="0.1" name="kpi_roi_target" value="<?php echo htmlspecialchars((string)getSetting('kpi_roi_target', '0')); ?>"></div>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4 mt-2 text-xs text-slate-600 leading-relaxed">
          <strong class="text-midnight">Cách tính Organic ROI:</strong> (số lead organic × giá trị/lead − chi phí SEO quy đổi theo kỳ) ÷ chi phí SEO × 100%.
        </div>
      </div>
    </div>

    <div class="border-t border-slate-100 mt-6 pt-5 flex justify-end">
      <button type="submit" class="btn-adm"><i class="bi bi-shield-check"></i> Lưu cấu hình đo lường</button>
    </div>
  </form>
</div>

<div class="a-card mb-5">
  <div class="a-card-header"><h2>Các bước cấp quyền Google APIs</h2></div>
  <div class="a-card-body grid md:grid-cols-2 xl:grid-cols-4 gap-5 text-sm text-slate-600">
    <div class="setup-step"><span class="setup-step-num">1</span><p>Tạo Service Account trong Google Cloud và bật <strong>Google Analytics Data API</strong> cùng <strong>Search Console API</strong>.</p></div>
    <div class="setup-step"><span class="setup-step-num">2</span><p>Thêm email Service Account vào GA4 Property với quyền <strong>Viewer</strong>.</p></div>
    <div class="setup-step"><span class="setup-step-num">3</span><p>Thêm cùng email vào Search Console property với quyền đọc dữ liệu.</p></div>
    <div class="setup-step"><span class="setup-step-num">4</span><p>Tải JSON key, nhập Property ID/property URL ở trên và nhấn lưu.</p></div>
    <div class="setup-step"><span class="setup-step-num">5</span><p>Trong GA4 Events, đánh dấu <code>generate_lead</code> là <strong>Key event</strong> để dùng trong các báo cáo chuẩn của Google.</p></div>
  </div>
</div>
<?php endif; ?>

<?php include dirname(__DIR__) . '/includes/admin/footer.php'; ?>
