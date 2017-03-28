#!/bin/sh

## setting mink base_url to run the functional tests

docker exec -i user-service-php-fpm /bin/sh -c "echo $BEHAT_PARAMS"
docker exec -i user-service-php-fpm /bin/sh -c "echo ZZZZZZ"
docker exec -i user-service-php-fpm /bin/sh -c ./vendor/bin/behat --profile default

