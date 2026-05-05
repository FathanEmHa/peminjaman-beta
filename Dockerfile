FROM php:8.2-apache

# 1. Install dependencies sistem yang dibutuhin buat server Linux
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzipd

# Bersihin cache apt biar ukuran image gak bengkak
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install ekstensi PHP yang diwajibkan oleh Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Aktifin mod_rewrite Apache (wajib biar routing Laravel jalan)
RUN a2enmod rewrite

# 4. Ubah default folder Apache ke folder /public milik Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Tarik Composer dari image resminya buat install package Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Set working directory ke folder web default
WORKDIR /var/www/html

# 7. Copy semua file project lu ke dalam container
COPY . .

# 8. Kasih akses read-write ke folder storage dan cache (khas Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache