#!/bin/bash

cd infrastructure/development/docker/

docker-compose down

docker-compose up --build -d 

cd ../../../

sudo rm -R ./service/application/var/cache/*

sudo chmod 777 -R ./service/application/var