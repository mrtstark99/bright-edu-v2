<?php

/**
 * Google Analytics 4 + Search Console reporting helpers.
 * Service-account credentials are encrypted before being stored in SQLite.
 */

function analyticsBase64UrlEncode(string $value): string
{
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

function analyticsEncryptSecret(string $plainText): string
{
    if ($plainText === '' || !function_exists('openssl_encrypt')) {
        return '';
    }

    $key = hash('sha256', SECURE_AUTH_KEY, true);
    $iv = random_bytes(12);
    $tag = '';
    $cipherText = openssl_encrypt($plainText, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    if ($cipherText === false) {
        return '';
    }

    return 'v1:' . base64_encode($iv . $tag . $cipherText);
}

function analyticsDecryptSecret(string $encrypted): string
{
    if ($encrypted === '') {
        return '';
    }

    // Backward-compatible support for credentials saved as plain JSON.
    if (str_starts_with(ltrim($encrypted), '{')) {
        return $encrypted;
    }

    if (!str_starts_with($encrypted, 'v1:') || !function_exists('openssl_decrypt')) {
        return '';
    }

    $payload = base64_decode(substr($encrypted, 3), true);
    if ($payload === false || strlen($payload) < 29) {
        return '';
    }

    $iv = substr($payload, 0, 12);
    $tag = substr($payload, 12, 16);
    $cipherText = substr($payload, 28);
    $key = hash('sha256', SECURE_AUTH_KEY, true);
    $plainText = openssl_decrypt($cipherText, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);

    return $plainText === false ? '' : $plainText;
}

function analyticsGetCredentials(): ?array
{
    $json = analyticsDecryptSecret((string)getSetting('google_service_account_enc', ''));
    if ($json === '') {
        return null;
    }

    $credentials = json_decode($json, true);
    if (!is_array($credentials)
        || empty($credentials['client_email'])
        || empty($credentials['private_key'])
        || ($credentials['token_uri'] ?? '') !== 'https://oauth2.googleapis.com/token') {
        return null;
    }

    return $credentials;
}

function analyticsHttpRequest(string $method, string $url, array $headers = [], ?string $body = null): array
{
    if (function_exists('curl_init')) {
        $handle = curl_init($url);
        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => false,
        ]);
        if ($body !== null) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
        }
        $responseBody = curl_exec($handle);
        $status = (int)curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $error = curl_error($handle);
        curl_close($handle);

        return [
            'ok' => $responseBody !== false && $status >= 200 && $status < 300,
            'status' => $status,
            'body' => $responseBody === false ? '' : $responseBody,
            'error' => $error,
        ];
    }

    $options = [
        'http' => [
            'method' => strtoupper($method),
            'header' => implode("\r\n", $headers),
            'content' => $body ?? '',
            'timeout' => 25,
            'ignore_errors' => true,
        ],
    ];
    $responseBody = @file_get_contents($url, false, stream_context_create($options));
    $status = 0;
    foreach ($http_response_header ?? [] as $header) {
        if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $matches)) {
            $status = (int)$matches[1];
        }
    }

    return [
        'ok' => $responseBody !== false && $status >= 200 && $status < 300,
        'status' => $status,
        'body' => $responseBody === false ? '' : $responseBody,
        'error' => $responseBody === false ? 'Không thể kết nối đến Google APIs.' : '',
    ];
}

