<?php 

/*
commands:
1 = list all seeds
2 = generate filter list thing with vegetable types and checkboxes
3 = generate html table of all the seeds matching the type of veg that I clicked
	html table includes type name year origin days tags 
4 = generate html table of all seeds matching type and checkboxes
*/
session_start();


require_once('searchView.php'); //needed this line
require_once('seedModel.php');
require_once('editSeedView.php');
require_once('../filesForTypeTagPage/typeModel.php'); //used for command 11
require_once('../filesForTypeTagPage/tagModel.php'); //used for command 14
require_once('../../PHPExcelLibrary/PHPExcel.php');
class searchController{
	public $searchViewThing;//changed $searchView to $searchViewThing
	public $seedModel;
	public $dbConnection;
	public $editSeedViewThing;
	
	public function __construct(){
		$this->editSeedViewThing = new editSeedView();
		$this->searchViewThing = new searchView();//Thing because don't want instance variable and class name to be mixed up
		$this->seedModelThing = new seedModel();
		
		$this->typeModelCustomThing = new typeModelCustom();
		
		$this->tagModelThing = new tagModel();
		
		//open db connection
		$this->seedModelThing->databaseConnection->setupDB();
		$this->seedModelThing->databaseConnection->setupConnection();
		
		//used with command 11
		$this->typeModelCustomThing->databaseConnection->setupDB();
		$this->typeModelCustomThing->databaseConnection->setupConnection();
		
		//used with command 14]
		$this->tagModelThing->databaseConnection->setupDB();
		$this->tagModelThing->databaseConnection->setupConnection();
		
		if($_POST['commandForSearchController'] === '1'){
			$this->listAllSeeds();
		}
		else if($_POST['commandForSearchController'] === '2'){
			$this->generateFilterList();
		}
		else if($_POST['commandForSearchController'] === '3'){
			$this->htmlTableSeedsType();
		}
		else if($_POST['commandForSearchController'] === '4'){
			$this->htmlTableSeedsTypeAndCheckboxes();
		}
		else if($_POST['commandForSearchController'] === '5'){
			$this->editButtonClicked();
		}
		else if($_POST['commandForSearchController'] === '6'){
			$this->editASeed();
		}
		else if($_POST['commandForSearchController'] === '7'){
			$this->addButtonClicked(); //goes to addSeed.php
		}
		else if($_POST['commandForSearchController'] === '8'){
			$this->deleteButtonClicked();
		}
		else if($_POST['commandForSearchController'] === '9'){
			$this->addNewSeed();
		}
		else if($_POST['commandForSearchController'] === '10'){
			$this->addACropButtonClicked();
		}
		else if($_POST['commandForSearchController'] === '11'){
			$this->allTagsForSingleType();
		}
		else if($_POST['commandForSearchController'] === '12'){
			$this->editSeedCheckboxs();
		}
		else if($_POST['commandForSearchController'] === '13'){
			$this->getAvailableSeedOrigins();
		}
		else if($_POST['commandForSearchController'] === '14'){
			$this->getAvailableSeedTags();
		}
		else if($_POST['commandForSearchController'] === '15'){
			$this->getAvailableSeedTypes();
		}
		else if($_POST['commandForSearchController'] === '16'){
			$this->textFileSeedListDownload();
		}
		else if($_POST['commandForSearchController'] === '17'){
			$this->isSeedUnique();
		}
		else if($_POST['commandForSearchController'] === '18'){
			$this->isSeedUniqueEdit();
		}
		else if($_POST['commandForSearchController'] === '19'){
			$this->updateSeed5MainFields();
		}
		else if($_POST['commandForSearchController'] === '20'){
			$this->didDaysOrQuantityChange();
		}
		else if($_POST['commandForSearchController'] === '21'){
			$this->updateDaysAndQuantity();
		}
		else if($_POST['commandForSearchController'] === '22'){
			$this->didListOfTagsChange();
		}
		else if($_POST['commandForSearchController'] === '23'){
			$this->updateListOfTags();
		}
		else if($_POST['commandForSearchController'] === '24'){
			$this->did5MainFieldsChange();
		}		
		else if($_POST['commandForSearchController'] === '25'){
			$this->getValuesEditASeedForm();
		}
		
		
		// assign null to db connection handle
		$this->seedModelThing->databaseConnection->dbConnection = NULL;
		$this->seedModelThing->databaseConnection = NULL;
		
		//used with command 11
		$this->typeModelCustomThing->databaseConnection->dbConnection = NULL;
		$this->typeModelCustomThing->databaseConnection = NULL;
		
		//used with command 14
		$this->tagModelThing->databaseConnection->dbConnection = NULL;
		$this->tagModelThing->databaseConnection = NULL;
	}
	
