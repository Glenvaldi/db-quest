FROM php:8.2-apache

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libicu-dev libzip-dev

# 2. Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Configure & Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# 4. Enable mod_rewrite (Kembali ke mode aman bawaan image)
RUN a2enmod rewrite

# 5. Set DocumentRoot ke folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Ubah port ke 8080
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 7. Set working directory
WORKDIR /var/www/html

# 8. Copy file
COPY . .

# 9. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 10. Install dependencies
RUN composer install --no-dev --optimize-autoloader

# 11. Atur permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 12. JURUS PAMUNGKAS (Sudah dibersihkan dari jebakan key:generate)
RUN echo '#!/bin/bash\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan migrate --force\n\
apache2-foreground' > /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

# 13. Expose port dan Jalankan
EXPOSE 8080
CMD ["start-container"]