function analyticsGoogleAccessToken(array $credentials): array
{
    static $cachedToken = null;
    if ($cachedToken !== null) {
        return ['ok' => true, 'token' => $cachedToken];
    }

    if (!function_exists('openssl_sign')) {
        return ['ok' => false, 'error' => 'Máy chủ chưa bật OpenSSL để ký Google OAuth token.'];
    }

    $now = time();
    $header = ['alg' => 'RS256', 'typ' => 'JWT'];
    if (!empty($credentials['private_key_id'])) {
        $header['kid'] = $credentials['private_key_id'];
    }
    $claims = [
        'iss' => $credentials['client_email'],
        'scope' => implode(' ', [
            'https://www.googleapis.com/auth/analytics.readonly',
            'https://www.googleapis.com/auth/webmasters.readonly',
        ]),
        'aud' => $credentials['token_uri'] ?: 'https://oauth2.googleapis.com/token',
        'iat' => $now - 30,
        'exp' => $now + 3500,
    ];

    $unsigned = analyticsBase64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES))
        . '.' . analyticsBase64UrlEncode(json_encode($claims, JSON_UNESCAPED_SLASHES));
    $signature = '';
    $privateKey = openssl_pkey_get_private($credentials['private_key']);
    if ($privateKey === false || !openssl_sign($unsigned, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
        return ['ok' => false, 'error' => 'Private key của Service Account không hợp lệ.'];
    }

    $jwt = $unsigned . '.' . analyticsBase64UrlEncode($signature);
    $response = analyticsHttpRequest(
        'POST',
        $credentials['token_uri'],
        ['Content-Type: application/x-www-form-urlencoded'],
        http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ])
    );
    $decoded = json_decode($response['body'], true);
    if (!$response['ok'] || empty($decoded['access_token'])) {
        $message = $decoded['error_description'] ?? $decoded['error'] ?? $response['error'] ?? 'Không lấy được Google access token.';
        return ['ok' => false, 'error' => is_string($message) ? $message : 'Google OAuth từ chối yêu cầu.'];
    }

    $cachedToken = $decoded['access_token'];
    return ['ok' => true, 'token' => $cachedToken];
}

function analyticsGoogleApiPost(string $url, string $token, array $payload): array
{
    $response = analyticsHttpRequest(
        'POST',
        $url,
        [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ],
        json_encode($payload, JSON_UNESCAPED_SLASHES)
    );
    $decoded = json_decode($response['body'], true);
    if (!$response['ok']) {
        $message = $decoded['error']['message'] ?? $response['error'] ?? ('Google API HTTP ' . $response['status']);
        return ['ok' => false, 'error' => $message, 'data' => []];
    }

    return ['ok' => true, 'data' => is_array($decoded) ? $decoded : []];
}

function analyticsMetricMap(array $report, int $rowIndex = 0): array
{
    $headers = array_column($report['metricHeaders'] ?? [], 'name');
    $values = $report['rows'][$rowIndex]['metricValues'] ?? [];
    $mapped = [];
    foreach ($headers as $index => $name) {
        $mapped[$name] = (float)($values[$index]['value'] ?? 0);
    }
    return $mapped;
}

function analyticsFetchGa4(string $propertyId, string $token, int $days): array
{
    $baseUrl = 'https://analyticsdata.googleapis.com/v1beta/properties/' . rawurlencode($propertyId) . ':runReport';
    $dateRanges = [['startDate' => $days . 'daysAgo', 'endDate' => 'yesterday']];
    $summary = analyticsGoogleApiPost($baseUrl, $token, [
        'dateRanges' => $dateRanges,
        'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
        'metrics' => array_map(static fn($name) => ['name' => $name], [
            'sessions',
            'engagedSessions',
            'engagementRate',
            'userEngagementDuration',
            'keyEvents',
            'sessionKeyEventRate',
        ]),
        'dimensionFilter' => [
            'filter' => [
                'fieldName' => 'sessionDefaultChannelGroup',
                'stringFilter' => ['matchType' => 'EXACT', 'value' => 'Organic Search'],
            ],
        ],
        'limit' => 1,
    ]);
    if (!$summary['ok']) {
        return $summary;
    }

    $events = analyticsGoogleApiPost($baseUrl, $token, [
        'dateRanges' => $dateRanges,
        'dimensions' => [['name' => 'eventName']],
        'metrics' => [['name' => 'eventCount']],
        'dimensionFilter' => [
            'andGroup' => [
                'expressions' => [
                    [
                        'filter' => [
                            'fieldName' => 'eventName',
                            'inListFilter' => [
                                'values' => ['click_to_call', 'copy_email', 'scroll_depth', 'lead_form_start', 'form_submit_attempt', 'generate_lead'],
                            ],
                        ],
                    ],
                    [
                        'filter' => [
                            'fieldName' => 'sessionDefaultChannelGroup',
                            'stringFilter' => ['matchType' => 'EXACT', 'value' => 'Organic Search'],
                        ],
                    ],
                ],
            ],
        ],
        'orderBys' => [['metric' => ['metricName' => 'eventCount'], 'desc' => true]],
    ]);

    $eventCounts = [];
    if ($events['ok']) {
        foreach ($events['data']['rows'] ?? [] as $row) {
            $eventCounts[$row['dimensionValues'][0]['value'] ?? ''] = (int)($row['metricValues'][0]['value'] ?? 0);
        }
    }

    $summaryMetrics = analyticsMetricMap($summary['data']);
    $summaryMetrics['averageEngagementTimePerSession'] = ($summaryMetrics['sessions'] ?? 0) > 0
        ? ($summaryMetrics['userEngagementDuration'] ?? 0) / $summaryMetrics['sessions']
        : 0;

    return [
        'ok' => true,
        'summary' => $summaryMetrics,
        'events' => $eventCounts,
        'event_error' => $events['ok'] ? '' : $events['error'],
    ];
}

