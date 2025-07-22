#!/bin/bash

# 等待数据库连接
echo "等待数据库连接..."
while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    sleep 1
done

# 运行数据库迁移
echo "运行数据库迁移..."
php artisan migrate --force

# 清除缓存
echo "清除缓存..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 生成应用密钥（如果不存在）
if [ -z "$APP_KEY" ]; then
    echo "生成应用密钥..."
    php artisan key:generate --force
fi

# 创建存储链接
php artisan storage:link

# 优化配置
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 启动 PHP-FPM
echo "启动 PHP-FPM..."
php-fpm -D

# 启动 Nginx
echo "启动 Nginx..."
nginx -g "daemon off;" &

# 启动 cron
echo "启动 cron 服务..."
cron

# 保持容器运行
wait