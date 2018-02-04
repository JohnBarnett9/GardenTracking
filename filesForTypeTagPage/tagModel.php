<?php 

require_once('../databaseConnection.php');

if(!isset($_SESSION)) 
{ 
	session_start(); 
} 

class tagModel {
	
	public $databaseConnection;
	
	public function __construct(){
		$this->databaseConnection = new databaseConnection();
	}
	
	public function listAllTags(){
		$sql = 
		"SELECT * 
		FROM tag
		ORDER BY tag.tag_name";
		$stmt = $this->databaseConnection->dbConnection->query($sql);
		return $stmt;
	}
	
	public function deleteTag($tagID){
		//must delete tag from seedtag table
		//and typetag table,
		//before I can delete tag from tag table
		
		//delete tag from seedtag table
		$tagID = $_POST['tagID'];
		$sql1 = 
		"DELETE FROM 
		seedtag 
		WHERE seedtag.tag_id= :tagID;";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':tagID', $tagID);
		$query1->execute();
		
		//delete tag from typetag table
		$sql2 = 
		"DELETE FROM 
		typetag
		WHERE typetag.tag_id= :tagID";
		$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
		$query2->bindParam(':tagID', $tagID);
		$query2->execute();
		
		//delete tag from tag table
		$sql3 = 
		"DELETE FROM
		tag
		WHERE tag.tag_id= :tagID";
		$query3 = $this->databaseConnection->dbConnection->prepare($sql3);
		$query3->bindParam(':tagID', $tagID);
		$query3->execute();
	}
	
	public function editATag(){
		$tagID = $_POST['tagID'];
		$tagName = $_POST['currTagName'];
		$tagName = trim($tagName); //may not be necessary because if any whitespace,
		//then ajax call won't even happen
		
		$sql1 = 
		"SELECT *, COUNT(*) as rowcount 
		FROM tag 
		WHERE tag.tag_name = :newTagName";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':newTagName', $tagName);
		$query1->execute();
		$row1 = $query1->fetch();
		if($row1['rowcount'] === "0"){
			$sql2 = 
			"UPDATE tag 
			SET tag_name = :newTagName
			WHERE tag_id = :currTagID";
			$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':newTagName', $tagName);
			$query2->bindParam(':currTagID', $tagID);
			$query2->execute();
			echo "true";
		}
		else{
			echo "false";
		}		
	}
	
	public function addATag($tagName){
		$newTagName = $_POST['newTag'];
		$sql1 = 
		"SELECT *, COUNT(*) AS rowcount
		FROM tag 
		WHERE tag.tag_name = :newTagName 
		LIMIT 1";
		$query1 = $this->databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':newTagName', $newTagName);
		$query1->execute();
		$row1 = $query1->fetch();
		if($row1['rowcount'] === "0"){		
			$sql2 = 
			"INSERT INTO tag (tag_id, tag_name)
			VALUES (null, :newTag)";
			$query2 = $this->databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':newTag', $tagName);
			$query2->execute();
			echo "true";
		}
		else{
			echo "false";
		}		
	}
	
	//used with command 14 from searchController.php
	//called from testModule.js, availSeedTags, which calls searchController
	//called from testModuleEditSeed.js
	public function getAvailableSeedTags(){
		//$sql = "SELECT DISTINCT tag_description FROM tags";
		$sql = "SELECT DISTINCT tag_name FROM tag";
		$stmt = $this->databaseConnection->dbConnection->prepare($sql);
		$stmt->execute();
		$arrayThing = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			//echo "type " . $row['type'] . "<br>";
			array_push($arrayThing, $row['tag_name']);
		}
		$myJSON = json_encode($arrayThing);
		echo $myJSON;
	}
	
	public function isTagDuplicate(){
		$currTagName = $_POST['currTagName'];
		$sql = 
		"SELECT *, COUNT(*) AS rowcount
		FROM tag
		WHERE tag.tag_name= :currTagName
		LIMIT 1";
		$query = $this->databaseConnection->dbConnection->prepare($sql);
		$query->bindParam(':currTagName', $currTagName);
		$query->execute();
		$row = $query->fetch();
		return $row;
	}
}
?>