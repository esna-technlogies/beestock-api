#!/bin/bash

cd infrastructure/development/docker/

docker-compose down

docker-compose up --build -d 
