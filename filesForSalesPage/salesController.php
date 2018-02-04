<?php 

require_once('salesModel.php');
class salesController{
	public $salesModel;
	public function __construct(){
		$this->salesModelThing = new salesModel();
		$this->salesModelThing->databaseConnection->setupDB();
		$this->salesModelThing->databaseConnection->setupConnection();
		
		if($_POST['commandForSalesController'] === '1'){
			$this->insertSale();
		}

	}
	
	public function insertSale(){
		$this->salesModelThing->addNewSale();
	}
}

$salesControllerOne = new salesController();

?>