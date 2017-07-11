#!/usr/bin/env bash
cd /app/tools/docker/
bash generateSSL.sh
docker-compose up -d