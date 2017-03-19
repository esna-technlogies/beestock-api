Docker User Guideline 
=====================

This is a guide for developers who are new to Docker and a cheat sheet on how to use docker with this project.

## Running Docker compose for this project  

### Starting the docker containers 

The following command will take care of starting the project and running all the instructions in the docker-compose.yml file

    docker-compose up

### Stopping the docker containers   

To stop the containers :  

    docker-compose down 

### Killing all running containers    

    docker-compose kill 

### Delete all running containers and their images (clean up the mess )

    docker rm -f $(docker ps -aq )  && docker rmi -f $(docker images -aq)
    
## General guideline and cheat-sheet for using docker  

### Build an image for the first time (given you have a dockerfile of the image)
    docker build -t php-symfony .

### list the currently built docker images
    docker images

### list all the running container
    docker ps -a

### Run the built image  (port external:internal)
    docker run -d -p 80:9000 --name php-symfony  -v code:/var/www/html  php-symfony

### check and make sure port is open
    docker port php-symfony 9000

### inspecting details of a docker container
    docker inspect php-symfony

### connect over SSH with the docker (where f78f15080e6b is the Container ID)
    docker exec -ti f78f15080e6b /bin/sh


### Remove a running docker container (where php-symfony is the container name)
    docker rm -f php-symfony

### Stop all the running docker machines
    docker stop $(docker ps -a -q)

### Remove all docker machines
    docker rm $(docker ps -a -q)

