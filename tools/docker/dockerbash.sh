#!/usr/bin/env bash
if [ -z "$1" ]
  then
    echo "Please provide container_name!"
else
    docker exec -it $1 bash
fi