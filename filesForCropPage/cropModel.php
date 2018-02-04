<?php 

require_once('../databaseConnection.php');

if(!isset($_SESSION)) 
{ 
	session_start(); 
} 

class cropModel{
	public $databaseConnection;

	public function __construct(){
		$this->databaseConnection = new databaseConnection();
	}
		
	public function sortCropsOnTypeOrDate(){
		$eitherTypeOrDate = $_POST['filterBy'];
		$sql1 = "";
		if($eitherTypeOrDate === "type"){
			$sql1 =
			"SELECT *
			FROM crop
			INNER JOIN seed ON crop.seed_id = seed.seed_id
			INNER JOIN type ON seed.type_id = type.type_id
			ORDER BY type.type_name, crop.start_date DESC";
		}
		else{ //date
			$sql1 = 
			"SELECT *
			FROM crop
			INNER JOIN seed ON crop.seed_id = seed.seed_id
			INNER JOIN type ON seed.type_id = type.type_id
			ORDER BY crop.start_date DESC";
		}
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->execute();
		return $query1;		
	}
	
	public function htmlYearCheckboxes(){
		$sql1 = 
		"SELECT DISTINCT seed.year
		FROM seed
		ORDER BY seed.year DESC;";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->execute();
		return $query1;
	}
	
	public function allCrops(){
		$sql1 = 
		"SELECT crop.start_date, seed.origin, seed.year
		FROM crop
		INNER JOIN seed ON crop.seed_id = seed.seed_id";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->execute();
		return $query1;		
	}
	
