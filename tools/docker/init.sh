#!/usr/bin/env bash
echo "Fixing SSH keys..."
chmod 600 /root/.ssh/id_rsa
chmod 600 /root/.ssh/id_rsa.pub
ssh-keyscan -H git.assembla.com  >> /root/.ssh/known_hosts\

echo "Installing Composer dependencies..."
cd /var/www/html/source/symfony && composer install --no-progress
