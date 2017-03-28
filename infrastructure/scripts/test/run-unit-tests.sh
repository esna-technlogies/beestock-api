#!/bin/sh


docker exec -i user-service-php-fpm /bin/bash -c "./vendor/bin/simple-phpunit"