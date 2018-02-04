<?php 

require_once('../databaseConnection.php');

class salesModel {
	public $databaseConnection;

	public function __construct(){
		$this->databaseConnection = new databaseConnection();
	}

	/*
	eventually, date of sale will be from 
	Date and Time mm-dd-yyyy
	on Show Notes page,
	and will be used in notes table and in sales table
	
	*/
	public function addNewSale(){
		$saleQuantity = $_POST['Ftype'];
		echo "saleQuantity = " . $saleQuantity . "<br>";
		$sql1 = 
		"INSERT INTO 
		sales(sale_id, sale_quantity) 
		VALUES (null,:saleQuantity)";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':saleQuantity', $saleQuantity);
		$query1->execute();
	}
}

?>