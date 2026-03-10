<?php
//基础配置
$web_title = "StudyPad CrackDoc"; //网站标题
$favicon = ""; //网站图标
$background_url = "https://api.xhuaxs.com/wallpaper"; //背景图片
$message_p = "欢迎访问" . $web_title . "喵~";  //网站描述
$yiyanapi = 'https://api.xhuaxs.com/yiyan.php'; //一言API接口

//跳转链接配置(修改这里可以添加或删除跳转链接)
$link_data = [
    ["id" => 1, "name" => '必应', "link" => 'https://www.bing.com/', "fee" => 13],
    ["id" => 2, "name" => '抖音', "link" => 'https://www.douyin.com/', "fee" => 8],
    ["id" => 3, "name" => 'Geogebra', "link" => 'https://www.geogebra.org/calculator', "fee" => 3],
    ["id" => 4, "name" => '豆包', "link" => 'https://www.doubao.com/', "fee" => 8],
    ["id" => 5, "name" => '哔哩哔哩', "link" => 'https://www.bilibili.com/', "fee" => 7],
    ["id" => 6, "name" => '图片快传', "link" => './upload.php', "fee" => 0],
    ["id" => 7, "name" => '网易云游戏', "link" => 'https:///cg.163.com/', "fee" => 10],
];

//生日预报配置
$birthday_enable = true; //是否开启生日预报功能
$birthday_csv = './id.csv'; //生日数据文件
$birthday_description = '离当前日期最近的生日'; //描述

//RSS Feed配置
$rss_enable = true; //是否开启RSS Feed功能
$rss_title = 'News'; //RSS Feed标题
$rss_feed = 'https://www.affy.top/rss.xml'; //RSS Feed地址
//来源站点信息可以选择自动获取，也可以手动设置，如果选自动获取就把下面这两个改成auto
// 比如：
// $rss_author = 'auto'; //RSS来源站点标题
// $rss_author_web = 'auto'; //RSS来源站点地址

$rss_author = 'Affy的栖息地'; //RSS来源站点标题
$rss_author_web = 'https://www.affy.top'; //RSS来源站点地址

// 自动获取网站标题和域名(38~101行不要动)
if ($rss_author == 'auto' || $rss_author_web == 'auto') {
    // 从 RSS Feed 地址提取主域名
    if (!empty($rss_feed)) {
        $parsed_url = parse_url($rss_feed);
        $rss_author_web = $parsed_url['scheme'] . '://' . $parsed_url['host'];
        if (isset($parsed_url['port'])) {
            $rss_author_web .= ':' . $parsed_url['port'];
        }
    }

    // 获取网站标题
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
        'http' => [
            'timeout' => 5,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ],
    ]);

    $html = @file_get_contents($rss_author_web . '/', false, $context);
    if ($html) {
        // 尝试提取网站标题
        if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $matches)) {
            $rss_author = trim($matches[1]);
        }
    }

    // 如果获取失败，使用默认值
    if (empty($rss_author) || $rss_author == 'auto') {
        $rss_author = 'RSS Feed';
    }
} else {
    // 即使不是auto，也从$rss_author_web中提取主域名
    $parsed_url = parse_url($rss_author_web);
    $domain = $parsed_url['scheme'] . '://' . $parsed_url['host'];
    if (isset($parsed_url['port'])) {
        $domain .= ':' . $parsed_url['port'];
    }
    $rss_author_web = $domain;

    // 自动获取网站标题
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
        'http' => [
            'timeout' => 5,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ],
    ]);

    $html = @file_get_contents($rss_author_web . '/', false, $context);
    if ($html) {
        // 尝试提取网站标题
        if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $matches)) {
            $rss_author = trim($matches[1]);
        }
    }
}

$rss_description = '来自 <a href="' . $rss_author_web . '" target="_blank" style="color: #504e4e; text-decoration: none; transition: color 300ms ease;">' . $rss_author . '</a>'; //RSS Feed描述


//页脚配置
$footer_enable = true; //是否开启页脚
$footer_text = '<p>Made by <a href="https://www.affy.top" target="_blank">ChiceAffy</a> & YCYz Minecraft Community</p>'; //页脚文本

//文件快传配置
//token验证机制（是否关闭老好人模式）
//如果您的服务器带宽足够大，也不用担心流量问题，可以关闭该选项
$token_verification = true; //是否开启Token验证机制
$image_limit = true; //是否开启图片上传限制(君子协议属于是)
$token_admin = 'Admin@123'; //管理员Token

//引流设置
//必须存在token才能访问index.php
$index_require_token = true;
$token_info_display = true; //是否在网站末尾显示Token信息