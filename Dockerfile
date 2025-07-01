FROM php:8.2-fpm

# Cài các extension cần thiết
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cài Node.js (LTS) và npm từ NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

# Kiểm tra version để debug (có thể xóa dòng này nếu không cần)
RUN node -v && npm -v

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first for better caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy package files for npm
COPY package.json package-lock.json ./
RUN npm ci

# Copy application code
COPY . .

# Build assets
RUN npm run build

# Tạo thư mục storage cần thiết
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs

# Phân quyền
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Xóa node_modules và npm cache để giảm kích thước image
RUN rm -rf node_modules npm-debug.log*

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
