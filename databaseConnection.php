<?php 

require_once("private/initialize.php");
/*
require_once("../private/initialize.php");
require_once("../private/isUserLoggedInIsUserValid.php");
*/

class databaseConnection{

	public $dbConnection;
    public $servername;
	public $username;
	public $password;	
	public $nameOfDatabase;

	public function __construct(){}

	public function setupDB(){
		$this->servername = $_SESSION['configServername'];
		$this->username = $_SESSION['configUsername'];
		$this->password = $_SESSION['configPassword'];
		$this->nameOfDatabase = $_SESSION['userDatabaseName'];
	}

	public function setupConnection() {
		try{
			$this->dbConnection = new PDO("mysql:host=$this->servername;dbname=$this->nameOfDatabase;charset=utf8", 
				$this->username, $this->password);
			$this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			echo "Connection failed: " . $e->getMessage();
		}
	}
}
?>