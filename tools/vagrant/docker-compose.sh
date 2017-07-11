#!/usr/bin/env bash
cd /app/tools/docker/
echo "Generating SSL Certificate..."
bash generateSSL.sh &> /dev/null
docker-compose up -d