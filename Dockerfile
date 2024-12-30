#
FROM serversideup/php:8.4-fpm-nginx-alpine

USER root

RUN install-php-extensions \
    gd \
    intl \
    mbstring \
    imagick

RUN apk add --no-cache \
    freetype \
    libpng

WORKDIR /var/www/html/public

COPY . .

RUN chown -R www-data:www-data /var/www/html/public

RUN rm -f /var/www/html/public/Dockerfile

USER www-data