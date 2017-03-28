#!/bin/sh


cp -R ../../../service ../../development/docker/php-fpm
docker exec -i user-service-php-fpm /bin/bash -c "composer install --no-progress"