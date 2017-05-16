#!/usr/bin/env bash
cd /var/www/html/source/symfony && composer install --no-progress --ignore-platform-reqs && php bin/console assets:install --symlink