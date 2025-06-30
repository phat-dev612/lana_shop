#!/bin/bash

set -e

cd /var/www

# Install Composer nếu vendor chưa có
if [ ! -d "vendor" ]; then
  echo "📦 Installing composer dependencies..."
  composer install --no-interaction --prefer-dist
fi

# Chờ MySQL
until php -r "new PDO('mysql:host=mysql-db;dbname=lana_shop', 'root', 'root');" 2>/dev/null; do
  echo "⏳ Waiting for MySQL..."
  sleep 2
done

# Laravel setup
echo "🔑 Generating key..."
php artisan key:generate --force

echo "🗃️ Running migrations..."
php artisan migrate --force

echo "🚀 Starting PHP-FPM..."
exec php-fpm
