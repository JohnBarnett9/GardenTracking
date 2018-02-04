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
		<script src="showNotesForSeedJavaScript.js"></script>
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
				<h3 style="font-size:20px; font-weight:normal;">Show All Notes For Seed</h3>			
				<div class="col-md-12">
					<h3>Existing Notes For Seed</h3>
					<div id="seedtypeseedname">
					</div>
					<div id="existingnotesforseed">	
					</div>
					<h3>Edit A Note</h3>
					<div id="editnoteform">
					</div>					
				</div>
				<div class="col-md-2">
				</div>
				<!--edit note button clicked-->
				<form id="editbuttonclickedform" action="cropController.php" method="POST">
					<input id="cropprimarykey" type="hidden" name="cropprimarykey" value="">
					<input id="noteprimarykey" type="hidden" name="noteprimarykey" value="">
					<input id="commandForCropController" type="hidden" name="commandForCropController" value="17">
				</form>
			</div><!--row-->
		</div><!--container-->
	</body>
</html>
