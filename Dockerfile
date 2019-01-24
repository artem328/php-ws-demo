FROM 4xxi/php:flex

RUN apt-get update; exit 0;

RUN git clone https://github.com/swoole/swoole-src.git \
    && cd swoole-src \
    && phpize \
    && ./configure \
    && make \
    && make install

RUN echo 'extension=swoole.so' > /usr/local/etc/php/conf.d/swoole.ini

COPY docker-entrypoint.sh /opt/docker-entrypoint.sh
ENTRYPOINT ./docker-entrypoint.sh