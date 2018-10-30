require 'yaml'
if File.file?('./tools/vagrant/config.yml')
    conf = YAML.load_file('tools/vagrant/config.yml')
else
    conf = YAML.load_file('tools/vagrant/config.yml.dist')
end

module OS
    def OS.windows?
        (/cygwin|mswin|mingw|bccwin|wince|emx/ =~ RUBY_PLATFORM) != nil
    end
    def OS.mac?
        (/darwin/ =~ RUBY_PLATFORM) != nil
    end
end

hostnames = conf["hostnames"]
ip_address = conf["ip_address"]
cache_dir = conf["composer_cache_dir"]

Vagrant.configure("2") do |config|
    config.vm.box = "mediamonks/linux-docker"

    system("bash tools/vagrant/triggers.sh")

    config.trigger.after [:up, :resume] do |trigger|
        trigger.ruby do |env,machine|
            hostnames.each do |host|
                if OS.windows?
                    system('which sed')
                    system("powershell -Command \"Start-Process tools/vagrant/add-host.bat #{ip_address}, #{host} -verb RunAs\"")
                else
                    system("bash tools/vagrant/add-host.sh #{ip_address} #{host}")
                end
            end
            system("bash tools/vagrant/welcome.sh #{hostnames.first}")
        end
    end

    config.trigger.after [:suspend, :halt, :destroy] do |trigger|
        trigger.ruby do |env,machine|
            hostnames.each do |host|
                if OS.windows?
                    system('which sed')
                    system("powershell -Command \"Start-Process tools/vagrant/remove-host.bat #{ip_address} -verb RunAs\"")
                else
                    system("bash tools/vagrant/remove-host.sh #{ip_address}")
                end
            end
        end
    end

    config.vm.network "private_network", ip: ip_address
    config.vm.synced_folder "./", "/vagrant", type: "nfs"
    if cache_dir
        config.vm.synced_folder cache_dir, "/home/vagrant/composer-cache", type: "nfs"
    end

    config.vm.provision "shell", run: "always", inline: <<-SHELL
        cd /vagrant/tools/docker && \
        bash generateSSL.sh &> /dev/null && \
        docker-compose rm -f && \
        docker-compose up -d && \
        docker exec apache bash /var/www/html/tools/docker/init.sh
    SHELL
end