Symfony template micro-service 
=============================

This is a template for running and managing symfony based micro-service.  



Getting started with development 
--------------------------------

### Development Requirements

To get your hands dirty with the code :

- Install VirtualBox (the current version is tested with VirtualBox 5.1.14) :  https://virtualbox.org 

- Install Vagrant (the current version is tested with Vagrant 1.9.1) : https://vagrantup.com


### Getting started 

- To runt the machine, clone the microservice template into your local work directory 
  
    git clone git@github.com:almasry/symfony-template-microservice.git
    
        cd symfony-template-microservice 

- Install the necessary vagrant lugins 

        vagrant plugin install vagrant-docker-compose
        
        
- Provision the machine :

        vagrant up
    

- Connect with the vagrant box via SSH  :

        vagrant ssh


- To get out of the vagrant box, you may use : logout or exit command.

- To shutdown the vagrant machine (recommended every time you'e done with the development and when you'e about to shutdown 
    or restart your device)

        vagrant halt 



- In your local /etc/hosts  file, add the domain names mapping to the IP address of the Vagrant Box :
 
        sudo vim /etc/hosts 
        
    add the following record :
        
        192.100.100.100  templat-micro-service.dev 
        
    You may change the vale of the IP address 192.100.100.100 to any other value that doesn' conflict with other devices on your local network.
     
    If you change it, you should also change the IP set for the vagrant box in the Vagrantfile :
     
        config.vm.network "private_network", ip: "192.100.100.100"
        
-    Visit the domain name you specified in the previous line (in the /etc/hosts) to browse the micro-service you'e developing.       