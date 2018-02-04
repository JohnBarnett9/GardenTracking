<?php 
	require_once("../private/initialize.php");
	require_once("../private/isUserLoggedInIsUserValid.php");
	
	
	
	//echo "server https = " . $_SERVER["HTTPS"] . "<br>";
	// create a new cURL resource
	/*
	https://stackoverflow.com/questions/6382539/call-to-undefined-function-curl-init
	http://php.net/manual/en/function.curl-init.php
	http://php.net/manual/en/function.curl-setopt.php#110457
	https://stackoverflow.com/questions/3865143/what-do-i-have-to-code-to-use-https
	
	
	$ch = curl_init();	
	*/
/* 
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


if(empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
	//echo "not https<br>";
}
else{
	echo "yes https<br>";
}
*/

	
?>

<!DOCTYPE html>

<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; 
		any other head content must come *after* these tags -->
		
		<script src="/John/jquery-3.2.0.js"></script>
		<script src="/John/jquery-ui.js"></script>
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
	</head>
	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("mainMenu.php");
		?>
		<h1>Welcome to Garden Tracking!</h1>
		<div id="divout" >
		</div>
		
		<div id="seedTable">
		</div>
		
		<div id="typeAndTag">
		</div>
	</body>
</html>
