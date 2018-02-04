<?php 

require_once('../databaseConnection.php');

if(!isset($_SESSION)) 
{ 
	session_start(); 
} 

class seedModel{

	public $typeModel;
	public $seedTagModel;
	public $databaseConnection;

	public function __construct(){
		$this->databaseConnection = new databaseConnection();
	}
	
	/*
	Returns resultset of all seeds in the seed table.
	*/
	public function listAllSeedsinseedtable(){
		$sql = 
			"SELECT seed.seed_id, type.type_name, seed.name, seed.year, seed.origin, seed.days, seed.quantity, seed.note, COUNT(*) as rowcount, group_concat(tag.tag_name) AS tags
			FROM seed
			INNER JOIN type ON seed.type_id = type.type_id
			LEFT JOIN seedtag ON seed.seed_id = seedtag.seed_id
			LEFT JOIN tag ON seedtag.tag_id = tag.tag_id
			GROUP BY type.type_name, seed.name, seed.year, seed.origin";		
		$stmt = $this->databaseConnection->dbConnection->query($sql);
		return $stmt;
	}

	/*
	for download text document, only difference is sorted by Type, Name, Origin, Year
	*/
	public function allSeedsXLSDownload(){
		$sql = 
			"SELECT seed.note, seed.seed_id, type.type_name, seed.name, seed.year, seed.origin, seed.days, seed.quantity, group_concat(tag.tag_name) AS tags
			FROM seed
			INNER JOIN type ON seed.type_id = type.type_id
			LEFT JOIN seedtag ON seed.seed_id = seedtag.seed_id
			LEFT JOIN tag ON seedtag.tag_id = tag.tag_id
			GROUP BY type.type_name, seed.name, seed.origin, seed.year
			ORDER BY seed.note DESC";
		$stmt = $this->databaseConnection->dbConnection->query($sql);
		return $stmt;
	}	
	
