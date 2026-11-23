#!/bin/sh
set -e

echo "Checking environment configuration..."

if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.docker or .env.example..."
    if [ -f /var/www/html/.env.docker ]; then
        cp /var/www/html/.env.docker /var/www/html/.env
    else
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

if [ ! -d /var/www/html/vendor ]; then
    echo "Vendor folder missing, running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

APP_KEY=$(grep -E '^APP_KEY=' /var/www/html/.env | cut -d '=' -f2)
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

if [ -n "$DB_HOST" ]; then
    PORT=${DB_PORT:-3306}
    echo "Waiting for database on $DB_HOST:$PORT..."
    while ! nc -z "$DB_HOST" "$PORT"; do
        sleep 2
    done
    echo "Database is ready!"
fi

# Create storage symlink
php artisan storage:link || true

# Run migrations and default seeds automatically
echo "Running database migrations and seeders..."
php artisan migrate --force --seed || true

# Optimize cache
php artisan optimize:clear

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "===> Application setup complete. Starting services..."

exec "$@"
