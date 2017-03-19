FROM php:7.1.2-fpm

MAINTAINER Mohamed Almasry <almasry@almasry.ws>

RUN apt-get update && apt-get install -y --no-install-recommends apt-utils
RUN apt-get install -y autoconf pkg-config libssl-dev libxml2-dev zlib1g-dev libicu-dev g++
RUN pecl install xdebug mongodb xml

# Install libraries dependencies
RUN apt-get install -y \
        curl \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        libgmp-dev \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-configure intl && docker-php-ext-install intl

RUN docker-php-ext-install iconv mcrypt bcmath gmp\
        opcache \
        soap \
        zip \

    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd


# Install apcu and its backwords compatible apc for development
RUN pecl install apcu-5.1.8  apcu_bc-1.0.3 \
    && echo extension=apcu.so > /usr/local/etc/php/conf.d/apcu.ini \
    && echo extension=apc.so > /usr/local/etc/php/conf.d/bc_apc.ini


RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini


RUN pecl install geoip


COPY php.ini /usr/local/etc/php/conf.d/50-setting.ini
COPY php-fpm.conf /usr/local/etc/php/php-fpm.conf
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# set user to sosverkehrsrecht
ARG USERID=1000
RUN echo >> /usr/local/etc/php-fpm.conf
RUN echo user = $USERID >> /usr/local/etc/php-fpm.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \

RUN curl -sS https://phar.phpunit.de/phpunit.phar | php -- --install-dir=/usr/local/bin --filename=phpunit \

EXPOSE 9000

CMD ["php-fpm", "-F"]