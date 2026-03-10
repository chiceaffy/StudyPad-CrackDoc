<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $web_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo $favicon; ?>" rel="icon">
    <style>
        @font-face {
            font-family: HarmonyOS;
            font-style: normal;
            font-display: swap;
            src: url(fonts/HarmonyOS_Sans_SC_Medium.subset.woff2) format('woff2')
        }

        body,
        html {
            margin: 0;
        }

        body,
        div,
        html,
        a,
        span,
        b,
        button,
        input,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: HarmonyOS;
        }

        .credit a {
            display: inline-block;
            color: #000;
            -webkit-transition: color 300ms ease;
            -o-transition: color 300ms ease;
            transition: color 300ms ease;
            padding: 15px 10px;
        }

        .credit a:hover {
            color: blue;
        }

        .card-list {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: auto;
            min-height: 100vh;
            padding: 20px 0;
        }

        .card {
            margin-bottom: 20px !important;
        }

        .rss-item {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            background: rgba(255, 255, 255, 0.75);
            border-radius: 25px;
            width: 500px;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            position: relative;
            padding: 100px 0 0 0;
            -webkit-box-shadow: 0 0 60px -15px rgba(0, 0, 0, 0.25);
            box-shadow: 0 0 60px -15px rgba(0, 0, 0, 0.25);
            -webkit-transition: -webkit-transform 300ms ease;
            transition: -webkit-transform 300ms ease;
            -o-transition: transform 300ms ease;
            transition: transform 300ms ease;
            transition: transform 300ms ease, -webkit-transform 300ms ease;
        }

        .colorband {
            height: 2px;
            background-image: -o-linear-gradient(left, #e19fbd 0%, #38f9d7 100%);
            background-image: -webkit-gradient(linear, left top, right top, from(#e19fbd), to(#38f9d7));
            background-image: linear-gradient(to right, #e19fbd 0%, #38f9d7 100%);
        }

        .card h2 {
            margin: 0;
            padding: 1rem;
            text-align: center;
            font-weight: bolder;
            color: #2f2d2d;
        }

        .card .title {
            padding: 0 1rem 1rem;
            font-size: 0.75em;
            text-align: center;
            color: #504e4e;
        }

        .card .desc {
            padding: 1rem 2rem;
            font-size: 0.9em;
            margin: 10px 0 10px 0;
            text-align: center;
        }

        .card .actions {
            -webkit-transition: -webkit-box-shadow 300ms ease;
            transition: -webkit-box-shadow 300ms ease;
            -o-transition: box-shadow 300ms ease;
            transition: box-shadow 300ms ease;
            transition: box-shadow 300ms ease, -webkit-box-shadow 300ms ease;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            background-color: rgba(250, 250, 250, 0.71);
            border-bottom-left-radius: 25px;
            border-bottom-right-radius: 25px;
            position: relative;
        }

        .card:hover {
            -webkit-transform: scale(1.03);
            -ms-transform: scale(1.03);
            transform: scale(1.03);
        }


        .actions button {
            width: 25%;
            float: left;
            border: none;
            padding: 1rem;
            font-size: 1em;
            -webkit-transition: background 300ms ease, -webkit-transform 300ms ease;
            transition: background 300ms ease, -webkit-transform 300ms ease;
            -o-transition: transform 300ms ease, background 300ms ease;
            transition: transform 300ms ease, background 300ms ease;
            transition: transform 300ms ease, background 300ms ease, -webkit-transform 300ms ease;
            outline: none;
            font-family: Lato, sans-serif;
            cursor: pointer;
            background: transparent;
        }

        .faguang {
            background-image: -webkit-gradient(linear, left 0, right 0, from(rgb(217, 82, 102)), to(rgb(20, 99, 225)));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }


        .actions button:hover {
            -webkit-transform: scale(1.1);
            -ms-transform: scale(1.1);
            transform: scale(1.1);
            background-image: -o-linear-gradient(left, rgba(67, 233, 123, 0.53) 0%, rgba(56, 249, 215, 0.49) 100%);
            background-image: -webkit-gradient(linear, left top, right top, from(rgba(67, 233, 123, 0.59)), to(rgba(56, 249, 215, 0.54)));
            background-image: linear-gradient(to right, rgba(67, 233, 123, 0.51) 0%, rgba(56, 249, 215, 0.55) 100%);
            color: rgba(255, 255, 255, 0.75);
            border: none;
            z-index: 100;
            border-radius: 25px;
            -webkit-box-shadow: 0 5px 15px 0px rgba(0, 0, 0, 0.1);
            box-shadow: 0 5px 15px 0px rgba(0, 0, 0, 0.1);
        }

        .actions button:active {
            -webkit-transform: scale(0.9);
            -ms-transform: scale(0.9);
            transform: scale(0.9);
        }

        #card-1 .actions button:hover {
            background-image: -o-linear-gradient(left, #43e97b 0%, #38f9d7 100%);
            background-image: -webkit-gradient(linear, left top, right top, from(#43e97b), to(#38f9d7));
            background-image: linear-gradient(to right, #43e97b 0%, #38f9d7 100%);
        }

        #card-2 .actions button:hover {
            background-image: -o-linear-gradient(left, #4facfe 0%, #00f2fe 100%);
            background-image: -webkit-gradient(linear, left top, right top, from(#4facfe), to(#00f2fe));
            background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
        }

        #card-3 .actions button:hover {
            background-image: -o-linear-gradient(315deg, #667eea 0%, #764ba2 100%);
            background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .actions button>span {
            display: block;
        }

        .avatar {
            position: absolute;
            top: -60px;
            left: 50%;
            width: 135px;
            height: 135px;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.58);
            -webkit-box-shadow: 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            box-shadow: 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            -webkit-transition: -webkit-transform 200ms ease;
            transition: -webkit-transform 200ms ease;
            -o-transition: transform 200ms ease;
            transition: transform 200ms ease;
            transition: transform 200ms ease, -webkit-transform 200ms ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar:hover {
            -webkit-transform: translateX(-50%) scale(1.1);
            -ms-transform: translateX(-50%) scale(1.1);
            transform: translateX(-50%) scale(1.1);
        }

        .avatar .face {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            opacity: .8;
        }

        .header {
            margin-bottom: 20px;
        }

        @media screen and (max-width: 767px) {
            .card-list {
                margin-left: 10px;
                margin-right: 10px;
            }
        }
    </style>
    <style>
        /* 模态框样式 */
        .modal {
            display: none;
            /* 默认隐藏 */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 25px;
            box-shadow: 0 0 60px -15px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .modal-header h3 {
            margin: 0;
            color: #2f2d2d;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-body input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .modal-footer {
            text-align: center;
        }

        .modal-footer button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
        }

        .modal-footer .btn-confirm {
            background: linear-gradient(to right, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .modal-footer .btn-cancel {
            background: #f0f0f0;
            color: #333;
        }
    </style>
    <script>
        function customRedirect() {
            document.getElementById('urlModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('urlModal').style.display = 'none';
        }

        function submitUrl() {
            var url = document.getElementById('urlInput').value;
            if (url) {
                // 确保URL以http://或https://开头
                if (!url.startsWith('http://') && !url.startsWith('https://')) {
                    url = 'https://' + url;
                }
                window.open(url, '_blank');
                closeModal();
                // 清空输入框
                document.getElementById('urlInput').value = 'https://';
            }
        }

        // 点击模态框外部关闭
        window.onclick = function(event) {
            var modal = document.getElementById('urlModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</head>

<body style="background: url(<?php echo $background_url; ?>) fixed no-repeat;background-size: cover;">
    <div class="card-list">
        <div class="card">
            <div class="header">
                <h2><?php echo $web_title; ?></h2>
                <div class="title"><?php $content = file_get_contents($yiyanapi);
                                    echo $content; ?></div>
            </div>
            <div class="colorband"></div>
            <div class="desc">
                <p><?php echo $message_p; ?></p>
            </div>
            <div class="actions">
                <?php foreach ($link_data as $link) {
                    echo '<a href="' . $link['link'] . '" target="_blank">
                    <button><span><i class="far fa-heart"></i><span class="faguang"> ' . $link['name'] . '</span></span>
                    </button>
                </a>';
                } ?>

                <button onclick="customRedirect()"><span><i class="far fa-heart"></i><span class="faguang"> 自定义跳转</span></span>
                </button>
            </div>
        </div>
        <!-- Birthday Card -->
        <?php if ($birthday_enable) { ?>
            <div class="card">
                <div class="header">
                    <!--名称-->
                    <h2>生日预报</h2>
                    <!--Birthday Info-->
                    <div class="title"><?php echo $birthday_description; ?></div>
                </div>
                <div class="colorband"></div>
                <!--Birthday Content-->
                <div class="desc">
                    <?php
                    // 读取CSV文件
                    $csv_file = $birthday_csv;
                    if (file_exists($csv_file)) {
                        $csv_data = array_map('str_getcsv', file($csv_file));

                        $current_date = date('md');
                        $current_year = date('Y');
                        $min_days = PHP_INT_MAX;
                        $closest_people = array();
                        $closest_ages = array();

                        // 第一次遍历：找出最小距离
                        foreach ($csv_data as $row) {
                            if (count($row) >= 2) {
                                $name = $row[0];
                                $birth_date_str = $row[1]; // 直接使用CSV中的生日字段

                                // 从8位生日字符串中提取月日
                                $birth_month = substr($birth_date_str, 4, 2);
                                $birth_day = substr($birth_date_str, 6, 2);
                                $birth_md = $birth_month . $birth_day;

                                // 计算距离
                                $days_diff = (int)$birth_md - (int)$current_date;
                                if ($days_diff < 0) {
                                    // 如果生日已经过了，计算到明年的距离
                                    $days_diff += 366; // 考虑闰年
                                }

                                // 找出最小距离
                                if ($days_diff < $min_days) {
                                    $min_days = $days_diff;
                                }
                            }
                        }

                        // 第二次遍历：收集所有在最小距离生日的人
                        foreach ($csv_data as $row) {
                            if (count($row) >= 2) {
                                $name = $row[0];
                                $birth_date_str = $row[1]; // 直接使用CSV中的生日字段

                                // 从8位生日字符串中提取月日
                                $birth_month = substr($birth_date_str, 4, 2);
                                $birth_day = substr($birth_date_str, 6, 2);
                                $birth_md = $birth_month . $birth_day;

                                // 计算距离
                                $days_diff = (int)$birth_md - (int)$current_date;
                                if ($days_diff < 0) {
                                    // 如果生日已经过了，计算到明年的距离
                                    $days_diff += 366; // 考虑闰年
                                }

                                // 收集最小距离的人
                                if ($days_diff == $min_days) {
                                    $closest_people[] = $name;
                                    // 计算年龄
                                    $birth_year = substr($birth_date_str, 0, 4);
                                    $age = $current_year - $birth_year;
                                    $closest_ages[] = $age;
                                }
                            }
                        }

                        if (!empty($closest_people)) {
                            echo '<div class="rss-item">';
                            echo '<p style="text-align: center;">';
                            if ($min_days == 0) {
                                $birthday_messages = array();
                                for ($i = 0; $i < count($closest_people); $i++) {
                                    $birthday_messages[] = $closest_people[$i] . ' ' . $closest_ages[$i] . '岁生日快乐！';
                                }
                                echo '<strong style="font-size: 1.1em; color: #ff6b6b;">' . implode('、', $birthday_messages) . '</strong>';
                            } else {
                                $birthday_messages = array();
                                $age_messages = array();
                                for ($i = 0; $i < count($closest_people); $i++) {
                                    $birthday_messages[] = $closest_people[$i];
                                    $age_messages[] = $closest_ages[$i] . '岁';
                                }
                                echo '<strong style="font-size: 1.1em;">' . implode('、', $birthday_messages) . '</strong><br>';
                                echo '<span style="font-size: 0.9em;">距离' . implode('、', $age_messages) . '生日还有 ' . $min_days . ' 天</span>';
                            }
                            echo '</p>';
                            echo '</div>';
                        } else {
                            echo '<div class="rss-item">';
                            echo '<p>无法计算最近生日</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="rss-item">';
                        echo '<p>id.csv文件不存在</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
        <!-- RSS Feed Card -->
        <?php if ($rss_enable) { ?>
            <div class="card">
                <div class="header">
                    <!--名称-->
                    <h2><?php echo $rss_title; ?></h2>
                    <!--RSS Feed-->
                    <div class="title"><?php echo $rss_description; ?></div>
                </div>
                <div class="colorband"></div>
                <!--RSS内容-->
                <div class="desc">
                    <?php
                    // 获取RSS内容
                    $rss_url = $rss_feed;
                    $rss_content = file_get_contents($rss_url);

                    // 解析RSS
                    $rss = simplexml_load_string($rss_content);

                    // 显示RSS内容
                    if ($rss) {
                        foreach ($rss->channel->item as $item) {
                            echo '<a href="' . $item->link . '" target="_blank" style="text-decoration: none; display: block;">';
                            echo '<div class="rss-item">';
                            echo '<strong style="color: #2f2d2d;">' . $item->title . '</strong><br>';
                            echo '<span style="font-size: 0.8em; color: #504e4e;">' . $item->pubDate . '</span><br>';
                            echo '<span style="font-size: 0.9em;">' . $item->description . '</span>';
                            echo '</div>';
                            echo '</a>';
                        }
                    } else {
                        echo '<div class="rss-item">';
                        echo '<p>无法加载RSS内容</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- 自定义跳转模态框 -->
    <div id="urlModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>自定义跳转</h3>
            </div>
            <div class="modal-body">
                <input type="text" id="urlInput" placeholder="请输入要跳转的网址" value="https://">
            </div>
            <div class="modal-footer">
                <button class="btn-confirm" onclick="submitUrl()">确定</button>
                <button class="btn-cancel" onclick="closeModal()">取消</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <style>
        footer a {
            color: #2f2f2d;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: rgb(249, 185, 63, 1);
        }
    </style>
    <?php if ($footer_enable) { ?>
        <footer style="background: rgba(255, 255, 255, 0.7); border-radius: 25px; padding: 20px; margin: 20px auto; width: 90%; max-width: 500px; text-align: center; box-shadow: 0 0 60px -15px rgba(0, 0, 0, 0.25);">
            <?php echo $footer_text; ?>
            <p style="margin: 10px 0;">
                <a href="https://github.com/chiceaffy/StudyPad-CrackDoc" target="_blank" style="color: #504e4e; text-decoration: none; transition: color 300ms ease;">本项目在GitHub开源(点击跳转)</a>
            </p>
        </footer>
    <?php } ?>
</body>

</html>