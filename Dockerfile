FROM nexus.infra.toris.vpn:8091/composer:1.9 AS composer

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-scripts \
    --prefer-dist

COPY --from=composer /usr/bin/composer /usr/bin/composer

FROM php:7.2-fpm

RUN apt update && \
    apt install -y --no-install-recommends --no-install-suggests nginx unzip git libzip-dev libpq-dev zlib1g-dev libpng-dev libxml2-dev gnupg2 && \
    curl -LsS https://getcomposer.org/download/1.8.5/composer.phar -o /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    docker-php-ext-install zip mysqli pdo pdo_mysql gd intl

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

WORKDIR /app

COPY --chown=www-data:www-data --from=composer /app/vendor/ /app/vendor/

COPY ./environments/dev/nginx.conf /etc/nginx/nginx.conf

ENTRYPOINT ["/app/entrypoint.sh"]
