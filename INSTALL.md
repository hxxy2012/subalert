# SubAlert 安装部署指南

## 目录
1. [环境要求](#环境要求)
2. [Docker 部署（推荐）](#docker-部署推荐)
3. [手动部署](#手动部署)
4. [配置说明](#配置说明)
5. [数据库设置](#数据库设置)
6. [Web 服务器配置](#web-服务器配置)
7. [定时任务配置](#定时任务配置)
8. [SSL 证书配置](#ssl-证书配置)
9. [备份策略](#备份策略)
10. [故障排除](#故障排除)

## 环境要求

### 最低要求
- PHP >= 8.0
- MySQL >= 8.0
- Web 服务器 (Nginx/Apache)
- Composer
- 2GB RAM
- 10GB 硬盘空间

### 推荐配置
- PHP 8.1+
- MySQL 8.0+
- Nginx 1.18+
- Redis 6.0+
- 4GB RAM
- 20GB SSD 硬盘空间

## Docker 部署（推荐）

### 1. 准备环境

```bash
# 安装 Docker 和 Docker Compose
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# 安装 Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### 2. 克隆项目

```bash
git clone https://github.com/hxxy2012/subalert.git
cd subalert
```

### 3. 配置环境变量

```bash
cp .env.example .env
```

编辑 `.env` 文件：

```env
# 应用配置
APP_NAME=SubAlert
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# 数据库配置
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=subalert
DB_USERNAME=root
DB_PASSWORD=your-secure-password

# 邮件配置
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com

# 通知配置
FEISHU_WEBHOOK_URL=https://open.feishu.cn/open-apis/bot/v2/hook/your-webhook
WECHAT_WEBHOOK_URL=https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=your-key

# JWT 配置
JWT_SECRET=your-jwt-secret-key
JWT_TTL=1440
JWT_REFRESH_TTL=20160
```

### 4. 启动服务

```bash
# 构建并启动容器
docker-compose up -d

# 查看日志
docker-compose logs -f app

# 检查服务状态
docker-compose ps
```

### 5. 初始化数据

```bash
# 进入应用容器
docker-compose exec app bash

# 生成应用密钥
php artisan key:generate

# 运行数据库迁移
php artisan migrate --force

# 创建管理员账户（可选）
php artisan make:admin
```

## 手动部署

### 1. 安装 PHP 和扩展

#### Ubuntu/Debian
```bash
sudo apt update
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-zip php8.1-mbstring php8.1-gd php8.1-bcmath
```

#### CentOS/RHEL
```bash
sudo yum install php81 php81-php-fpm php81-php-mysqlnd php81-php-xml php81-php-curl php81-php-zip php81-php-mbstring php81-php-gd php81-php-bcmath
```

### 2. 安装 Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 3. 安装 MySQL

```bash
# Ubuntu/Debian
sudo apt install mysql-server-8.0

# CentOS/RHEL
sudo yum install mysql80-server
```

### 4. 配置 MySQL

```bash
sudo mysql_secure_installation

# 登录 MySQL
sudo mysql -u root -p

# 创建数据库和用户
CREATE DATABASE subalert CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'subalert'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON subalert.* TO 'subalert'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. 部署应用

```bash
# 克隆项目
cd /var/www
sudo git clone https://github.com/hxxy2012/subalert.git
cd subalert

# 设置权限
sudo chown -R www-data:www-data /var/www/subalert
sudo chmod -R 755 /var/www/subalert
sudo chmod -R 775 /var/www/subalert/storage
sudo chmod -R 775 /var/www/subalert/bootstrap/cache

# 安装依赖
composer install --no-dev --optimize-autoloader

# 配置环境
cp .env.example .env
php artisan key:generate

# 运行迁移
php artisan migrate --force

# 优化配置
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 创建存储链接
php artisan storage:link
```

## 配置说明

### 环境变量详解

| 变量名 | 说明 | 示例值 |
|--------|------|--------|
| APP_NAME | 应用名称 | SubAlert |
| APP_ENV | 环境类型 | production |
| APP_DEBUG | 调试模式 | false |
| APP_URL | 应用URL | https://your-domain.com |
| DB_CONNECTION | 数据库类型 | mysql |
| DB_HOST | 数据库主机 | 127.0.0.1 |
| DB_PORT | 数据库端口 | 3306 |
| DB_DATABASE | 数据库名 | subalert |
| DB_USERNAME | 数据库用户 | subalert |
| DB_PASSWORD | 数据库密码 | secure_password |

### 邮件服务配置

#### Gmail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

#### Outlook
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

#### 阿里云邮件推送
```env
MAIL_MAILER=smtp
MAIL_HOST=smtpdm.aliyun.com
MAIL_PORT=465
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=ssl
```

## Web 服务器配置

### Nginx 配置

创建配置文件 `/etc/nginx/sites-available/subalert`：

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/subalert/public;
    index index.php index.html;

    # 安全头
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    # Gzip 压缩
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # 静态文件缓存
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # API 速率限制
    location /api/ {
        limit_req zone=api burst=10 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }
}

# 速率限制配置
http {
    limit_req_zone $binary_remote_addr zone=api:10m rate=60r/m;
}
```

启用站点：

```bash
sudo ln -s /etc/nginx/sites-available/subalert /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 定时任务配置

### 系统 Crontab

```bash
# 编辑 crontab
sudo crontab -e

# 添加以下内容
* * * * * cd /var/www/subalert && php artisan schedule:run >> /dev/null 2>&1
```

## SSL 证书配置

### 使用 Let's Encrypt

```bash
# 安装 Certbot
sudo apt install certbot python3-certbot-nginx

# 获取证书
sudo certbot --nginx -d your-domain.com

# 自动续期
sudo crontab -e
# 添加：0 12 * * * /usr/bin/certbot renew --quiet
```

## 备份策略

### 数据库备份

创建备份脚本 `/opt/backup/mysql-backup.sh`：

```bash
#!/bin/bash
BACKUP_DIR="/opt/backup/mysql"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="subalert"
DB_USER="subalert"
DB_PASS="your-password"

mkdir -p $BACKUP_DIR

# 备份数据库
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/subalert_$DATE.sql

# 压缩备份文件
gzip $BACKUP_DIR/subalert_$DATE.sql

# 删除7天前的备份
find $BACKUP_DIR -name "*.gz" -mtime +7 -delete

echo "数据库备份完成: subalert_$DATE.sql.gz"
```

### 自动备份

```bash
# 添加到 crontab
0 2 * * * /opt/backup/mysql-backup.sh
```

## 故障排除

### 常见问题

#### 1. 数据库连接失败
```bash
# 检查数据库服务
sudo systemctl status mysql

# 检查连接
mysql -u subalert -p -h localhost subalert

# 查看错误日志
tail -f /var/log/mysql/error.log
```

#### 2. 权限问题
```bash
# 重置权限
sudo chown -R www-data:www-data /var/www/subalert
sudo chmod -R 755 /var/www/subalert
sudo chmod -R 775 /var/www/subalert/storage
sudo chmod -R 775 /var/www/subalert/bootstrap/cache
```

#### 3. 邮件发送失败
```bash
# 测试邮件配置
php artisan tinker
Mail::raw('测试邮件', function($msg) { $msg->to('test@example.com')->subject('测试'); });

# 查看邮件日志
tail -f storage/logs/laravel.log
```

### 性能优化

#### 1. 启用 OPcache
```ini
# 在 php.ini 中添加
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

#### 2. 使用 Redis 缓存
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 安全建议

1. **定期更新系统和软件包**
2. **使用强密码和密钥**
3. **启用防火墙**
4. **定期备份数据**
5. **监控异常访问**
6. **使用 HTTPS**
7. **限制文件上传权限**
8. **定期安全审计**

更多详细信息请参考项目文档或联系技术支持。

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