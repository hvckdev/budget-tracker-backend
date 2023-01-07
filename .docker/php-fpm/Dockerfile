FROM php:8.1.14-fpm-alpine3.16

RUN apk add --update \
    $PHPIZE_DEPS \
    zlib-dev \
    libzip-dev \
    libxslt-dev \
    icu-dev \
    oniguruma-dev

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl intl opcache exif mbstring

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer