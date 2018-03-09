#!/usr/bin/env bash
chmod 600 /root/.ssh/id_rsa
chmod 600 /root/.ssh/id_rsa.pub
ssh-keyscan -H git.assembla.com  >> /root/.ssh/known_hosts
cd /var/www/html/source/symfony && composer install --no-progress