function analyticsFetchSearchConsole(string $siteUrl, string $token, int $days): array
{
    $endDate = date('Y-m-d', strtotime('-1 day'));
    $startDate = date('Y-m-d', strtotime('-' . $days . ' days'));
    $url = 'https://www.googleapis.com/webmasters/v3/sites/' . rawurlencode($siteUrl) . '/searchAnalytics/query';
    $basePayload = [
        'startDate' => $startDate,
        'endDate' => $endDate,
        'type' => 'web',
        'dataState' => 'final',
    ];

    $summary = analyticsGoogleApiPost($url, $token, $basePayload + ['rowLimit' => 1]);
    if (!$summary['ok']) {
        return $summary;
    }

    $keywords = analyticsGoogleApiPost($url, $token, $basePayload + [
        'dimensions' => ['query'],
        'rowLimit' => 20,
    ]);

    $summaryRow = $summary['data']['rows'][0] ?? [];
    $keywordRows = [];
    if ($keywords['ok']) {
        foreach ($keywords['data']['rows'] ?? [] as $row) {
            $keywordRows[] = [
                'keyword' => $row['keys'][0] ?? '',
                'clicks' => (int)round($row['clicks'] ?? 0),
                'impressions' => (int)round($row['impressions'] ?? 0),
                'ctr' => (float)($row['ctr'] ?? 0),
                'position' => (float)($row['position'] ?? 0),
            ];
        }
    }

    return [
        'ok' => true,
        'summary' => [
            'clicks' => (int)round($summaryRow['clicks'] ?? 0),
            'impressions' => (int)round($summaryRow['impressions'] ?? 0),
            'ctr' => (float)($summaryRow['ctr'] ?? 0),
            'position' => (float)($summaryRow['position'] ?? 0),
        ],
        'keywords' => $keywordRows,
        'keyword_error' => $keywords['ok'] ? '' : $keywords['error'],
    ];
}

function analyticsCacheGet(string $key): ?array
{
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT payload, updated_at FROM analytics_cache WHERE cache_key = ? AND expires_at > datetime('now','localtime')");
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    $payload = json_decode($row['payload'], true);
    if (!is_array($payload)) {
        return null;
    }
    $payload['cache_updated_at'] = $row['updated_at'];
    return $payload;
}

function analyticsCacheSet(string $key, array $payload, int $minutes = 30): void
{
    $db = Database::getInstance();
    $stmt = $db->prepare("INSERT OR REPLACE INTO analytics_cache (cache_key, payload, expires_at, updated_at) VALUES (?, ?, datetime('now','localtime', ?), datetime('now','localtime'))");
    $stmt->execute([$key, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), '+' . $minutes . ' minutes']);
}

function analyticsClearCache(): void
{
    Database::getInstance()->exec('DELETE FROM analytics_cache');
}

