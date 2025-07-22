# SubAlert 快速开始指南

## 🚀 一键启动（Docker 方式）

```bash
# 1. 克隆项目
git clone https://github.com/hxxy2012/subalert.git
cd subalert

# 2. 启动服务
docker-compose up -d

# 3. 访问应用
# 前端: http://localhost
# API: http://localhost/api
```

## 📋 手动安装

```bash
# 1. 运行安装脚本
chmod +x install.sh
./install.sh

# 2. 配置数据库（编辑 .env 文件）
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=subalert
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 3. 启动应用
php artisan serve
```

## 🔧 快速配置

### 邮件服务（可选）
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### 第三方通知（可选）
```env
FEISHU_WEBHOOK_URL=https://open.feishu.cn/open-apis/bot/v2/hook/your-webhook
WECHAT_WEBHOOK_URL=https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=your-key
```

## 📚 更多文档

- [完整安装指南](INSTALL.md)
- [API 文档](API.md)
- [功能测试清单](TESTING.md)

## 🆘 常见问题

1. **数据库连接失败**: 检查 MySQL 服务是否启动，`.env` 配置是否正确
2. **权限错误**: 运行 `chmod -R 775 storage bootstrap/cache`
3. **邮件发送失败**: 检查邮件服务器配置和网络连接

## 🎯 默认账户

- **前端用户**: 需要注册创建
- **管理员**: `admin@subalert.com` / `admin123` (运行 seeder 后)