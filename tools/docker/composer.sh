#!/usr/bin/env bash
docker exec php bash -c "php -d memory_limit=-1 /usr/bin/composer $1 $2 $3 $4 $5"