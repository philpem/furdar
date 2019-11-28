# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

    config.vm.box = "ubuntu/bionic64"

	config.vm.define "app" do |normal|

        normal.vm.box = "ubuntu/bionic64"

        normal.vm.network "forwarded_port", guest: 80, host: 8080
        normal.vm.network "forwarded_port", guest: 443, host: 8443
		normal.vm.network "forwarded_port", guest: 8025, host: 8025
		normal.vm.network "forwarded_port", guest: 8081, host: 8081

		normal.vm.synced_folder ".", "/vagrant",  :owner=> 'ubuntu', :group=>'users', :mount_options => ['dmode=777', 'fmode=777']

		normal.vm.provider "virtualbox" do |vb|
			# Display the VirtualBox GUI when booting the machine
			vb.gui = false

			# Customize the amount of memory on the VM:
			vb.memory = "1024"
		end

		normal.vm.provision :shell, path: "vagrant/app/bootstrap.sh"

	end

end
