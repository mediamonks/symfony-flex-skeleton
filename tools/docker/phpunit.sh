#!/usr/bin/env bash
docker exec php bash -c "vendor/bin/simple-phpunit $1 $2 $3 $4 $5"
