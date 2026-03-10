# 项目结构

## 项目根目录

```
StudyPad-CrackDoc/
├── VBS/                                # VBS脚本文件夹
│   └── install.vbs                     # 安装脚本
├── fonts/                              # 字体文件夹
│   └── HarmonyOS_Sans_SC_Medium.subset.woff2  # HarmonyOS字体文件
├── api.php                             # API接口文件
├── config.php                          # 配置文件
├── hosts                               # hosts文件
├── id.csv                              # 生日数据文件
├── index.php                           # 主页文件
├── upload.php                          # 文件上传与获取页面
├── README.md                           # 项目说明文件
└── Structure.md                       # 项目结构说明文件（本文件）
```

## upload/ 目录（运行时自动创建）

```
upload/
├── codes.txt                        # 已生成的8位数字指代码文件
├── mappings.txt                     # 指代码与文件名的对应关系文件
└── tokens.txt                       # Token及其剩余操作次数文件
```

## 文件功能说明

### config.php

- **功能**：全局配置文件，包含网站所有配置项
- **主要配置**：
  - 基础配置：网站标题、图标、背景图片、描述、一言API接口
  - 跳转链接配置：首页快捷跳转链接
  - 生日预报配置：生日数据文件和描述
  - RSS Feed配置：RSS功能开关、参数、来源站点信息（支持自动获取网站标题）
  - 页脚配置：页脚显示开关和文本
  - 文件快传配置：Token验证、图片上传限制、管理员Token
  - 引流设置：index.php访问需要Token、Token信息显示开关
- **新增功能**：
  - 自动获取RSS来源网站标题：从网站URL自动提取域名并获取网站标题
  - $index_require_token：控制访问index.php是否需要Token
  - $token_info_display：控制是否在网站末尾显示Token信息

### index.php

- **功能**：网站主页，提供导航和快捷跳转功能
- **主要功能**：
  - Token验证：访问时验证Cookie中的Token并扣除次数
  - Token自动清理：Token剩余次数≤0时自动删除
  - Token信息显示：显示当前Token和剩余次数
  - 显示网站标题和描述
  - 提供快捷跳转链接
  - 生日预报功能
  - RSS Feed显示（支持SSL错误处理）
  - 自定义跳转模态框
  - 页脚显示

### upload.php

- **功能**：文件上传与获取页面，提供用户界面
- **主要功能**：
  - 文件上传：上传图片并获取指代码
  - 文件获取：通过指代码获取文件
  - Token设置：设置和保存Token到Cookie，设置后自动跳转主页
  - Token管理：管理Token（添加、删除、查询、修改、列出）
  - 错误提示：根据URL参数显示不同的错误提示信息
  - 模态框设计：提供良好的用户体验
  - 响应式布局：适配不同屏幕尺寸
- **新增功能**：
  - Token设置成功后2秒自动跳转到主页
  - 显示错误提示信息（无Token/无效Token）

### api.php

- **功能**：提供API接口，处理文件上传、文件获取和Token管理
- **主要功能**：
  - 文件上传：接收文件，生成唯一指代码，保存文件
  - 文件获取：根据指代码返回文件路径
  - Token管理：添加、删除、查询、修改、列出Token
  - 管理员验证：检查Token是否为管理员Token
  - Token自动清理：Token剩余次数≤0时自动删除
- **关键函数**：
  - `readTokens()`: 读取Token文件
  - `writeTokens()`: 写入Token文件
  - `validateToken()`: 验证Token并返回剩余次数，自动清理用尽的Token
  - `generateUniqueCode()`: 生成唯一8位数字指代码
  - `getFilePathFromCode()`: 根据指代码获取文件路径
  - `processRequest()`: 处理上传和获取文件请求

### 数据文件

#### hosts

- **功能**：hosts配置文件
- **用途**：用于域名解析配置

#### id.csv

- **功能**：生日数据文件
- **用途**：存储生日信息，用于生日预报功能
- **格式**：姓名|生日（8位数字，格式：YYYYMMDD）

### 字体文件

#### fonts/HarmonyOS_Sans_SC_Medium.subset.woff2

- **功能**：HarmonyOS字体文件
- **用途**：提供网站字体样式

### 运行时文件

#### upload/codes.txt

- **功能**：存储已生成的8位数字指代码
- **用途**：确保指代码唯一性

#### upload/mappings.txt

- **功能**：存储指代码与文件名的对应关系
- **格式**：指代码|文件名（每行一个）

#### upload/tokens.txt

- **功能**：存储Token及其剩余操作次数
- **格式**：Token|剩余次数（每行一个）

## 数据流程

