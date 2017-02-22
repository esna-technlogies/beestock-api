FROM php:7.1.0-fpm

MAINTAINER Abed Halawi <abed.halawi@vinelab.com>

RUN apt-get update
RUN apt-get install -y autoconf pkg-config libssl-dev
RUN pecl install mongodb-1.6.14 xdebug opcache
RUN docker-php-ext-install bcmath
RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

# Install Laravel dependencies
RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-install iconv mcrypt mbstring \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd


COPY php.ini /etc/php7/conf.d/50-setting.ini
COPY php-fpm.conf /etc/php7/php-fpm.conf
COPY xdebug.ini /etc/php7/conf.d/xdebug.ini


# set user to sosverkehrsrecht
ARG USERID=1000
RUN echo >> /etc/php7/php-fpm.conf
RUN echo user = $USERID >> /etc/php7/php-fpm.conf

# Install phpunit
RUN curl -sSL https://phar.phpunit.de/phpunit.phar -o phpunit.phar \
    && chmod +x phpunit.phar \
    && mv phpunit.phar /usr/local/bin/phpunit

EXPOSE 9000

CMD ["php-fpm7", "-F"]