	public function listAllSeeds(){
		$allSeedsResultSet = $this->seedModelThing->listAllSeedsinseedtable();
		
		//Alex wanted all seeds to include listing all columns
		$this->searchViewThing->generateHTMLTableSeedType($allSeedsResultSet);		
	}
	
	public function generateFilterList(){
		$filterListResultSet = $this->seedModelThing->filterList();
		$this->searchViewThing->generateFilterList($filterListResultSet);
	}
	
	//resulting seeds based on type
	public function htmlTableSeedsType(){
		$htmlTableResultSet = $this->seedModelThing->htmlTableSeedType();
		$this->searchViewThing->generateHTMLTableSeedType($htmlTableResultSet);
	}
	
	//resulting seeds based on type and checkboxes
	public function htmlTableSeedsTypeAndCheckboxes(){
		$htmlTableResultSet = $this->seedModelThing->htmlTableSeedTypeCheck();
		$this->searchViewThing->generateHTMLTableSeedType($htmlTableResultSet);
	}

	public function editButtonClicked(){
		$_SESSION['editSeedPrimaryKey'] = $_POST['seedPrKey'];//to be used in seedModel editSeed SQL query
		//echo "SESSION editSeedPrimaryKey = " . $_SESSION['editSeedPrimaryKey'] . "<br>";
		//echo "POST seedPrKey " . $_POST['seedPrKey'] . "<br>";
		
		/*
		refactoring, putting html directly in editSeed.php instead
		$htmlForEditSeedPage = $this->editSeedViewThing->generateForm($currentSeedResultSet);
		*/
		
		
		$_SESSION['formForEditPage'] = $htmlForEditSeedPage;
		header("Location: editSeed.php");
	}
	
	/*
	command 6
	form to edit a seed has been submitted
	*/
	public function editASeed(){
		$this->seedModelThing->editSeed();
	}
	
	/*
	delete button clicked on searchSeeds.php
	*/
	public function deleteButtonClicked(){
		$this->seedModelThing->deleteSingleSeed($_POST['seedPrimaryKey']);
	}
	
	public function addButtonClicked(){
		header("Location: addSeed.php");
	}

	
	//command 9
	public function addNewSeed(){
		$result = $this->seedModelThing->addANewSeed();
	}
	
	//command 10
	public function addACropButtonClicked(){
		echo "in searchController in addACropButtonClicked() <br>";
		echo "seedid = " . $_POST['seedid'] . "<br>";
		$_SESSION['addCropToThisSeed'] = $_POST['seedid']; //used in cropController.php getPrimaryKeySeedOfCrop()
		echo "session = " . $_SESSION['addCropToThisSeed'] . "<br>";
		header("Location: ../filesForCropPage/addCrop.php");
		/*
		echo "seedid = " . $_POST['seedid'] . "<br>";
		$_SESSION['cropseedid'] = $_POST['seedid'];
		header("Location: ../filesForCropPage/crop.php");
		*/
	}
	
	//command 11
	public function allTagsForSingleType(){
		$this->typeModelCustomThing->allTagsForSingleType();
	}
	
	//command 12
	public function editSeedCheckboxs(){
		$this->typeModelCustomThing->editSeedCheckboxs();
	}
	
	//command 13
	public function getAvailableSeedOrigins(){
		$this->seedModelThing->getAvailableSeedOrigins();
	}
	
	//command 14
	public function getAvailableSeedTags(){
		$this->tagModelThing->getAvailableSeedTags();
	}
	
	//command 15
	public function getAvailableSeedTypes(){
		$this->typeModelCustomThing->getAvailableSeedTypes();
	}
	
