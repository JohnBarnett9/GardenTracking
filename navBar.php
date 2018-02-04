<?php 

function isActivePage($requestUri){

	echo '<nav class="navbar navbar-default navbar-fixed-top">';
		echo '<div class="container-fluid">';
			echo '<div class="navbar-header">';

			if($requestUri == "mainMenu.php"){
				echo '<a class="navbar-brand" href="../filesForMainMenuPage/mainMenu.php">Garden Tracking</a>';
			}
			else{
				echo '<a class="navbar-brand" href="../filesForMainMenuPage/mainMenu.php">Garden Tracking</a>';
			}

		echo '</div>';
	echo '<ul class="nav navbar-nav">';

	if($requestUri == "searchSeeds.php"){
		echo '<li class="active"><a href="../filesForSearchSeedsPage/searchSeeds.php">Seed</a></li>';
	}
	else{
		echo '<li><a href="../filesForSearchSeedsPage/searchSeeds.php">Seed</a></li>';
	}
	if($requestUri == "crop.php"){
		echo '<li class="active"><a href="../filesForCropPage/crop.php">Crop</a></li>';
	}
	else{
		echo '<li><a href="../filesForCropPage/crop.php">Crop</a></li>';
	}
	if($requestUri == "typeTag.php"){
		echo '<li class="active"><a href="../filesForTypeTagPage/typeTag.php">Type Tag</a></li>';
	}
	else{
		echo '<li><a href="../filesForTypeTagPage/typeTag.php">Type Tag</a></li>';
	}
	/*
	if($requestUri == "eventPage.php"){
		echo '<li class="active"><a href="../filesForEventPage/eventPage.php">Event</a></li>';
	}
	else{
		echo '<li><a href="../filesForEventPage/eventPage.php">Event</a></li>';
	}
	if($requestUri == "weatherEventPage.php"){
		echo '<li class="active"><a href="../filesForWeatherPage/weatherEventPage.php">Weather Event</a></li>';
	}
	else{
		echo '<li><a href="../filesForWeatherPage/weatherEventPage.php">Weather Event</a></li>';
	}
	if($requestUri == "sales.php"){
		echo '<li class="active"><a href="../filesForSalesPage/sales.php">Sales</a></li>';
	}
	else{
		echo '<li><a href="../filesForSalesPage/sales.php">Sales</a></li>';
	}
	if($requestUri == "userProfile.php"){
		echo '<li class="active"><a href="../filesForUserProfile/userProfile.php">User</a></li>';
	}
	else{
		echo '<li><a href="../filesForUserProfile/userProfile.php">User</a></li>';
	}
	*/
	if($requestUri == "admin.php"){
		echo '<li class="active"><a href="../filesForAdminPage/admin.php">Admin</a></li>';
	}
	else{
		echo '<li><a href="../filesForAdminPage/admin.php">Admin</a></li>';
	}
	echo '<li><a href="../logout.php">Logout</a></li>';


echo <<<thing2
		</ul>
		<span class="nav navbar-nav navbar-right navbar-text" style="margin: 20px;">
		  User:{$_SESSION['username']}
		  &nbsp;
		  DB:{$_SESSION['userDatabaseName']}
		</span>
	</div>
</nav>
thing2;
}
?>