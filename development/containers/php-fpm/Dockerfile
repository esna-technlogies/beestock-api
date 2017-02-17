FROM almasry/php7.0-fpm

RUN apk --update add \
        php7-xdebug \
    && rm -rf /var/cache/apk/*

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

RUN echo 'alias symfony="php bin/console"' >> ~/.bashrc

EXPOSE 9000

CMD ["php-fpm7", "-F"]