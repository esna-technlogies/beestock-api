#!/bin/sh

## setting mink base_url to run the functional tests

docker exec -i user-service-php-fpm /bin/sh -c "export BEHAT_PARAMS='{\"extensions\":{\"Behat\\MinkExtension\":{\"base_url\":\"http://user-service.dev/app_test.php/\"}}}' "

docker exec -i user-service-php-fpm /bin/sh -c "echo $BEHAT_PARAMS"
docker exec -i user-service-php-fpm /bin/sh -c ./vendor/bin/behat --profile default

