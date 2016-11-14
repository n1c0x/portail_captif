#!/bin/bash

# Script to delete users from the ldap after their leaving

cur_timestamp=$(date +%Y%m%d%H%M%S)
tmp_file="/tmp/desc_ts"

ldapsearch -xLLL -b "dc=nodomain" uid=* description | grep description | awk -F " " '{print $2}' > $tmp_file

while read line
	do echo $cur_timestamp : $line
	if [ $cur_timestamp -gt $line ]
	then
		echo Delete user
	fi
done < $tmp_file
