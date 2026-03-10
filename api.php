<?php
include './config.php';

if (!file_exists('./upload')) {
    mkdir('./upload', 0777, true);
}

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
            list($token, $count) = explode('|', $line);
            $tokens[$token] = (int)$count;
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

// Function to check if token is valid and has remaining operations
function validateToken($token)
{
    global $token_verification;

    // If token verification is disabled, always return true
    if (!$token_verification) {
        return ['valid' => true, 'remaining' => -1];
    }

    // If token verification is enabled, check from file
    $tokens = readTokens();

    if (isset($tokens[$token]) && $tokens[$token] > 0) {
        // Decrease token count
        $remaining = $tokens[$token] - 1;
        if ($remaining <= 0) {
            // Remove token if remaining count is 0 or less
            unset($tokens[$token]);
        } else {
            $tokens[$token] = $remaining;
        }
        writeTokens($tokens);
        return ['valid' => true, 'remaining' => $remaining];
    }

    return ['valid' => false, 'remaining' => 0];
}

// Function to generate unique 8-digit number
function generateUniqueCode()
{
    $codeFile = './upload/codes.txt';
    $codes = [];

    // Read existing codes
    if (file_exists($codeFile)) {
        $codes = file($codeFile, FILE_IGNORE_NEW_LINES);
    }

    do {
        $code = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    } while (in_array($code, $codes));

    // Save new code
    file_put_contents($codeFile, $code . PHP_EOL, FILE_APPEND);

    return $code;
}

// Function to get file path from code
function getFilePathFromCode($code)
{
    $mappingFile = './upload/mappings.txt';

    if (!file_exists($mappingFile)) {
        return false;
    }

    $lines = file($mappingFile, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        list($storedCode, $filename) = explode('|', $line);
        if ($storedCode == $code) {
            return '/upload/' . $filename;
        }
    }

    return false;
}

// Check if token is admin
if (isset($_POST['type']) && $_POST['type'] === 'check_admin') {
    if (isset($_POST['token'])) {
        global $token_admin;
        if ($_POST['token'] === $token_admin) {
            echo json_encode([
                'success' => true,
                'is_admin' => true
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'is_admin' => false
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing token parameter'
        ]);
    }
    exit;
}

// Check if token is valid for regular operations
if (isset($_POST['type']) && $_POST['type'] === 'token_set') {
    // Token management requires valid token
    global $token_admin;
    if (isset($_POST['token']) && $_POST['token'] === $token_admin) {
        if (isset($_POST['action']) && (isset($_POST['tokenname']) || $_POST['action'] === 'list')) {
            $tokens = readTokens();

            switch ($_POST['action']) {
                case 'add':
                    if (isset($_POST['token_count']) && is_numeric($_POST['token_count'])) {
                        $tokenName = $_POST['tokenname'];
                        $tokenCount = (int)$_POST['token_count'];
                        $tokens[$tokenName] = $tokenCount;
                        writeTokens($tokens);
                        echo 'Token成功添加喵~';
                    } else {
                        echo 'token_count 参数缺失或无效';
                    }
                    break;

                case 'del':
                    $tokenName = $_POST['tokenname'];
                    if (isset($tokens[$tokenName])) {
                        unset($tokens[$tokenName]);
                        writeTokens($tokens);
                        echo 'Token成功删除喵~';
                    } else {
                        echo '没有找到指定的Token喵~';
                    }
                    break;

                case 'query':
                    $tokenName = $_POST['tokenname'];
                    if (isset($tokens[$tokenName])) {
                        echo json_encode([
                            'success' => true,
                            'token' => $tokenName,
                            'count' => $tokens[$tokenName]
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Token not found'
                        ]);
                    }
                    break;

                case 'update':
                    $tokenName = $_POST['tokenname'];
                    if (isset($_POST['token_count']) && is_numeric($_POST['token_count'])) {
                        $tokenCount = (int)$_POST['token_count'];
                        if (isset($tokens[$tokenName])) {
                            $tokens[$tokenName] = $tokenCount;
                            writeTokens($tokens);
                            echo 'Token成功更新喵~';
                        } else {
                            echo '没有找到指定的Token喵~';
                        }
                    } else {
                        echo 'token_count 参数缺失或无效';
                    }
                    break;

                case 'list':
                    echo json_encode([
                        'success' => true,
                        'tokens' => $tokens
                    ]);
                    break;

                default:
                    echo 'Invalid action';
                    break;
            }
        } else {
            echo '缺少 action 或 tokenname 参数';
        }
    } else {
        echo '无效的Token喵~';
    }
} else {
    // Regular operations require valid token
    global $token_verification;

    if ($token_verification) {
        // Token verification enabled, check token
        if (isset($_POST['token'])) {
            $validation = validateToken($_POST['token']);
            if ($validation['valid']) {
                // Process request and return remaining token count
                ob_start();
                processRequest();
                $response = ob_get_clean();
                // If response is a valid code or file path, add remaining count
                if (strlen($response) === 8 && ctype_digit($response) || strpos($response, '/upload/') === 0) {
                    echo json_encode([
                        'success' => true,
                        'data' => $response,
                        'remaining' => $validation['remaining']
                    ]);
                } else {
                    echo $response;
                }
            } else {
                echo '无效的Token喵~';
            }
        } else {
            echo '缺少 token 参数';
        }
    } else {
        // Token verification disabled, skip token check
        ob_start();
        processRequest();
        $response = ob_get_clean();
        // If response is a valid code or file path, add remaining count
        if (strlen($response) === 8 && ctype_digit($response) || strpos($response, '/upload/') === 0) {
            echo json_encode([
                'success' => true,
                'data' => $response,
                'remaining' => -1
            ]);
        } else {
            echo $response;
        }
    }
}



// Process the request
function processRequest()
{
    if (isset($_POST['type'])) {
        switch ($_POST['type']) {
            case 'upload':
                // Handle file upload
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    // Generate filename with timestamp and original extension
                    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    $timestamp = time();
                    $filename = $timestamp . '.' . $extension;
                    $targetPath = './upload/' . $filename;

                    // Move uploaded file
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                        // Generate unique code
                        $code = generateUniqueCode();

                        // Store mapping
                        $mappingFile = './upload/mappings.txt';
                        file_put_contents($mappingFile, $code . '|' . $filename . PHP_EOL, FILE_APPEND);

                        // Return code
                        echo $code;
                    } else {
                        echo '上传失败喵~';
                    }
                } else {
                    echo '没有上传文件或上传错误喵~';
                }
                break;

            case 'getfile':
                // Handle getfile request
                if (isset($_POST['code']) && strlen($_POST['code']) === 8 && ctype_digit($_POST['code'])) {
                    $filePath = getFilePathFromCode($_POST['code']);
                    if ($filePath) {
                        echo $filePath;
                    } else {
                        echo '没有找到指定的文件喵~';
                    }
                } else {
                    echo '无效的文件代码喵~';
                }
                break;

            default:
                echo '无效的类型喵~';
                break;
        }
    } else {
        echo '缺少 type 参数';
    }
}
