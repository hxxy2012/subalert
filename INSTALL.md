# SubAlert 订阅提醒管理工具 - 安装部署指南

## 🚀 快速开始

SubAlert 是一个基于 Laravel 10 的订阅提醒管理工具，帮助您轻松管理各种订阅服务。

## 📋 系统要求

- PHP 8.0+
- MySQL 8.0+ 或 SQLite
- Composer
- Node.js (可选，用于前端资源编译)

## 🛠️ 安装步骤

### 1. 克隆项目
```bash
git clone https://github.com/hxxy2012/subalert.git
cd subalert
```

### 2. 安装依赖
```bash
composer install --no-dev
```

### 3. 环境配置
```bash
# 复制环境配置文件
cp .env.example .env

# 生成应用密钥
php artisan key:generate
```

### 4. 配置数据库
编辑 `.env` 文件，配置数据库连接：

#### MySQL 配置
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=subalert
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### SQLite 配置（开发环境推荐）
```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/your/database.sqlite
```

### 5. 数据库迁移和初始化
```bash
# 创建数据库表
php artisan migrate

# 填充初始数据（包含默认管理员账户）
php artisan db:seed
```

### 6. 存储目录权限
```bash
chmod -R 755 storage bootstrap/cache
```

### 7. 启动应用
```bash
# 开发环境
php artisan serve

# 生产环境配置 Web 服务器指向 public 目录
```

## 🔑 默认账户

### 管理员账户
- 用户名：`admin`
- 密码：`admin123`
- 登录地址：`/admin/login`

## 🎯 功能特色

- ✅ 用户注册和登录
- ✅ 订阅管理（增删改查）
- ✅ 提醒设置和管理
- ✅ 管理员后台
- ✅ 数据统计和分析
- ✅ 响应式设计

## 📱 使用说明

### 前台用户功能
1. 注册/登录账户
2. 添加订阅服务（Netflix、Spotify 等）
3. 设置提醒时间
4. 查看订阅统计

### 后台管理功能
1. 用户管理
2. 订阅数据管理
3. 系统统计
4. 提醒日志

## 🔧 配置说明

### 邮件配置
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS="noreply@subalert.com"
```

### 消息推送配置
```env
# 飞书机器人
FEISHU_WEBHOOK_URL=your-feishu-webhook-url

# 企业微信机器人
WECHAT_WEBHOOK_URL=your-wechat-webhook-url
```

## 🚀 宝塔面板部署

### 1. 上传代码
将项目文件上传到网站根目录

### 2. 设置运行目录
在宝塔面板中设置运行目录为 `public`

### 3. 安装依赖
在网站根目录执行：
```bash
composer install --no-dev
```

### 4. 配置环境
复制 `.env.example` 为 `.env` 并配置数据库等信息

### 5. 数据库操作
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 6. 设置权限
```bash
chmod -R 755 storage bootstrap/cache
```

## 🛡️ 安全建议

1. 修改默认管理员密码
2. 配置 HTTPS
3. 定期备份数据库
4. 设置防火墙规则

## 📞 技术支持

如有问题，请提交 Issue 或联系开发者。

## 📄 许可证

MIT License

---

**SubAlert** - 让订阅管理更简单！ 🎉