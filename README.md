Symfony template micro-service 
=============================
   
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat-square)](https://php.net/)


> This is an archetype PHP microservice that can be used for running and building symfony-based stand-alone web services. This project follows 
a number of the best practices of designing microservices and PHP standards but is not a one-size-fits tool for all php projects. 

This template or archetype can be used to quickly bootstrap a PHP project without a lot of infrastructure hassle, and it utilizes :

* PHP 7.0
* Symfony 3.2.x project 
* Dockerized containers that are AWS ready
* HATEOAS API  

The microservice template comes with 2  separate yet dependent docker images :
    
* nginx 
* PHP7.0-fpm

## Project Structure   
    
    
Development Environment  
=========================

## Development prerequisite

To get your hands dirty with the code, please install  :

- VirtualBox (the current version is tested with VirtualBox 5.1.14) :  https://virtualbox.org 

- Vagrant (the current version is tested with Vagrant 1.9.1) : https://vagrantup.com

- Docker  


## Getting started  

- Clone the microservice template into your local work directory 
  
        git clone git@github.com:almasry/symfony-template-microservice.git
        
        cd symfony-template-microservice 

- Install the necessary vagrant plugins to enable docker for this vagrant box 

        vagrant plugin install vagrant-docker-compose
        

## Preparing the docker Environment   

- Create a docker machine 

        docker-machine create --driver virtualbox default
    
    Make sure the mahine is created 
    
        docker-machine ls
        
        
        # You are supposed to get a result similar to the following L
        NAME      ACTIVE   DRIVER       STATE     URL                         SWARM   DOCKER    ERRORS
        default   -        virtualbox   Running   tcp://192.168.99.100:2376           v1.13.1
        
    Check the machine environment variables (you will need them later to configure your IDE) 
    
        docker-machine env default
        
     Run this command to configure your shell:
     
        eval "$(docker-machine env default)"
        
     Start the docker compose to build all the images and containers : 
     
        cd development/containers
        
        composer up 

    
## Working with the vagrant box 

- Provision and boot the machine :
        
        vagrant up

- To connect with the vagrant box via SSH at any time:

        vagrant ssh
    
- To get out of the vagrant box, you can use any of : logout or exit linux commands.
    
- To shutdown the vagrant machine (highly recommended every time you'e done with the development and when you'e about to shutdown 
    or restart your laptop or PC)

        vagrant halt 



## Preparing the web server 

- In your development/containers/nginx/site.conf specify the server_name directive and other configuration of the web server.

- In your local /etc/hosts  file, add the domain names mapping to the IP address of the Vagrant Box :
 
        sudo vim /etc/hosts 
        
    add the following record :
        
        192.100.100.100  sample-micro-service.dev 
        
    You may change the vale of the IP address 192.100.100.100 to any other value that doesn't conflict with other devices on your local network.
     
    If you change it, you should also change the IP set for the vagrant box in the Vagrantfile :
     
        config.vm.network "private_network", ip: "192.100.100.100"
        
        
-    Visit the domain name you specified in the previous line (in the /etc/hosts) to browse the micro-service you'e developing.  


## Installing dependencies 

-    Install composer dependencies :
     
            docker exec -it php_fpm /bin/sh -c "cd /var/www/html/application && composer install --prefer-dist"


## Testing the microservice   

- Unit testing: Running the unit tests can be as simple as : 

        docker exec -it php_fpm /bin/sh -c "cd /var/www/html/application  && ./vendor/bin/simple-phpunit"

    This will run the unit tests in the ./application/tests directory.
    
    For further tweaks please check the [unit testing guide for symfony](http://symfony.com/doc/current/create_framework/unit_testing.html) and the tweaks of [running PHPunit tests with Symfony 3.2](http://symfony.com/blog/how-to-solve-phpunit-issues-in-symfony-3-2-applications) 
    
    
- Functional testing: To be added.

- Integration testing: To be added.