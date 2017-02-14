Symfony template micro-service 
=============================

## About this project  

This is a template PHP microservice that can be used for running and managing symfony-based stand-alone services. This project template follows 
a number of the best practices of designing microservices and PHP standards but is not a one-size-fits tool. 

This template or archetype can be used to quickly bootstrap a PHP project without a lot of infrastructure hassle, and it utilizes :

* PHP 7.0
* Symfony 3.2.x project 
* Dockerized containers that are AWS ready
* HATEOAS API  

The microservice template comes with 2  separate yet dependent docker images :
    
* nginx 
* PHP7.0-fpm
    

Project Structure   
=================
    

Development Environment  
=========================

## Development prerequisite

To get your hands dirty with the code, please install  :

- VirtualBox (the current version is tested with VirtualBox 5.1.14) :  https://virtualbox.org 

- Vagrant (the current version is tested with Vagrant 1.9.1) : https://vagrantup.com


## Getting started  

- Clone the microservice template into your local work directory 
  
        git clone git@github.com:almasry/symfony-template-microservice.git
        
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

- Unit testing: To be added.

- Functional testing: To be added.

- Integration testing: To be added.