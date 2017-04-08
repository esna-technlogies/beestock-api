CommonServices \ UserServiceBundle 
==================================
   
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat)](https://php.net/)   [![Travis Build Status](https://travis-ci.org/almasry/user-microservice.svg?branch=master)](https://travis-ci.org/almasry/user-microservice)



> This is a user microservice that can be used for running and building symfony-based stand-alone web services. This project follows 
a number of the best practices of designing microservices and PHP standards but is not a one-size-fits tool for all php projects. 

This template or archetype can be used to quickly bootstrap a PHP project without a lot of infrastructure hassle, and it utilizes :

* PHP 7.1.x
* Symfony 3.2.x project 
* Dockerized containers that are AWS ready
* HATEOAS API  

The microservice template comes with 3 separate yet dependent docker images :
    
* nginx 
* PHP7.1.3-fpm
* mongodb

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
  
        git clone git@github.com:almasry/symfony-archetype-microservice.git
        
        cd symfony-template-microservice 

- Install the necessary vagrant plugins to enable docker for this vagrant box 

        vagrant plugin install vagrant-docker-compose
        

    
## Working with the vagrant box 

- Provision and boot the machine :
        
        vagrant up

- To connect with the vagrant box via SSH at any time:

        vagrant ssh
    
- To get out of the vagrant box, you can use any of : logout or exit linux commands.
    
- To shutdown the vagrant machine (highly recommended every time you'e done with the development and when you'e about to shutdown 
    or restart your laptop or PC)

        vagrant halt 

- In your development/docker/nginx/site.conf specify the server_name directive and other configuration of the web server.

- In your local /etc/hosts  file, add the domain names mapping to the IP address of the Vagrant Box :
 
        sudo vim /etc/hosts 
        
    add the following record :
        
        192.100.100.101  user-service.dev 
        
    You may change the vale of the IP address 192.168.99.101 any other value that doesn't conflict with other devices on your local network.
     
    If you change it, you should also change the IP set for the vagrant box in the Vagrantfile (if you use Vagrant):
     
        config.vm.network "private_network", ip: "192.100.100.101"
        
        
-    Visit the domain name you specified in the previous line (in the /etc/hosts) to browse the micro-service you'e developing.  


        

## Registering the cron-jobs  

- Add the cronjobs to your crontab :

            vagrant ssh 
            
            ## create cron logs file 
            touch  /home/ubuntu/cronrun
        
            ## Edit cron jobs 
            crontab -e 
            
- at the end of the cron file add the following line :

            * * * * * sudo docker exec -i user-service-php-cli /bin/bash -c "bin/console cron:run"  >> /home/ubuntu/cronrun 2>&1
        
- you can monitor the output of the crontab either through watching :

            tail -f /home/ubuntu/cronrun
            
            // or 
            
            tail -f /var/log/syslog

            
            
## Installing dependencies 

- Generate SSl certificates for the JWT authentication 

            cd service/ssl-keys/jwt
            
            openssl genrsa -out private.pem -aes256 4096
            
            openssl rsa -pubout -in  private.pem -out  public.pem
            

-    Install composer dependencies :
     
            vagrant ssh 
            
            docker exec -it user-service-php-fpm /bin/sh -c "cd /service/application && composer install --prefer-dist"


## Testing the microservice   

- Unit testing: Running the unit tests can be as simple as : 

        vagrant ssh 
            
        docker exec -it user-service-php-fpm /bin/sh -c "cd /var/www/html/application  && ./vendor/bin/simple-phpunit"

    This will run the unit tests in the ./application/tests directory.
    
    For further tweaks please check the [unit testing guide for symfony](http://symfony.com/doc/current/create_framework/unit_testing.html) and the tweaks of [running PHPunit tests with Symfony 3.2](http://symfony.com/blog/how-to-solve-phpunit-issues-in-symfony-3-2-applications) 
    
    
- Functional testing: 
    
                
        docker exec -it user-service-php-fpm /bin/sh -c "cd /var/www/html/application  && ./vendor/bin/simple-phpunit"


Before you run the Behat tests, you should first set the environment variables from inside the PHP machine:

        export BEHAT_PARAMS='{"extensions":{"Behat\\MinkExtension":{"base_url":"http://192.100.100.101/app_test.php/"}}}'

Forgetting to set the base_ur of mink through environment variables, will definitely fail all the tests. 
    
