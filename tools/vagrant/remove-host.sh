#!/usr/bin/env bash
sudo -i <<EOF
    sed -i .bak -e '/'$1'/d' "/etc/hosts"
EOF