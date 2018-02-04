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
		<script src=""></script>
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
	</head>

	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("sales.php");
		?>
		<h1>Sales</h1>
		<button type="button" >Most Sold Seeds</button>
		<br>
		<button type="button" >Total Cash Made So Far</button>
		<br>
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<form action="salesController.php" method="POST" class="form-horizontal" id="addSeedForm">
						<div class="form-group">
							<label class="control-label col-sm-2">Crop Sold</label>
							<div class="col-sm-10">
								<input id="seedTypeInput" name="Ftype" type="text" value="" list="dropdownlistseedtypes" required>
								<span class="pull-right">
									<div id="errorsOfType">
									</div>
								</span>
							</div>
							<datalist id="dropdownlistseedtypes">
							</datalist>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Amount Cash</label>
							<div class="col-sm-10">
								<input id="seedNameInput" name="Fname" type="text" value="" required>
								<span class="pull-right">
									<div id="errorsOfName">
									</div>
								</span>								
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Location of Sale</label>
							<div class="col-sm-10">
								<input id="seedOriginInput" name="Forigin" type="text" value="" required>
								<span class="pull-right">
									<div id="errorsOfOrigin">
									</div>
								</span>								
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button class="btn btn-primary" id="addSeedFormButton" name="a">Add New Sale</button>
							</div>
						</div>		

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button class="btn btn-primary" id="addSeedFormButton" name="a">Cancel</button>
							</div>
						</div>		
						
						<input id="addSeedFormHidden" type="hidden" name="commandForSalesController" value="1">
					</form>

				</div> <!--col-sm-6-->
				<div class="col-sm-6">
					filler
					<div id="typeTagCheckboxes">
					</div>
				</div>
			</div class="col-xs-12"> <!--row-->
			<div class="row">
			</div> <!--2nd row-->
		</div> <!--container-->
	</body>
</html>