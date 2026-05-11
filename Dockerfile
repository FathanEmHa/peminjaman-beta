FROM php:8.2-apache

# 1. Install dependencies sistem, Node.js, dan NPM (wajib buat Vite/Tailwind)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install ekstensi PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Aktifin mod_rewrite Apache
RUN a2enmod rewrite

# 4. Ubah default folder Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/sipa.conf \
&& a2enconf sipa

# 5. Tarik Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Set working directory
WORKDIR /var/www/html

# 7. Copy semua file project
COPY . .

# 8. Install Vendor (PHP) dan Node Modules (Frontend), lalu Build UI
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# 9. Setup Environment & Database SQLite untuk Demo
RUN cp .env.example .env
RUN php artisan key:generate
RUN touch database/database.sqlite
RUN php artisan migrate:fresh --seed --force

# 10. Bantai symlink lama dan generate baru
RUN rm -rf public/storage && php artisan storage:link

# 11. Kasih hak akses ke Apache (Wajib masukin folder /database biar SQLite bisa ditulis)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/storage /var/www/html/database \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/storage /var/www/html/database