#!/usr/bin/env bash
chmod 600 ~/.ssh/id_rsa
chmod 600 ~/.ssh/id_rsa.pub
ssh-keyscan -H git.assembla.com  >> ~/.ssh/known_hosts

echo "alias dockerbash='docker exec -it apache bash'" >> ~/.bashrc
echo "alias dockerinit='docker exec apache bash /var/www/html/tools/docker/init.sh'" >> ~/.bashrc
source ~/.bashrc