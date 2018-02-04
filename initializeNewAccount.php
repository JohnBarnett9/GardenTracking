<?php 

require_once('databaseConnectionAdmin.php');
require_once('databaseConnection.php');
require_once('readConfigFile.php');//read in servername, username, password, salt

//to Reset db, add minimal data
//require_once('filesForAdminPage/adminDatabaseFunctions.php');

/*
setup new database for this user 
put schema into new database 
put minimal data into new schema
*/

$newUsername = $_POST['newusername'];
$newPassword = $_POST['newpassword'];
/*
echo "newUsername " . $newUsername . "<br>";
echo "newPassword " . $newPassword . "<br>";
echo "in createNewDatabase<br>";
*/

$newUsername = trim($newUsername);

$newDBName = "garden" . $newUsername;

try{
	$currdbConnection = new databaseConnectionAdmin();
	$currdbConnection->setupDB();
	$currdbConnection->setupConnection();
	/*
	$dbConnection = new PDO("mysql:host=$servername;dbname=$nameOfDatabase;charset=utf8", $username, $password);
	*/

	$salt = $_SESSION['salt'];
	$saltedPassword = crypt($newPassword, $salt);
	/*
	echo "newUsername = " . $newUsername . "<br>";
	echo "saltedPassword = " . $saltedPassword . "<br>";
	echo "user database name = " . $newDBName . "<br>";
	*/
	//add user to gardenadmin.user_garden_tracking
	$sqlInsertUser = 
	"INSERT INTO 
	user_garden_tracking(user_id, user_name, user_password, user_database_name) 
	VALUES (null,:newUsername,:newPassword,:userDatabaseName)";
	$queryInsertUser = $currdbConnection->dbConnection->prepare($sqlInsertUser);
	/*
	$queryInsertUser = $dbConnection->prepare($sqlInsertUser);
	*/
	$queryInsertUser->bindParam(':newUsername', $newUsername);
	$queryInsertUser->bindParam(':newPassword', $saltedPassword);
	$queryInsertUser->bindParam(':userDatabaseName', $newDBName);
	$queryInsertUser->execute();	
}
catch(PDOException $e){
	echo "Connection failed: " . $e->getMessage();
}

//login to database assigned to this user

try{
	$currdbConnection2 = new databaseConnectionAdmin();
	$currdbConnection2->setupDB();
	$currdbConnection2->setupConnectionToServer();//no dbname
	$currdbConnection2->dbConnection->exec("CREATE DATABASE " . $newDBName);
	//$currdbConnection2->exec("CREATE DATABASE " . $newDBName); this is not working, so simplify
	
	/*
	$dbConnection2 = new PDO("mysql:host=$servername2;charset=utf8", $username2, $password2);
	$dbConnection2->exec("CREATE DATABASE " . $newDBName);
	*/
	
	$currdbConnection3 = new databaseConnection();
	$_SESSION['userDatabaseName'] = $newDBName; //so the connection will actually work
	$currdbConnection3->setupDB();
	$currdbConnection3->setupConnection();
	/*
	do I need this?
	$currdbConnection3->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
	*/
	
	/*
	$dbConnection3 = new PDO("mysql:host=$servername2;dbname=$nameOfDatabase2;charset=utf8", $username2, $password2);
	$dbConnection3->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
	*/
	
	//reset db 
	$contents = file_get_contents("filesForAdminPage/dropTablesCreateTables.txt", "r");
	$explosion = explode(";", $contents); //removes ';', but whatever
	/*
	this foreach is only for debugging
	
	foreach($explosion as $item){
		echo $item . "<br>";
	}
	*/
	$currdbConnection3->dbConnection->exec($contents);
	
	//add minimal data 
	$contents2 = file_get_contents("filesForAdminPage/addMinimalData.txt", "r");
	$explosion = explode(";", $contents2); //removes ';', but whatever
	/*
	foreach($explosion as $item){
		echo $item . "<br>";
	}
	*/
	$currdbConnection3->dbConnection->exec($contents2);
	
	
	
	/*
	$dbConnection3->exec($contents);
	*/
}
catch(PDOException $e){
	echo "Connection failed: " . $e->getMessage();
}

header("Location: login.php");

?>