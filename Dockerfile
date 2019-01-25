FROM php:7.2-alpine

RUN apk update && apk add \
    git \
    icu-dev \
    zlib-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-install \
    bcmath \
    intl \
    opcache \
    zip

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

RUN git clone https://github.com/swoole/swoole-src.git \
    && cd swoole-src \
    && phpize \
    && ./configure \
    && make \
    && make install

RUN echo 'extension=swoole.so' > /usr/local/etc/php/conf.d/swoole.ini

ARG HOST_USER_ID=1000
ENV HOST_UID=$HOST_USER_ID

WORKDIR /var/www/html

ENTRYPOINT ./docker-entrypoint.sh