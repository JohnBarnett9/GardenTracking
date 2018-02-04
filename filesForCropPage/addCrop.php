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
		<script src="addCropJavaScript.js"></script>
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
	</head>
	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("crop.php");
		?>
		<h3 style="font-size:20px; font-weight:normal;">Add A Crop</h3>

		<div class="container">
			<div class="row">
					<form id="addcropform" class="form-horizontal" action="cropController.php" method="POST">
						<div class="form-group">
							<label class="control-label col-sm-2">Seed ID</label>
							<div class="col-sm-10">
								<input id="seedidfornewcropinput" name="" type="text" value="" list="dropdownlistseedtypes" readonly>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Crop Start Date</label>
							<div class="col-sm-10">
								<input id="cropstartdateinput" name="cropstartdate" type="datetime-local" value="" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Initial Note for Crop</label>
							<div class="col-sm-10">
								<input id="initialnoteinput" name="initialnote" type="text" value="" required>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button id="submitnewcropbutton" type="button" class="btn btn-primary" name="">Submit New Crop</button>
							</div>
						</div>		

						<input id="addSeedFormHidden" type="hidden" name="commandForCropController" value="21">
					</form>

			</div><!--row-->
		</div><!--container-->
	</body>
</html>
