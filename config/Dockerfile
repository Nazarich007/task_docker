FROM php:7.4-fpm-alpine

WORKDIR /var/www/html

RUN apk update && apk add --no-cache \
    build-base shadow vim curl \
    php7 \
    php7-fpm \
    php7-common \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-mcrypt \
    php7-mbstring \
    php7-xml \
    php7-openssl \
    php7-json \
    php7-phar \
    php7-zip \
    php7-gd \
    php7-dom \
    php7-session \
    php7-zlib

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-enable pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install xdebug-2.9.8 \
  && docker-php-ext-enable xdebug \
  && apk del pcre-dev ${PHPIZE_DEPS}

RUN rm -rf /var/cache/apk/*
RUN usermod -u 1000 www-data
COPY --chown=www-data:www-data . /var/www/html
EXPOSE 9000
CMD ["php-fpm"]
