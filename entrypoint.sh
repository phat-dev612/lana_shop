#!/bin/bash

# Chờ MySQL sẵn sàng
until php -r "new PDO('mysql:host=db;dbname=lana_shop', 'root', 'root');" 2>/dev/null; do
  echo "Waiting for MySQL..."
  sleep 2
done

# Generate key nếu chưa có
php artisan key:generate --force

# Chạy migrate
php artisan migrate --force

# Khởi động php-fpm
php-fpm