#!/bin/bash
grep -q -F "$(echo "$(grep "DHCPACK(wlan0)" /var/log/syslog | tail -1 | awk -F " " '{print $7" "$8}')")" users.conf  || echo "$(grep "DHCPACK(wlan0)" /var/log/syslog | tail -1 | awk -F " " '{print $7" "$8}')" >> users.conf
