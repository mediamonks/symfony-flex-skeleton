#!/usr/bin/env bash
#
# Composer install
#============================================
cd /var/www/source/symfony; composer install

#
# Setting folder permissions
#============================================
cd /var/www/source/symfony; chmod -R 777 var/log var/cache

#
# Configuring environments
# https://wiki.mediamonks.net/Hosting_Environment_Naming_Convention
#============================================
if [ -d /var/www/source/symfony/config/packages/local ]; then
   echo Environments are correct.
else
    cd /var/www/source/symfony/config/packages

    mkdir local
    sed -i 's/dev/local/g' /var/www/source/symfony/config/bootstrap.php

    if [ -d /var/www/source/symfony/config/packages/prod ]; then
      mv prod production
      sed -i 's/prod/production/g' /var/www/source/symfony/config/bootstrap.php
    fi

    [[ -d /var/www/source/symfony/config/packages/testing ]] || cp -r production testing

    sed -i 's/APP_ENV=dev/APP_ENV=local/g' /var/www/source/symfony/.env

    echo Environment configured.
fi

#
# Ready message
#============================================
echo Project is ready!