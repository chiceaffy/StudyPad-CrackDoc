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
├── redirect.php                        # 跳转中间页面
├── upload.php                          # 文件上传与获取页面
├── README.md                           # 项目说明文件
└── Structure.md                        # 项目结构说明文件（本文件）
```

## upload/ 目录（运行时自动创建）

```
upload/
├── codes.txt                           # 已生成的8位数字指代码文件
├── mappings.txt                        # 指代码与文件名的对应关系文件
└── tokens.txt                          # Token及其剩余操作次数文件
```

## 文件功能说明

### config.php

- **功能**：全局配置文件，包含网站所有配置项
- **主要配置**：
  - 基础配置：网站标题、图标、背景图片、描述、一言API接口
  - 跳转链接配置：首页快捷跳转链接（包含fee额度配置）
  - 生日预报配置：生日数据文件和描述
  - RSS Feed配置：RSS功能开关、参数、来源站点信息（支持自动获取网站标题）
  - 页脚配置：页脚显示开关和文本
  - 文件快传配置：Token验证、图片上传限制、管理员Token
  - 引流设置：index.php访问需要Token、Token信息显示开关
- **新增功能**：
  - 自动获取RSS来源网站标题：从网站URL自动提取域名并获取网站标题
  - $index_require_token：控制访问index.php是否需要Token
  - $token_info_display：控制是否在网站末尾显示Token信息
  - 链接fee配置：每个链接可配置扣除的额度

### index.php

- **功能**：网站主页，提供导航和快捷跳转功能
- **主要功能**：
  - Token验证：访问时验证Cookie中的Token并扣除次数
  - Token自动清理：Token剩余次数≤0时自动删除
  - Token信息显示：显示当前Token和剩余次数
  - 显示网站标题和描述
  - 提供快捷跳转链接（通过redirect.php扣除额度后跳转）
  - 生日预报功能
  - RSS Feed显示（支持SSL错误处理）
  - 自定义跳转模态框（通过redirect.php扣除10点额度）
  - 页脚显示

### redirect.php

- **功能**：跳转中间页面，处理额度扣除和显示过场动画
- **主要功能**：
  - 链接跳转：处理首页快捷链接的跳转，扣除对应fee额度
  - 自定义跳转：处理自定义URL跳转，固定扣除10点额度
  - Token验证：验证Token有效性并扣除额度
  - 额度不足提示：显示错误页面引导用户获取Token
  - 过场动画：显示精美的跳转动画，包含：
    - 火箭旋转加载动画
    - 本次消耗额度显示
    - 剩余额度显示
    - 进度条可视化
    - 5秒自动跳转
  - 清新淡雅配色：薄荷绿到淡粉色的渐变背景
- **请求参数**：
  - `link_id`：链接ID，用于预设链接跳转
  - `custom_url`：自定义URL，用于自定义跳转

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
  - 显示错误提示信息（无Token/无效Token/额度不足）

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
- **格式**：姓名,生日(YYYYMMDD)
- **用途**：生日预报功能的数据源

#### upload/codes.txt

- **功能**：已生成的8位数字指代码记录
- **用途**：确保指代码唯一性

#### upload/mappings.txt

- **功能**：指代码与文件名的对应关系
- **格式**：指代码|文件名
- **用途**：通过指代码查找文件

#### upload/tokens.txt

- **功能**：Token及其剩余操作次数
- **格式**：Token|剩余次数
- **用途**：Token验证和额度管理

## 数据流程

### Token验证流程

1. **访问index.php**
   - 检查Cookie中是否有upload_token
   - 如果$index_require_token为true且没有token，跳转到upload.php?error=no_token
   - 验证token有效性，扣除1次访问次数
   - 如果token无效，跳转到upload.php?error=invalid_token
   - 显示Token信息和剩余次数

2. **访问快捷跳转链接**
   - 点击链接跳转到redirect.php?link_id=xxx
   - 验证token有效性
   - 扣除对应链接的fee额度
   - 显示过场动画，5秒后自动跳转到目标链接

3. **自定义跳转**
   - 在index.php点击自定义跳转按钮
   - 输入URL后提交，跳转到redirect.php?custom_url=xxx
   - 验证token有效性
   - 扣除10点额度
   - 显示过场动画，5秒后自动跳转到目标链接

4. **Token耗尽处理**
   - Token剩余次数≤0时自动删除
   - 用户需要重新获取Token

### 文件上传流程

1. **设置Token**
   - 在upload.php输入Token
   - 保存到Cookie（7天有效期）
   - 2秒后自动跳转到主页

2. **上传文件**
   - 选择文件并上传
   - 生成唯一8位数字指代码
   - 保存文件到upload/目录
   - 记录指代码与文件名的映射关系
   - 返回指代码给用户

3. **获取文件**
   - 输入指代码
   - 查询映射关系获取文件路径
   - 返回文件

### Token管理流程（管理员）

1. **添加Token**
   - 生成随机Token
   - 设置初始次数
   - 保存到tokens.txt

2. **删除Token**
   - 从tokens.txt中移除指定Token

3. **查询Token**
   - 查询指定Token的剩余次数

4. **修改Token**
   - 修改指定Token的剩余次数

5. **列出所有Token**
   - 显示所有Token及其剩余次数
