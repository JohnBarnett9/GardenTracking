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
		<script src="testModule.js"></script>
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
	</head>

	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("searchSeeds.php");
		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<h3 style="font-size:20px; font-weight:normal;">Add A Seed</h3>
					<div id="validationErrors" class="alert-danger">
					</div>
					<div id="successMessage" class="alert-success">
					</div>
					<form class="form-horizontal" id="addSeedForm">
						<div class="form-group">
							<label class="control-label col-sm-2">Type</label>
							<div class="col-sm-10">
								<input id="seedTypeInput" name="Ftype" type="text" value="" list="dropdownlistseedtypes" required>
							</div>
							<datalist id="dropdownlistseedtypes">
							</datalist>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Name</label>
							<div class="col-sm-10">
								<input id="seedNameInput" name="Fname" type="text" value="" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Origin</label>
							<div class="col-sm-10">
							<input required id="seedOriginInput" name="Forigin" type="text" value="" list="dropdownlistseedorigins" >
							</div>
							<datalist id="dropdownlistseedorigins">
							</datalist>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Year</label>
							<div class="col-sm-10">
								<input id="seedYearInput" name="Fyear" type="text" value="" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Days</label>
							<div class="col-sm-10">
								<input id="seedDaysInput" name="Fdays" type="text" value="" required>
							</div>
						</div>

						
						<div class="form-group">
							<label class="control-label col-sm-2">Quantity</label>
							<div class="col-sm-10">
								<input id="seedQuantityInput" name="Fquantity" type="text" value="" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Note</label>
							<div class="col-sm-10">
								<input id="seedNoteInput" name="Fnote" type="text" value="" required>
							</div>
						</div>
						
						
						<!--making type="button" because of reasons. 
						These reasons are explained more in testModule.js in the handler of 
						$("#addSeedFormButton") -->
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" class="btn btn-primary" id="addSeedFormButton" name="a">Submit New Seed</button>
							</div>
						</div>		

						<!--
						<input id="addSeedFormHidden" type="hidden" name="commandForSearchController" value="9">
						<input id="listTagsForSeed" type="hidden" name="listTags" value="">
						-->
					</form>

				</div> <!--col-sm-6-->
				<div class="col-sm-6">
					tags for the selected seed type
					<div id="typeTagCheckboxes">
					</div>
				</div>
			</div class="col-xs-12"> <!--row-->
			<div class="row">
			</div> <!--2nd row-->
		</div> <!--container-->
	</body>
</html>