	public function yearTypeDate(){
		$eitherTypeOrDate = $_POST['filterBy'];
		$yearChecked = $_POST['yearChecked'];
		$sql1 = "";
		if($eitherTypeOrDate === "type"){
			$sql1 =
			"SELECT type.type_name, seed.name, seed.origin, seed.year, crop.start_date
			FROM crop
			INNER JOIN seed ON crop.seed_id = seed.seed_id
			INNER JOIN type ON seed.type_id = type.type_id
			WHERE seed.year= :yearChecked
			ORDER BY type.type_name, crop.start_date DESC";
		}
		else{ //date
			$sql1 = 
			"SELECT crop.start_date, type.type_name, seed.name, seed.origin, seed.year
			FROM crop
			INNER JOIN seed ON crop.seed_id = seed.seed_id
			INNER JOIN type ON seed.type_id = type.type_id
			WHERE seed.year = :yearChecked
			ORDER BY crop.start_date DESC";
		}
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':yearChecked', $yearChecked);
		$query1->execute();
		return $query1;
	}
	
	public function getAllNotesForCrop(){
		$cropPrimaryKey = $_SESSION['cropPrimaryKey'];
		$sql1 = 
		"SELECT * 
		FROM crop 
		INNER JOIN note ON crop.crop_id = note.crop_id
		WHERE crop.crop_id= :cropPrimaryKey";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':cropPrimaryKey', $cropPrimaryKey);
		$query1->execute();
		return $query1;
	}
	
	//addNoteToCrop.php
	public function getInfoSingleCrop(){
		$cropPrimaryKey = $_SESSION['cropPrimaryKey'];
		$sql1 =
		"SELECT * 
		FROM crop
		INNER JOIN seed ON crop.seed_id = seed.seed_id
		INNER JOIN type ON seed.type_id = type.type_id
		WHERE crop.crop_id= :cropPrimaryKey";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':cropPrimaryKey', $cropPrimaryKey);
		$query1->execute();
		$row = $query1->fetch();
		return $row;
	}

	public function allNotesForAllCropsThisSeed(){
		$sql1 = "
		SELECT seed.seed_id, seed.name, crop.start_date, crop.crop_container_totals, t.note_id, t.note_date, t.crop_id, t.note_description, t.note_action, 
		(
		SELECT COUNT(*) FROM note AS ct
		WHERE ct.crop_id=t.crop_id
		) as numnotesforcrop
		FROM
		note AS t
		INNER JOIN crop ON t.crop_id = crop.crop_id 
		INNER JOIN seed ON seed.seed_id = crop.seed_id
		WHERE seed.seed_id = :seedPrimaryKey
		ORDER BY crop.crop_id, t.note_date ASC";
		
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':seedPrimaryKey', $_SESSION['seedPrimaryKey']);
		$query1->execute();
		$numRows = $query1->rowCount();
		if($numRows === 0){
			return "0 rows";
		}	
		return $query1;
	}
	
	public function addNoteToCrop(){
		$dateTime = $_POST['dateandtimeInputhidden']; //already properly formatted
		$newNote = $_POST['cropnotenote'];
		$action = $_POST['action'];
		echo "action = " . $action . "<br>";

		$cropPrimaryKey = $_POST['cropPrimaryKey'];
		echo "in addNoteToCrop()<br>";
		echo "dateTime = ". $dateTime . "<br>";
		echo "newNote = ". $newNote . "<br>";
		echo "cropPrimaryKey = " . $cropPrimaryKey . "<br>";

		$sql1 = 
		"INSERT INTO 
		note (note_id, crop_id, note_action, note_date, note_description) 
		VALUES (NULL, :cropPrimaryKey, :currAction, :newDateTime, :newNote)";
		
		try {
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':cropPrimaryKey', $cropPrimaryKey);
			$query1->bindParam(':currAction', $action);
			$query1->bindParam(':newDateTime', $dateTime);
			$query1->bindParam(':newNote', $newNote);
			$query1->execute();
		} catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}

	}
	
	//filterlist, copied from seedModel.php filterlist()
	public function filterList(){
		$sql =
			"SELECT type_name 
			FROM type
			WHERE type.type_name != '000'
			ORDER BY type.type_name ASC";
		$stmt = $this->databaseConnection->dbConnection->query($sql);
		$outerArray = [];
		$singleNameWithTags = [];
		$currentVeg = "";
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$currentVeg = $row['type_name'];
			$tagsArray = [];
			$tagsSQL = 
						"SELECT tag.tag_name, tag.tag_id
						FROM type
						INNER JOIN typetag ON type.type_id = typetag.type_id
						INNER JOIN tag ON typetag.tag_id = tag.tag_id
						WHERE type.type_name = '$currentVeg'
						ORDER BY tag.tag_name";
			$tagsStmt = $this->databaseConnection->dbConnection->prepare($tagsSQL);
			$tagsStmt->execute();
			while($tagsRow = $tagsStmt->fetch(PDO::FETCH_ASSOC)){
				$tagDesc = $tagsRow['tag_name'];
				$tagDesc = str_replace(' ', '', $tagDesc);
				$tagDesc = $tagDesc . "-" . $tagsRow['tag_id'];
				array_push($tagsArray, $tagDesc);
			}
			array_push($outerArray, $currentVeg);
			array_push($outerArray, $tagsArray);
		}

		return $outerArray;
	}
	
	public function radioChanged(){
		$typeOrDate = $_POST['currentRadioSortOrder'];
		$resultSet = null;
		if($typeOrDate === "type"){
			$resultSet = $this->radioChangedToType();
		}
		else{ //if no radio button selected, control flow goes here
			$resultSet = $this->radioChangedToDate();
		}
		return $resultSet;
	}
	
	public function radioChangedToType(){
		$currentType = $_POST['currentType'];
		$numCheckedBoxes = $_POST['numCheckedBoxes'];

		$checkedStuff = $_POST['checkedStuff'];
		$tagArray = explode('-', $checkedStuff);
		$tagIDList = "";
		for($i = 0; $i < count($tagArray); $i++){
			if($i % 2 !== 0){
				$temp = $tagArray[$i];
				$tagIDList = $tagIDList . $temp . ",";
			}
		}
		$tagIDList = substr($tagIDList, 0, -1);
		
		$sql1 = "";
		$query1 = null;
		
		//a type has been selected
		if($currentType !== "none"){
			/*
			tomato and no tags
			number of tags === 0
			WHERE type.type_name = selected type
			ORDER BY type.type_name
			*/
			if($numCheckedBoxes  === '0'){
				$sql1 = 
				"SELECT type.type_name, seed.name, seed.origin, seed.year, crop.start_date, seed.seed_id, crop.crop_id
				FROM crop
				INNER JOIN seed ON crop.seed_id = seed.seed_id
				INNER JOIN type ON seed.type_id = type.type_id
				WHERE type.type_name = :selectedType
				ORDER BY type.type_name";
				$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
				$query1->bindParam(':selectedType', $currentType);
			}
			/*
			tomato and 1 or more tags
			WHERE type.type_name = selected type
			inner query
			ORDER BY type.type_name				
			*/
			else{
				
				$sql1 =
				"SELECT seed.seed_id, type.type_name, seed.name, seed.year, seed.origin, seed.days, seed.quantity, crop.crop_id, crop.start_date, group_concat(tag.tag_name) AS tags
				FROM crop
				INNER JOIN seed ON crop.seed_id = seed.seed_id
				INNER JOIN
				(
					SELECT seedtag.seed_id FROM seedtag
					WHERE seedtag.tag_id IN (:tagIDList)
					GROUP BY seedtag.seed_id
					HAVING COUNT(*) = :numCheckedBoxes
				) AS st
				ON seed.seed_id=st.seed_id
				INNER JOIN type ON seed.type_id = type.type_id
				INNER JOIN seedtag ON seed.seed_id = seedtag.seed_id
				INNER JOIN tag ON seedtag.tag_id = tag.tag_id
				WHERE type.type_name = :selectedType
				GROUP BY type.type_name, seed.name
				ORDER BY type.type_name";
				$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
				$query1->bindParam(':tagIDList', $tagIDList);
				$query1->bindParam(':numCheckedBoxes', $numCheckedBoxes);
				$query1->bindParam(':selectedType', $currentType);				
			}				
		}
		//no type has been selected
		//INNER JOIN seedtag and INNER JOIN tag is to print out the tags for every seed,
		//ignoring for now
		else{
			//ORDER BY type.type_name
			/* this query displays only 2 crops
			$sql1 =
			"SELECT type.type_name, seed.name, seed.origin, seed.year, crop.start_date, seed.seed_id, crop.crop_id, group_concat(tag.tag_name) AS tags
			FROM crop
			INNER JOIN seed ON crop.seed_id = seed.seed_id
			INNER JOIN seedtag ON seed.seed_id = seedtag.seed_id
			INNER JOIN tag ON seedtag.tag_id = tag.tag_id
			INNER JOIN type ON seed.type_id = type.type_id
			GROUP BY type.type_name
			ORDER BY type.type_name";
			*/
			/* I think this query is more correct, but it gives error currently.*/
			$sql1 = 
			"SELECT type.type_name, seed.name, crop.start_date, seed.origin, seed.year, seed.seed_id
			FROM crop
			INNER JOIN seed ON crop.seed_id = seed.seed_id
			INNER JOIN type ON seed.type_id = type.type_id
			ORDER BY type.type_name, crop.start_date DESC";
			
			
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		}
		//query goes here
		$query1->execute();
		return $query1;		
	}
	
	public function radioChangedToDate(){
		$currentType = $_POST['currentType'];
		$numCheckedBoxes = $_POST['numCheckedBoxes'];
		
		$checkedStuff = $_POST['checkedStuff'];
		$tagArray = explode('-', $checkedStuff);
		$tagIDList = "";
		for($i = 0; $i < count($tagArray); $i++){
			if($i % 2 !== 0){
				$temp = $tagArray[$i];
				$tagIDList = $tagIDList . $temp . ",";
			}
		}
		$tagIDList = substr($tagIDList, 0, -1);
				
		$sql1 = "";
		$query1 = null;

		//a type has been selected
		if($currentType !== "none"){
			//tomato and no tags
			//number of tags === 0
			/*
			WHERE type.type_name = selected type					
			ORDER BY date
			*/			
			if($numCheckedBoxes === '0'){
				$sql1 =
				"SELECT type.type_name, seed.name, seed.origin, seed.year, crop.start_date, seed.seed_id, crop.crop_id
				FROM crop
				INNER JOIN seed ON crop.seed_id = seed.seed_id
				INNER JOIN type ON seed.type_id = type.type_id
				WHERE type.type_name = :selectedType
				ORDER BY crop.start_date DESC";
				$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
				$query1->bindParam(':selectedType', $currentType);
			}
			//tomato and 1 or more tags
			/*
			WHERE type.type_name = selected type				
			inner query
			ORDER BY date
			*/			
			else{ 
				$sql1 =
				"SELECT seed.seed_id, type.type_name, seed.name, seed.year, seed.origin, seed.days, seed.quantity, crop.crop_id, crop.start_date, group_concat(tag.tag_name) AS tags
				FROM crop
				INNER JOIN seed ON crop.seed_id = seed.seed_id
				INNER JOIN
				(
					SELECT seedtag.seed_id FROM seedtag
					WHERE seedtag.tag_id IN (:tagIDList)
					GROUP BY seedtag.seed_id
					HAVING COUNT(*) = :numCheckedBoxes
				) AS st
				ON seed.seed_id=st.seed_id
				INNER JOIN type ON seed.type_id = type.type_id
				INNER JOIN seedtag ON seed.seed_id = seedtag.seed_id
				INNER JOIN tag ON seedtag.tag_id = tag.tag_id
				WHERE type.type_name = :selectedType
				GROUP BY type.type_name, seed.name
				ORDER BY crop.start_date DESC";
				$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
				$query1->bindParam(':tagIDList', $tagIDList);
				$query1->bindParam(':numCheckedBoxes', $numCheckedBoxes);
				$query1->bindParam(':selectedType', $currentType);
			}				
		}
		//no type has been selected
		else{
			//ORDER BY date
			
			$sql1 =
			"SELECT type.type_name, seed.name, seed.origin, seed.year, crop.start_date, seed.seed_id, crop.crop_id
			FROM crop
			INNER JOIN seed ON crop.seed_id = seed.seed_id
			INNER JOIN type ON seed.type_id = type.type_id
			ORDER BY crop.start_date DESC";
			
			
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		}
		//query goes here
		$query1->execute();
		return $query1;
	}
	
	public function makeEditNoteForm(){
		$cropPrimaryKey = $_POST['cropprimarykey'];
		$notePrimaryKey = $_POST['noteprimarykey'];
		$_SESSION['updatenoteprimarykey'] = $_POST['noteprimarykey']; //used in updateNote()

		$sql1 = 
		"SELECT *
		FROM note
		WHERE note.note_id = :notePrimaryKey";

		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':notePrimaryKey', $notePrimaryKey);
		$query1->execute();
		$row = $query1->fetch();
		return $row;
	}
	
	//called from pressing 'Edit Note' on Show Existing Notes for Seed
	public function updateNote(){
		$newDateTime = $_POST['cropnotedateandtime'];
		$newNote = $_POST['cropnotenote'];
		$notePrimaryKey = $_SESSION['updatenoteprimarykey']; //set in makeEditNoteForm
		$action = $_POST['action'];
		$action = trim($action);
		
		$sql1 = 		
		"UPDATE note 
		SET note_date= :newDateTime, note_action= :newAction, note_description= :newNote
		WHERE note.note_id= :notePrimaryKey";
		
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':newDateTime', $newDateTime);
		$query1->bindParam(':newAction', $action);
		$query1->bindParam(':newNote', $newNote);
		$query1->bindParam(':notePrimaryKey', $notePrimaryKey);
		$query1->execute();
	}
	
	/*
	for showNotes.php, if radio button is Date
	*/
	public function allNotesForSingleCrop(){
		$currCropPrimaryKey = $_SESSION['cropPrimaryKey'];
		$sql1 = 
		"SELECT * 
		FROM crop 
		INNER JOIN note ON note.crop_id = crop.crop_id
		INNER JOIN seed ON crop.seed_id = seed.seed_id
		INNER JOIN type ON seed.type_id = type.type_id		
		WHERE crop.crop_id= :cropPrimaryKey";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':cropPrimaryKey', $currCropPrimaryKey);
		$query1->execute();
		$numRows = $query1->rowCount();
		
		if($numRows === 0){ //suddenly type is a number not a String?
			return "0 rows";
		}
		return $query1;
	}
	
	
	/*
	gets seed info of the seed I did 'Show Notes' for
	*/
	public function whichSeedOnShowNotesPage(){
		if($_SESSION['typeOrDateRadio'] === "type"){
			$currSeedPrimaryKey = $_SESSION['seedPrimaryKey']; //set in cropController.php, showNotesButtonClicked()
			$sql1 = 
			"SELECT * 
			FROM seed 
			INNER JOIN type ON seed.type_id = type.type_id
			WHERE seed.seed_id = :seedPrimaryKey
			LIMIT 1";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':seedPrimaryKey', $currSeedPrimaryKey);			
		}
		else{
			$currCropPrimaryKey = $_SESSION['cropPrimaryKey']; //set in cropController.php, showNotesButtonClicked()
			$sql1 = 
			"SELECT * 
			FROM seed 
			INNER JOIN type ON seed.type_id = type.type_id
			INNER JOIN crop ON crop.seed_id = seed.seed_id
			WHERE crop.crop_id = :cropPrimaryKey";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':cropPrimaryKey', $currCropPrimaryKey);
		}
		$query1->execute();
		$row = $query1->fetch();
		return $row;
	}

	public function deleteANote(){
		$notePrimaryKey = $_POST['notePrimaryKey'];
		$sql1 = 
		"DELETE FROM note 
		WHERE note.note_id = :notePrimaryKey";
		try{
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':notePrimaryKey', $notePrimaryKey);
			$query1->execute();
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}
	
	/*
	using note start date for both crop.start_date and note.note_date
	*/
	public function addACrop(){
		$seedPrimaryKey = $_SESSION['addCropToThisSeed'];
		$newStartDate = $_POST['cropstartdate'];
		$newCropContainerTotals = "totals 2inch: 0, 4inch: 0, picnic:0";
		$newNoteAction = "Initial Note.";
		$newNote = $_POST['initialnote'];
		$sql1 = 
		"INSERT INTO 
		crop(crop_id, seed_id, start_date, crop_container_totals) 
		VALUES (null,:seedPrimaryKey,:newStartDate, :newCropContainerTotals)";
		try{
			//insert crop
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':seedPrimaryKey', $seedPrimaryKey);
			$query1->bindParam(':newStartDate', $newStartDate);
			$query1->bindParam(':newCropContainerTotals', $newCropContainerTotals);
			$query1->execute();
			
			//insert note, and attach to crop
			$newCropPrimaryKey = $this->databaseConnection->dbConnection->lastInsertId();
			//$_SESSION['cropLastInsertId'] = $newCropPrimaryKey; //used in controller, addACrop()
			$sql2 = 
			"INSERT INTO 
			note(note_id, crop_id, note_date, note_action, note_description) 
			VALUES (null,:newCropPrimaryKey,:noteStartDate, :newNoteAction, :newNote)";
			$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':newCropPrimaryKey', $newCropPrimaryKey);
			$query2->bindParam(':noteStartDate', $newStartDate);
			$query2->bindParam(':newNoteAction', $newNoteAction);
			$query2->bindParam(':newNote', $newNote);
			$query2->execute();
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}
	
	public function updateContainerTotals(){
		$cropPrimaryKey = $_POST['cropPrimaryKey'];
		$containerTotalsString = $_POST['containerTotalsString'];
		echo "crop p k = " . $cropPrimaryKey . "<br> string = " . $containerTotalsString . "<br>";
		try{
			$sql1 = 
			"UPDATE crop 
			SET crop_container_totals= :totalsString
			WHERE crop.crop_id= :cropPrimaryKey";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':totalsString', $containerTotalsString);
			$query1->bindParam(':cropPrimaryKey', $cropPrimaryKey);
			$query1->execute();
		}
		catch(PDOException $ex){
			echo "Error: " . $ex->getMessage();
		}
	}
	
	//gets last insert id of note table 
	public function getMostRecentCropID(){
		$newCropPrimaryKey = $this->databaseConnection->dbConnection->lastInsertId();
		echo "getMostRecentCropID() newCropPrimaryKey = " . $newCropPrimaryKey . "<br>";
		return $newCropPrimaryKey;
	}
	
	/*
	using user defined variable @last_id 
	*/
	public function getMostRecentCropIDV2(){
		$recentCropID;
		try{
			$this->databaseConnection->dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
			
			$sql1 = 
			"SELECT @last_id := MAX(crop_id)
			FROM crop";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->execute();
			
			$sql2 = 
			"SELECT * 
			FROM crop 
			WHERE crop.crop_id = @last_id";
			$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
			$query2->execute();
			$row2 = $query2->fetch();
			echo "row2 crop_id " . $row2['crop_id'] . "<br>";
			$recentCropID = $row2['crop_id'];
		}
		catch(PDOException $ex){
			echo "Error: " . $ex->getMessage();
		}
		
		return $recentCropID;
	}
	
	
}
?>