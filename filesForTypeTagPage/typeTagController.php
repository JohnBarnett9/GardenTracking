<?php 
session_start();

require_once('typeTagView.php');
require_once('typeModel.php');
require_once('tagModel.php');

class typeTagController {
	public $typeTagViewThing;
	public $typeModelThing;
	public $tagModelThing;
	
	public function __construct(){
		$this->typeTagViewThing = new typeTagView();
		$this->typeModelThing = new typeModelCustom();
		$this->tagModelThing = new tagModel();
		
		$this->typeModelThing->databaseConnection->setupDB();
		$this->typeModelThing->databaseConnection->setupConnection();
		
		$this->tagModelThing->databaseConnection->setupDB();
		$this->tagModelThing->databaseConnection->setupConnection();
		
		if($_POST['commandForTypeTagController'] === '1'){
			$this->listAllTypes();
		}
		else if($_POST['commandForTypeTagController'] === '2'){
			$this->listAllTags();
		}
		else if($_POST['commandForTypeTagController'] === '3'){
			$this->generateDropdownBox();
		}
		else if($_POST['commandForTypeTagController'] === '4'){
			$this->tagsForAType();
		}
		else if($_POST['commandForTypeTagController'] === '5'){
			$this->availableTagsForType();
		}
		else if($_POST['commandForTypeTagController'] === '7'){
			$this->deleteTagFromType();
		}
		else if($_POST['commandForTypeTagController'] === '8'){
			$this->addTagToType();
		}
		else if($_POST['commandForTypeTagController'] === '9'){
			$this->deleteType();
		}
		else if($_POST['commandForTypeTagController'] === '10'){
			$this->editTypeName();
		}
		else if($_POST['commandForTypeTagController'] === '11'){
			$this->addAType();
		}
		else if($_POST['commandForTypeTagController'] === '12'){
			$this->deleteTag();
		}
		else if($_POST['commandForTypeTagController'] === '13'){
			$this->editATag();
		}
		else if($_POST['commandForTypeTagController'] === '14'){
			$this->addATag();
		}
		else if($_POST['commandForTypeTagController'] === '15'){
			$this->isTagDuplicateForEditTag();
		}
		else if($_POST['commandForTypeTagController'] === '16'){
			$this->isTagDuplicateForAddTag();
		}
		
		$this->typeModelThing->databaseConnection->dbConnection = NULL;
		$this->typeModelThing->databaseConnection = NULL;
		
		$this->tagModelThing->databaseConnection->dbConnection = NULL;
		$this->tagModelThing->databaseConnection = NULL;
	}
	
	public function listAllTypes(){
		$allTypesResultSet = $this->typeModelThing->listAllTypes();
		$this->typeTagViewThing->generateHTMLTypesCheckboxes($allTypesResultSet);
	}
	
	public function listAllTags(){
		$allTagsResultSet = $this->tagModelThing->listAllTags();
		$this->typeTagViewThing->generateHTMLTagsCheckboxes($allTagsResultSet);
	}
	
	public function generateDropdownBox(){
		//reuse listAllTypes() from typeModel
		$allTypesResultSet = $this->typeModelThing->listAllTypes();
		$this->typeTagViewThing->assignTagsToTypeDropdownBox($allTypesResultSet);
	}
	
	public function tagsForAType(){
		$tagsForTypeResultSet = $this->typeModelThing->allTagsForAType();
		$this->typeTagViewThing->assignedTagsForThisType($tagsForTypeResultSet);
	}
	
	public function availableTagsForType(){
		$resultSet = $this->typeModelThing->whichTagsDoesThisTypeNotHave();
		$this->typeTagViewThing->generateCheckboxesAvailableTagsForType($resultSet);
	}
	
	/*
	I am making decision to do delete from the typeModelCustom class instead of 
	from the tagModel class.
	*/
	public function deleteTagFromType(){
		$typeID = $_POST['typeID'];
		$tagID = $_POST['tagID'];
		echo "typeID = " . $typeID . " tagID = " . $tagID . "<br>";
		$this->typeModelThing->deleteTagFromType($typeID, $tagID);
	}
	
	public function addTagToType(){
		$typeID = $_POST['typeID'];
		$tagID = $_POST['tagID'];
		$this->typeModelThing->addTagToType($typeID, $tagID);
	}
	
	public function deleteType(){
		$typeID = $_POST['typeID'];
		$this->typeModelThing->deleteType($typeID); //change type_name to '000'
		$this->typeModelThing->deleteAllTagForType($typeID); //delete all tags associated to this type, in typetag table
		/*
		where MVC in PHP really shines:
		can reuse code that I have already written
		list the type checkboxes
		*/
		$this->listAllTypes();
	}
	
	public function editTypeName(){
		$typeID = $_POST['typeID'];
		$typeName = $_POST['typeName'];
		$this->typeModelThing->editTypeName($typeID, $typeName);
	}
	
	//command 11
	public function addAType(){
		$typeName = $_POST['newTagName'];
		$this->typeModelThing->addAType($typeName);
	}
	
	public function deleteTag(){
		$tagID = $_POST['tagID'];
		$this->tagModelThing->deleteTag($tagID); //delete tag from seedtag, typtag, then tag
	}
	
	public function editATag(){
		$tagID = $_POST['tagID'];
		$tagName = $_POST['currTagName'];
		$this->tagModelThing->editATag($tagID, $tagName);
	}
	
	//command 14
	public function addATag(){
		$tagName = $_POST['newTag'];
		$this->tagModelThing->addATag($tagName);
	}
	
	//if edit tag button clicked
	public function isTagDuplicateForEditTag(){
		$row = $this->tagModelThing->isTagDuplicate();
		if($row['rowcount'] == "0"){
			echo "true"; //rowcount is 0
		}
		else{
			echo "false";
		}
	}
	
	//command 16 
	//if add tag button clicked
	public function isTagDuplicateForAddTag(){
		$row = $this->tagModelThing->isTagDuplicateAdd();

	}
}

$ttController = new typeTagController();
?>