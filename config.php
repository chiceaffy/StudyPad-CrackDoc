<?php
//基础配置
$web_title = "StudyPad CrackDoc"; //网站标题
$favicon = ""; //网站图标
$background_url = "https://api.xhuaxs.com/wallpaper"; //背景图片
$message_p = "欢迎访问" . $web_title . "喵~";  //网站描述
$yiyanapi = 'https://api.xhuaxs.com/yiyan.php'; //一言API接口

//跳转链接配置
$link_data = [
    ["id" => 1, "name" => '必应', "link" => 'https://www.bing.com/'],
    ["id" => 2, "name" => '抖音', "link" => 'https://www.douyin.com/'],
    ["id" => 3, "name" => 'Geogebra', "link" => 'https://www.geogebra.org/calculator'],
    ["id" => 4, "name" => '豆包', "link" => 'https://www.doubao.com/'],
    ["id" => 5, "name" => '哔哩哔哩', "link" => 'https://www.bilibili.com/'],
    ["id" => 6, "name" => '图片快传', "link" => './upload.php'],
    ["id" => 7, "name" => '网易云游戏', "link" => 'https:///cg.163.com/'],
];

//生日预报配置
$birthday_enable = true; //是否开启生日预报功能
$birthday_csv = './id.csv'; //生日数据文件
$birthday_description = '离当前日期最近的生日'; //描述

//RSS Feed配置
$rss_enable = true; //是否开启RSS Feed功能
$rss_title = 'News'; //RSS Feed标题
$rss_description = '来自 <a href="https://www.affy.top" target="_blank" style="color: #504e4e; text-decoration: none; transition: color 300ms ease;">Affy的栖息地</a>'; //RSS Feed描述
$rss_feed = 'https://www.affy.top/feed/'; //RSS Feed地址

//页脚配置
$footer_enable = true; //是否开启页脚
$footer_text = '<p>Made by <a href="https://www.affy.top" target="_blank">ChiceAffy</a> & YCYz Minecraft Community</p>'; //页脚文本

//文件快传配置
//token验证机制（是否关闭老好人模式）
//如果您的服务器带宽足够大，也不用担心流量问题，可以关闭该选项
$token_verification = true; //是否开启Token验证机制
$image_limit = true; //是否开启图片上传限制(君子协议属于是)
$token_admin = 'Admin@123'; //管理员Token