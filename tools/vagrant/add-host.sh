#!/usr/bin/env bash
sudo -i <<EOF
    echo "$1 $2" >> "/etc/hosts"
EOF