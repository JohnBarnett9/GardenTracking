<?php 

require_once('../databaseConnection.php');

if(!isset($_SESSION)) 
{ 
	session_start(); 
} 



class typeModelCustom {

	public $databaseConnection;
	
	public function __construct(){
		$this->databaseConnection = new databaseConnection();
	}
	
	public function listAllTypes(){
		$sql = 
			"SELECT * 
			FROM type
			ORDER BY type_name='000' DESC, type.type_name ASC;";
		$stmt = $this->databaseConnection->dbConnection->query($sql);
		return $stmt;
	}
	
	public function allTagsForAType(){
		$typeID = $_POST['typeID'];
		$sql = 
			"SELECT * 
			FROM type
			INNER JOIN typetag ON type.type_id = typetag.type_id
			INNER JOIN tag ON typetag.tag_id = tag.tag_id
			WHERE type.type_id= :typeID
			ORDER BY tag.tag_name";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql);
		$query1->bindParam(':typeID', $typeID);
		$query1->execute();
		return $query1;
	}

	//called from testModule.js 
	//different from allTagsForAType() because uses json_encode()
	public function allTagsForSingleType(){
		$seedType = $_POST['seedType'];
		$sql = 
			"SELECT type.type_name, tag.tag_name, tag.tag_id
			FROM type
			INNER JOIN typetag ON type.type_id = typetag.type_id
			INNER JOIN tag ON typetag.tag_id = tag.tag_id
			WHERE type.type_name= :seedType";
		$stmt = $this->databaseConnection->dbConnection->prepare($sql);
		$stmt->bindParam(':seedType', $seedType);
		$stmt->execute();
		$arrayThing = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			//echo "type " . $row['type_name'] . "<br>";
			array_push($arrayThing, $row['tag_name'] . "-" . $row['tag_id']);
		}
		$myJSON = json_encode($arrayThing);
		echo $myJSON;
	}	
	
	
	
	public function whichTagsDoesThisTypeNotHave(){
		$typeID = $_POST['typeID'];		
		$sql = 
			"SELECT *
			FROM tag
			WHERE tag.tag_name
			NOT IN 
			(
				SELECT tag.tag_name
				FROM type
				INNER JOIN typetag on type.type_id = typetag.type_id
				INNER JOIN tag on typetag.tag_id = tag.tag_id
				WHERE type.type_id = :typeID
			)
			ORDER BY tag.tag_name";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql);
		$query1->bindParam(':typeID', $typeID);
		$query1->execute();
		return $query1;
	}
	
	public function deleteTagFromType($typeID, $tagID){
		$sql = 
			"DELETE FROM typetag 
			WHERE typetag.type_id= :typeID 
			AND typetag.tag_id= :tagID";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql);
		$query1->bindParam(':typeID', $typeID);
		$query1->bindParam(':tagID', $tagID);
		$query1->execute();
	}
	
	public function addTagToType($typeID, $tagID){
		echo "in typeModel addTagToType typeId = " . $typeID . " tagID = " . $tagID . "<br>";
		$sql = "INSERT INTO typetag (type_id, tag_id) VALUES (:typeID, :tagID)";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql);
		$query1->bindParam(':typeID', $typeID);
		$query1->bindParam(':tagID', $tagID);
		$query1->execute();		
	}
	
	public function deleteType($typeID){
		$sql =
			"UPDATE type 
			SET type_name='000' 
			WHERE type_id= :typeID";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql);
		$query1->bindParam(':typeID', $typeID);
		$query1->execute();
	}
	
	public function editTypeName($typeID, $typeName){
		echo "in typeModelCustom editTypeName<br>";
		echo "typeName = " . $typeName . "<br>";
		$sql = 
		"UPDATE type
		SET type_name = :newTypeName
		WHERE type_id = :typeID";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql);
		$query1->bindParam(':newTypeName', $typeName);
		$query1->bindParam(':typeID', $typeID);
		$query1->execute();
		
	}
	
	public function addAType($typeName){
		try{
			$typeName = trim($typeName);
			$sql0 = 
			"SELECT *, COUNT(*) AS rowcount 
			FROM type 
			WHERE type.type_name = :newTypeName 
			LIMIT 1";
			$query0 = $this->databaseConnection->dbConnection->prepare($sql0);
			$query0->bindParam(':newTypeName', $typeName);
			$query0->execute();
			$row1 = $query0->fetch();
			
			if($row1['rowcount'] === "0"){
				
				$sql = 
				"INSERT INTO 
				type (type_id, type_name)
				VALUES (null, :newType)";
				$query1 = $this->databaseConnection->dbConnection->prepare($sql);
				$query1->bindParam(':newType', $typeName);
				$query1->execute();		
				echo "true";
			}
			else{
				echo "false";
			}
		}
		catch (PDOException $ex) {
			echo "Error: " . $ex->getMessage();
		}
		
	}
	
	//called from deleteType() in controller
	public function deleteAllTagForType($typeID){
		$sql = 
			"DELETE FROM typetag 
			WHERE typetag.type_id = :typeID";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql);
		$query1->bindParam(':typeID', $typeID);
		$query1->execute();
	}
	
	/*
	on the editSeed.php page, I need a list of checkboxes
	of the tags available for this seed.
	And the checkboxes this seed already has, need to be checkboxed.
	*/
	public function editSeedCheckboxs(){
		//tag ids for specific seed
		$seedName = $_POST['seedName'];
		$sql2 = 
			"SELECT tag.tag_id
			FROM seed
			INNER JOIN seedtag ON seed.seed_id = seedtag.seed_id
			INNER JOIN tag ON seedtag.tag_id = tag.tag_id
			WHERE seed.name= :seedName";
		$stmt2 = $this->databaseConnection->dbConnection->prepare($sql2);
		$stmt2->bindParam(':seedName', $seedName);
		$stmt2->execute();
		$tagsForSeed = [];
		while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
			array_push($tagsForSeed, $row2['tag_id']);
		}

		//for every tag for this type
		//if the tag is attached to the specific seed, checked
		//if the tag is not attached to the specific seed, no checked
		$seedType = $_POST['seedType'];
		$sql = 
			"SELECT type.type_name, tag.tag_name, tag.tag_id
			FROM type
			INNER JOIN typetag ON type.type_id = typetag.type_id
			INNER JOIN tag ON typetag.tag_id = tag.tag_id
			WHERE type.type_name= :seedType
			ORDER BY tag.tag_name";
		$stmt = $this->databaseConnection->dbConnection->prepare($sql);
		$stmt->bindParam(':seedType', $seedType);
		$stmt->execute();
		$tagsForType = [];
		$listOfCheckboxes = "";
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$tagID = $row['tag_id'];
			$tagName = $row['tag_name'];
			if(in_array($tagID, $tagsForSeed)){
				//checked
				$listOfCheckboxes = $listOfCheckboxes . '<input type="checkbox" name="typeTag" id="' . $tagName . "-" . $tagID . '" checked>' . $tagName . '<br>';
			}
			else{
				//unchecked
				$listOfCheckboxes = $listOfCheckboxes . '<input type="checkbox" name="typeTag" id="' . $tagName . "-" . $tagID . '">' . $tagName . '<br>';
			}
		}
		echo $listOfCheckboxes;
	}
	
	//called from testModule.js availSeedType
	//called from testModuleEditSeed.js availSeedType
	public function getAvailableSeedTypes(){
		$sql = 
			"SELECT DISTINCT type.type_name 
			FROM type
			WHERE type.type_name != '000'
			ORDER BY type.type_name";
		$stmt = $this->databaseConnection->dbConnection->prepare($sql);
		$stmt->execute();
		$arrayThing = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			array_push($arrayThing, $row['type_name']);
		}
		$myJSON = json_encode($arrayThing);
		echo $myJSON;
	}
}
?>