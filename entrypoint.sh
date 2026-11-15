#!/bin/sh
set -e

echo "==> Menyiapkan environment Laravel..."

# Pastikan folder storage dan cache writable
mkdir -p /app/storage/framework/cache
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/logs
mkdir -p /app/storage/app/public
chmod -R 777 /app/storage /app/bootstrap/cache

# Generate storage link
php artisan storage:link || true

# Jalankan migrasi database
echo "==> Menjalankan migrasi database..."
php artisan migrate --force

# Optimasi cache Laravel untuk production
echo "==> Optimasi cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup PORT di nginx config
PORT=${PORT:-80}
sed -i "s/listen 80 default_server;/listen $PORT default_server;/g" /app/nginx.conf
sed -i "s/listen \[::\]:80 default_server;/listen \[::\]:$PORT default_server;/g" /app/nginx.conf

# Start PHP-FPM di background
echo "==> Menjalankan PHP-FPM..."
php-fpm -D || php-fpm -F &

# Start Nginx di foreground
echo "==> Menjalankan Nginx di port $PORT..."
nginx -c /app/nginx.conf -g 'daemon off;'
