<?php
define("LDAP_ADMIN_PW","ldap");
define("LDAP_ADMIN_DN","cn=admin,dc=nodomain");
define("LDAP_BASE_DN","ou=people,dc=nodomain");
define("ROOM_LEAVING_TIME","220000"); # define the last leaving hour of the room


#function HashPassword($password)
#{
#  mt_srand((double)microtime()*1000000);
#  $salt = mhash_keygen_s2k(MHASH_SHA1, $password, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
#  $hash = "{SSHA}".base64_encode(mhash(MHASH_SHA1, $password.$salt).$salt);
#  return $hash;
#}

function add_in_file(){
	$cmd_add_in_file = "sudo /var/www/html/portail/add_user.sh";
	shell_exec($cmd_add_in_file);
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

function add_iptables($ip){
# ajoute la regle iptables associee a l'adresse IP donnee en argument
	$cmd_add_iptables = "sudo iptables -t nat -I PREROUTING -s ".$ip." -p tcp -j ACCEPT";
	shell_exec($cmd_add_iptables);
}

function delete_iptables($ip){
# supprime la regle iptables associee a l'adresse IP donnee en argument
	$cmd_iptables_http = "sudo iptables -t nat -D PREROUTING -s $ip -p tcp -j ACCEPT";
	$cmd_write_temp_file = "echo ".$cmd_iptables_http." > /tmp/iptables";
	$cmd_delete_temp_file = "rm /tmp/iptables";
	exec($cmd_write_temp_file);
	$cmd_at = "sudo at -M now + 24 hour < /tmp/iptables";
	echo $cmd_at."<br>";
	exec($cmd_at);
	exec($cmd_delete_temp_file);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(isset($_POST["id"])){$id = test_input($_POST["id"]);}
	if(isset($_POST["pw"])){$pw = test_input($_POST["pw"]);}
}


if (verify_login(format_id_to_dn($id),$pw)) { 
	$ip = $_SERVER['REMOTE_ADDR'];
	$arp =`arp -a $ip`;
	$mac = explode(" ",$arp);
#	echo "adresse IP: ".$ip."<br>";
#	echo "adresse MAC: ".$mac[3]."<br>";

	$ds=ldap_connect("localhost");
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
	$r = ldap_bind($ds,LDAP_ADMIN_DN,LDAP_ADMIN_PW);	

	$add_infos["macAddress"][0] = "$mac[3]";
	
	ldap_modify($ds,format_id_to_dn($id),$add_infos);
	ldap_close($ds);

	add_in_file();
	add_iptables($ip);
	delete_iptables($ip);

	header("Location: http://www.google.fr");

} else {
	echo '<h4>Impossible de vérfier les identifants. Veuillez réessayer. <a href="/">Retour au portail</a></h4>';
}

?>
