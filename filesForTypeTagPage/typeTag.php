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


		<!--<link  href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">-->
		<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
		
		<link href="/John/jquery-ui.css" rel="stylesheet">
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link href="../customStyleSheet.css" rel="stylesheet" type="text/css" >
		<script src="/John/jquery-3.2.0.js"></script>
		<script src="/John/jquery-ui.js"></script>
		<script src="typeTagJavaScript.js"></script>
		
	</head>
	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("typeTag.php");
		?>

		<div class="container">
			<div class="row">
				<h3 style="font-size:20px; font-weight:normal;">Type Tag</h3>
				<div class="col-sm-4 typetag-div-outline" id="typecolumn">
					Type
					<div id="listoftypes">
					</div>
					<button class="btn btn-primary" id="deletetypebutton">Delete Type</button>
					<br>
					<br>
					<label class="control-label ">edit type</label>
					<br>
					<input type="text" id="edittypetextfield">
					<br>
					<br>
					<button class="btn btn-primary" id="edittypebutton" name="a">Submit Edit</button>
					<br>
					<br>
					<label class="control-label ">add type</label>
					<br>
					<input type="text" id="addtypetextfield">
					<br>
					<br>
					<button class="btn btn-primary" id="addtypebutton" name="a">Add Type</button>
				
				</div>
				<div class="col-sm-4 typetag-div-outline" id="typetagcolumn">
					type tag
					<div id="typetagdropdownbox">
					</div>
					assigned tags for this type
					<div id="assignedTagsForType">
					</div>

					<button class="btn btn-primary" id="deletetagfromtype">Delete Tag From Type</button>
					<br>
					assign another tag to this type
					<div id="availabletagsfortype">
					</div>
					<button class="btn btn-primary" id="addtagtotype">Add Tag To Type</button>
				</div>
				<div class="col-sm-4 typetag-div-outline">
					Tag
					<div id="listoftags">
					</div>
					<button class="btn btn-primary" id="deletetagbutton">Delete Tag</button>
					<br>
					<br>
					<label class="control-label ">edit tag</label>
					<br>
					<input type="text" id="edittagtextfield">
					<br>
					<br>
					<button class="btn btn-primary" id="editTagFormButton" name="a">Submit Edit</button>
					
					<br>
					<br>
					<label class="control-label ">add tag</label>
					<br>
					<input type="text" id="addtagtextfield">
					<br>
					<br>
					<button class="btn btn-primary" id="addTagFormButton" name="a">Add Tag</button>
				</div>				
			</div>
		</div>
	</body>
</html>