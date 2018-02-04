<?php 
	require_once("../private/initialize.php");
	require_once("../private/isUserLoggedInIsUserValid.php");
	
	/*
	only root can access this page 
	
	if($_SESSION['username'] != "root"){
		header("Location: ../filesForMainMenuPage/mainMenu.php");
	}
	*/
?>

<!DOCTYPE html>

<html>
	<head>
		<script src="/John/jquery-3.2.0.js"></script>
		<script src="/John/jquery-ui.js"></script>
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
		<script src="adminJavaScript.js"></script>
	</head>
	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("admin.php");
		?>
		<h1>Drop Tables Create Tables</h1>
		<button type="button" id="droptablescreatetables" class="btn btn-primary">Drop Tables Create Tables</button>
		<br>
		<br>
		
		<h1>Add Minimal Data</h1>
		<button type="button" id="addminimaldata" class="btn btn-primary">Add Minimal Data</button>
		<br>
		<br>
		<h1>Realistic Data Set</h1>
		<button type="button" id="realisticdataset" class="btn btn-primary">Realistic Data Set</button>
		<br>
		<br>
		
		<h1>Drop Tables Create Tables Output</h1>
		<div id="droptablescreatetablesoutput">
		</div>
		
		<h1>Add Minimal Data Output</h1>
		<div id="addminimaldataoutput">
		</div>
		
		<h1>Realistic Data Set Output</h1>
		<div id="realisticoutput">
		</div>
		
	</body>
</html>
