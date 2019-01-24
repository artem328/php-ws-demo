FROM 4xxi/php:flex

COPY docker-entrypoint.sh /opt/docker-entrypoint.sh
ENTRYPOINT ./docker-entrypoint.sh