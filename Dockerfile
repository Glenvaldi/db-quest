FROM php:8.2-apache

# 1. Install system dependencies (termasuk libicu-dev dan libzip-dev)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \
    libzip-dev

# 2. Clear cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Configure & Install PHP extensions (termasuk intl dan zip)
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# 4. Enable Apache mod_rewrite & Perbaiki bentrok MPM di Railway
RUN a2enmod rewrite
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# 5. Ubah DocumentRoot Apache ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Ubah port Apache agar jalan di 8080
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 7. Set working directory
WORKDIR /var/www/html

# 8. Copy seluruh file project dari GitHub ke dalam container
COPY . .

# 9. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 10. Install dependencies Laravel & Filament
RUN composer install --no-dev --optimize-autoloader

# 11. Atur permission folder storage dan cache agar bisa ditulisi oleh sistem
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 12. ==========================================
# JURUS PAMUNGKAS: Script otomatis saat container menyala
# Script ini akan memastikan Key di-generate, database di-migrate, dan cache dibersihkan
RUN echo '#!/bin/bash\n\
php artisan key:generate --force\n\
php artisan migrate --force\n\
php artisan config:clear\n\
php artisan cache:clear\n\
apache2-foreground' > /usr/local/bin/start-container

# Beri izin eksekusi pada script tersebut
RUN chmod +x /usr/local/bin/start-container

# Jalankan script saat container dihidupkan
CMD ["start-container"]
# ==========================================

# 13. Buka port 8080
EXPOSE 8080