### Token验证流程（index.php）

1. 用户访问index.php
2. 检查$index_require_token配置
3. 如果开启验证：
   - 检查Cookie中是否有upload_token
   - 如果没有Token，跳转到upload.php?error=no_token
   - 如果有Token，调用validateToken()验证
   - 验证失败，跳转到upload.php?error=invalid_token
   - 验证成功，扣除一次剩余次数
4. 显示页面内容（包含Token信息Card）

### Token自动清理流程

1. validateToken()被调用
2. 检查Token是否存在且剩余次数>0
3. 剩余次数-1
4. 如果剩余次数≤0，从tokens.txt中删除该Token
5. 返回验证结果

### 文件上传流程

1. 用户在upload.php页面选择文件并上传
2. upload.php通过fetch API调用api.php
3. api.php验证Token（如果启用）
4. api.php生成唯一指代码
5. api.php保存文件到upload/目录
6. api.php保存指代码与文件名的对应关系到mappings.txt
7. api.php返回指代码给前端
8. upload.php显示指代码给用户

### 文件获取流程

1. 用户在upload.php页面输入指代码
2. upload.php通过fetch API调用api.php
3. api.php验证Token（如果启用）
4. api.php从mappings.txt中查找文件名
5. api.php返回文件路径给前端
6. upload.php显示下载链接给用户

### Token管理流程

1. 管理员在upload.php页面设置管理员Token
2. upload.php通过fetch API调用api.php的check_admin端点
3. api.php验证Token是否为管理员Token
4. api.php返回验证结果给前端
5. 如果是管理员Token，显示管理按钮
6. 管理员可以通过管理按钮添加、删除、查询、修改、列出Token

### RSS Feed自动获取流程

1. 系统加载config.php
2. 检查$rss_author和$rss_author_web配置
3. 如果不是'auto'：
   - 从$rss_author_web中提取主域名
   - 访问网站获取HTML
   - 从HTML中提取<title>标签内容
   - 将提取的标题赋值给$rss_author
4. 生成$rss_description

## 安全机制

1. **Token验证**：通过config.php中的$token_verification开关控制
2. **管理员验证**：通过config.php中的$token_admin设置管理员Token
3. **操作次数限制**：每个Token有剩余操作次数，每次操作减1
4. **Token自动清理**：Token剩余次数≤0时自动删除
5. **文件类型限制**：只允许上传图片文件（可通过$image_limit开关控制）
6. **唯一指代码**：确保指代码不重复，避免冲突
7. **index.php访问控制**：通过$index_require_token开关控制是否需要Token

## 技术栈

- **后端**：PHP
- **前端**：HTML、CSS、JavaScript
- **数据存储**：文本文件（.txt, .csv）
- **字体**：HarmonyOS Sans SC
- **API**：RESTful API（POST请求）
- **Cookie**：用于存储用户Token
- **网络请求**：file_get_contents（支持SSL错误处理）

## 配置项说明

### 基础配置

| 配置项          | 说明        |
| --------------- | ----------- |
| $web_title      | 网站标题    |
| $favicon        | 网站图标URL |
| $background_url | 背景图片URL |
| $message_p      | 网站描述    |
| $yiyanapi       | 一言API接口 |

### 跳转链接配置

| 配置项     | 说明             |
| ---------- | ---------------- |
| $link_data | 快捷跳转链接数组 |

### 生日预报配置

| 配置项                | 说明                 |
| --------------------- | -------------------- |
| $birthday_enable      | 是否开启生日预报功能 |
| $birthday_csv         | 生日数据文件路径     |
| $birthday_description | 描述                 |

### RSS Feed配置

| 配置项          | 说明                                  |
| --------------- | ------------------------------------- |
| $rss_enable     | 是否开启RSS Feed功能                  |
| $rss_title      | RSS Feed标题                          |
| $rss_feed       | RSS Feed地址                          |
| $rss_author     | RSS来源站点标题（设为'auto'自动获取） |
| $rss_author_web | RSS来源站点地址（设为'auto'自动获取） |

### 页脚配置

| 配置项         | 说明         |
| -------------- | ------------ |
| $footer_enable | 是否开启页脚 |
| $footer_text   | 页脚文本     |

### 文件快传配置

| 配置项              | 说明                  |
| ------------------- | --------------------- |
| $token_verification | 是否开启Token验证机制 |
| $image_limit        | 是否开启图片上传限制  |
| $token_admin        | 管理员Token           |

### 引流设置

| 配置项               | 说明                       |
| -------------------- | -------------------------- |
| $index_require_token | 访问index.php是否需要Token |
| $token_info_display  | 是否显示Token信息          |
