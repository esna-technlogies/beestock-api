#!/bin/sh

docker exec -i user-service-php-fpm /bin/sh -c "composer install --no-progress"