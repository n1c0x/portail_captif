<?php
function add_iptables($ip){
	$cmd_add_iptables = "sudo iptables -t nat -I PREROUTING -s ".$ip." -p tcp -j ACCEPT";
	shell_exec($cmd_add_iptables);
}

function HashPassword($password)
{
  mt_srand((double)microtime()*1000000);
  $salt = mhash_keygen_s2k(MHASH_SHA1, $password, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
  $hash = "{SSHA}".base64_encode(mhash(MHASH_SHA1, $password.$salt).$salt);
  return $hash;
}

function format_id_to_dn($login){
	return "uid=".$login.",ou=people,dc=nodomain";
}

function verify_login($login,$pass){
	$ds=ldap_connect("localhost");
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	if($r = ldap_bind($ds,$login,$pass)){
		return true;
	}else{
		return false;
	}
	ldap_close($ds);
}

function test_input($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;	
}
?>
