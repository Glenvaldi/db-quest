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

# 8. Atur permission folder bootstrap
RUN chmod -R 777 bootstrap/cache

# 9. JURUS PAMUNGKAS + AUTO-BUILD STORAGE FOLDER (TERMASUK LIVEWIRE)
RUN echo '#!/bin/bash\n\
# Bangun ulang struktur folder di dalam Volume kosong\n\
mkdir -p /app/storage/framework/cache/data\n\
mkdir -p /app/storage/framework/sessions\n\
mkdir -p /app/storage/framework/views\n\
mkdir -p /app/storage/logs\n\
mkdir -p /app/storage/app/public\n\
mkdir -p /app/storage/app/livewire-tmp\n\
# Berikan akses penuh ke folder tersebut\n\
chmod -R 777 /app/storage\n\
# Hancurkan symlink lama jika nyangkut\n\
rm -rf /app/public/storage\n\
# Jalankan perintah artisan\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan migrate --force\n\
php artisan storage:link\n\
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}' > /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

# 10. Jalankan aplikasi
CMD ["start-container"]
