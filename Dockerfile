FROM php:8.3-apache
COPY code/ /var/www/html/
RUN docker-php-ext-install pdo pdo_mysql