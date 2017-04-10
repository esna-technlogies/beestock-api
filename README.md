CommonServices \ UserServiceBundle 
==================================
   
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat)](https://php.net/)   [![Travis Build Status](https://travis-ci.org/almasry/user-microservice.svg?branch=master)](https://travis-ci.org/almasry/user-microservice)



> This is a user microservice that can be used for running and building symfony-based stand-alone user management service. This project follows 
a number of the best practices of designing microservices and PHP standards but is not a solution to all . 

This template or archetype can be used to quickly bootstrap a PHP project without a lot of infrastructure hassle, and it utilizes :

* PHP 7.1
* Symfony 3.2.x application 
* HATEOAS REST, well documented API  
* Dockerized development and testing infrastructure


The service utilizes 5 separate yet dependent docker images :
    
* nginx 
* mongodb
* rabbitmq
* PHP-fpm (v. 7.1.x)
* PHP-cli (v. 7.1.x)

## Project Structure   
    
    
Development Environment  
=========================

## Development prerequisite

To get your hands dirty with the code, please install  :

- VirtualBox (the current version is tested with VirtualBox 5.1) :  https://virtualbox.org 

- Vagrant (the current version is tested with Vagrant 1.9) : https://vagrantup.com


## Getting started  

- Clone the microservice template into your local work directory 
  
        git clone git@github.com:almasry/user-service.git
        
        cd user-service 

- Install the dependencies of vagrant plugins to enable docker for this vagrant box 

        vagrant plugin install vagrant-docker-compose

    
## Setting up the service for development  

- Provision, boot the machine and ssh into it:
        
        vagrant up && vagrant ssh
    
- Set the server_name directive in the infrastructure/development/docker/nginx/site.conf and modify other server configuration (if needed).

- On your machine, add the hostname (service domain) to the /etc/hosts file to the IP address of the Vagrant Box :
 
        sudo vim /etc/hosts 
        
    add append following line :
        
        192.100.100.101  user-service.dev 
        
- Changing the busy box IP address :     
    
    You may change the vale of the IP address of the busy box 192.168.99.101 to any other value that doesn't conflict with other devices on your local network. To do that, edit the Vagrant file : 
     
        config.vm.network "private_network", ip: "192.100.100.101"

    If you change IP address, you should accordingly change it in your /etc/hosts file.
        
-    In your browser, hit https://user-service.dev or .  


## Installing dependencies 

- Generate SSl certificates for the JWT authentication 

        cd service/ssl-keys/jwt

        openssl genrsa -out private.pem -aes256 4096
            
        openssl rsa -pubout -in  private.pem -out  public.pem
            

-    Install composer dependencies :
     
            vagrant ssh 
            
            docker exec -it user-service-php-fpm /bin/sh -c "composer install --prefer-dist"


## Testing the microservice   

- Unit testing: running the unit tests can be as simple as : 

        vagrant ssh 
            
        docker exec -it user-service-php-fpm /bin/sh -c "./vendor/bin/simple-phpunit"

    This will run the unit tests in the  service/application/tests directory.
    
    For further tweaks please check the [unit testing guide for symfony](http://symfony.com/doc/current/create_framework/unit_testing.html) and the tweaks of [running PHPunit tests with Symfony 3.2](http://symfony.com/blog/how-to-solve-phpunit-issues-in-symfony-3-2-applications) 
    
    
- Functional testing: 
    
                
        docker exec -it user-service-php-fpm /bin/sh -c "./vendor/bin/behat"


The bhat functional tests are using Mink - without selenium - the base_url value is set through both the development and testing dockers (php-fpm) and the value is set by default to the http://user-service-nginx-server/app_test.php  since the tests are run internally across the docker machines.
    
