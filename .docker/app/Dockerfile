FROM php:7.3-fpm-alpine

# Install application dependencies
RUN apk add --no-cache curl bash
RUN curl https://getcaddy.com | bash -s personal http.expires,http.realip
RUN docker-php-ext-install mbstring mysqli pdo pdo_mysql

ADD . /srv/app
ADD .docker/app/Caddyfile /etc/Caddyfile
COPY .docker/app/config/php.ini /usr/local/etc/php/

WORKDIR /srv/app/
RUN chown -R www-data:www-data /srv/app

CMD ["/usr/local/bin/caddy", "--conf", "/etc/Caddyfile", "--log", "stdout"]
