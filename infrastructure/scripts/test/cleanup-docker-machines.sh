#!/usr/bin/env bash

docker stop $(docker ps -a -q)
docker rm -f $(docker ps -aq )
docker rmi -f $(docker images -aq)