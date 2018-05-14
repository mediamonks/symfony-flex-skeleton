#!/usr/bin/env bash
docker exec apache sh -c "cd /var/www/html/source/symfony && php bin/console $1 $2 $3 $4 $5"