#!/bin/sh


docker exec -i user-service-php-fpm /bin/sh -c "./vendor/bin/simple-phpunit"