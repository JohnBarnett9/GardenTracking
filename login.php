<?php require_once("private/initialize.php"); ?>

<!doctype html>
<html lang="en">
	<head>
		<title>Log in</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; 
		any other head content must come *after* these tags -->
		
		<script src="/John/jquery-3.2.0.js"></script>
		<script src="/John/jquery-ui.js"></script>
		<script src="login.js"></script>
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<style>
			form { margin: 0px 10px; }

			h2 {
			  margin-top: 2px;
			  margin-bottom: 2px;
			}

			.container { max-width: 360px; }

			.divider {
			  text-align: center;
			  margin-top: 20px;
			  margin-bottom: 5px;
			}

			.divider hr {
			  margin: 7px 0px;
			  width: 35%;
			}

			.left { float: left; }

			.right { float: right; }		
		</style>
		
	</head>
	<body>
		<!--
		code is from
		https://bootsnipp.com/snippets/kEzq3
		-->
		<div class="container">
			<div class="row">
				<div class="panel panel-primary">
					<div class="panel-body">
						<form >
							
							<div class="form-group">
								<h2>Sign in</h2>
							</div>
							<div id="loginerror" class="alert-danger">
							
							</div>
							<div class="form-group">
								<strong>Username</strong>
								<!--
								original
								<input id="signinEmail" type="email" maxlength="50" class="form-control">
								-->
								<input id="signinUsername" type="text" name="newusername" class="form-control" value="" />
							</div>
							<div class="form-group">
								<strong>Password</strong>
								<input id="signinPassword" type="password" name="newpassword" value="" class="form-control">
								<!--
								original
								<input id="signinPassword" type="password" maxlength="25" class="form-control">
								-->
							</div>
							<div class="form-group" style="padding-top: 12px;">
								<button id="signinSubmit" type="button" class="btn btn-success btn-block">Sign in</button>
							</div>
							<div class="form-group divider">
								<!--
								<hr class="left"><small>New to GardenTracking?</small><hr class="right">
								-->
								New to GardenTracking?
							</div>
							<p class="form-group">
								<a href="createAnAccount.php" class="btn btn-info btn-block">
									Create an account
								</a>
							</p>
							<p class="form-group">
								By signing in you are agreeing to our 
								<a href="https://choosealicense.com/licenses/agpl-3.0/">
									Terms of Use
								</a>
								and our 
								<a href="https://www.softwareadvice.com/privacy/">
									Privacy Policy
								</a>.
							</p>
							<span class="left">
								<a href="forgotYourPassword.php">
									Forgot your password?
								</a>
							</span>							
						</form>
					</div>
				</div>
			</div>
		</div><!-- container-->
	</body>
</html>
