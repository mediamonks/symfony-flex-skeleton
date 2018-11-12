#!/usr/bin/env bash
if [ -z "$1" ]
  then
    echo "Please provide environment!"
else
    docker exec php bash -c "rm -rf var/cache/$1"
fi