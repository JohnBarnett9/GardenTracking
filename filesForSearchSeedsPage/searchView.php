<?php 

class searchView{
	public $currentResultSet;
	
	public function __construct(){	}
	
	public function generateHTMLtable($resultSet){
		echo "<table class=\"table table-striped\">";
		echo "<tr>";
		echo "<th>name</th>";
		echo "</tr>";
		foreach($resultSet as $row){
			echo "<tr>";
			echo "<td>" . $row['name'] 	. "</td>";
			echo "</tr>";			
		}
		echo "</table>";			
	}
	
	public function generateFilterListOld($vegArray){
		for($i = 0; $i < count($vegArray); $i++){
			$currentVeg = $vegArray[$i];
			echo '<li>';
			echo '<button id="' . $currentVeg . '" class="btn btnx type">' . $currentVeg . '<br/></button>';
			echo '<div id="' . $currentVeg . 'tags" class="tag">';
			echo '<button class="btn btnx clear">clear</button><br/>';
			echo '</div>';
			echo '</li>';			
		}
	}

	public function generateFilterList($outerArray){
		for($i = 0; $i < (count($outerArray) - 1); $i=$i+2){ //-1 becuase every other element is name of veg
			$currentVeg = $outerArray[$i];
			if($currentVeg !== "000"){
				echo '<li>';
				echo '<button id="' . $currentVeg . '" class="btn btnx type">' . $currentVeg . '<br/></button>';
				echo '<div id="' . $currentVeg . 'tags" class="tag">';
				echo '<button class="btn btnx clear">clear</button><br/>';
				$tagsArray = $outerArray[$i + 1];
				for($j = 0; $j < count($tagsArray); $j++){
					$currentTag = $tagsArray[$j];
					$currentTagIDArray = explode("-", $currentTag); //before explode, $currentTag is something like "orange-9"
					$currentTagName = $currentTagIDArray[0];
					$currentTagID = $currentTagIDArray[1];
					echo '<span><input type="checkbox" id="' . $currentTagName . "-" . $currentTagID . '" />' . $currentTagName . '</span><br/>';
				}
				echo '</div>';
				echo '</li>';					
			}
		}
	}

	public function generateHTMLTableSeedType($stmt){
		echo '<div class="containerSeedList">';
		echo "<table class=\"table table-striped\">";
		echo "<tr>";
		echo "<th>Type</th>";
		echo "<th>Name</th>";
		echo "<th>Year</th>";
		echo "<th>Origin</th>";
		echo "<th>Days</th>";
		echo "<th>Quantity</th>";
		echo "<th>Note</th>";
		echo "<th>Tags</th>";
		echo "</tr>";
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$editButtonID = "edit-" . $row['name'] . "-" . $row['seed_id'];
			$editButtonID = str_replace(' ', '', $editButtonID);
			$deleteButtonID = "delete-" . $row['name'] . "-" . $row['seed_id'];
			$deleteButtonID = str_replace(' ', '', $deleteButtonID);
			$cropButtonID = "crop-" . $row['name'] . "-" . $row['seed_id'];
			$cropButtonID = str_replace(' ', '', $cropButtonID);
			echo "<tr>";
			echo "<td>" . $row['type_name'] 	. "</td>";
			echo "<td>" . $row['name'] 		. "</td>";
			echo "<td>" . $row['year'] 		. "</td>";
			echo "<td>" . $row['origin'] 		. "</td>";
			echo "<td>" . $row['days'] 	. "</td>";
			echo "<td>" . $row['quantity'] 	. "</td>";
			echo "<td>" . $row['note'] 	. "</td>";
			echo "<td>" . $row['tags'] 	. "</td>";

			echo '<td>' . '<button type="button" class="btn btn-primary btn-sm editbutton" id="' . $editButtonID . '">Edit</button>' . '</td>';

			echo '<td>' . '<button type="button" class="btn btn-primary btn-sm deletebutton" id="' . $deleteButtonID . '">Delete</button>' . '</td>';
			echo '<td>' . '<button type="button" class="btn btn-primary btn-sm addcroptoseed" id="addcrop-seedid-' .  $row['seed_id'] . '">Add Crop</button>' . '</td>';
			echo "</tr>";
		}		
		echo "</table>";	
		echo '</div>';
	}
	
	public function editASeedFormValues($resultSet){
		$seedType = $resultSet['type_name'];
		$formName = $resultSet['name'];
		$formYear = $resultSet['year'];
		$formOrigin = $resultSet['origin'];
		$formDays = $resultSet['days'];
		$formQuantity = $resultSet['quantity'];
		$formNote = $resultSet['note'];
		
		$formValues = array(
		'seedType' => $seedType,
		'formName' => $formName,
		'formYear' => $formYear,
		'formOrigin' => $formOrigin,
		'formDays' => $formDays,
		'formQuantity' => $formQuantity,
		'formNote' => $formNote
		);

		$formTags = $resultSet['tags'];
		
		
		
		echo json_encode($formValues);
		
		
	}
	
}
?>