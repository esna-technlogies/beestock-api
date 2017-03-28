FROM almasry/php-7.1-fpm:latest

MAINTAINER Mohamed Almasry <almasry@almasry.ws>

## Cleaning after installing dependencies
RUN apt-get update && apt-get install && rm -rf /var/cache/apk/*

## Install xdebug extension
RUN pecl install xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

## Copy necessary PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/50-setting.ini
COPY php-fpm.conf /usr/local/etc/php/php-fpm.conf

## set user to sosverkehrsrecht
ARG USERID=1000
RUN echo >> /usr/local/etc/php-fpm.conf
RUN echo user = $USERID >> /usr/local/etc/php-fpm.conf

## installing PHPUnit and Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://phar.phpunit.de/phpunit.phar | php -- --install-dir=/usr/local/bin --filename=phpunit


## setting behat / testing environment variables
ENV BEHAT_PARAMS='{"extensions":{"Behat\\MinkExtension":{"base_url":"http://nginx-server/app_test.php/"}}}'

RUN mkdir -p /service && chmod 777 -R /service

COPY ./service /service
WORKDIR /service/application

EXPOSE 9000

CMD ["php-fpm", "-F"]