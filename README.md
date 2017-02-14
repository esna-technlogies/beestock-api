Symfony template micro-service 
=============================

This is a template for running and managing symfony based micro-service.  



Development Environment  
-----------------------

### Development prerequisite

To get your hands dirty with the code :

- Install VirtualBox (the current version is tested with VirtualBox 5.1.14) :  https://virtualbox.org 

- Install Vagrant (the current version is tested with Vagrant 1.9.1) : https://vagrantup.com


### Getting started with development 

- Clone the microservice template into your local work directory 
  
    git clone git@github.com:almasry/symfony-template-microservice.git
        
        cd symfony-template-microservice 

- Install the necessary vagrant plugins to enable docker for this vagrant box 

        vagrant plugin install vagrant-docker-compose
        
    

- Working with the vagrant box :
        
    - Provision and boot the machine :
        
            vagrant up

    - To connect with the vagrant box via SSH at any time:

            vagrant ssh
    
    - To get out of the vagrant box, you can use any of : logout or exit linux commands.
    
    - To shutdown the vagrant machine (highly recommended every time you'e done with the development and when you'e about to shutdown 
    or restart your laptop or PC)

            vagrant halt 


- In your development/containers/nginx/site.conf specify the server_name directive and other configuration of the web server.

- In your local /etc/hosts  file, add the domain names mapping to the IP address of the Vagrant Box :
 
        sudo vim /etc/hosts 
        
    add the following record :
        
        192.100.100.100  sample-micro-service.dev 
        
    You may change the vale of the IP address 192.100.100.100 to any other value that doesn't conflict with other devices on your local network.
     
    If you change it, you should also change the IP set for the vagrant box in the Vagrantfile :
     
        config.vm.network "private_network", ip: "192.100.100.100"
        
-    Visit the domain name you specified in the previous line (in the /etc/hosts) to browse the micro-service you'e developing.       