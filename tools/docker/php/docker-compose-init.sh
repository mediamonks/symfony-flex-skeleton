#!/usr/bin/env bash

#
# Docker init script
#============================================
chmod +x *.sh

#
# Generate SSL
#============================================
if [ -f ssl.crt ]; then
   echo SSL Configuration already exists.
else
    HOST=skeleton.lcl
    IP=192.168.33.2
    COUNTRY=NL
    STATE=UT
    CITY=Hilversum
    ORGANIZATION=MediaMonks
    ORGANIZATION_UNIT=PHP
    EMAIL=info@$HOST

    (
    echo [req]
    echo default_bits = 2048
    echo prompt = no
    echo default_md = sha256
    echo x509_extensions = v3_req
    echo distinguished_name = dn
    echo [dn]
    echo C = $COUNTRY
    echo ST = $STATE
    echo L = $CITY
    echo O = $ORGANIZATION
    echo OU = $ORGANIZATION_UNIT
    echo emailAddress = $EMAIL
    echo CN = $HOST
    echo [v3_req]
    echo subjectAltName = @alt_names
    echo [alt_names]
    echo DNS.1 = $IP
    echo DNS.2 = $HOST
    )>ssl.cnf

    openssl req -new -x509 -newkey rsa:2048 -sha256 -nodes -keyout ssl.key -days 9000 -out ssl.crt -config ssl.cnf
fi

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
   echo Environments already in place.
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

    echo Environments created.
fi
