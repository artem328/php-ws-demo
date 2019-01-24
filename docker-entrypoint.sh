#!/bin/sh

if [ "$APP_ENV" = 'prod' ]; then
    composer install --prefer-dist --no-dev --no-progress --no-suggest --optimize-autoloader --classmap-authoritative --no-interaction
else
    composer install --prefer-dist --no-progress --no-suggest --no-interaction
fi

chown -R www-data \
    bin/ \
    config/ \
    public/ \
    src/ \
    templates/ \
    var/ \
    vendor/ \
    .env \
    .env.dist \
    composer.json \
    composer.lock \
    symfony.lock
php-fpm
