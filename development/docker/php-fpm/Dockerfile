FROM almasry/php7.0-fpm


RUN echo '@testing http://nl.alpinelinux.org/alpine/edge/testing' >> /etc/apk/repositories && \
    echo '@community http://nl.alpinelinux.org/alpine/edge/community' >> /etc/apk/repositories && \
    # DEPENDENCIES #############################################################
    apk update && apk upgrade && apk --no-cache add bash git ca-certificates curl openssh


ADD ./build.sh /build.sh
RUN sh /build.sh && rm -fr /build.sh



RUN apk --update add \
        php7-xdebug \
        php7-mongodb@testing \
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

EXPOSE 9000

CMD ["php-fpm7", "-F"]