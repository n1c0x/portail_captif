<?php
define("LDAP_ADMIN_PW","ldap");
define("LDAP_ADMIN_DN","cn=admin,dc=nodomain");
define("LDAP_BASE_DN","ou=people,dc=nodomain");
define("ROOM_LEAVING_TIME","220000"); # define the last leaving hour of the room

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

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(isset($_POST["id"])){$id = test_input($_POST["id"]);}
	if(isset($_POST["pw"])){$pw = test_input($_POST["pw"]);}
	if(isset($_POST["new_name"])){$new_name = test_input($_POST["new_name"]);}
	$new_id = $_POST["new_id"];
	$new_pw = $_POST["new_pw"];
	if(isset($_POST["new_mobile"])){$new_mobile = test_input($_POST["new_mobile"]);}
	if(isset($_POST["new_mail"])){$new_mail = test_input($_POST["new_mail"]);}
	if(isset($_POST["new_timeout"])){$new_timeout = test_input($_POST["new_timeout"]);}
}


# Connection to the LDAP server (localhost)
$ds=ldap_connect("localhost");
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

if ($ds) { 
	$r = ldap_bind($ds,LDAP_ADMIN_DN,LDAP_ADMIN_PW);
	#$r=ldap_bind($ds);		# anonymous binding to the ldap
	
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Administration - Portail captif</title>
			<link rel="stylesheet" href="/css/w3.css">
			<link rel="stylesheet" href="/css/font-awesome.min.css">
		</head>
		<script>
			function RandomGenerator(id,type){
				var text = " ";
				var len = 12;
    		    var charset_user = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    		    var charset_pass = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-+=_?!@";
    			if(type == "user"){
					for( var i=0; i < len; i++ )
			        	text += charset_user.charAt(Math.floor(Math.random() * charset_user.length));	
				}else if(type == "pass"){
					for( var i=0; i < len; i++ )
			        	text += charset_pass.charAt(Math.floor(Math.random() * charset_pass.length));	
				}
				document.getElementById(id).value = text;
			}
		</script>
		<body>
			<div class="w3-row">
				<div class="w3-col m2"><p></p></div>
				<div class="w3-col m8 w3-container">
					<div class="w3-card-4">
<?php
	
	if(!isset($_COOKIE["user"])) {
?>
		<div class="w3-panel w3-red">
			<div class="w3-center">
				<h2>Authentification &eacute;chou&eacute;e</h2>
				<h2><a href="/admin">Retour &agrave; l'accueil</a></h2>
			</div>
		</div>
<?php
	}else{
?>
		<div class="w3-panel w3-green">
			<div class="w3-center">
				<h2>Interface d'administration</h2>
			</div>
		</div>
		<div class="w3-panel">
			<a class="w3-btn w3-green w3-large" href="/" onclick="javascript:event.target.port=3000" >Supervision</a>
			<a class="w3-btn w3-green w3-large" href="/?page=TopHosts" onclick="javascript:event.target.port=3000" >Clients connectés</a>
		</div>

		
		<div class="w3-panel">
			<h3>Ajouter un nouvel utilisateur</h3>
			<form action="<?php echo test_input($_SERVER["PHP_SELF"]); ?>" method="POST">
				<div class="w3-row">
					<div class="w3-col m6 w3-panel">
						<p>
							<input class="w3-input w3-border" type="text" name="new_name">
							<label class="w3-label w3-text-green">Nom du client</label>
						</p>
					</div> <!-- end of w3-panel -->
					<div class="w3-col m3 w3-panel">
					<p>
						<input class="w3-input w3-border" type="text" name="new_mobile">
						<label class="w3-label w3-text-green">No de t&eacute;l&eacute;phone</label>
					</p>
					</div> <!-- end of w3-panel -->
					<div class="w3-col m3 w3-panel">
					<p>
						<input class="w3-input w3-border" type="email" name="new_mail">
						<label class="w3-label w3-text-green">Adresse mail</label>
					</p>
					</div> <!-- end of w3-panel -->



				</div>
				<div class="w3-row">
					<div class="w3-col m3 w3-panel">
						<p>
							<input class="w3-input w3-border" type="text" name="new_id" id="new_id">
							<label class="w3-label w3-text-green">Identifiant</label>
						</p>
						<p>
							<a href=# class="w3-btn w3-orange" onclick=RandomGenerator("new_id","user")>
								G&eacute;n&eacute;rer identifiant
							</a>
						</p>
					</div> <!-- end of w3-panel -->
					<div class="w3-col m3 w3-panel">
						<p>
							<input class="w3-input w3-border" type="text" name="new_pw" id="new_pw">
							<label class="w3-label w3-text-green">Mot de passe</label>
						</p>
						<p>
							<a class="w3-btn w3-orange" onclick=RandomGenerator("new_pw","pass")>
								G&eacute;n&eacute;rer mot de passe
							</a>
						</p>
					</div> <!-- end of w3-panel -->
					<div class="w3-col m3 w3-panel">
						<p>
							<input class="w3-input w3-border" type="date" name="new_timeout" min="<?php echo date("Y-m-d");?>">
							<label class="w3-label w3-text-green">Validit&eacute;</label>
						</p>
					</div> <!-- end of w3-panel -->
				</div> <!-- end of w3-row -->
				<p>
					<input class="w3-btn w3-hover-green" type="submit" value="Enregistrer">
				</p>
			</form>
			
		</div> <!-- end of w3-panel -->
<?php

		$add_infos["objectClass"][3] = "ieee802Device";
		$add_infos["objectClass"][2] = "inetOrgPerson";
		$add_infos["objectClass"][1] = "person";
		$add_infos["objectClass"][0] = "top";
		$add_infos["cn"] = $new_name;
		$add_infos["sn"] = $new_name;
		$add_infos["uid"] = $new_id;
		#$add_infos["userPassword"] = $new_pw;
		$add_infos["userPassword"] = HashPassword($new_pw);
		$add_infos["mobile"] = $new_mobile;
		$add_infos["mail"] = $new_mail;
		$new_timeout = str_replace("-","",$new_timeout);
		$new_timeout = $new_timeout.ROOM_LEAVING_TIME;
		$add_infos["description"] = $new_timeout;
		$add_infos["macAddress"] = "";

#		echo "<pre>";
#		var_dump($add_infos);
#		echo "</pre>";

		$r = ldap_add($ds,format_id_to_dn($new_id),$add_infos);

?>
		<div class="w3-panel">
		</div> <!-- end of w3-panel -->
<?php
		$search_array = array("uid","cn","mobile","mail","macAddress");
#		$search_array = array("member");
		$sr=ldap_search($ds,LDAP_BASE_DN,"uid=*",$search_array);
?>
		<hr>
		<div class="w3-panel">
			<h3>Liste des utilisateurs</h3>
<?php

		echo 'Il y a '.ldap_count_entries($ds,$sr).' utilisateurs';

		$info = ldap_get_entries($ds, $sr);
?>
		<p>
		<table class="w3-table w3-triped w3-border w3-bordered">
		<tr>
			<th>Nom</th>
			<th>Identifiant</th>
			<th>Adresse mail</th>
			<th>No de t&eacute;l&eacute;phone</th>
		</tr>
<?php	for ($i=0; $i<$info["count"]; $i++) { 
		
#		echo "<pre>";
#		var_dump($info);
#		echo "</pre>";
		
		if($info[$i]['uid'][0] != "portal_admin"){
?>
			<tr>
<!--				<td><a href="/lua/mac_details.lua?mac=<?php echo $info[$i]['macaddress'][0]; ?>" onclick="javascript:event.target.port=3000" >
					<?php echo $info[$i]['cn'][0] ?></a></td>-->
				<td><?php echo $info[$i]['cn'][0] ?></td>
				<td><strong><?php echo $info[$i]['uid'][0] ?></strong></td>
				<td><?php echo $info[$i]['mail'][0] ?></td>
				<td><?php echo $info[$i]['mobile'][0] ?></td>
			</tr>
<?php	
		}
		}?>

			</table></p>
<?php
	} ?>
					</div> <!-- end of w3-panel -->
				</div> <!-- end of w3-card -->
			</div> <!-- end of w3-container -->
			<div class="w3-col m2"><p></p></div>
		</div> <!-- end of .row -->
	</body>
</html>
	<?php
	ldap_close($ds);

} else {
	echo '<h4>Impossible de se connecter au serveur LDAP.</h4>';
}

?>
