FROM php:8-alpine

COPY . /var/www/
WORKDIR /var/www

RUN apk add composer \
            php-xml \
            php-simplexml \
            php-xmlwriter \
            php-mbstring \
            php-tokenizer \
            php-dom \
    && composer install --no-dev

RUN chown -R www-data:www-data /var/www/ && rm -r /var/www/tests

EXPOSE 8080
USER www-data

CMD ["/usr/bin/php", "-S", "0.0.0.0:8080", "-t", "public"]

