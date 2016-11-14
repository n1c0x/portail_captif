<?php
include "../func.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(isset($_POST["id"])){$id = test_input($_POST["id"]);}
	if(isset($_POST["pw"])){$pw = test_input($_POST["pw"]);}
}
if(verify_login(format_id_to_dn($id),$pw)){
	$cookie_name = "user";
	setcookie($cookie_name,"portal_admin");
}
header("Location:http://".$_SERVER['HTTP_HOST']."/admin/admin.php");
?>
