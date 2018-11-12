#!/usr/bin/env bash
sh ./generateSSL.sh
cd /var/www/source/symfony
composer install
chmod -R 777 var/log var/cache