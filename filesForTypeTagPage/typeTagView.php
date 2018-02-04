<?php 

class typeTagView {
	public function __construct(){}
	public function generateHTMLTypesCheckboxes($stmt){
		echo '<div class="containerTypeList">';
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$typeID = $row['type_id'];
			$typeName = $row['type_name'];
			//checkbox
			echo '<input type="checkbox" name="typeTag" id="checkbox-' . $typeName . "-" . $typeID . '">' . $typeName;
			//break
			echo '<br>';
		}
		echo '</div>';
	}
	
	public function generateHTMLTagsCheckboxesOld($stmt){
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$tagID = $row['tag_id'];
			$tagName = $row['tag_name'];
			//checkbox
			echo '<input type="checkbox" name="typeTag" id="checkbox-' . $tagName . "-" . $tagID . '">' . $tagName;
			//break
			echo '<br>';
		}
	}
	
	public function generateHTMLTagsCheckboxes($stmt){
		echo '<div class="containerTagList">';
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$tagID = $row['tag_id'];
			$tagName = $row['tag_name'];
			//checkbox
			echo '<input type="checkbox" name="typeTag" id="checkbox-' . $tagName . "-" . $tagID . '">' . $tagName;
			echo '<br>';
		}
		echo '</div>';
	}
		
	public function assignTagsToTypeDropdownBox($stmt){
		echo '<select id="assignTagsToTypeDropdownSelect">';
		/*without this option, 'bean' is the type selected and the jquery does 
		not appear to pick up the click event of the type already selected*/
		echo '<option value="null">Select A Type</option>';
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$typeID = $row['type_id'];
			$typeName = $row['type_name'];
			if($typeName !== "000"){
				echo '<option value="' . $typeName . '-' . $typeID . '">'. $typeName . ' </option>';
			}
		}
		echo '</select>';
	}
	
	public function assignedTagsForThisType($stmt){
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$tagID = $row['tag_id'];
			$tagName = $row['tag_name'];
			//checkbox
			echo '<input type="checkbox" name="" id="checkbox-'. $tagName . '-' . $tagID . '">' . $tagName;
			//br
			echo '<br>';
		}
	}

	public function generateCheckboxesAvailableTagsForType($stmt){
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$tagID = $row['tag_id'];
			$tagName = $row['tag_name'];
			//checkbox
			echo '<input type="checkbox" name="" id="checkbox-'. $tagName . '-' . $tagID . '">' . $tagName;
			//br
			echo '<br>';
		}
	}
}
?>