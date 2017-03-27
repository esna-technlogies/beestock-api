FROM php:7.1.3-fpm

MAINTAINER Mohamed Almasry <almasry@almasry.ws>


RUN apt-get update && apt-get install -y --no-install-recommends apt-utils

## Install libraries dependencies
RUN apt-get install -y \
        autoconf \
        pkg-config \
        libssl-dev \
        libxml2-dev \
        zlib1g-dev \
        libicu-dev \
        g++ \
        curl \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        libgmp-dev

## Cleaning after installing dependencies
RUN apt-get update && apt-get install && rm -rf /var/cache/apk/*

## Install pecl extensions
RUN pecl install mongodb xml geoip

## Installing unofficial PHP extensions from source coe using docker-php-ext-install
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-install iconv mcrypt bcmath gmp\
        opcache \
        zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

## Install apcu and its backwords compatible apc for development
RUN pecl install apcu-5.1.8  apcu_bc-1.0.3 \
    && echo extension=apcu.so > /usr/local/etc/php/conf.d/apcu.ini \
    && echo extension=apc.so > /usr/local/etc/php/conf.d/bc_apc.ini

## configuring mongodb
RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

## set user to sosverkehrsrecht
ARG USERID=1000
RUN echo >> /usr/local/etc/php-fpm.conf
RUN echo user = $USERID >> /usr/local/etc/php-fpm.conf

EXPOSE 9000

CMD ["php-fpm", "-F"]