function analyticsDashboardData(int $days = 28, bool $forceRefresh = false): array
{
    $days = in_array($days, [7, 28, 90], true) ? $days : 28;
    $cacheKey = 'google_dashboard_' . $days;
    if (!$forceRefresh) {
        $cached = analyticsCacheGet($cacheKey);
        if ($cached !== null) {
            $cached['from_cache'] = true;
            return $cached;
        }
    }

    $propertyId = preg_replace('/\D+/', '', (string)getSetting('ga_property_id', ''));
    $siteUrl = trim((string)getSetting('gsc_site_url', ''));
    $credentials = analyticsGetCredentials();
    $data = [
        'days' => $days,
        'from_cache' => false,
        'ga' => null,
        'gsc' => null,
        'errors' => [],
        'configured' => [
            'ga' => $propertyId !== '',
            'gsc' => $siteUrl !== '',
            'credentials' => $credentials !== null,
        ],
    ];

    if ($credentials === null) {
        $data['errors'][] = 'Chưa có Service Account JSON hợp lệ.';
        return $data;
    }

    $tokenResult = analyticsGoogleAccessToken($credentials);
    if (!$tokenResult['ok']) {
        $data['errors'][] = $tokenResult['error'];
        return $data;
    }

    if ($propertyId !== '') {
        $gaResult = analyticsFetchGa4($propertyId, $tokenResult['token'], $days);
        if ($gaResult['ok']) {
            $data['ga'] = $gaResult;
        } else {
            $data['errors'][] = 'GA4: ' . $gaResult['error'];
        }
    } else {
        $data['errors'][] = 'Chưa nhập GA4 Property ID.';
    }

    if ($siteUrl !== '') {
        $gscResult = analyticsFetchSearchConsole($siteUrl, $tokenResult['token'], $days);
        if ($gscResult['ok']) {
            $data['gsc'] = $gscResult;
        } else {
            $data['errors'][] = 'Search Console: ' . $gscResult['error'];
        }
    } else {
        $data['errors'][] = 'Chưa nhập Search Console property.';
    }

    analyticsCacheSet($cacheKey, $data, ($data['ga'] !== null || $data['gsc'] !== null) ? 30 : 5);

    return $data;
}

