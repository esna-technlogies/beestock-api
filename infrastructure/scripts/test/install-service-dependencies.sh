#!/bin/sh


chmod 777 -R ../../../service/application
docker exec -i user-service-php-fpm /bin/bash -c "composer install --no-progress"