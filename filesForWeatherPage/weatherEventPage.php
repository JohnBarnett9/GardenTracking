<?php 
	require_once("../private/initialize.php");
	require_once("../private/isUserLoggedInIsUserValid.php");
?>

<!DOCTYPE html>

<html>
	<head>
		<script src="/John/jquery-3.2.0.js"></script>
		<script src="/John/jquery-ui.js"></script>
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
	</head>
	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("weatherEventPage.php");
		?>
		<h1>Weather Events</h1>


<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<form class="form-horizontal" id="addSeedForm">
						<div class="form-group">
							<label class="control-label col-sm-2">General Type of Weather</label>
							<div class="col-sm-10">
								<input id="seedTypeInput" name="Ftype" type="text" value="" list="dropdownlistweathertypes" required>
							</div>
							<datalist id="dropdownlistweathertypes">
							</datalist>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Date and Time YYYY-MM-DD HH:MM</label>
							<div class="col-sm-10">
								<input id="seedNameInput" name="Fname" type="datetime-local" value="" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Notes</label>
							<div class="col-sm-10">
								<input id="seedOriginInput" name="Forigin" type="text" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2">Crops Affected</label>
							<div class="col-sm-10">
								<input id="seedYearInput" name="Fyear" type="text" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2">Locations Affected</label>
							<div class="col-sm-10">
								<input id="seedYearInput" name="Fyear" type="text" value="" required>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button class="btn btn-primary" id="addSeedFormButton" name="a">Add New Weather Event</button>
							</div>
						</div>		

						<input id="addSeedFormHidden" type="hidden" name="commandForSearchController" value="9">
					</form>

				</div> <!--col-sm-6-->
				<div class="col-sm-6">
					API of Wunderground
					<div id="wundergroundapi">
					</div>
				</div>
			</div class="col-xs-12"> <!--row-->
			<div class="row">
			</div> <!--2nd row-->
		</div> <!--container-->

		
	</body>
</html>
