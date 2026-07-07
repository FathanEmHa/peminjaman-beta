#!/bin/bash
set -e

echo "──────────────────────────────────────────────"
echo "  SIPA – Docker Entrypoint"
echo "──────────────────────────────────────────────"

# ── 0. Ensure Laravel directories exist ──────────────────────────────────────
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ── 1. Generate APP_KEY if not set ───────────────────────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] Generating application key..."
    php artisan key:generate --force --no-interaction || true
else
    echo "[entrypoint] APP_KEY already set — skipping key:generate"
fi

# ── 2. Wait for database ─────────────────────────────────────────────────────
echo "[entrypoint] Waiting for database ($DB_HOST:$DB_PORT)..."

max_tries=30
count=0

until php -r "new PDO('mysql:host=$DB_HOST;port=$DB_PORT', '$DB_USERNAME', '$DB_PASSWORD');" 2>/dev/null; do
    count=$((count + 1))

    if [ "$count" -ge "$max_tries" ]; then
        echo "[entrypoint] ERROR: Database not reachable."
        exit 1
    fi

    sleep 2
done

echo "[entrypoint] Database is ready."

# ── 3. Package discovery ─────────────────────────────────────────────────────
echo "[entrypoint] Running package discovery..."
php artisan package:discover --ansi || true

# ── 4. Run migrations ────────────────────────────────────────────────────────
echo "[entrypoint] Running migrations..."
php artisan migrate --force --no-interaction || true

# ── 4.5 Initial seed (only if users table is empty) ─────────────────────────
echo "[entrypoint] Running database seed..."
php artisan db:seed --force || true

# ── 5. Storage link ──────────────────────────────────────────────────────────
echo "[entrypoint] Linking storage..."
php artisan storage:link --force 2>/dev/null || true

# ── 6. Production cache ──────────────────────────────────────────────────────
if [ "${SKIP_CACHE:-false}" != "true" ]; then
    echo "[entrypoint] Building caches..."

    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
    php artisan event:cache || true
fi

echo "[entrypoint] Bootstrap complete. Starting: $*"

exec "$@"