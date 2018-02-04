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
		
		<link href="/John/jquery-ui.css" rel="stylesheet">
		<link href="/John/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet">
		
		<script src="/John/jquery-3.2.0.js"></script>
		<script src="/John/jquery-ui.js"></script>
		<script src="searchSeedsJavaScript.js"></script>
		
		<link href="../customStyleSheet.css" rel="stylesheet" type="text/css" >
	</head>
	<body style="background-color:powderblue;">
		<?php 
			require_once('../navBar.php');
			isActivePage("searchSeeds.php");
		?>
		<div class="container">
			<div class="row"> 
				<div class="col-md-3"> 
					<h3 style="font-size:20px; font-weight:normal;">Seed Search Filtering</h3>
					<button id="addASeed">add a seed</button>
					<br>
					<br>
					<br>
					<div id="filterlist">
					</div> 
					<br/>
					<button type="button" id="downloadtxtfilebutton">Download Excel File of Seeds</button>
					<br>
				</div>   <!-- md-2 -->
				<div class="col-md-9">
					<div id="checklist">
					</div>
					<div id="statuslist">
					</div>
					<div id="output">
					</div>			
					<br/>
				</div>  <!-- md-10 -->

				<!--code for the popup window for delete button-->
				<div id="dialog-confirm" title="Do you really want to delete this seed?">
					<p>
					<span class="ui-icon-alert" style="float:left; margin:12px 12px 20px 0;">
					</span>
					</p>
				</div>		

				<!--edit button clicked-->
				<form id="myform" action="searchController.php" method="POST">
					<input id="myinput" type="hidden" name="commandForSearchController" value="5">
					<input id="keytopass" type="hidden" name="seedPrKey" value="">
				</form>

				<!--delete button clicked-->
				<form id="myform2" action="searchController.php" method="POST">
					<input id="myinput" type="hidden" name="commandForSearchController" value="8">
					<input id="keytopass2" type="hidden" name="seedPrKey" value="">
				</form>

				<!--crops for this seed clicked-->
				<form id="cropbuttonform" action="searchController.php" method="POST">
					<input id="cropcommand" type="hidden" name="commandForSearchController" value="10">
					<input id="seedidofcrop" type="hidden" name="seedid" value="">
				</form>
				
				<form id="addcroptoseedform" action="searchController.php" method="POST">
					<input id="cropcommand" type="hidden" name="commandForSearchController" value="10">
					<input id="seedprimarykey" type="hidden" name="seedid" value="">
				</form>
				
				<!--to download text file list of seeds-->
				<form id="downloadtxtfile" action="searchController.php" method="POST">
					<input id="command" type="hidden" name="commandForSearchController" value="16">
				</form>
			</div>  <!-- row -->
		</div>  <!-- container-->		
	</body>
</html>