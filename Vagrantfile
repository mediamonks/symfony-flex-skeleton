require 'yaml'

if File.file?('./tools/vagrant/config.yml')
    conf = YAML.load_file('tools/vagrant/config.yml')
else
    conf = YAML.load_file('tools/vagrant/config.yml.dist')
end

cache_dir = conf["composer_cache_dir"]
hostname = conf["hostname"]
ip_address = conf["ip_address"]

Vagrant.configure("2") do |config|
    config.vm.box = "mediamonks/ubuntu16-docker"

	config.trigger.before :up do
        run "bash ./tools/vagrant/hostupdate-up.sh #{ip_address} #{hostname}"
    end
    config.trigger.before :resume do
        run "bash ./tools/vagrant/hostupdate-up.sh #{ip_address} #{hostname}"
    end
    config.trigger.before :reload do
        run "bash ./tools/vagrant/hostupdate-down.sh #{ip_address}"
    end
    config.trigger.after :reload do
        run "bash ./tools/vagrant/hostupdate-up.sh #{ip_address} #{hostname}"
    end
    config.trigger.before :suspend do
        run "bash ./tools/vagrant/hostupdate-down.sh #{ip_address}"
    end
    config.trigger.before :halt do
        run "bash ./tools/vagrant/hostupdate-down.sh #{ip_address}"
    end
    config.trigger.before :destroy do
        run "bash ./tools/vagrant/hostupdate-down.sh #{ip_address}"
    end

	config.vm.network "private_network", ip: ip_address
	config.vm.synced_folder "./", "/app", type: "nfs"
    if cache_dir
        config.vm.synced_folder cache_dir, "/composer-cache", type: "nfs"
    end

    config.vm.provision "shell", path: "tools/vagrant/docker-compose.sh"
    config.vm.provision "file", source: "tools/vagrant/id_rsa.pub", destination: "~/.ssh/id_rsa.pub"
    config.vm.provision "file", source: "tools/vagrant/id_rsa", destination: "~/.ssh/id_rsa"
    config.vm.provision "shell", path: "tools/vagrant/provision_root.sh"
    config.vm.provision "shell", path: "tools/vagrant/provision.sh", privileged: false
    config.vm.provision "shell", path: "tools/vagrant/init.sh"

	config.vm.provider "virtualbox" do |v|
	    v.customize [ "modifyvm", :id, "--uartmode1", "disconnected" ]
	end
end