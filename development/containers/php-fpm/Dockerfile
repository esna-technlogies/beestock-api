FROM almasry/php7.0-fpm

RUN apk --update add \
        php7-xdebug \
    && rm -rf /var/cache/apk/*

COPY php.ini /etc/php7/conf.d/50-setting.ini

COPY php-fpm.conf /etc/php7/php-fpm.conf

COPY xdebug.ini /etc/php7/conf.d/xdebug.ini

RUN HOST_IP="$(/sbin/ip route|awk '/default/ { print $3 }')" \
    && sed -i "$ a\xdebug.remote_host=192.168.99.100" /etc/php7/conf.d/xdebug.ini

# Install phpunit
RUN curl -sSL https://phar.phpunit.de/phpunit.phar -o phpunit.phar \
    && chmod +x phpunit.phar \
    && mv phpunit.phar /usr/local/bin/phpunit

RUN apk update && apk add ca-certificates && update-ca-certificates && apk add openssl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/html

RUN chown www-data:www-data -R  /var/www/html

RUN chmod 777 -R  /var/www/html

EXPOSE 9000

CMD ["php-fpm7", "-F"]