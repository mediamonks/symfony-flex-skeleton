require 'yaml'

if File.file?('./tools/vagrant/config.yml')
    conf = YAML.load_file('tools/vagrant/config.yml')
else
    conf = YAML.load_file('tools/vagrant/config.yml.dist')
end

cache_dir = conf["composer_cache_dir"]
hostnames = conf["hostnames"]
ip_address = conf["ip_address"]

Vagrant.configure("2") do |config|
    config.vm.box = "mediamonks/linux-docker"

    unless Vagrant.has_plugin?("vagrant-triggers")
      raise "Please install vagrant-triggers plugin! run 'vagrant plugin install vagrant-triggers'"
    end

    config.trigger.after [:up, :resume] do
        if Vagrant::Util::Platform.windows? then
            run "which sed"
            hostnames.each do |host|
                system("powershell -Command \"Start-Process tools/vagrant/add-host.bat #{ip_address}, #{host} -verb RunAs\"")
            end
        else
            hostnames.each do |host|
                system("bash tools/vagrant/add-host.sh #{ip_address} #{host}")
            end
        end
    end

    config.trigger.after [:suspend, :halt, :destroy] do
        if Vagrant::Util::Platform.windows? then
            run "which sed"
            system("powershell -Command \"Start-Process tools/vagrant/remove-host.bat #{ip_address} -verb RunAs\"")
        else
            system("bash tools/vagrant/remove-host.sh #{ip_address}")
        end
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
end