FROM php:8.3.6-fpm-alpine3.19

ARG PHP_CONF_DIR="/usr/local/etc/php/conf.d"

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN printf "[Date]\ndate.timezone = \"$TZ\"\n" > $PHP_CONF_DIR/date.ini
COPY ./ini.d/x-custom.ini $PHP_INI_DIR/conf.d/x-custom.ini

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions intl pdo_pgsql pgsql zip amqp-2.1.1

RUN rm -rf /tmp/* /var/cache/apk/*
