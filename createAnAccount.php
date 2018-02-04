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
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="createAnAccountCSS.css">
		<script src="createAnAccountJS.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4 text-center">
					<div class="search-box">
						<div class="caption">
							<h3>Create An Account</h3>
						</div>

						<div id="validationErrors" class="alert-danger">
						</div>
						
						<form id="newaccountform" action="initializeNewAccount.php" method="POST" class="loginForm">
							<div class="input-group">
								<input type="text" id="usernameinput" class="form-control" placeholder="Username" name="newusername">
								<input type="password" id="passwordinput" class="form-control" placeholder="Password" name="newpassword">
								<input type="button" id="newuserbutton" class="form-control btn btn-primary" value="Create New Account">
							</div>
						</form>
					</div>
				</div>
				
				<!--
				<div class="col-md-4">
					<div class="aro-pswd_info">
						<div id="pswd_info">
							<h4>Password must be requirements</h4>
							<ul>
								<li id="length" class="invalid">Be at least <strong>8 characters</strong></li>
								
								<li id="letter" class="invalid">At least <strong>one letter</strong></li>
								<li id="capital" class="invalid">At least <strong>one capital letter</strong></li>
								<li id="number" class="invalid">At least <strong>one number</strong></li>
								<li id="space" class="invalid">be<strong> use [~,!,@,#,$,%,^,&,*,-,=,.,;,']</strong></li>
								
							</ul>
						</div>
					</div>
				</div>
				-->
			</div>
		</div>	
	</body>
</html>