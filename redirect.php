<?php
include 'config.php';

// Token file path
$tokenFile = './upload/tokens.txt';

// Function to read tokens from file
function readTokens()
{
    global $tokenFile;
    $tokens = [];

    if (file_exists($tokenFile)) {
        $lines = file($tokenFile, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '|') !== false) {
                list($token, $count) = explode('|', $line);
                $tokens[$token] = (int)$count;
            }
        }
    }

    return $tokens;
}

// Function to write tokens to file
function writeTokens($tokens)
{
    global $tokenFile;
    $content = '';

    foreach ($tokens as $token => $count) {
        $content .= $token . '|' . $count . PHP_EOL;
    }

    file_put_contents($tokenFile, $content);
}

// 获取请求参数
$linkId = isset($_GET['link_id']) ? (int)$_GET['link_id'] : 0;
$customUrl = isset($_GET['custom_url']) ? $_GET['custom_url'] : '';
$token = isset($_COOKIE['upload_token']) ? $_COOKIE['upload_token'] : '';

// 自定义跳转处理
if (!empty($customUrl)) {
    $targetUrl = $customUrl;
    $linkName = '自定义跳转';
    $fee = 15; // 自定义跳转扣除15点额度
} else {
    // 验证参数
    if ($linkId <= 0) {
        showError('无效的链接ID');
        exit;
    }

    // 查找链接配置
    $linkConfig = null;
    foreach ($link_data as $link) {
        if ($link['id'] === $linkId) {
            $linkConfig = $link;
            break;
        }
    }

    if (!$linkConfig) {
        showError('链接配置不存在');
        exit;
    }

    $targetUrl = $linkConfig['link'];
    $linkName = $linkConfig['name'];
    $fee = $linkConfig['fee'];
}

// 如果不需要token验证，直接跳转
if (!isset($token_verification) || !$token_verification) {
    showRedirectPage($linkName, $targetUrl, 0, -1, $fee);
    exit;
}

// 检查是否有token
if (empty($token)) {
    showError('请先获取Token', 'upload.php?error=no_token');
    exit;
}

// 读取tokens
$tokens = readTokens();

// 检查token是否存在且额度足够
if (!isset($tokens[$token])) {
    showError('Token无效或已过期', 'upload.php?error=invalid_token');
    exit;
}

if ($tokens[$token] < $fee) {
    showError('Token剩余次数不足，需要 ' . $fee . ' 次，剩余 ' . $tokens[$token] . ' 次', 'upload.php?error=insufficient_quota');
    exit;
}

// 扣除前的剩余额度
$remainingBefore = $tokens[$token];

// 扣除fee额度
$tokens[$token] -= $fee;

// 扣除后的剩余额度
$remainingAfter = $tokens[$token];

// 如果额度用完，删除token
if ($tokens[$token] <= 0) {
    unset($tokens[$token]);
    $remainingAfter = 0;
}

// 保存tokens
writeTokens($tokens);

// 显示跳转页面
showRedirectPage($linkName, $targetUrl, $fee, $remainingAfter, $remainingBefore);
exit;

