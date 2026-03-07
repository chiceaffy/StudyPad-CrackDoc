# 项目结构


### 项目根目录

```
website/
├── fonts/                           # 字体文件夹
│   └── HarmonyOS_Sans_SC_Medium.subset.woff2  # HarmonyOS字体文件
├── api.php                          # API接口文件
├── config.php                        # 配置文件
├── hosts                            # hosts文件
├── id.csv                           # 生日数据文件
├── index.php                        # 主页文件
├── jiegou.md                       # 项目结构说明文件（本文件）
└── upload.php                        # 文件上传与获取页面
```

### upload/ 目录（运行时自动创建）

```
upload/
├── codes.txt                        # 已生成的8位数字指代码文件
├── mappings.txt                     # 指代码与文件名的对应关系文件
└── tokens.txt                       # Token及其剩余操作次数文件
```

### 文件功能说明

#### api.php

- **功能**：提供API接口，处理文件上传、文件获取和Token管理
- **主要功能**：
  - 文件上传：接收文件，生成唯一指代码，保存文件
  - 文件获取：根据指代码返回文件路径
  - Token管理：添加、删除、查询、修改、列出Token
  - 管理员验证：检查Token是否为管理员Token
- **关键函数**：
  - `readTokens()`: 读取Token文件
  - `writeTokens()`: 写入Token文件
  - `validateToken()`: 验证Token并返回剩余次数
  - `generateUniqueCode()`: 生成唯一8位数字指代码
  - `getFilePathFromCode()`: 根据指代码获取文件路径
  - `processRequest()`: 处理上传和获取文件请求

#### config.php

- **功能**：全局配置文件，包含网站所有配置项
- **主要配置**：
  - 基础配置：网站标题、图标、背景图片、描述
  - 跳转链接配置：首页快捷跳转链接
  - 生日预报配置：生日数据文件和描述
  - RSS Feed配置：RSS功能开关和参数
  - 页脚配置：页脚显示开关和文本
  - 文件快传配置：Token验证、图片上传限制、管理员Token

#### index.php

- **功能**：网站主页，提供导航和快捷跳转功能
- **主要功能**：
  - 显示网站标题和描述
  - 提供快捷跳转链接
  - 生日预报功能
  - RSS Feed显示
  - 自定义跳转模态框
  - 页脚显示

#### upload.php

- **功能**：文件上传与获取页面，提供用户界面
- **主要功能**：
  - 文件上传：上传图片并获取指代码
  - 文件获取：通过指代码获取文件
  - Token设置：设置和保存Token到Cookie
  - Token管理：管理Token（添加、删除、查询、修改、列出）
  - 模态框设计：提供良好的用户体验
  - 响应式布局：适配不同屏幕尺寸

### 数据文件

#### hosts

- **功能**：hosts配置文件
- **用途**：用于域名解析配置

#### id.csv

- **功能**：生日数据文件
- **用途**：存储生日信息，用于生日预报功能

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

### 文件上传流程

1. 用户在```upload.php```页面选择文件并上传
2. ```upload.php```通过```fetch API```调用```api.php```
3. ```api.php```验证```Token```（如果启用）
4. ```api.php```生成唯一指代码
5. ```api.php```保存文件到``upload/```目录
6. ```api.php```保存指代码与文件名的对应关系到```mappings.txt```
7. ```api.php```返回指代码给前端
8. ```upload.php```显示指代码给用户

### 文件获取流程

1. 用户在```upload.php```页面输入指代码
2. ```upload.php```通过```fetch API```调用```api.php```
3. ```api.php```验证```Token```（如果启用）
4. ```api.php```从```mappings.txt```中查找文件名
5. ```api.php```返回文件路径给前端
6. ```upload.php```显示下载链接给用户

### Token管理流程

1. 管理员在```upload.php```页面设置```管理员Token```
2. ```upload.php```通过```fetch API```调用```api.php```的```check_admin```端点
3. ```api.php```验证```Token```是否为```管理员Token```
4. ```api.php```返回验证结果给前端
5. 如果是```管理员Token```，显示管理按钮
6. 管理员可以通过管理按钮添加、删除、查询、修改、列出```Token```

### 安全机制

1. **Token验证**：通过```config.php```中的```$token_verification```开关控制
2. **管理员验证**：通过```config.php```中的```$token_admin```设置```管理员Token```
3. **操作次数限制**：每个```Token```有剩余操作次数，每次操作减1
4. **文件类型限制**：只允许上传图片文件
5. **唯一指代码**：确保指代码不重复，避免冲突

### 技术栈

- **后端**：```PHP```
- **前端**：```HTML```、```CSS```、```JavaScript```
- **数据存储**：文本文件（```.txt```,```.csv```）
- **字体**：```HarmonyOS Sans SC```
- **API**：```RESTful API```（POST请求）
- **Cookie**：用于存储用户```Token```