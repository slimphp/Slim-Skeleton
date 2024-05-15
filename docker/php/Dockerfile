FROM php:8.3.3-alpine

RUN apk update && apk upgrade --update-cache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

COPY . /var/www

CMD ["php-fpm"]