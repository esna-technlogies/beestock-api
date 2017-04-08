FROM almasry/php-7.1-fpm:latest

MAINTAINER Mohamed Almasry <almasry@almasry.ws>

## Cleaning after installing dependencies
RUN apt-get update && apt-get install && rm -rf /var/cache/apk/*

## Copy necessary PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/50-setting.ini
COPY php-fpm.conf /usr/local/etc/php/php-fpm.conf

## set PHP User
ARG USERID=1000
RUN echo >> /usr/local/etc/php-fpm.conf
RUN echo user = $USERID >> /usr/local/etc/php-fpm.conf

RUN mkdir -p /service/application && chmod ug+rwx /service/application
WORKDIR /service/application

EXPOSE 9000

CMD ["php-fpm", "-F"]