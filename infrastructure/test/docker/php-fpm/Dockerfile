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


## installing PHPUnit and Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://phar.phpunit.de/phpunit.phar | php -- --install-dir=/usr/local/bin --filename=phpunit


## setting behat / testing environment variables
ENV APP_ENVIRONMENT='test'
ENV BEHAT_PARAMS='{"extensions":{"Behat\\MinkExtension":{"base_url":"http://nginx-server/app_test.php/"}}}'

RUN mkdir -p /service && chmod 777 -R /service

COPY ./service /service

## workaround to solve the cache clear problem
RUN chown www-data:www-data -R /service
RUN cd /service/application && chmod 777 -R ../* && rm -rf ./var/cache/* && composer install --no-progress

WORKDIR /service/application
EXPOSE 9000

CMD ["php-fpm", "-F"]