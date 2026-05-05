FROM php:8.2-cli

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libicu-dev libzip-dev

# 2. Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Configure & Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# 4. Set working directory
WORKDIR /app

# 5. Copy file project
COPY . .

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# 8. Atur permission folder penting
RUN chmod -R 777 storage bootstrap/cache

# 9. JURUS PAMUNGKAS (Menggunakan Artisan Serve, selamat tinggal Apache!)
RUN echo '#!/bin/bash\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan migrate --force\n\
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}' > /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

# 10. Jalankan aplikasi
CMD ["start-container"]
