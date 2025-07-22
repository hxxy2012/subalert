# SubAlert 订阅提醒管理工具

一个专业的订阅服务管理工具，帮助用户管理各种订阅服务并提供及时的到期提醒。

## 功能特色

- 🔔 **智能提醒** - 支持邮件、飞书、企业微信等多种提醒方式
- 📊 **支出分析** - 详细的订阅支出统计和分析
- 🛡️ **安全可靠** - 企业级安全保障，JWT Token 认证
- 📱 **响应式设计** - 完美支持各种设备
- ⚙️ **管理后台** - 强大的后台管理功能
- 🚀 **RESTful API** - 完整的 API 接口支持
- 🐳 **Docker 部署** - 容器化部署，简单易用

## 技术栈

- **后端**: Laravel 10, PHP 8.0+
- **数据库**: MySQL 8.0+
- **认证**: Laravel Sanctum + JWT
- **缓存**: Redis
- **前端**: Blade 模板 + Bootstrap
- **部署**: Docker + Nginx

## 环境要求

- PHP >= 8.0
- MySQL >= 8.0
- Composer
- Node.js & NPM (可选，用于前端资源编译)
- Docker & Docker Compose (推荐)

## 快速开始

### 使用 Docker 部署（推荐）

1. **克隆项目**
```bash
git clone <repository-url> subalert
cd subalert
```

2. **配置环境变量**
```bash
cp .env.example .env
# 编辑 .env 文件，配置数据库和其他设置
```

3. **启动服务**
```bash
docker-compose up -d
```

4. **访问应用**
- 前端: http://localhost
- API: http://localhost/api

### 手动部署

1. **克隆项目**
```bash
git clone <repository-url> subalert
cd subalert
```

2. **安装依赖**
```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. **配置数据库**
编辑 `.env` 文件：
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=subalert
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. **运行迁移**
```bash
php artisan migrate
```

5. **创建存储链接**
```bash
php artisan storage:link
```

6. **启动服务**
```bash
php artisan serve
```

## 配置说明

### 邮件配置
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@subalert.com
```

### 第三方通知配置
```env
# 飞书机器人 Webhook
FEISHU_WEBHOOK_URL=https://open.feishu.cn/open-apis/bot/v2/hook/your-webhook-url

# 企业微信机器人 Webhook
WECHAT_WEBHOOK_URL=https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=your-key
```

### JWT 配置
```env
JWT_SECRET=your-jwt-secret
JWT_TTL=1440
JWT_REFRESH_TTL=20160
```

## API 文档

### 认证相关

- `POST /api/register` - 用户注册
- `POST /api/login` - 用户登录
- `POST /api/logout` - 用户退出
- `POST /api/refresh` - 刷新令牌

### 订阅管理

- `GET /api/subscriptions` - 获取订阅列表
- `POST /api/subscriptions` - 创建订阅
- `GET /api/subscriptions/{id}` - 获取订阅详情
- `PUT /api/subscriptions/{id}` - 更新订阅
- `DELETE /api/subscriptions/{id}` - 删除订阅

### 提醒管理

- `GET /api/reminders` - 获取提醒列表
- `POST /api/reminders` - 创建提醒
- `PUT /api/reminders/{id}` - 更新提醒
- `DELETE /api/reminders/{id}` - 删除提醒

### 统计分析

- `GET /api/statistics/dashboard` - 获取仪表板数据
- `GET /api/statistics/monthly-expenses` - 获取月度支出
- `GET /api/statistics/expiring` - 获取即将到期的订阅

## 定时任务

系统会自动创建以下定时任务：

```bash
# 每分钟检查并发送提醒
* * * * * php artisan reminders:send

# 每天清理旧的提醒记录
0 0 * * * php artisan reminders:cleanup
```

## 管理后台

访问 `/admin` 路径进入管理后台，默认管理员账户需要通过数据库手动创建。

## 开发

### 运行测试
```bash
php artisan test
```

### 代码风格检查
```bash
./vendor/bin/pint
```

### 生成 API 文档
```bash
php artisan api:docs
```

## 部署到生产环境

1. **优化配置**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **设置定时任务**
添加到系统 crontab：
```bash
* * * * * cd /path/to/subalert && php artisan schedule:run >> /dev/null 2>&1
```

3. **配置 Web 服务器**
参考 `docker/nginx.conf` 配置 Nginx。

## 故障排除

### 常见问题

1. **数据库连接失败**
   - 检查数据库服务是否启动
   - 检查 `.env` 文件中的数据库配置

2. **邮件发送失败**
   - 检查邮件服务器配置
   - 确认邮件服务商是否支持 SMTP

3. **定时任务未执行**
   - 检查 cron 服务是否启动
   - 确认定时任务配置正确

## 许可证

本项目基于 MIT 许可证开源。

## 支持

如有问题或建议，请提交 Issue 或 Pull Request。