	/*
	Returns an array of Types and the list of Tags for each Type.
	Example:
	Type Bean has these tags: pole, bush, broad.
	*/
	public function filterList(){
		$sql = 
		"SELECT type_name 
		FROM type
		ORDER BY type.type_name";
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
	
	/*
	This code runs when a vegetable Type in the filterlist is clicked.
	The List of seeds returned includes only the seeds whose Type was clicked.
	*/
	public function htmlTableSeedType(){
		$a = $_POST['currentType'];
		$sql = 
			"SELECT seed.seed_id, type.type_name, seed.name, seed.year, seed.origin, seed.days, seed.quantity, seed.note, group_concat(tag.tag_name) AS tags
			FROM seed
			INNER JOIN type ON seed.type_id = type.type_id
			LEFT JOIN seedtag ON seed.seed_id = seedtag.seed_id
			LEFT JOIN tag ON seedtag.tag_id = tag.tag_id
			WHERE type.type_name = '$a'
			GROUP BY type.type_name, seed.name, seed.year, seed.origin";
		$stmt = $this->databaseConnection->dbConnection->query($sql);
		if($stmt === false){
			die("Error executing the query: SELECT * FROM seed WHERE type ='$a'");
		}
		return $stmt;
	}
	
	/*
	This code runs when a checkbox in the filterlist is clicked.
	The List of seeds returned includes only the seeds whose Type was clicked, and whose tags have been checkboxed.
	*/
	public function htmlTableSeedTypeCheck(){
		$sql = "";
		$a = $_POST['currentType'];
		$numCheckedBoxes = $_POST['numCheckedBoxes'];
		$checkedTagNames = $_POST['checkedTagNames'];
		$tagArray = explode('-', $checkedTagNames);
		$tagIDList = "";
		for($i = 0; $i < count($tagArray); $i++){
			if($i % 2 !== 0){
				$temp = $tagArray[$i];
				$tagIDList = $tagIDList . $temp . ",";
			}
		}
		$tagIDList = substr($tagIDList, 0, -1);
				
		$sql = 
			"SELECT seed.seed_id, type.type_name, seed.name, seed.year, seed.origin, seed.days, seed.quantity, seed.note,  group_concat(tag.tag_name) AS tags
			FROM seed 
			INNER JOIN
			(
				SELECT seedtag.seed_id FROM seedtag
				WHERE seedtag.tag_id IN ($tagIDList)
				GROUP BY seedtag.seed_id
				HAVING COUNT(*) = $numCheckedBoxes
			) AS st
			ON seed.seed_id=st.seed_id
			INNER JOIN type ON seed.type_id = type.type_id
			INNER JOIN seedtag ON seed.seed_id = seedtag.seed_id
			INNER JOIN tag ON seedtag.tag_id = tag.tag_id
			GROUP BY type.type_name, seed.name, seed.year, seed.origin";		
		$stmt = $this->databaseConnection->dbConnection->query($sql);
		if($stmt === false){
			die("Error executing the query: SELECT * FROM seed WHERE type = .....");
		}
		return $stmt;
	}
	
	/*
	Called from controller editButtonClicked().
	Returns resultset that contains all available information about a single seed.
	*/
	public function allInfoSingleSeed($seedID){
		$sql = 
			"SELECT seed.seed_id, type.type_name, seed.name, seed.year, seed.origin, seed.days, seed.quantity, seed.note, group_concat(tag.tag_name) AS tags
			FROM seed 
			INNER JOIN type ON seed.type_id = type.type_id
			LEFT JOIN seedtag ON seed.seed_id = seedtag.seed_id
			LEFT JOIN tag ON seedtag.tag_id = tag.tag_id
			WHERE seed.seed_id = :param
			GROUP BY type.type_name, seed.name;";
		$query = $this->databaseConnection->dbConnection->prepare($sql);
		$query->bindParam(':param', $seedID);
		$query->execute();
		$row = $query->fetch();

		return $row;		
	}

	
	/*
	Called from commandForSearchController: 6.
	This function can handle duplicate seed and no tags vs tags.
	*/
	public function editSeed(){
		$currSeedPrimaryKey = $_SESSION['editSeedPrimaryKey'];
		
		//unpack serialized textfields
		$params = array();
		parse_str($_POST['serializedData'], $params);
		
		$type = trim($params['Ftype']);
		$name = trim($params['Fname']);
		$year = trim($params['Fyear']);
		$origin = trim($params['Forigin']);
		$days = trim($params['Fdays']);
		$quantity = trim($params['Fquantity']);

		try{
			//transform Type name into Type ID 
			$sql1 = 
			"SELECT type.type_id 
			FROM type 
			WHERE type.type_name = :typeName";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':typeName', $type);
			$query1->execute();
			$row1 = $query1->fetch();
			$typeID = $row1['type_id'];
			
			//UPDATE all values of seed
			$sql3 = 
			"UPDATE seed 
			SET seed.type_id= :seedType, seed.name= :seedName, seed.year= :seedYear, seed.origin= :seedOrigin, seed.days= :seedDays, seed.quantity= :seedQuantity
			WHERE seed.seed_id= :seedPK";
			$query3 = $this->databaseConnection->dbConnection->prepare($sql3);
			$query3->bindParam(':seedType', $typeID);
			$query3->bindParam(':seedName', $name);
			$query3->bindParam(':seedYear', $year);
			$query3->bindParam(':seedOrigin', $origin);
			$query3->bindParam(':seedDays', $days);
			$query3->bindParam(':seedQuantity', $quantity);
			$query3->bindParam(':seedPK', $currSeedPrimaryKey);				
			$query3->execute();
			
			$last_id = $_SESSION['editSeedPrimaryKey']; //UPDATEing not INSERTing, so not using lastInsertId()

			//delete all seedtag entries 
			$sql5 = 
			"DELETE FROM seedtag 
			WHERE seed_id = :seedPK";
			$query5 = $this->databaseConnection->dbConnection->prepare($sql5);
			$query5->bindParam(':seedPK', $last_id);
			$query5->execute();
			
			//if tags were checkboxed, process tags 
			$listTags = $_POST['listTagIDs'];
			if($listTags !== ''){
				$arrayTagIDs = explode("-", $listTags);
				foreach($arrayTagIDs as $item){
					$sql4 = 
					"INSERT INTO seedtag 
					(seed_id, tag_id) 
					VALUES (:seedPK, :tagPK)";
					$query4 = $this->databaseConnection->dbConnection->prepare($sql4);
					$query4->bindParam(':seedPK', $last_id);
					$query4->bindParam(':tagPK', $item);
					$query4->execute();
				}
			}
			echo "updated";
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}		
	}	
	
	
	/*
	This function runs when the delete button on the searchSeeds.php page is clicked.
	*/
	public function deleteSingleSeed($seedPrimaryKey){
		$sql1 = "DELETE FROM seedtag WHERE seedtag.seed_id=$seedPrimaryKey";
		$stmt1 = $this->databaseConnection->dbConnection->query($sql1);
		if($stmt1 === false){
			die("Error executing the query: ");
		}

		$sql2 = "DELETE FROM seed WHERE seed_id=$seedPrimaryKey";
		$stmt2 = $this->databaseConnection->dbConnection->query($sql2);
		if($stmt2 === false){
			die("Error executing the query: ");
		}
	}

	
	/*
	used with add seed 
	
	called from testModule.js 
	$("#addSeedFormButton").on("click", function(event){
	this is the 1st half of INSERTing a new seed
	*/
	public function isSeedUnique(){
		$params = array();
		parse_str($_POST['serializedData'], $params);
		
		$type = $params['Ftype'];
		$name = $params['Fname'];
		$year = $params['Fyear'];
		$origin = $params['Forigin'];
		$days = $params['Fdays'];
		$quantity = $params['Fquantity'];
		$note = $params['Fnote'];
		
		$type = trim($type);
		$name = trim($name);
		$year = trim($year);
		$origin = trim($origin);
		$days = trim($days);
		$quantity = trim($quantity);
		$note = trim($note);

		try{
			/*
			access ID of type that corresponds with name of type
			that was entered by user			
			*/
			$sql1 = 
			"SELECT type_id 
			FROM type 
			WHERE type_name= :typeVariable";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':typeVariable', $type);
			$query1->execute();
			$row1 = $query1->fetch();
			$typeID = $row1['type_id'];
			
			/*
			find rowcount,
			if rowcount == "0", seed is unique
			*/
			$sql2 = 
			"SELECT COUNT(*) as rowcount 
			FROM seed 
			WHERE 
				seed.type_id = :possibleSeedType
				AND seed.name = :possibleSeedName 
				AND seed.origin = :possibleSeedOrigin 
				AND seed.year = :possibleSeedYear
				AND seed.note = :possibleSeedNote";
			$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':possibleSeedType', $typeID);
			$query2->bindParam(':possibleSeedName', $name);
			$query2->bindParam(':possibleSeedOrigin', $origin);
			$query2->bindParam(':possibleSeedYear', $year);
			$query2->bindParam(':possibleSeedNote', $note);
			$query2->execute();
			$row2 = $query2->fetch();

			if($row2['rowcount'] == "0"){
				echo "unique";
			}
			else{
				echo "duplicate";
			}
			
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}		
	}

