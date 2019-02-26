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
#============================================
if [ -d /var/www/source/symfony/config/packages/local ]; then
   echo Environments are correct.
else
    cd /var/www/source/symfony/config/packages
    mv dev local

    sed -i 's/APP_ENV=dev/APP_ENV=local/g' /var/www/source/symfony/.env
    sed -i 's/dev/local/g' /var/www/source/symfony/config/bootstrap.php
    sed -i 's/prod/production/g' /var/www/source/symfony/config/bootstrap.php

    echo Environment dev changed to local and renamed in bootstrap.php
fi

#
# Ready message
#============================================
echo Project is ready!