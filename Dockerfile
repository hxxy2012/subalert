FROM php:8.1-fpm

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    cron \
    && rm -rf /var/lib/apt/lists/*

# 安装 PHP 扩展
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www/html

# 复制应用程序文件
COPY . .

# 安装 PHP 依赖
RUN composer install --no-dev --optimize-autoloader

# 设置权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# 复制 Nginx 配置
COPY docker/nginx.conf /etc/nginx/sites-available/default

# 复制启动脚本
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# 复制 crontab 配置
COPY docker/crontab /etc/cron.d/subalert-cron
RUN chmod 0644 /etc/cron.d/subalert-cron && crontab /etc/cron.d/subalert-cron

# 暴露端口
EXPOSE 80

# 启动脚本
CMD ["/start.sh"]