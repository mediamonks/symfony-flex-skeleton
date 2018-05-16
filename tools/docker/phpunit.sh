#!/usr/bin/env bash
docker exec apache sh -c "cd /var/www/html/source/symfony && bin/phpunit $1 $2 $3 $4 $5"
