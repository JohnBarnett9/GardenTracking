<?php 
	require_once("../private/initialize.php");
	require_once("../private/isUserLoggedInIsUserValid.php");
	
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<!--css-->
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
		<!--js-->
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="/John/jquery-3.2.0.js"></script>
		<script src="/John/jquery-ui.js"></script>
		<!-- Bootstrap compiled plugins (below), or individual files as needed -->
		<script src="/John/bootstrap3.3.7/js/bootstrap.min.js"></script>
		
		<!--validation when Submitted, uses module pattern-->
		<script src="testModuleEditSeed.js"></script>
	</head>

	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("searchSeeds.php");
		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<h3 style="font-size:20px; font-weight:normal;">Edit A Seed</h3>
					<div id="validationErrors" class="alert-danger">
					</div>
					<div id="successMessage" class="alert-success">
					</div>					
					
					<form class="form-horizontal" id="myform">
						<div class="form-group">
							<label class="control-label col-sm-2">Type</label>
							<div class="col-sm-10">
								<input class="form-control" id="seedTypeInput" name="Ftype" type="text" value="" list="dropdownlistseedtypes" required>
							</div>
							<datalist id="dropdownlistseedtypes">
							</datalist>		
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2" for="Fname">Name</label>
							<div class="col-sm-10">
								<input class="form-control" id="seedFname" name="Fname" type="text" value=""	required/>
							</div>
						</div>
						<span>
							<div id="errorsOfName">
							</div>
						</span>
						
						<br>
						<div class="form-group">
							<label class="control-label col-sm-2" for="Fyear">Year</label>
							<div class="col-sm-10">
								<input class="form-control" id="seedFyear" name="Fyear" type="number" value="" />
							</div>
						</div>
						
						<br>
						<div class="form-group">
							<label class="control-label col-sm-2" for="Forigin">Origin</label>
							<div class="col-sm-10">
								<input class="form-control" id="seedForigin" name="Forigin" type="text" value="" />
							</div>
						</div>
						<span>
							<div id="errorsOfOrigin">
							</div>
						</span>
						
						<br>
						<div class="form-group">
							<label class="control-label col-sm-2" for="Fdays">Days</label>
							<div class="col-sm-10">
								<input class="form-control" id="seedFdays" name="Fdays" type="number" min="0" max="200" value=""/>
							</div>
						</div>
						<span>
							<div id="errorsOfDays">
							</div>
						</span>
						<br>
						
						<div class="form-group">
							<label class="control-label col-sm-2" for="Fquantity">Quantity</label>
							<div class="col-sm-10">
								<input class="form-control" id="seedFquantity" name="Fquantity" value=""/>
							</div>
						</div>
						<span>
							<div id="errorsOfQuantity">
							</div>
						</span>
						<br>	
						
						<div class="form-group">
							<label class="control-label col-sm-2" for="Fnote">Note</label>
							<div class="col-sm-10">
								<input class="form-control" id="seedFnote" name="Fnote" value=""/>
							</div>
						</div>	
						

						<div class="form-group">
							<button type="button" class="btn btn-primary" id="submiteditseedform" name="s">Submit Changes of Seed</button>
						</div>

						<a href="searchSeeds.php" class="btn btn-primary">Cancel</a>
					</form>					
					
				</div> <!--col-sm-6-->
				<div class="col-sm-6">
					tags for the selected seed
					<div id="typeTagCheckboxes">
					</div>
				</div>
			</div class="col-xs-12"> <!--row-->
		</div> <!--container-->
	</body>
</html>