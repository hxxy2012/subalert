#!/bin/bash

# SubAlert 快速安装脚本
# 此脚本用于快速设置 SubAlert 应用

set -e

echo "🚀 SubAlert 快速安装向导"
echo "========================"

# 检查 PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP 未安装，请先安装 PHP 8.0+"
    exit 1
fi

# 检查 Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer 未安装，请先安装 Composer"
    exit 1
fi

# 检查 MySQL
if ! command -v mysql &> /dev/null; then
    echo "⚠️  MySQL 未安装，请确保 MySQL 8.0+ 已安装并运行"
fi

echo "✅ 环境检查通过"

# 安装依赖
echo "📦 安装 PHP 依赖..."
composer install

# 复制环境文件
if [ ! -f .env ]; then
    echo "📝 创建环境配置文件..."
    cp .env.example .env
    echo "📝 请编辑 .env 文件配置数据库连接"
fi

# 生成应用密钥
if grep -q "APP_KEY=base64:" .env; then
    echo "🔑 应用密钥已存在"
else
    echo "🔑 生成应用密钥..."
    php artisan key:generate
fi

# 检查数据库连接
echo "🔗 检查数据库连接..."
if php artisan migrate:status &> /dev/null; then
    echo "✅ 数据库连接成功"
    
    # 运行迁移
    echo "🗄️ 运行数据库迁移..."
    php artisan migrate --force
else
    echo "❌ 数据库连接失败，请检查 .env 文件中的数据库配置"
    echo "📝 配置示例："
    echo "   DB_CONNECTION=mysql"
    echo "   DB_HOST=127.0.0.1"
    echo "   DB_PORT=3306"
    echo "   DB_DATABASE=subalert"
    echo "   DB_USERNAME=your_username"
    echo "   DB_PASSWORD=your_password"
    exit 1
fi

# 创建存储链接
echo "🔗 创建存储链接..."
php artisan storage:link

# 缓存配置
echo "⚡ 优化配置..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 设置权限
echo "🔐 设置文件权限..."
chmod -R 775 storage bootstrap/cache

echo ""
echo "🎉 安装完成！"
echo ""
echo "📋 接下来的步骤："
echo "1. 配置 Web 服务器指向 public 目录"
echo "2. 设置定时任务: * * * * * cd $(pwd) && php artisan schedule:run"
echo "3. 配置邮件服务器 (在 .env 文件中)"
echo "4. 配置第三方通知服务 (飞书、企业微信)"
echo ""
echo "🌐 本地开发可以运行: php artisan serve"
echo "📖 详细部署说明请参考 INSTALL.md"