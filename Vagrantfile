# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.provider "virtualbox" do |v|
    v.name = "user_service"
    v.customize ["modifyvm", :id, "--cpuexecutioncap", "50"]
    v.memory = 4096
    v.cpus = 2
  end

  config.vm.box = "ubuntu/xenial64"

  # Configuring docker providers
  config.vm.provision :docker

  ## Docker compose provider https://github.com/leighmcculloch/vagrant-docker-compose
  config.vm.provision :docker_compose,
    yml: [
      "/vagrant/development/docker/docker-compose.yml"
    ],
    rebuild: true,
    run: "always"

  ## Setting a fixed IP address of the machine
  config.vm.network "private_network", ip: "192.100.100.101"

  config.vm.synced_folder "./service", "/service", :nfs => true, create: true

end
