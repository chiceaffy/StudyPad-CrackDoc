<?php
// Include config file
include_once './config.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>文件上传与获取</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @font-face {
            font-family: HarmonyOS;
            font-style: normal;
            font-display: swap;
            src: url(fonts/HarmonyOS_Sans_SC_Medium.subset.woff2) format('woff2')
        }

        body,
        html {
            height: 100%;
            font-family: HarmonyOS, sans-serif;
            overflow: hidden;
        }

        body {
            background: url('https://api.xhuaxs.com/wallpaper') fixed no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* 主容器 */
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        /* 功能按钮 */
        .function-btn {
            width: 200px;
            height: 60px;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .upload-btn {
            background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            color: white;
        }

        .getfile-btn {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: white;
        }

        .function-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* 模态框 */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            margin: 10% auto;
            padding: 30px;
            width: 90%;
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: slideIn 0.3s ease;
        }

        /* 关闭按钮 */
        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: #f8f9fa;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: #e9ecef;
            transform: scale(1.1);
        }

        /* 表单样式 */
        .modal-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .modal-header h2 {
            color: #343a40;
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .modal-header p {
            color: #6c757d;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #495057;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            font-size: 16px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .form-group input[type="file"]:focus {
            outline: none;
            border-color: #66a6ff;
            box-shadow: 0 0 0 0.2rem rgba(102, 166, 255, 0.25);
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            font-size: 16px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #8fd3f4;
            box-shadow: 0 0 0 0.2rem rgba(143, 211, 244, 0.25);
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .upload-submit {
            background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            color: white;
        }

        .getfile-submit {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: white;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* 结果显示 */
        .result {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #66a6ff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .result h4 {
            margin-bottom: 10px;
            color: #343a40;
        }

        .result p {
            margin: 5px 0;
            color: #6c757d;
        }

        .download-link {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .download-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* 动画 */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* 响应式设计 */
        @media screen and (max-width: 767px) {
            .modal-content {
                margin: 20% auto;
                padding: 20px;
                width: 95%;
            }

            .function-btn {
                width: 180px;
                height: 50px;
                font-size: 16px;
            }
        }

        /* 页脚 */
        footer {
            position: absolute;
            bottom: 20px;
            text-align: center;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        footer a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #38f9d7;
        }
    </style>
</head>

<body>
    <?php
    // 显示错误提示
    if (isset($_GET['error'])) {
        $error_message = '';
        switch ($_GET['error']) {
            case 'no_token':
                $error_message = '请先设置Token才能访问主页';
                break;
            case 'invalid_token':
                $error_message = 'Token无效或已用完，请重新设置';
                break;
        }
        if ($error_message) {
            echo '<div style="background: rgba(255, 100, 100, 0.9); color: white; padding: 15px; text-align: center; position: fixed; top: 0; left: 0; right: 0; z-index: 9999; font-weight: bold;">';
            echo htmlspecialchars($error_message);
            echo '</div>';
        }
    }
    ?>
    <!-- 主容器 - 带毛玻璃效果 -->
    <div style="position: relative; text-align: center; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 20px; padding: 40px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); max-width: 500px; width: 90%;">
        <!-- 标题区域 -->
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 48px; font-weight: bold; color: white; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); margin: 0;">图片快传</h1>
            <p style="font-size: 18px; color: white; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); margin: 10px 0 0 0;">快速上传和分享图片</p>
        </div>

        <div class="main-container">
            <button class="function-btn upload-btn" onclick="openUploadModal()">文件上传</button>
            <button class="function-btn getfile-btn" onclick="openGetFileModal()">获取文件</button>
            <button class="function-btn" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #343a40;" onclick="openTokenModal()">设置Token</button>
            <button id="tokenManageBtn" class="function-btn" style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%); color: #343a40; display: none;" onclick="openTokenManageModal()">管理Token</button>
            <button class="function-btn" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #343a40;" onclick="window.location.href='index.php'">返回主页</button>
        </div>
    </div>

    <!-- 上传模态框 -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeUploadModal()">&times;</button>
            <div class="modal-header">
                <h2>文件上传</h2>
                <p>上传图片获取8位指代码</p>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">选择图片文件：</label>
                    <input type="file" id="file" name="file" <?php if ($image_limit) { ?> accept="image/*" <?php } ?> required>
                </div>
                <?php if ($token_verification) { ?>
                    <div class="form-group">
                        <label for="uploadToken">Token：</label>
                        <input type="text" id="uploadToken" name="token" placeholder="请输入Token" required>
                    </div>
                <?php } ?>
                <button type="submit" class="submit-btn upload-submit">上传文件</button>
            </form>
            <div id="uploadResult" class="result" style="display: none;">
                <h4>上传结果</h4>
                <p>指代码：<span id="uploadCode"></span></p>
            </div>
        </div>
    </div>

    <!-- 获取文件模态框 -->
    <div id="getFileModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeGetFileModal()">&times;</button>
            <div class="modal-header">
                <h2>获取文件</h2>
                <p>输入8位指代码获取文件</p>
            </div>
            <form id="getFileForm">
                <div class="form-group">
                    <label for="code">8位指代码：</label>
                    <input type="text" id="code" name="code" placeholder="请输入8位数字" maxlength="8" required>
                </div>
                <?php if ($token_verification) { ?>
                    <div class="form-group">
                        <label for="getFileToken">Token：</label>
                        <input type="text" id="getFileToken" name="token" placeholder="请输入Token" required>
                    </div>
                <?php } ?>
                <button type="submit" class="submit-btn getfile-submit">获取文件</button>
            </form>
            <div id="getFileResult" class="result" style="display: none;">
                <h4>获取结果</h4>
                <p>文件路径：<span id="filePath"></span></p>
                <a id="downloadLink" class="download-link" href="" target="_blank">下载文件</a>
            </div>
        </div>
    </div>
    </div>

    <!-- Token设置模态框 -->
    <div id="tokenModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeTokenModal()">&times;</button>
            <div class="modal-header">
                <h2>设置Token</h2>
                <p>输入Token并保存到浏览器</p>
            </div>
            <form id="tokenForm">
                <div class="form-group">
                    <label for="tokenInput">Token：</label>
                    <input type="text" id="tokenInput" name="token" placeholder="请输入Token" required>
                </div>
                <button type="submit" class="submit-btn upload-submit">保存Token</button>
            </form>
            <div id="tokenResult" class="result" style="display: none;">
                <h4>设置结果</h4>
                <p id="tokenMessage"></p>
            </div>
        </div>
    </div>

    <!-- Token管理模态框 -->
    <div id="tokenManageModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeTokenManageModal()">&times;</button>
            <div class="modal-header">
                <h2>管理Token</h2>
                <p>添加或删除Token</p>
            </div>
            <div class="form-group">
                <label for="manageAction">操作类型：</label>
                <select id="manageAction">
                    <option value="add">添加Token</option>
                    <option value="del">删除Token</option>
                    <option value="query">查询Token</option>
                    <option value="update">修改Token</option>
                    <option value="list">列出所有Token</option>
                </select>
            </div>
            <div class="form-group">
                <label for="manageTokenName">Token名称：</label>
                <input type="text" id="manageTokenName" placeholder="请输入Token名称" required>
            </div>
            <div id="tokenCountGroup" class="form-group">
                <label for="manageTokenCount">剩余次数：</label>
                <input type="number" id="manageTokenCount" placeholder="请输入剩余次数" min="1" required>
            </div>
            <button type="button" class="submit-btn upload-submit" onclick="manageToken()">执行操作</button>
            <div id="manageResult" class="result" style="display: none;">
                <h4>操作结果</h4>
                <p id="manageMessage"></p>
                <div id="tokenList" class="token-list" style="display: none;"></div>
            </div>
            <style>
                input[type="text"],
                input[type="number"],
                select {
                    width: 100%;
                    padding: 12px 15px;
                    margin: 8px 0;
                    display: inline-block;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    box-sizing: border-box;
                    font-size: 16px;
                    transition: all 0.3s ease;
                    background-color: #f9f9f9;
                }

                input[type="text"]:focus,
                input[type="number"]:focus,
                select:focus {
                    outline: none;
                    border-color: #4CAF50;
                    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
                    background-color: #fff;
                }

                /* 美化文件输入 */
                input[type="file"] {
                    width: 100%;
                    padding: 10px;
                    margin: 8px 0;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    box-sizing: border-box;
                    background-color: #f9f9f9;
                    transition: all 0.3s ease;
                }

                input[type="file"]:focus {
                    outline: none;
                    border-color: #4CAF50;
                    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
                }

                /* Token列表样式 */
                .token-list {
                    margin-top: 20px;
                    padding: 15px;
                    background-color: #f9f9f9;
                    border-radius: 8px;
                    border: 1px solid #ddd;
                    max-height: 300px;
                    overflow-y: auto;
                }

                .token-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 10px;
                    margin-bottom: 10px;
                    background-color: #fff;
                    border-radius: 6px;
                    border: 1px solid #eee;
                }

                .token-item:last-child {
                    margin-bottom: 0;
                }

                .token-name {
                    font-weight: bold;
                    color: #333;
                }

                .token-count {
                    color: #666;
                    background-color: #f0f0f0;
                    padding: 5px 10px;
                    border-radius: 20px;
                    font-size: 14px;
                }

                /* 模态框内容滚动 */
                .modal-content {
                    max-height: 80vh;
                    overflow-y: auto;
                }
            </style>
        </div>
    </div>

    <footer>
        <?php echo $footer_text; ?>
    </footer>

    <script>
        // Cookie操作函数
        function setCookie(name, value, days) {
            var expires = '';
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        }

        function getCookie(name) {
            var nameEQ = name + '=';
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // 模态框控制
        function openUploadModal() {
            document.getElementById('uploadModal').style.display = 'block';
            // 从cookie中获取token并填充
            var token = getCookie('upload_token');
            if (token) {
                var uploadToken = document.getElementById('uploadToken');
                if (uploadToken) {
                    uploadToken.value = token;
                }
            }
        }

        function openGetFileModal() {
            document.getElementById('getFileModal').style.display = 'block';
            // 从cookie中获取token并填充
            var token = getCookie('upload_token');
            if (token) {
                var getFileToken = document.getElementById('getFileToken');
                if (getFileToken) {
                    getFileToken.value = token;
                }
            }
        }

        function openTokenModal() {
            document.getElementById('tokenModal').style.display = 'block';
            // 从cookie中获取token并填充
            var token = getCookie('upload_token');
            if (token) {
                var tokenInput = document.getElementById('tokenInput');
                if (tokenInput) {
                    tokenInput.value = token;
                }
            }
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
            document.getElementById('uploadResult').style.display = 'none';
        }

        function closeGetFileModal() {
            document.getElementById('getFileModal').style.display = 'none';
            document.getElementById('getFileResult').style.display = 'none';
        }

        function closeTokenModal() {
            document.getElementById('tokenModal').style.display = 'none';
            document.getElementById('tokenResult').style.display = 'none';
        }

        function openTokenManageModal() {
            document.getElementById('tokenManageModal').style.display = 'block';
        }

        function closeTokenManageModal() {
            document.getElementById('tokenManageModal').style.display = 'none';
            document.getElementById('manageResult').style.display = 'none';
        }

        // 点击模态框外部关闭
        window.onclick = function(event) {
            var uploadModal = document.getElementById('uploadModal');
            var getFileModal = document.getElementById('getFileModal');
            var tokenModal = document.getElementById('tokenModal');
            var tokenManageModal = document.getElementById('tokenManageModal');
            if (event.target == uploadModal) {
                closeUploadModal();
            }
            if (event.target == getFileModal) {
                closeGetFileModal();
            }
            if (event.target == tokenModal) {
                closeTokenModal();
            }
            if (event.target == tokenManageModal) {
                closeTokenManageModal();
            }
        }

        // 操作类型切换
        var manageAction = document.getElementById('manageAction');
        if (manageAction) {
            manageAction.addEventListener('change', function() {
                var action = this.value;
                var tokenCountGroup = document.getElementById('tokenCountGroup');
                var manageTokenNameGroup = document.getElementById('manageTokenName').closest('.form-group');
                var manageTokenName = document.getElementById('manageTokenName');
                if (tokenCountGroup) {
                    if (action === 'add' || action === 'update') {
                        tokenCountGroup.style.display = 'block';
                        if (manageTokenNameGroup) {
                            manageTokenNameGroup.style.display = 'block';
                        }
                        if (manageTokenName) {
                            manageTokenName.required = true;
                        }
                    } else if (action === 'list') {
                        tokenCountGroup.style.display = 'none';
                        if (manageTokenNameGroup) {
                            manageTokenNameGroup.style.display = 'none';
                        }
                        if (manageTokenName) {
                            manageTokenName.required = false;
                        }
                    } else {
                        tokenCountGroup.style.display = 'none';
                        if (manageTokenNameGroup) {
                            manageTokenNameGroup.style.display = 'block';
                        }
                        if (manageTokenName) {
                            manageTokenName.required = true;
                        }
                    }
                }
            });
        }

        // Token管理操作
        function manageToken() {
            var manageAction = document.getElementById('manageAction');
            var manageTokenName = document.getElementById('manageTokenName');
            var manageTokenCount = document.getElementById('manageTokenCount');
            var resultDiv = document.getElementById('manageResult');
            var messageDiv = document.getElementById('manageMessage');
            var tokenListDiv = document.getElementById('tokenList');

            if (!manageAction || !resultDiv || !messageDiv) {
                return;
            }

            var action = manageAction.value;
            var tokenName = manageTokenName ? manageTokenName.value : '';
            var tokenCount = manageTokenCount ? manageTokenCount.value : '';

            if (action !== 'list' && !tokenName) {
                alert('请输入Token名称');
                return;
            }

            if ((action === 'add' || action === 'update') && !tokenCount) {
                alert('请输入剩余次数');
                return;
            }

            var formData = new FormData();
            formData.append('type', 'token_set');
            // 使用当前设置的token作为验证，不再硬编码密码
            var currentToken = getCookie('upload_token');
            formData.append('token', currentToken);
            formData.append('action', action);
            if (action !== 'list') {
                formData.append('tokenname', tokenName);
            }
            if (action === 'add' || action === 'update') {
                formData.append('token_count', tokenCount);
            }

            fetch('api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(data) {
                    // 尝试解析JSON响应
                    try {
                        var jsonData = JSON.parse(data);
                        if (jsonData.success) {
                            if (action === 'query') {
                                messageDiv.textContent = 'Token: ' + jsonData.token + ', 剩余次数: ' + jsonData.count;
                            } else if (action === 'list') {
                                messageDiv.textContent = '所有Token列表：';
                                if (tokenListDiv) {
                                    tokenListDiv.innerHTML = '';
                                    for (var token in jsonData.tokens) {
                                        var tokenItem = document.createElement('div');
                                        tokenItem.className = 'token-item';
                                        tokenItem.innerHTML = '<span class="token-name">' + token + '</span><span class="token-count">剩余次数: ' + jsonData.tokens[token] + '</span>';
                                        tokenListDiv.appendChild(tokenItem);
                                    }
                                    tokenListDiv.style.display = 'block';
                                }
                            } else {
                                messageDiv.textContent = data;
                            }
                        } else {
                            messageDiv.textContent = jsonData.message || data;
                        }
                    } catch (e) {
                        // 非JSON响应，按原方式处理
                        messageDiv.textContent = data;
                    }
                    resultDiv.style.display = 'block';
                    // 隐藏token列表（如果不是列表操作）
                    if (action !== 'list' && tokenListDiv) {
                        tokenListDiv.style.display = 'none';
                    }
                })
                .catch(function(error) {
                    alert('操作失败：' + error.message);
                });
        }

        // Token设置处理
        var tokenForm = document.getElementById('tokenForm');
        if (tokenForm) {
            tokenForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var tokenInput = document.getElementById('tokenInput');
                var tokenMessage = document.getElementById('tokenMessage');
                var tokenResult = document.getElementById('tokenResult');
                if (tokenInput && tokenMessage && tokenResult) {
                    var token = tokenInput.value;
                    setCookie('upload_token', token, 30); // 保存30天
                    tokenMessage.textContent = 'Token已保存，将在30天内有效';
                    tokenResult.style.display = 'block';
                }
            });
        }

        // 文件上传处理
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData();
            formData.append('file', document.getElementById('file').files[0]);
            formData.append('type', 'upload');

            // 只有当token输入字段存在时，才添加token
            var uploadToken = document.getElementById('uploadToken');
            if (uploadToken) {
                formData.append('token', uploadToken.value);
            }

            fetch('api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(data) {
                    try {
                        // 尝试解析JSON响应
                        var jsonData = JSON.parse(data);
                        if (jsonData.success) {
                            document.getElementById('uploadCode').textContent = jsonData.data;
                            var uploadResult = document.getElementById('uploadResult');
                            uploadResult.style.display = 'block';
                            // 添加剩余次数信息
                            if (jsonData.remaining !== -1) {
                                var remainingInfo = document.createElement('p');
                                remainingInfo.textContent = 'Token剩余次数：' + jsonData.remaining;
                                remainingInfo.style.marginTop = '10px';
                                remainingInfo.style.fontWeight = 'bold';
                                // 清除之前的剩余次数信息
                                var oldRemainingInfo = uploadResult.querySelector('.remaining-info');
                                if (oldRemainingInfo) {
                                    oldRemainingInfo.remove();
                                }
                                remainingInfo.className = 'remaining-info';
                                uploadResult.appendChild(remainingInfo);
                            }
                        } else {
                            alert('上传失败：' + jsonData.data);
                        }
                    } catch (e) {
                        // 非JSON响应，按原方式处理
                        if (data.length === 8 && /^\d+$/.test(data)) {
                            document.getElementById('uploadCode').textContent = data;
                            document.getElementById('uploadResult').style.display = 'block';
                        } else {
                            alert('上传失败：' + data);
                        }
                    }
                })
                .catch(function(error) {
                    alert('上传失败：' + error.message);
                });
        });

        // 文件获取处理
        document.getElementById('getFileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var code = document.getElementById('code').value;

            if (code.length !== 8 || !/^\d+$/.test(code)) {
                alert('请输入有效的8位数字代码');
                return;
            }

            var formData = new FormData();
            formData.append('code', code);
            formData.append('type', 'getfile');

            // 只有当token输入字段存在时，才添加token
            var getFileToken = document.getElementById('getFileToken');
            if (getFileToken) {
                formData.append('token', getFileToken.value);
            }

            fetch('api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(data) {
                    try {
                        // 尝试解析JSON响应
                        var jsonData = JSON.parse(data);
                        if (jsonData.success) {
                            document.getElementById('filePath').textContent = jsonData.data;
                            document.getElementById('downloadLink').href = jsonData.data;
                            var getFileResult = document.getElementById('getFileResult');
                            getFileResult.style.display = 'block';
                            // 添加剩余次数信息
                            if (jsonData.remaining !== -1) {
                                var remainingInfo = document.createElement('p');
                                remainingInfo.textContent = 'Token剩余次数：' + jsonData.remaining;
                                remainingInfo.style.marginTop = '10px';
                                remainingInfo.style.fontWeight = 'bold';
                                // 清除之前的剩余次数信息
                                var oldRemainingInfo = getFileResult.querySelector('.remaining-info');
                                if (oldRemainingInfo) {
                                    oldRemainingInfo.remove();
                                }
                                remainingInfo.className = 'remaining-info';
                                getFileResult.appendChild(remainingInfo);
                            }
                        } else {
                            alert('获取失败：' + jsonData.data);
                        }
                    } catch (e) {
                        // 非JSON响应，按原方式处理
                        if (data.startsWith('/upload/')) {
                            document.getElementById('filePath').textContent = data;
                            document.getElementById('downloadLink').href = data;
                            document.getElementById('getFileResult').style.display = 'block';
                        } else {
                            alert('获取失败：' + data);
                        }
                    }
                })
                .catch(function(error) {
                    alert('获取失败：' + error.message);
                });
        });

        // 检查token是否为管理员
        function checkAdminToken(token) {
            var formData = new FormData();
            formData.append('type', 'check_admin');
            formData.append('token', token);

            fetch('api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success && data.is_admin) {
                        var tokenManageBtn = document.getElementById('tokenManageBtn');
                        if (tokenManageBtn) {
                            tokenManageBtn.style.display = 'block';
                        }
                    } else {
                        var tokenManageBtn = document.getElementById('tokenManageBtn');
                        if (tokenManageBtn) {
                            tokenManageBtn.style.display = 'none';
                        }
                    }
                })
                .catch(function(error) {
                    console.error('检查管理员token失败：', error);
                    var tokenManageBtn = document.getElementById('tokenManageBtn');
                    if (tokenManageBtn) {
                        tokenManageBtn.style.display = 'none';
                    }
                });
        }

        // 页面加载时检查token验证设置
        window.onload = function() {
            // 从cookie中获取token
            var token = getCookie('upload_token');
            if (token) {
                // 如果有token，自动填充到所有token输入框
                var uploadToken = document.getElementById('uploadToken');
                var getFileToken = document.getElementById('getFileToken');
                var tokenInput = document.getElementById('tokenInput');
                if (uploadToken) uploadToken.value = token;
                if (getFileToken) getFileToken.value = token;
                if (tokenInput) tokenInput.value = token;

                // 检查token是否为管理员
                checkAdminToken(token);
            } else {
                // 没有token，隐藏管理按钮
                var tokenManageBtn = document.getElementById('tokenManageBtn');
                if (tokenManageBtn) {
                    tokenManageBtn.style.display = 'none';
                }
            }
        };

        // Token设置处理
        document.getElementById('tokenForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var token = document.getElementById('tokenInput').value;
            setCookie('upload_token', token, 30); // 保存30天
            document.getElementById('tokenMessage').textContent = 'Token已保存，将在30天内有效';
            document.getElementById('tokenResult').style.display = 'block';

            // 检查token是否为管理员
            checkAdminToken(token);
        });
    </script>
</body>

</html>