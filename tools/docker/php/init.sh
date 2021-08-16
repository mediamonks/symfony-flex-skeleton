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
if grep -q APP_ENV=dev "/var/www/source/symfony/.env"; then
  sed -i 's/APP_ENV=dev/APP_ENV=local/' /var/www/source/symfony/.env
fi

if [ -d /var/www/source/symfony/config/packages/local ]; then
   echo Environments are correct.
else
    cd /var/www/source/symfony/config/packages

    if [ -d /var/www/source/symfony/config/packages/dev ]; then
      mv dev local
    fi

    if [ -d /var/www/source/symfony/config/packages/prod ]; then
      mv prod production
    fi
fi

#
# Ready message
#============================================
echo Project is ready!