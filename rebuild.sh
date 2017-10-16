#!/bin/bash

cd infrastructure/development/docker/

docker-compose down

docker stop $(docker ps -aq)

docker rm $(docker ps -aq)

docker rmi $(docker images -aq)

docker-compose up --build -d 

cd ../../../

sudo rm -R ./service/application/var/cache/*

sudo chmod -R  777 ./service/application/var/

docker exec -it beesstock-php-fpm /bin/sh -c "composer install --prefer-dist"

sudo chmod 777 -R ./service/application/var