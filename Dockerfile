# =============================================================================
# Stage 1 — Node.js: Build frontend assets (Vite/Tailwind)
# =============================================================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy only package files first for better layer caching
COPY package.json package-lock.json ./
RUN npm ci --frozen-lockfile

# Copy source files needed by Vite
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources/ ./resources/

# Build production assets (outputs to public/build/)
RUN npm run build


# =============================================================================
# Stage 2 — Composer: Install PHP dependencies (no dev)
# =============================================================================
FROM composer:2.7 AS composer-builder

WORKDIR /app

# Copy manifests first for dependency-layer caching
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts \
    --prefer-dist

# Copy application source
COPY . .


# =============================================================================
# Stage 3 — Final production image (PHP 8.2 + Apache)
# =============================================================================
FROM php:8.2-apache AS production

# ── System dependencies ──────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        zip \
        unzip \
        curl \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/*

# ── Apache configuration ─────────────────────────────────────────────────────
RUN a2enmod rewrite headers

# Point Apache document root to Laravel's public/ directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides (required for Laravel routing)
RUN printf '<Directory ${APACHE_DOCUMENT_ROOT}>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# ── Application files ────────────────────────────────────────────────────────
WORKDIR /var/www/html

# Copy PHP vendor from composer stage
COPY --from=composer-builder /app/vendor ./vendor

# Copy application source (excluding what's in .dockerignore)
COPY . .

# Copy compiled frontend assets from node stage
COPY --from=node-builder /app/public/build ./public/build

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# ── Permissions ──────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

# ── Entrypoint ───────────────────────────────────────────────────────────────
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]