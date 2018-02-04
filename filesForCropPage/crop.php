<?php 
	require_once("../private/initialize.php");
	require_once("../private/isUserLoggedInIsUserValid.php");
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
		<script src="cropJavaScript.js"></script>
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
	</head>
	<body style="background-color:powderblue;">
		<?php 
			
			require_once('../navBar.php');
			isActivePage("crop.php");
		?>
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<h3 style="font-size:20px; font-weight:normal;">Crop</h3>
					<fieldset id="foobar">
						<input type="radio" name="howtosort" value="bytype" id="radiotype">Type<br>
						<input type="radio" name="howtosort" value="bydate" id="radiodate">Date<br>
					</fieldset>
					<br>
					<br>
					<div id="filterlist">
					</div>
					<br>
					<br>
				</div>
				<div class="col-md-9">
					<div id="croplist">
						croplist
					</div>
				</div>
				<!--show notes button clicked
				if radio button type is selected, cropidtopass will not be set 
				if radio button date is selected, seedidtopass will not be set 
				command 9 goes to showNotesButtonClicked() in cropController-->
				<form id="shownotesform" action="cropController.php" method="POST">
					<input id="seedidtopass" type="hidden" name="seedid" value="0">
					<input id="cropidtopass" type="hidden" name="cropid" value="0">
					<input id="commandForCropController" type="hidden" name="commandForCropController" value="9">
				</form>
			</div><!--row-->
		</div><!--container-->
	</body>
</html>