function analyticsPagePerformanceData(int $days = 28, bool $forceRefresh = false): array
{
    $days = in_array($days, [7, 28, 90], true) ? $days : 28;
    $cacheKey = 'google_page_performance_' . $days;
    if (!$forceRefresh) {
        $cached = analyticsCacheGet($cacheKey);
        if ($cached !== null) {
            $cached['from_cache'] = true;
            return $cached;
        }
    }

    $propertyId = preg_replace('/\D+/', '', (string)getSetting('ga_property_id', ''));
    $siteUrl = trim((string)getSetting('gsc_site_url', ''));
    $credentials = analyticsGetCredentials();

    $data = [
        'days' => $days,
        'from_cache' => false,
        'performance' => [],
        'errors' => [],
        'configured' => [
            'ga' => $propertyId !== '',
            'gsc' => $siteUrl !== '',
            'credentials' => $credentials !== null,
        ]
    ];

    if ($credentials === null) {
        $data['errors'][] = 'Chưa có Service Account JSON hợp lệ.';
        return $data;
    }

    $tokenResult = analyticsGoogleAccessToken($credentials);
    if (!$tokenResult['ok']) {
        $data['errors'][] = $tokenResult['error'];
        return $data;
    }

    $token = $tokenResult['token'];
    $normalizePath = static function (string $url): string {
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $path = trim($path);
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }
        return $path === '' ? '/' : $path;
    };

    $pages = [];
    $gaData = [];

    // 1. Fetch Search Console page + query data
    if ($siteUrl !== '') {
        $gscUrl = 'https://www.googleapis.com/webmasters/v3/sites/' . rawurlencode($siteUrl) . '/searchAnalytics/query';
        $gscPayload = [
            'startDate' => date('Y-m-d', strtotime('-' . $days . ' days')),
            'endDate' => date('Y-m-d', strtotime('-1 day')),
            'type' => 'web',
            'dataState' => 'final',
            'dimensions' => ['page', 'query'],
            'rowLimit' => 1000
        ];
        $gscResult = analyticsGoogleApiPost($gscUrl, $token, $gscPayload);
        if ($gscResult['ok'] && isset($gscResult['data']['rows'])) {
            foreach ($gscResult['data']['rows'] as $row) {
                $pageUrl = $row['keys'][0] ?? '';
                $query = $row['keys'][1] ?? '';
                $clicks = (int)round($row['clicks'] ?? 0);
                $impressions = (int)round($row['impressions'] ?? 0);
                $ctr = (float)($row['ctr'] ?? 0);
                $position = (float)($row['position'] ?? 0);

                $normPath = $normalizePath($pageUrl);

                if (!isset($pages[$normPath])) {
                    $pages[$normPath] = [
                        'clicks' => 0,
                        'impressions' => 0,
                        'position_sum' => 0,
                        'top_queries' => []
                    ];
                }
                $pages[$normPath]['clicks'] += $clicks;
                $pages[$normPath]['impressions'] += $impressions;
                $pages[$normPath]['position_sum'] += ($position * $impressions);

                if (count($pages[$normPath]['top_queries']) < 5) {
                    $pages[$normPath]['top_queries'][] = [
                        'query' => $query,
                        'clicks' => $clicks,
                        'impressions' => $impressions,
                        'ctr' => $ctr,
                        'position' => $position
                    ];
                }
            }
            foreach ($pages as &$p) {
                $p['ctr'] = $p['impressions'] > 0 ? $p['clicks'] / $p['impressions'] : 0;
                $p['position'] = $p['impressions'] > 0 ? $p['position_sum'] / $p['impressions'] : 0;
                unset($p['position_sum']);
            }
        } else {
            $data['errors'][] = 'Search Console: ' . ($gscResult['error'] ?? 'Lỗi không rõ.');
        }
    }

    // 2. Fetch GA4 Landing Page organic channel report
    if ($propertyId !== '') {
        $gaUrl = 'https://analyticsdata.googleapis.com/v1beta/properties/' . rawurlencode($propertyId) . ':runReport';
        $gaPayload = [
            'dateRanges' => [['startDate' => $days . 'daysAgo', 'endDate' => 'yesterday']],
            'dimensions' => [['name' => 'landingPagePlusQueryString']],
            'metrics' => [
                ['name' => 'sessions'],
                ['name' => 'engagedSessions'],
                ['name' => 'engagementRate'],
                ['name' => 'userEngagementDuration'],
                ['name' => 'keyEvents'],
                ['name' => 'sessionKeyEventRate']
            ],
            'dimensionFilter' => [
                'filter' => [
                    'fieldName' => 'sessionDefaultChannelGroup',
                    'stringFilter' => ['matchType' => 'EXACT', 'value' => 'Organic Search'],
                ]
            ],
            'limit' => 500
        ];
        $gaResult = analyticsGoogleApiPost($gaUrl, $token, $gaPayload);
        if ($gaResult['ok'] && isset($gaResult['data']['rows'])) {
            foreach ($gaResult['data']['rows'] as $row) {
                $landingPage = $row['dimensionValues'][0]['value'] ?? '';
                $normPath = $normalizePath($landingPage);

                $metrics = $row['metricValues'] ?? [];
                $sessions = (float)($metrics[0]['value'] ?? 0);
                $engagedSessions = (float)($metrics[1]['value'] ?? 0);
                $engagementRate = (float)($metrics[2]['value'] ?? 0);
                $userDuration = (float)($metrics[3]['value'] ?? 0);
                $keyEvents = (float)($metrics[4]['value'] ?? 0);
                $sessionKeyEventRate = (float)($metrics[5]['value'] ?? 0);

                if (!isset($gaData[$normPath])) {
                    $gaData[$normPath] = [
                        'organic_sessions' => 0,
                        'engaged_sessions' => 0,
                        'user_duration' => 0,
                        'leads' => 0
                    ];
                }
                $gaData[$normPath]['organic_sessions'] += $sessions;
                $gaData[$normPath]['engaged_sessions'] += $engagedSessions;
                $gaData[$normPath]['user_duration'] += $userDuration;
                $gaData[$normPath]['leads'] += $keyEvents;
            }
            foreach ($gaData as &$g) {
                $g['engagement_rate'] = $g['organic_sessions'] > 0 ? $g['engaged_sessions'] / $g['organic_sessions'] : 0;
                $g['avg_engagement_time'] = $g['organic_sessions'] > 0 ? $g['user_duration'] / $g['organic_sessions'] : 0;
                $g['conversion_rate'] = $g['organic_sessions'] > 0 ? $g['leads'] / $g['organic_sessions'] : 0;
                unset($g['engaged_sessions'], $g['user_duration']);
            }
        } else {
            $data['errors'][] = 'GA4: ' . ($gaResult['error'] ?? 'Lỗi không rõ.');
        }
    }

    // 3. Combine results
    $performance = [];
    $allUrls = array_unique(array_merge(array_keys($pages), array_keys($gaData)));
    foreach ($allUrls as $url) {
        $performance[] = [
            'url' => $url,
            'gsc' => $pages[$url] ?? null,
            'ga4' => $gaData[$url] ?? null
        ];
    }

    $data['performance'] = $performance;
    
    // Cache for 30 minutes if successful
    analyticsCacheSet($cacheKey, $data, (!empty($performance)) ? 30 : 5);

    return $data;
}
