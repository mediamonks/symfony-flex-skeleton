#!/usr/bin/env bash
docker exec apache sh -c "cd /var/www/html/source/symfony/ && composer install"