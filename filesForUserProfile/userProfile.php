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
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../customStyleSheet.css">
	</head>
	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("userProfile.php");
		?>
		<h1>User Profile</h1>

		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<form class="form-horizontal" id="addSeedForm">
						<div class="form-group">
							<label class="control-label col-sm-2">Username</label>
							<div class="col-sm-10">
								<input id="usernameinput" name="username" type="text" value="" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">Password</label>
							<div class="col-sm-10">
								<input id="passwordinput" name="password" type="text" value="" required>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2">Reenter Password</label>
							<div class="col-sm-10">
								<input id="reenterpasswordinput" name="" type="text" value="" required>
							</div>
						</div>

						
						<div class="form-group">
							<label class="control-label col-sm-2">Email Address</label>
							<div class="col-sm-10">
								<input id="emailaddressinput" name="emailaddress" type="text" value="" required>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button id="savechangesbutton" class="btn btn-primary" type="button"  name="a">Save Changes</button>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button id="cancelbutton" class="btn btn-primary" type="button"  name="a">Cancel</button>
							</div>
						</div>

						<input id="addSeedFormHidden" type="hidden" name="commandForSearchController" value="9">
					</form>

				</div> <!--col-sm-6-->
				<div class="col-sm-6">
					2nd column
				</div>
			</div class="col-xs-12"> <!--row-->
			<div class="row">
			</div> <!--2nd row-->
		</div> <!--container-->

		
	</body>
</html>
