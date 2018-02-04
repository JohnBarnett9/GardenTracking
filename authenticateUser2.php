<?php

require_once("private/initialize.php");
require_once("databaseConnectionAdmin.php");
require_once("databaseConnection.php"); //initialize values, from config file
require_once('readConfigFile.php');

$currUsername = $_POST['newusername'];
$plaintextPassword = $_POST['newpassword'];

$databaseConnectionAdmin = new databaseConnectionAdmin();
$databaseConnectionAdmin->setupDB();
$databaseConnectionAdmin->setupConnection();

//must declare the SESSION vars before I call the function that uses the SESSION vars.
$_SESSION['userFoundStatus'] = "";
$_SESSION['debugInfo'] = "";

//echo "<br>in authenticateUser2.php, before ifs<br>";
//this line cannot be after 'echo json_encode()'
$_SESSION['debugInfo'] = $_SESSION['debugInfo'] . "<br>in authenticateUser2.php, before ifs<br>";


setUserfoundSessionVar($databaseConnectionAdmin, $currUsername, $plaintextPassword);

//echo "debug info after call setUserfoundSessionVar()  = " . $_SESSION['debugInfo'] . "<br>";

/*
$userFoundStatusAndDebugInfo = 
	array(
		"userFoundStatus" => $_SESSION['userFoundStatus'], 
		"debugInfo" => $_SESSION['debugInfo']
	);
*/

//echo "session userFoundStatus = " . $_SESSION['userFoundStatus'] . "<br>";

$userFoundStatus = $_SESSION['userFoundStatus'];
$debugInfo = $_SESSION['debugInfo'];

$userFoundStatusAndDebugInfo = 
	array(
		"userFoundStatus" => $userFoundStatus, 
		"debugInfo" => $debugInfo
	);

echo json_encode($userFoundStatusAndDebugInfo);

/*
NOTICE:
Only functions can be after this comment.
Because execution flow returns from this page, after the echo json_encode() occurs.
*/


function setUserfoundSessionVar($databaseConnectionAdmin, $currUsername, $plaintextPassword){
	if(findUser($databaseConnectionAdmin, $currUsername, $plaintextPassword)){//user found
		//echo "<br>in authenticateUser2php, in if(findUser()){}<br>";
		after_successful_login();
		$_SESSION['username'] = $currUsername;
		
		/*
		My guess is that doing a header redirect while not having completed 
		the ajax call is a bad idea.
		header("Location: filesForMainMenuPage/mainMenu.php");
		*/
		//echo "userfound";
		$_SESSION['userFoundStatus'] = "userfound";
	}
	else{//user not found
		//echo "usernotfound<br>";
		$_SESSION['userFoundStatus'] = "usernotfound";
	}	
}


//returns true if username appears in gardenadmin AND 
//crypt of password from database matches entered password
function findUser($databaseConnectionAdmin, $currUsername, $plaintextPassword){
	//echo "<br>in authenticateUser2.php findUser()<br>";
	$currUsername = trim($currUsername);
	$user = false;
	$sql = 
	"SELECT *, count(*) AS rowcount 
	FROM user_garden_tracking 
	WHERE user_name= :currUsername";
	$query = $databaseConnectionAdmin->dbConnection->prepare($sql);
	$query->bindParam(':currUsername', $currUsername);
	$query->execute();
	$row = $query->fetch();
	$userID = $row['user_id'];
	$userDatabaseName = $row['user_database_name'];
	$passwordFromDB = $row['user_password'];
	
	$salt = $_SESSION['salt'];

	//make SESSION variable
	$_SESSION['userDatabaseName'] = $row['user_database_name'];
	//echo "in authenticateUser.php SESSION userDatabaseName = " . $_SESSION['userDatabaseName']  . "<br>";
	
	if($row['rowcount'] === '1'){
		if(crypt($plaintextPassword, $salt) === $passwordFromDB){
			$user = true; //a user profile was found matching that username
			recordUserLogin($databaseConnectionAdmin, $userID);
		}
		else{
			echo "crypt NOT match<br>";
		}
	}
	else{
		//user not found
		echo "rowcount not 1<br>";
	}
	return $user;
}

//record user login 
function recordUserLogin($databaseConnectionAdmin, $userID){
	$newRemoteAddress = $_SERVER['REMOTE_ADDR'];
	$newDateTime = date("Y-m-d H:i:s");
	$sql1 = 
	"INSERT INTO every_user_login (login_id, ipaddress_of_user, time_of_login) 
	VALUES (null, :newRemoteAddress, :newDateTime)";

	try{
		$query1 = $databaseConnectionAdmin->dbConnection->prepare($sql1);
		$query1->bindParam(':newRemoteAddress', $newRemoteAddress);
		$query1->bindParam(':newDateTime', $newDateTime);
		$query1->execute();	
	}
	catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage();
	}

	$lastLoginID = $databaseConnectionAdmin->dbConnection->lastInsertId();
	$currentUserID = $userID;
	$sql2 = 
	"INSERT INTO user_login_junction_table (user_id, login_id) 
	VALUES (:currentUserID, :lastLoginID)";

	try{
		$query2 = $databaseConnectionAdmin->dbConnection->prepare($sql2);
		$query2->bindParam(':currentUserID', $currentUserID);
		$query2->bindParam(':lastLoginID', $lastLoginID);
		$query2->execute();	
	}
	catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage();
	}	
}

?>