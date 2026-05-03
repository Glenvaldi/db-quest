FROM richarvey/php-apache-heroku:latest
ENV PHP_ERRORS_STDERR 1
ENV ARTIFACTS_DIR /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
COPY . /var/www/html
RUN composer install --no-dev
RUN npm install && npm run build
RUN php artisan key:generate