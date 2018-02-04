<?php 

require_once("cropView.php");
require_once("cropModel.php");
require_once("../filesForSalesPage/salesModel.php");
class cropController{
	public $cropModelThing;
	public $cropViewThing;
	public $salesModelThing;
	public $dbConnection;
	
	public function __construct(){
		$this->cropModelThing = new cropModel();
		$this->cropViewThing = new cropView();
		
		$this->salesModelThing = new salesModel();
		
		$this->cropModelThing->databaseConnection->setupDB();
		$this->cropModelThing->databaseConnection->setupConnection();
		
		if($_POST['commandForCropController'] === '1'){
			//data: {commandForCropController: 1, filterBy: filterCropsBy},
			$this->radioButtonTypeDateClicked();
		}
		if($_POST['commandForCropController'] === '6'){
			$this->addNoteButtonClicked();
		}
		if($_POST['commandForCropController'] === '7'){
			$this->getCropInfo();
		}
		if($_POST['commandForCropController'] === '8'){
			$this->getExistingNotesForCrop();
		}
		if($_POST['commandForCropController'] === '9'){
			$this->showNotesButtonClicked();
		}		
		if($_POST['commandForCropController'] === '10'){
			//all notes for all crops of this seed 
			$this->allNotesForAllCropsThisSeed();
		}
		if($_POST['commandForCropController'] === '11'){
			$this->addNoteToCrop();
		}
		if($_POST['commandForCropController'] === '12'){
			$this->generateFilterList();
		}
		if($_POST['commandForCropController'] === '15'){
			$this->radioChanged();
		}
		if($_POST['commandForCropController'] === '17'){
			$this->editNoteButtonClicked();//on showNotesForSeed.php 
		}
		if($_POST['commandForCropController'] === '18'){
			//on editNoteOnShowNotesForSeedPage.php
			$this->updateNote();
		}
		if($_POST['commandForCropController'] === '19'){
			$this->whichSeedOnShowNotesPage();
		}
		if($_POST['commandForCropController'] === '20'){
			$this->deleteANote();
		}
		if($_POST['commandForCropController'] === '21'){
			$this->addACrop();
		}
		if($_POST['commandForCropController'] === '22'){
			$this->getPrimaryKeySeedOfCrop();
		}
		if($_POST['commandForCropController'] === '23'){
			$this->extraInputForAction();
		}
		if($_POST['commandForCropController'] === '24'){
			$this->saveContainerTotal();
		}
		
		$this->cropModelThing->databaseConnection->dbConnection = NULL;
		$this->cropModelThing->databaseConnection = NULL;
	}
	
	public function radioButtonTypeDateClicked(){
		$resultSet = $this->cropModelThing->sortCropsOnTypeOrDate();
		$typeOrDate = $_POST['filterBy'];
		//no sections of crops
		//$this->cropViewThing->htmlCropsSortedOnTypeOrDate($resultSet, $typeOrDate);
		
		//sections of crops
		$this->cropViewThing->varietyCropSections($resultSet);
	}
	
	public function htmlYearCheckboxes(){
		$yearCheckboxesResultSet = $this->cropModelThing->htmlYearCheckboxes();
		$this->cropViewThing->generateYearCheckboxes($yearCheckboxesResultSet);
	}
	
	public function htmlCropList(){
		$cropsResultSet = $this->cropModelThing->allCrops();
		$this->cropViewThing->htmlCropsTable($cropsResultSet);
		
	}
	
	//using similar code to editButtonClicked in searchController.php
	public function addNoteButtonClicked(){
		$_SESSION['cropPrimaryKey'] = $_POST['cropid'];
		header("Location: addNoteToCrop.php");
	}
	
	public function getExistingNotesForCrop(){
		$resultSet = $this->cropModelThing->getAllNotesForCrop();
		$this->cropViewThing->htmlAllNotesForCrop($resultSet);
	}
	
	public function getCropInfo(){
		$resultRow = $this->cropModelThing->getInfoSingleCrop();
		$this->cropViewThing->htmlAllInfoSingleCrop($resultRow);
	}
	
	/*
	show notes button clicked on crop.php
	command 9
	*/
	public function showNotesButtonClicked(){
		//echo "in showNotesButtonClicked()<br>";
		//echo "sess typeOrDateRadio = " . $_SESSION['typeOrDateRadio'] . "<br>";
		//echo "POST['cropid'] = " . $_POST['cropid'] . "<br>";
		if($_SESSION['typeOrDateRadio'] === "type"){
			
			$_SESSION['seedPrimaryKey'] = $_POST['seedid'];
		}
		else{
			$_SESSION['cropPrimaryKey'] = $_POST['cropid'];
			//echo "in showNotesButtonClicked() crop primary key = " . $_SESSION['cropPrimaryKey'] . "<br>";
		}
		header("Location: showNotesForSeed.php");
	}
	
