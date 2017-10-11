#!/bin/bash

cd infrastructure/development/docker/

docker-compose down

docker-compose up --build -d 

rm -R ./service/application/var/cache/*

chmod 777 -R ./service/application/var