// 显示跳转页面（带过场动画）
function showRedirectPage($linkName, $targetUrl, $fee, $remaining, $remainingBefore)
{
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>正在跳转...</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .redirect-card {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 20px;
                padding: 50px 40px;
                max-width: 450px;
                width: 100%;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                animation: slideUp 0.5s ease;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .loading-spinner {
                width: 80px;
                height: 80px;
                margin: 0 auto 30px;
                position: relative;
            }

            .loading-spinner::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                border: 4px solid rgba(120, 180, 180, 0.2);
                border-top-color: #5cb8b2;
                animation: spin 1s linear infinite;
            }

            .loading-spinner::after {
                content: '🚀';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 32px;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            .redirect-title {
                font-size: 24px;
                color: #333;
                margin-bottom: 10px;
            }

            .redirect-target {
                font-size: 18px;
                color: #5cb8b2;
                margin-bottom: 25px;
                word-break: break-all;
            }

            .quota-info {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                border-radius: 15px;
                padding: 20px;
                margin-bottom: 25px;
            }

            .quota-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;
            }

            .quota-row:last-child {
                margin-bottom: 0;
                padding-top: 12px;
                border-top: 1px dashed rgba(0, 0, 0, 0.1);
            }

            .quota-label {
                font-size: 14px;
                color: #666;
            }

            .quota-value {
                font-size: 16px;
                font-weight: bold;
                color: #333;
            }

            .quota-value.used {
                color: #ff6b6b;
            }

            .quota-value.remaining {
                color: #2ecc71;
            }

            .progress-bar {
                width: 100%;
                height: 6px;
                background: rgba(0, 0, 0, 0.1);
                border-radius: 3px;
                overflow: hidden;
                margin-top: 15px;
            }

            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #5cb8b2 0%, #f5a6b9 100%);
                border-radius: 3px;
                transition: width 0.5s ease;
            }

            .redirect-tips {
                font-size: 13px;
                color: #999;
                margin-top: 20px;
            }

            .manual-link {
                display: inline-block;
                margin-top: 15px;
                padding: 10px 25px;
                background: linear-gradient(135deg, #5cb8b2 0%, #f5a6b9 100%);
                color: white;
                text-decoration: none;
                border-radius: 25px;
                font-size: 14px;
                transition: all 0.3s ease;
            }

            .manual-link:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(92, 184, 178, 0.4);
            }
        </style>
    </head>

    <body>
        <div class="redirect-card">
            <div class="loading-spinner"></div>
            <h1 class="redirect-title">正在前往</h1>
            <div class="redirect-target"><?php echo htmlspecialchars($linkName); ?></div>

            <div class="quota-info">
                <div class="quota-row">
                    <span class="quota-label">本次消耗</span>
                    <span class="quota-value used">-<?php echo $fee; ?> 次</span>
                </div>
                <?php if ($remaining >= 0): ?>
                    <div class="quota-row">
                        <span class="quota-label">剩余额度</span>
                        <span class="quota-value remaining"><?php echo $remaining; ?> 次</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $remainingBefore > 0 ? ($remaining / $remainingBefore * 100) : 0; ?>%"></div>
                    </div>
                <?php else: ?>
                    <div class="quota-row">
                        <span class="quota-label">验证状态</span>
                        <span class="quota-value remaining">无需验证</span>
                    </div>
                <?php endif; ?>
            </div>

            <a href="<?php echo htmlspecialchars($targetUrl); ?>" class="manual-link" target="_blank">立即跳转</a>

            <p class="redirect-tips">页面将在 5 秒后自动跳转...</p>
        </div>

        <script>
            // 5秒后自动跳转
            setTimeout(function() {
                window.location.href = '<?php echo htmlspecialchars($targetUrl); ?>';
            }, 5000);
        </script>
    </body>

    </html>
<?php
    exit;
}

// 显示错误页面
function showError($message, $redirectUrl = null)
{
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>跳转失败</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .error-card {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 20px;
                padding: 40px;
                max-width: 400px;
                width: 100%;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }

            .error-icon {
                font-size: 60px;
                margin-bottom: 20px;
            }

            .error-title {
                font-size: 24px;
                color: #333;
                margin-bottom: 15px;
            }

            .error-message {
                font-size: 16px;
                color: #666;
                margin-bottom: 30px;
                line-height: 1.5;
            }

            .error-actions {
                display: flex;
                gap: 10px;
                justify-content: center;
                flex-wrap: wrap;
            }

            .btn {
                padding: 12px 24px;
                border-radius: 25px;
                text-decoration: none;
                font-size: 14px;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }

            .btn-primary {
                background: linear-gradient(135deg, #5cb8b2 0%, #f5a6b9 100%);
                color: white;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(92, 184, 178, 0.4);
            }

            .btn-secondary {
                background: #f0f0f0;
                color: #666;
            }

            .btn-secondary:hover {
                background: #e0e0e0;
            }
        </style>
    </head>

    <body>
        <div class="error-card">
            <div class="error-icon">⚠️</div>
            <h1 class="error-title">跳转失败</h1>
            <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <div class="error-actions">
                <?php if ($redirectUrl): ?>
                    <a href="<?php echo htmlspecialchars($redirectUrl); ?>" class="btn btn-primary">去获取Token</a>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">返回首页</a>
            </div>
        </div>
    </body>

    </html>
<?php
    exit;
}
