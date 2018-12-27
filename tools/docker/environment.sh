#!/usr/bin/env bash
if [ -d /var/www/source/symfony/config/packages/local ]; then
   echo Environments are ready.
else
    cd /var/www/source/symfony/config/packages
    mv dev local
    mkdir development
    mkdir testing
    mkdir acceptance
    mkdir production

    sed -i 's/APP_ENV=dev/APP_ENV=local/g' /var/www/source/symfony/.env
    sed -i 's/dev/local/g' /var/www/source/symfony/config/bootstrap.php
    sed -i 's/prod/production/g' /var/www/source/symfony/config/bootstrap.php
fi