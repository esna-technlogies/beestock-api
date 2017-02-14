FROM almasry/php7.0-fpm

RUN apk --update add \
        php7-xdebug \
    && rm -rf /var/cache/apk/*

COPY php.ini /etc/php7/conf.d/50-setting.ini
COPY php-fpm.conf /etc/php7/php-fpm.conf

# ensure www-data user exists
RUN set -x \
	&& addgroup -g 82 -S www-data \
	&& adduser -u 82 -D -S -G www-data www-data

EXPOSE 9000

CMD ["php-fpm7", "-F"]
