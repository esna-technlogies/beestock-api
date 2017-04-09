#!/bin/sh

docker exec --user www-data -i user-service-php-fpm /bin/bash -c "./vendor/bin/simple-phpunit"