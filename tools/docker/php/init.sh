#!/usr/bin/env bash
#
# Composer install
#============================================
cd /var/www/source/symfony; composer install

#
# Symfony CLI
#============================================
symfony self-update

#
# Setting folder permissions
#============================================
cd /var/www/source/symfony; chmod -R 777 var/log var/cache

#
# Ready message
#============================================
echo Project is ready!