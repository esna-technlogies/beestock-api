FROM almasry/php-fpm:7.1.3

MAINTAINER Mohamed Almasry <almasry@almasry.ws>

## Cleaning after installing dependencies
RUN apt-get update && apt-get install && rm -rf /var/cache/apk/*

## Install xdebug extension
RUN pecl install xdebug

## add later to the standard php docker
RUN  docker-php-ext-install sockets
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


## setting timezone
ENV TZ="Europe/Berlin"
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN dpkg-reconfigure -f noninteractive tzdata

## setting behat / testing environment variables
ENV APP_ENVIRONMENT='dev'
ENV BEHAT_PARAMS='{"extensions":{"Behat\\MinkExtension":{"base_url":"http://nginx-server/app_test.php/"}}}'

WORKDIR /service/application
EXPOSE 9000

CMD ["php-fpm", "-F"]