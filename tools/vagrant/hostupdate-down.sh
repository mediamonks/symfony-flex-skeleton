#!/usr/bin/env bash
if [ -f "C:/Windows/System32/drivers/etc/hosts" ]
then
    cp C:/Windows/System32/drivers/etc/hosts C:/Windows/System32/drivers/etc/hosts_temp
	sed -i '/'$1'/d' C:/Windows/System32/drivers/etc/hosts_temp
	cp -f C:/Windows/System32/drivers/etc/hosts_temp C:/Windows/System32/drivers/etc/hosts
else
    cp /etc/hosts /etc/hosts_temp
	sed -i '/'$1'/d' /etc/hosts_temp
	cp -f /etc/hosts_temp /etc/hosts
fi