	/*
	summary:
	did5MainFieldsChange()
	isSeedUniqueEdit()
	updateSeed5MainFields()
	
	
	did any of the 5 main fields change?
	yes										no 
	is seed unique							do nothing
	yes				no 
	inserted		duplicate error
	
	
	command 24
	*/
	public function did5MainFieldsChange(){
		$currSeedPrimaryKey = $_SESSION['editSeedPrimaryKey'];//set in editButtonClicked() in controller
		
		$params = array();
		parse_str($_POST['serializedData'], $params);
		
		$type = $params['Ftype'];
		$name = $params['Fname'];
		$year = $params['Fyear'];
		$origin = $params['Forigin'];
		$days = $params['Fdays'];
		$quantity = $params['Fquantity'];
		$note = $params['Fnote'];
		
		//these variables are from ajax
		$type = trim($type);
		$name = trim($name);
		$year = trim($year);
		$origin = trim($origin);
		$days = trim($days);
		$quantity = trim($quantity);
		$note = trim($note);
		
		try{
			//need database values of this seed
			$sql1 = 
			"SELECT * 
			FROM seed 
			INNER JOIN type ON seed.type_id = type.type_id
			WHERE seed_id = :seedPrimaryKey
			LIMIT 1";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(":seedPrimaryKey", $currSeedPrimaryKey);
			$query1->execute();
			$row1 = $query1->fetch();
			
			//keeping naming consistent with variables in didDaysOrQuantityChange()
			$databaseVersionOfType = $row1['type_name'];
			$databaseVersionOfName = $row1['name'];
			$databaseVersionOfYear = $row1['year'];
			$databaseVersionOfOrigin = $row1['origin'];
			$databaseVersionOfNote = $row1['note'];
			
			if(
			(strcmp($databaseVersionOfType, $type) != 0) || 
			(strcmp($databaseVersionOfName, $name) != 0) ||
			(strcmp($databaseVersionOfYear, $year) != 0) ||
			(strcmp($databaseVersionOfOrigin, $origin) != 0) ||
			(strcmp($databaseVersionOfNote, $note) != 0)
			){
				//changed
				echo "changed";
			}
			else{
				//unchanged
				echo "unchanged";
			}			
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}				
	}
	
