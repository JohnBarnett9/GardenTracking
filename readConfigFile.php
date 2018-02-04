<?php 

/*
read config file secret info into databaseConnction.php and databaseConnectionAdmin.php
*/
readConfigFile();

function readConfigFile(){
	$configContents = file_get_contents("config.txt", "r");
	$configContents = trim($configContents);
	$configArray = explode(",", $configContents);
	$servername = explode(":", $configArray[0]); //converts "servername:000.000.000.000"
	$username = explode(":", $configArray[1]); //converts "username:bob"
	$password = explode(":", $configArray[2]); //converts "password:thing"
	$salt = explode(":", $configArray[3]); //converts "salt:jqw9ruf9082q3jhrf8923..."
	
	$_SESSION['configServername'] = trim($servername[1]);
	$_SESSION['configUsername'] = trim($username[1]);
	$_SESSION['configPassword'] = trim($password[1]);
	$_SESSION['salt'] = trim($salt[1]);
}


?>