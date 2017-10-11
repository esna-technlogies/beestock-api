#!/bin/bash

cd infrastructure/development/docker/

docker-compose up --build -d

cd ../../../

rm -R ./service/application/var/cache/*

chmod 777 -R ./service/application/var