	/*
	used with Edit Seed
	*/
	public function isSeedUniqueEdit(){
		//echo "in isSeedUniqueEdit()";
		$params = array();
		parse_str($_POST['serializedData'], $params);
		
		$type = $params['Ftype'];
		$name = $params['Fname'];
		$year = $params['Fyear'];
		$origin = $params['Forigin'];
		$days = $params['Fdays'];
		$quantity = $params['Fquantity'];
		$note = $params['Fnote'];
		
		$type = trim($type);
		$name = trim($name);
		$year = trim($year);
		$origin = trim($origin);
		$days = trim($days);
		$quantity = trim($quantity);
		$note = trim($note);

		try{
			/*
			access ID of type that corresponds with name of type
			that was entered by user			
			*/
			$sql1 = 
			"SELECT type_id 
			FROM type 
			WHERE type_name= :typeVariable";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':typeVariable', $type);
			$query1->execute();
			$row1 = $query1->fetch();
			$typeID = $row1['type_id'];
			
			/*
			find rowcount,
			if rowcount == "0", seed is unique
			*/
			$sql2 = 
			"SELECT COUNT(*) as rowcount 
			FROM seed 
			WHERE 
				seed.type_id = :possibleSeedType
				AND seed.name = :possibleSeedName 
				AND seed.origin = :possibleSeedOrigin 
				AND seed.year = :possibleSeedYear
				AND seed.note = :possibleSeedNote";
			$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':possibleSeedType', $typeID);
			$query2->bindParam(':possibleSeedName', $name);
			$query2->bindParam(':possibleSeedOrigin', $origin);
			$query2->bindParam(':possibleSeedYear', $year);
			$query2->bindParam(':possibleSeedNote', $note);
			$query2->execute();
			$row2 = $query2->fetch();

			if($row2['rowcount'] == "0"){
				echo "unique";
			}
			else{
				echo "duplicate";
			}
			
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}		
	}
	
	/*
	command 19 , called from testModuleEditSeed.js 
	the code to deserialize is same for command 18 also
	*/
	public function updateSeed5MainFields(){
		echo "in updateSeed5MainFields()";

		$currSeedPrimaryKey = $_SESSION['editSeedPrimaryKey'];//set in editButtonClicked() in controller
				
		$params = array();
		parse_str($_POST['serializedData'], $params);
		
		$type = $params['Ftype'];
		$name = $params['Fname'];
		$year = $params['Fyear'];
		$origin = $params['Forigin'];
		$days = $params['Fdays'];
		$quantity = $params['Fquantity'];
		$note = $params['Fnote'];
		
		$type = trim($type);
		$name = trim($name);
		$year = trim($year);
		$origin = trim($origin);
		$days = trim($days);
		$quantity = trim($quantity);
		$note = trim($note);

		try{
			/*
			access ID of type that corresponds with name of type
			that was entered by user			
			*/
			$sql1 = 
			"SELECT type_id 
			FROM type 
			WHERE type_name= :typeVariable";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':typeVariable', $type);
			$query1->execute();
			$row1 = $query1->fetch();
			$typeID = $row1['type_id'];
			
			//INSERT the new seed
			$sql2 = 
			"UPDATE seed 
			SET type_id = :typeVar, name = :nameVar, year = :yearVar, origin = :originVar, note = :noteVar
			WHERE seed_id = :seedPrimaryKey";
			$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':typeVar', $typeID);
			$query2->bindParam(':nameVar', $name);
			$query2->bindParam(':yearVar', $year);
			$query2->bindParam(':originVar', $origin);
			$query2->bindParam(':noteVar', $note);
			$query2->bindParam(':seedPrimaryKey', $currSeedPrimaryKey);
			$query2->execute();
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}		
	}
	
	
	/*
	command 20
	*/
	public function didDaysOrQuantityChange(){
		$currSeedPrimaryKey = $_SESSION['editSeedPrimaryKey'];//set in editButtonClicked() in controller
		
		$params = array();
		parse_str($_POST['serializedData'], $params);
		
		$days = $params['Fdays'];
		$quantity = $params['Fquantity'];
		
		$days = trim($days);
		$quantity = trim($quantity);

		try{
			$sql1 = 
			"SELECT * 
			FROM seed 
			WHERE seed_id = :seedPrimaryKey
			LIMIT 1";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(":seedPrimaryKey", $currSeedPrimaryKey);
			$query1->execute();
			$row1 = $query1->fetch();
			$databaseVersionOfDays = $row1['days'];
			$databaseVersionOfQty = $row1['quantity'];
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}

		//can't have debugging lines like this, or the if() in the js will fail
		//echo "days = " . $days . " db version of days " . $databaseVersionOfDays . "<br>";
		
		if((strcmp($databaseVersionOfDays, $days) != 0) || (strcmp($databaseVersionOfQty, $quantity) != 0)){
			//changed
			echo "changed";
		}
		else{
			//unchanged
			echo "unchanged";
		}
	}	
	
	/*
	command 21
	*/
	public function updateDaysAndQuantity(){
		$currSeedPrimaryKey = $_SESSION['editSeedPrimaryKey'];//set in editButtonClicked() in controller
		
		$params = array();
		parse_str($_POST['serializedData'], $params);
		
		$days = $params['Fdays'];
		$quantity = $params['Fquantity'];
		
		$days = trim($days);
		$quantity = trim($quantity);

		try{
			$sql1 = 
			"UPDATE seed 
			SET days = :newDays, quantity = :newQuantity
			WHERE seed_id = :seedPrimaryKey";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(":newDays", $days);
			$query1->bindParam(":newQuantity", $quantity);
			$query1->bindParam(":seedPrimaryKey", $currSeedPrimaryKey);
			$query1->execute();
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}
	
	/*
	command 22
	*/
	public function didListOfTagsChange(){
		//echo "1111<br>";
		$currSeedPrimaryKey = $_SESSION['editSeedPrimaryKey'];//set in editButtonClicked() in controller
		$listOfTags = $_POST['listTagIDs'];
		//echo "listOfTags = " . $listOfTags . "<br>";
		
		/*
		if size of arrays are different, automatically not same 
		then compare each element 
		start with not equal, boolean var to remember if any are equal
		trim each element before the compare 
		*/
		//process list of tags currently assigned to seed, from database
		$sql1 = 		
		"SELECT seed.seed_id, seed.name, COUNT(*) as rowcount, group_concat(tag.tag_id) as tagsID, group_concat(tag.tag_name) AS tags
		FROM seed
		LEFT JOIN seedtag ON seed.seed_id = seedtag.seed_id
		LEFT JOIN tag ON seedtag.tag_id = tag.tag_id
		WHERE seed.seed_id = :seedPrimaryKey
		GROUP BY seed.name
		LIMIT 1";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(":seedPrimaryKey", $currSeedPrimaryKey);
		$query1->execute();
		$row1 = $query1->fetch();
		$listTagsFromDB = $row1['tagsID'];
		$listTagsFromDBArray = explode(",", $listTagsFromDB);
		/*
		echo "2222<br>";
		echo "listTagsFromDBArray before sort <br>";
		echo "<br>";
		print_r($listTagsFromDBArray);
		echo "<br>";
		*/
		sort($listTagsFromDBArray);
		/*
		echo "<br>";
		echo "listTagsFromDBArray after sort ";
		echo "<br>";
		print_r($listTagsFromDBArray);
		echo "<br>";
		*/
		
		//process list of tags from the ajax call
		$tagsFromAjax = explode("-", $listOfTags);
		/*
		echo "tagsFromAjax before sort";
		echo "<br>";
		print_r($tagsFromAjax);
		echo "<br>";
		*/
		sort($tagsFromAjax);
		/*
		echo "<br>";
		echo "tagsFromAjax after sort";
		print_r($tagsFromAjax);
		echo "<br>";
		*/
		
		//if length of arrays differ, the contents will not matching
		if(count($listTagsFromDBArray) != count($tagsFromAjax)){
			//echo "3<br>";
			echo "changed";
		}
		else{
			/*
			need flag, otherwise for loop gets through array and when you 
			exit for loop, still don't know if contents are different.
			
			couldn't I just do echo "changed"; break; in the if in the for()?
			*/
			$different = false;
			//compare every element of each array
			for($i = 0; $i < count($listTagsFromDBArray); $i++){
				$currDB = $listTagsFromDBArray[$i];
				$currDB = trim($currDB);
				$currAjax = $tagsFromAjax[$i];
				$currAjax = trim($currAjax);
				if($currDB != $currAjax){ 
					//echo "tags from db = " . $currDB . " tags from ajax = " . $currAjax . "<br>";
					$different = true;
					break;
				}
			}
			//echo "4<br>";
			if($different == true){
				//echo "5<br>";
				echo "changed";
			}
		}
		
		
		
	}
	
	/*
	 command 23
	*/
	public function updateListOfTags(){
		$currSeedPrimaryKey = $_SESSION['editSeedPrimaryKey'];//set in editButtonClicked() in controller
		
		//delete all seedtag entries 
		$sql5 = 
		"DELETE FROM seedtag 
		WHERE seed_id = :seedPK";
		$query5 = $this->databaseConnection->dbConnection->prepare($sql5);
		$query5->bindParam(':seedPK', $currSeedPrimaryKey);
		$query5->execute();
		
		//if tags were checkboxed, process tags 
		$listTags = $_POST['listTagIDs'];
		if($listTags !== ''){
			$arrayTagIDs = explode("-", $listTags);
			foreach($arrayTagIDs as $item){
				$sql4 = 
				"INSERT INTO seedtag 
				(seed_id, tag_id) 
				VALUES (:seedPK, :tagPK)";
				$query4 = $this->databaseConnection->dbConnection->prepare($sql4);
				$query4->bindParam(':seedPK', $currSeedPrimaryKey);
				$query4->bindParam(':tagPK', $item);
				$query4->execute();
			}
		}
		echo "updated";
		
	}
	
	
	/*
	called from testModule.js 
	$("#addSeedFormButton").on("click", function(event){
	this is the 2nd half of INSERTing a new seed	
	*/
	public function addANewSeed(){

		$params = array();
		parse_str($_POST['serializedData'], $params);
		
		$type = $params['Ftype'];
		$name = $params['Fname'];
		$year = $params['Fyear'];
		$origin = $params['Forigin'];
		$days = $params['Fdays'];
		$quantity = $params['Fquantity'];
		$note = $params['Fnote'];
		
		
		try {
			/*
			access ID of type that corresponds with name of type
			that was entered by user			
			*/
			$sql1 = 
			"SELECT type_id 
			FROM type 
			WHERE type_name= :typeVariable";
			$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':typeVariable', $type);
			$query1->execute();
			$row1 = $query1->fetch();
			$typeID = $row1['type_id'];
			
			//INSERT the new seed
			$sql2 = 
			"INSERT INTO 
			seed (seed_id, type_id, name, year, origin, days, quantity, note) 
			VALUES (null, :typeVar, :nameVar, :yearVar, :originVar, :daysVar, :quantityVar, :noteVar)";
			$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':typeVar', $typeID);
			$query2->bindParam(':nameVar', $name);
			$query2->bindParam(':yearVar', $year);
			$query2->bindParam(':originVar', $origin);
			$query2->bindParam(':daysVar', $days);
			$query2->bindParam(':quantityVar', $quantity);
			$query2->bindParam(':noteVar', $note);
			$query2->execute();
			
			//get ID of last inserted record
			$last_id = $this->databaseConnection->dbConnection->lastInsertId();
			$listTags = $_POST['listTags'];
			if($listTags !== ''){
				$arrayTagIDs = explode("-", $listTags);
				
				//INSERT the tags corresponding to the new seed
				foreach($arrayTagIDs as $item){
					$query3 = $this->databaseConnection->dbConnection->prepare("INSERT INTO seedtag (seed_id, tag_id) VALUES (:pkSeed, :pkTag)");
					$query3->bindParam(':pkSeed', $last_id);
					$query3->bindParam(':pkTag', $item);
					$query3->execute();
				}					
			}
			echo "inserted";
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}
	}	
	
	/*
	Returns resultset containing the seed origins that are available.
	*/
	public function getAvailableSeedOrigins(){
		$sql = 
			"SELECT DISTINCT origin 
			FROM seed
			ORDER BY seed.origin";
		$stmt = $this->databaseConnection->dbConnection->prepare($sql);
		$stmt->execute();
		$arrayThing = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			//echo "type " . $row['type'] . "<br>";
			array_push($arrayThing, $row['origin']);
		}
		$myJSON = json_encode($arrayThing);
		echo $myJSON;
	}
}
?>