	//command 16
	public function textFileSeedListDownload(){		
		$temp = new PHPExcel();
		// We'll be outputting an excel file
		header('Content-type: application/vnd.ms-excel');

		// It will be called file.xls
		header('Content-Disposition: attachment; filename="file.xls"');

		//$temp->getActiveSheet()->SetCellValue('A1', "asdf"); works
		$temp->getActiveSheet()->SetCellValue('A1', "Seeds in database:" . $_SESSION['userDatabaseName']);
		$temp->getActiveSheet()->SetCellValue('A2', date("Y-m-d"));
		$temp->getActiveSheet()->SetCellValue('A3', date("h-i"));
		$temp->getActiveSheet()->SetCellValue('A5', "Note");
		$temp->getActiveSheet()->SetCellValue('B5', "Type");
		$temp->getActiveSheet()->SetCellValue('C5', "Name");
		$temp->getActiveSheet()->SetCellValue('D5', "Origin");
		$temp->getActiveSheet()->SetCellValue('E5', "Year");
		$temp->getActiveSheet()->SetCellValue('F5', "Quantity");
		$temp->getActiveSheet()->SetCellValue('G5', "Tags");
		
		$temp->getActiveSheet()->getStyle('A1')->getFont()->setBold(true); //'Seeds in database' bold
		$temp->getActiveSheet()->getStyle('A5:G5')->getFont()->setBold(true); //Column Headings bold
		
		//Name and Origin columns are wide enough
		$temp->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$temp->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$temp->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		
		//horizontal align Year and Quantity
		$temp->getActiveSheet()->getStyle('D6:D600')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$temp->getActiveSheet()->getStyle('E6:E600')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$temp->getActiveSheet()->getStyle('F6:F600')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$rowCount = 6;
		$allSeedsResultSet = $this->seedModelThing->allSeedsXLSDownload();
		while($row = $allSeedsResultSet->fetch(PDO::FETCH_ASSOC)){
			$temp->getActiveSheet()->SetCellValue('A' . $rowCount, $row['note']);
			$temp->getActiveSheet()->SetCellValue('B' . $rowCount, $row['type_name']);
			$temp->getActiveSheet()->SetCellValue('C' . $rowCount, $row['name']);
			$temp->getActiveSheet()->SetCellValue('D' . $rowCount, $row['origin']);
			$temp->getActiveSheet()->SetCellValue('E' . $rowCount, $row['year']);
			$temp->getActiveSheet()->SetCellValue('F' . $rowCount, $row['quantity']);
			$temp->getActiveSheet()->SetCellValue('G' . $rowCount, $row['tags']);
			$rowCount++;
		}
		
		$writer = PHPExcel_IOFactory::createWriter($temp, 'Excel5');
		
		// Write file to the browser
		$writer->save('php://output');
	}
	
	//command 17
	public function isSeedUnique(){
		$this->seedModelThing->isSeedUnique();
	}
	
	
	//command 24 
	public function did5MainFieldsChange(){
		$this->seedModelThing->did5MainFieldsChange();
	}
	
	//command 18
	public function isSeedUniqueEdit(){
		$this->seedModelThing->isSeedUniqueEdit();
	}
	
	//command 19
	//update 5 main fields of seed: Type, Name, Year, Origin, Note 
	public function updateSeed5MainFields(){
		$this->seedModelThing->updateSeed5MainFields();
	}
	
	/*
	command 20
	*/
	public function didDaysOrQuantityChange(){
		$this->seedModelThing->didDaysOrQuantityChange();
	}
	
	/*
	command 21
	*/
	public function updateDaysAndQuantity(){
		$this->seedModelThing->updateDaysAndQuantity();
	}
	
	/*
	command 22 
	*/
	public function didListOfTagsChange(){
		$this->seedModelThing->didListOfTagsChange();
	}
	
	/*
	command 23
	*/
	public function updateListOfTags(){
		$this->seedModelThing->updateListOfTags();
	}
	
	
	
	/*
	command 25 
	25 is just a placeholder number, just trying to get this 
	refactoring to work, make better number later
	*/
	public function getValuesEditASeedForm(){
		$currentSeedResultSet = $this->seedModelThing->allInfoSingleSeed($_SESSION['editSeedPrimaryKey']);
		//$currentSeedResultSet = $this->seedModelThing->allInfoSingleSeed($_POST['seedPrKey']);
		$this->searchViewThing->editASeedFormValues($currentSeedResultSet);
	}
	
}

$sController = new searchController();

?>