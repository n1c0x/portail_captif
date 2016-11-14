#!/bin/bash

sudo iptables -n -t nat -L PREROUTING --line-number

#<?php
#$ip = $_SERVER['REMOTE_ADDR'];
#function delete_iptables($ip){
#	$cmd_list_iptables = "sudo iptables -n -t nat -L PREROUTING --line-number";
#	exec($cmd_list_iptables,$output);
#
#	foreach($output as $value){
#		if(strpos($value,$ip) !== false){
#			if(strpos($value,"80") !== false){
#				$cmd_iptables_http = "sudo iptables -t nat -D PREROUTING $value[0]";
#			}elseif(strpos($value,"443") !== false){
#				$cmd_iptables_https = "sudo iptables -t nat -D PREROUTING $value[0]";
#			}
#		shell_exec($cmd_iptables_http);
#		shell_exec($cmd_iptables_http);
#		}else{ 
#			echo "nope<br>";
#		}
#		echo $value."<br>";
#	}
#}
#echo $ip;
#?>
