#!/usr/bin/env bash
if [ -z "$1" ]
  then
    echo "Please provide environment!"
else
    docker exec apache sh -c "rm -rf /var/www/html/source/symfony/var/cache/$1"
fi