	/*
	1st ajax call when showNotesForSeed.php loads, calls this function 
	command 10
	*/
	public function allNotesForAllCropsThisSeed(){
		//echo "allNotesForAllCropsThisSeed()<br>";
		if($_SESSION['typeOrDateRadio'] === "type"){
			//echo "in === type<br>";
			$resultSet = $this->cropModelThing->allNotesForAllCropsThisSeed();
			if($resultSet === "0 rows"){
				$this->cropViewThing->zeroNotesExistForThisSeed();
			}
			else{
				$this->cropViewThing->allNotesForAllCropsThisSeed($resultSet);
			}
		}
		else{
			//echo "in allNotesForAllCropsThisSeed() crop primary key = " . $_SESSION['cropPrimaryKey'] . "<br>";
			$resultSet = $this->cropModelThing->allNotesForSingleCrop();
			if($resultSet === "0 rows"){
				$this->cropViewThing->zeroNotesExistForThisCrop();
			}
			else{
				$this->cropViewThing->allNotesForAllCropsThisSeedDate($resultSet);
			}
		}
	}
	
	//command 11
	//regardless of radio button sorted by Type or Date, this function called 
	//when add Note to Crop
	public function addNoteToCrop(){
		echo "note " . $_POST['cropnotenote'] . "<br>";
		echo "date and time " . $_POST['cropnotedateandtime'] . "<br>";
		echo "crop primary key = " . $_POST['cropPrimaryKey'] . "<br>";
		echo "date and time hidden = " . $_POST['dateandtimeInputhidden'] . "<br>";
		$this->cropModelThing->addNoteToCrop();
		//$this->salesModelThing->addNewSale();
		if($_SESSION['typeOrDateRadio'] === "type"){
			header("Location: showNotesForSeed.php");
		}
		else{
			header("Location: showNotesForSeed.php");
		}		
	}
	
	//filterlist, checkboxes and clear button
	//copied from searchController.php generateFilterList()
	public function generateFilterList(){
		$filterListResultSet = $this->cropModelThing->filterList();
		$this->cropViewThing->generateFilterList($filterListResultSet);
	}
	
	public function radioButtonOrCheckboxClicked(){
		$resultSet = $this->cropModelThing->radioOrCheckboxClicked();
		$this->cropViewThing->varietyCropSections($resultSet);
	}
	
	public function radioButtonOrCheckboxClickedAndTags(){
		$resultSet = $this->cropModelThing->radioOrCheckboxClickedAndTags();
		$this->cropViewThing->varietyCropSections($resultSet);
	}
	
	//command 15
	public function radioChanged(){
		$resultSet = $this->cropModelThing->radioChanged();
		$typeOrDate = $_POST['currentRadioSortOrder'];
		$_SESSION['typeOrDateRadio'] = $_POST['currentRadioSortOrder']; //used in showNotesButtonClicked()
		if($typeOrDate === "type"){
			$this->cropViewThing->varietyCropSectionsType($resultSet);
		}
		else{
			$this->cropViewThing->varietyCropSectionsDate($resultSet);
		}
	}
	
	//command 17
	//edit button clicked on showNotesForThisSeed.php page
	public function editNoteButtonClicked(){
		$result = $this->cropModelThing->makeEditNoteForm();
		$this->cropViewThing->generateEditNoteForm($result);
	}
	
	//command 18
	public function updateNote(){
		$this->cropModelThing->updateNote();
		header("Location: showNotesForSeed.php");
	}

	public function whichSeedOnShowNotesPage(){
		$row = $this->cropModelThing->whichSeedOnShowNotesPage();
		$this->cropViewThing->whichSeedOnShowNotesPage($row);
	}
	
	public function deleteANote(){
		$this->cropModelThing->deleteANote();
	}
	
	//command 21
	/*
	I chose not to use 
	showNotesButtonClicked()
	becuase adapting it to be able to be used by addACrop() seemed 
	not worth the effort or complexity.
	Just put the header() in addACrop().
	*/
	public function addACrop(){
		echo "cropstartdate = " . $_POST['cropstartdate'] . "<br>";
		$this->cropModelThing->addACrop();
		/*
		1st way to get crop_id that was recently INSERTed
		cropLastInsertId set in cropModel.php, addACrop()
		$_SESSION['cropPrimaryKey'] = $_SESSION['cropLastInsertId']; //gives correct number, cropLastInsertId set in model, addACrop()		
		*/
		
		/*
		2nd way to get crop_id that was recently INSERTed
		*/
		$_SESSION['cropPrimaryKey'] = $this->cropModelThing->getMostRecentCropIDV2();
		echo "SESSION cropPrimaryKey " . $_SESSION['cropPrimaryKey'] . "<br>";
		
		//need to set 
		$_SESSION['typeOrDateRadio'] = "date";

		//Go to Show Notes page.
		header("Location: showNotesForSeed.php");
	}
	
	//command 22
	public function getPrimaryKeySeedOfCrop(){
		echo $_SESSION['addCropToThisSeed']; //set in searchController.php addACropButtonClicked()
	}
	
	//command 23 
	public function extraInputForAction(){
		$this->cropViewThing->extraInputForAction();
	}
	
	//command 24
	public function saveContainerTotal(){
		$this->cropModelThing->updateContainerTotals();
	}
}

$cController = new cropController();
?>