# Multi-stage Dockerfile for Laravel on Railway
FROM php:8.3-fpm-alpine

# Install system dependencies & extensions
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    libzip-dev \
    sqlite-dev \
    bash \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy dependency files first for layer caching
COPY composer.json composer.lock package.json package-lock.json* ./

# Install Composer dependencies (no-dev for production)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy whole project
COPY . .

# Complete composer autoload and npm build
RUN composer dump-autoload --optimize --no-dev
RUN npm ci || npm install
RUN npm run build

# Copy custom configs
COPY nginx.conf /etc/nginx/nginx.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 80

CMD ["/entrypoint.sh"]
