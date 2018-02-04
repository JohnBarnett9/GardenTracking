<?php 
class cropView{
	public function __construct(){}
	
	/*
	happens if radio button changed to Type on crop.php
	*/
	public function varietyCropSectionsType($resultSet){
		$prevVegName = "";
		$currVegName = "";
		$cropNumber = 0;
		foreach($resultSet as $row){
			
			$currVegName = $row['name'];
			if($currVegName !== $prevVegName){
				if($cropNumber !== 0){//every time except 1st time
					echo "</table>";
				}
				$cropNumber++;
				echo $row['type_name'] . "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";//space in html
				echo $currVegName . "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";
				echo '<button type="button" class="btn btn-primary btn-sm pull-right shownotes" id="show-notes-' . $row['seed_id'] . '">Show Notes</button>';
				echo "<table class=\"table table-striped\">";
				echo "<tr>";
				echo "<th>Type Name</th>";
				echo "<th>Name</th>";
				echo "<th>Start Date</th>";
				echo "<th>Origin</th>";
				echo "<th>Year</th>";
				echo "<tr>";
				$prevVegName = $currVegName;
			}
			echo "<tr>";
			echo "<td>" . $row['type_name'] . "</td>";
			echo "<td>" . $row['name'] . "</td>";

			$yearMonthDay = substr($row['start_date'], 0, -9); //trim h m s from DATETIME
			echo "<td>" . $yearMonthDay . "</td>";
			echo "<td>" . $row['origin'] . "</td>";
			echo "<td>" . $row['year'] . "</td>";
			echo "<tr>";
		}
	}
	
	/*
	happens if radio button changed to Date on crop.php
	*/
	public function varietyCropSectionsDate($resultSet){
		echo "<table class=\"table table-striped\">";
		echo "<tr>";
		echo "<th>Start Date</th>";
		echo "<th>Type Name</th>";
		echo "<th>Name</th>";
		echo "<th>Origin</th>";
		echo "<th>Year</th>";
		echo "<th></th>";
		echo "<tr>";
		foreach($resultSet as $row){
			echo "<tr>";
			
			$yearMonthDay = substr($row['start_date'], 0, -9); //trim h m s from DATETIME
			echo "<td>" . $yearMonthDay . "</td>";
			echo "<td>" . $row['type_name'] . "</td>";
			echo "<td>" . $row['name'] . "</td>";
			echo "<td>" . $row['origin'] . "</td>";
			echo "<td>" . $row['year'] . "</td>";
			echo "<td>" . '<button type="button" class="btn btn-primary btn-sm shownotes" id="show-notes-' . $row['crop_id'] . '">Show Notes</button>' . "</td>";				
			echo "<tr>";
		}
		echo "</table>";		
	}
	
	public function generateYearCheckboxes($resultSet){
		foreach($resultSet as $row){
			$year = $row['year'];
			echo '<input type="checkbox" name="" id="checkbox-year-' . $year . '">' . $year;
			echo "<br>";
		}
	}
	
	public function htmlCropsTable($resultSet){
		echo "<table class=\"table table-striped\">";
		echo "<tr>";
		echo "<th>start_date</th>";
		echo "<th>origin</th>";
		echo "<th>year</th>";		
		echo "<tr>";

		foreach($resultSet as $row){
			echo "<tr>";
			echo "<td>" . $row['start_date'] . "</td>";
			echo "<td>" . $row['origin'] . "</td>";
			echo "<td>" . $row['year'] . "</td>";			
			echo "<tr>";
		}
		echo "</table>";
	}
	
	public function htmlAllNotesForCrop($resultSet){
		echo "<table class=\"table table-striped\">";
		echo "<tr>";
		echo "<th>start_date</th>";
		echo "<th>note</th>";
		echo "<tr>";

		foreach($resultSet as $row){
			echo "<tr>";
			echo "<td>" . $row['start_date'] . "</td>";
			echo "<td>" . $row['note_description'] . "</td>";
			echo "<tr>";
		}
		echo "</table>";		
	}
	
	public function htmlAllInfoSingleCrop($resultRow){
		echo "<h4>" . $resultRow['type_name'] . "  " . $resultRow['name'] . "</h4>";
	}
	
	/*
	showNotesForSeed.php
	if radio button is Type
	*/
	public function allNotesForAllCropsThisSeed($resultSet){
		//echo "in view <br>";
		$prevCropID = "";
		$currCropID = "";
		$numberOfNotesForCrop = 0;
		$currentNoteNum = 0;
		foreach($resultSet as $row){
			$currCropID = $row['crop_id'];
			$currNoteID = $row['note_id'];
			if($currCropID != $prevCropID){
				$numberOfNotesForCrop = $row['numnotesforcrop'];
				$currentNoteNum = 0;//reset
				//echo "num notes for this crop " . $numberOfNotesForCrop . "<br>";
				//echo '<button class="btn btn-primary" id="" >tempButton</button>';
				$yearMonthDay = substr($row['start_date'], 0, -9); //trim h m s from DATETIME
				echo "crop " . $currCropID . " " . $yearMonthDay . "<br>";
				
				$prevCropID = $currCropID;
				//echo "totals 2inch: 1, 4inch:1, picnic:2<br>";
				//echo '' $row['crop_container_totals'];
				//echo '<input type="text" name="" value="' . $row['crop_container_totals'] . '" style="width:500px" >';
				//echo '<button type="button" class="btn savetotals" id="savetotals-cropid-' . $currCropID . '">Save</button>';
				//echo '<br>';
				echo '<div id="divbuttoncontainertotals">';
				echo '<button type="button" class="btnx containertotalstext" id="cropid-'.$currCropID.'">' . 'Crop Totals:' . $row['crop_container_totals'] . '</button>';
				echo '&emsp;&emsp;<span class="glyphicon glyphicon-pencil">';
				echo '</div>';
				echo '<br>';
				echo '<table>';
				echo '<tr>';
				echo '<th style="width:20%">Date and Time </th>';
				echo '<th style="width:30%">Action</th>';
				echo '<th style="width:30%">Note</th>';
				echo '<th style="width:20%"></th>';
				echo '</tr>';
			}
			
			if($currentNoteNum < $numberOfNotesForCrop){
				//echo "currentNoteNum < numberOfNotesForCrop " . $currentNoteNum . "--" . $numberOfNotesForCrop . "<br>";
				
				echo '<tr>';
				//echo '<td>' . $row['note_date'] . '</td>';
				//$yearMonthDayOfNote = substr($row['note_date'], 0, -3); //trim h m s from DATETIME
				//$yearMonthDayOfNote = DATE("Y-m-d g:i A");
				$yearMonthDayOfNote = DATE("Y-m-d g:i A", strtotime($row['note_date']));
				echo "<td>" . $yearMonthDayOfNote . "</td>";
				
				echo '<td>' . $row['note_action'] . '</td>';
				echo '<td>' . $row['note_description'] . '</td>';
				echo '<td>';
				echo '<button type="button" class="btn btn-primary btn-sm editbutton" id="editbutton-cropid-' . $currCropID . "-noteid-" . $currNoteID .  '">Edit</button>';
				echo '<button type="button" class="btn btn-primary btn-sm deletenotebutton" id="deletebutton-noteid-' . $currNoteID . '">Delete</button>';
				echo '</td>';
				echo '</tr>';
				$currentNoteNum++;
				if($currentNoteNum === ((int)$numberOfNotesForCrop)){
					
					$currentDateTime = $this->customDateTime();
					
					echo '</table>';
echo <<< ASDFF
					<form id="addnotetocrop-$currCropID" action="cropController.php" method="POST">
						<label>Date and Time mm-dd-yyyy</label>
						<input id="dateandtimeInput" name="cropnotedateandtime" type="datetime-local" value="$currentDateTime" min="1970-01-01T01:01" max="2020-01-01T01:01" required>
						<span id="spanofaction">
							<li><input type="radio" name="action" value="transplant" checked>Transplant<br></li>
							<li><input type="radio" name="action" value="discard">Discard<br></li>
							<li><input type="radio" name="action" value="harvest">Harvest</li>
							<li><input type="radio" name="action" value="sale">Sale</li>
							<li><input type="radio" name="action" value="germinate">Germinate</li>
						</span>
						<div id="extranotes">
						</div>
						<label>Note</label>
						<input id="noteInput" name="cropnotenote" type="text" value="" required>
						<button type="button" class="btn btn-primary addnotetocrop-type" id="addNoteToCropFormButton-$currCropID" name="a">Add Note To Crop</button>
						<input id="dateandtimeInputhidden" type="hidden" name="dateandtimeInputhidden" value="">						
						<input id="addNoteToCropCommand" type="hidden" name="commandForCropController" value="11">
						<input id="addNoteToCropCropPrimaryKey" type="hidden" name="cropPrimaryKey" value="$currCropID">
					</form>
ASDFF;
					echo "<br><br><br><br>";
				}
			}
		}
	}
	
	/*
	radio button is Type
	*/
	public function zeroNotesExistForThisSeed(){
		
	}
	
	/*
	showNotes.php 
	if radio button is Date
	
	making table that is formatted correclty
	*/
	public function allNotesForAllCropsThisSeedDate($resultSet){
		//echo "show notes clicked, radio button is Date, in view<br>";
		//echo "1111111111111111111111111";
		$currRowNum = 0;
		foreach($resultSet as $row){
			$currCropID = $row['crop_id'];
			if($currRowNum === 0){
				$yearMonthDay = substr($row['start_date'], 0, -9); //trim h m s from DATETIME
				
				echo "crop " . $row['crop_id'] . "&emsp;&emsp;&emsp;&emsp;&emsp;" . $yearMonthDay . "<br>";
				/*table starts after the line describing the type and name/variety of seed*/
				echo '<table>';
				echo '<tr>';
				echo '<th style="width:20%">Date and Time </th>';
				echo '<th style="width:30%">Action</th>';
				echo '<th style="width:30%">Note</th>';
				echo '<th style="width:20%"></th>';
				echo '</tr>';
			}
			//echo "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;" . $row['start_date'] . "-" . $row['note_description'];
			echo '<tr>';
			
			//$yearMonthDayOfNote = substr($row['note_date'], 0, -3); //trims seconds from DATETIME
			//$yearMonthDayOfNote = DATE("Y-m-d g:i A");
			$yearMonthDayOfNote = DATE("Y-m-d g:i A", strtotime($row['note_date']));
			echo '<td>' . $yearMonthDayOfNote . '</td>';
			echo '<td>' . $row['note_action'] . '</td>';
			echo '<td>' . $row['note_description'] . '</td>';
			echo '<td>';
			echo '<button type="button" class="btn btn-primary btn-sm editbutton" id="editbutton-cropid-' . $row['crop_id'] . "-noteid-" . $row['note_id'] . '">Edit</button>';
			echo '<button type="button" class="btn btn-primary btn-sm deletenotebutton" id="deletebutton-noteid-' . $row['note_id'] . '">Delete</button>';
			echo '</td>';			
			echo '</tr>';
			$currRowNum++;
		}
		echo '</table>';
		
		$currentDateTime = $this->customDateTime();
		
		//don't need to track the number of notes for this crop, because only 
		//dealing with 1 crop, so can put 'add note' form after the foreach()
echo <<< ASDFFDATE
	<form id="addnotetocrop" action="cropController.php" method="POST">
		<label>Date and Time mm-dd-yyyy</label>
		<input id="dateandtimeInput" name="cropnotedateandtime" type="datetime-local" value="$currentDateTime" min="1970-01-01T01:01" max="2020-01-01T01:01" required>
		<span id="spanofaction">
			<li><input type="radio" name="action" value="transplant" checked>Transplant<br></li>
			<li><input type="radio" name="action" value="discard">Discard<br></li>
			<li><input type="radio" name="action" value="harvest">Harvest</li>
			<li><input type="radio" name="action" value="sale">Sale</li>
			<li><input type="radio" name="action" value="germinate">Germinate</li>
		</span>
		<div id="extranotes">
		</div>		
		<label>Note</label>
		<input id="noteInput" name="cropnotenote" type="text" value="" required>
		<button type="button" class="btn btn-primary addnotetocrop-date" id="addNoteToCropFormButton" name="a">Add Note To Crop</button>
		<input id="dateandtimeInputhidden" type="hidden" name="dateandtimeInputhidden" value="">
		<input id="addNoteToCropCommand" type="hidden" name="commandForCropController" value="11">
		<input id="addNoteToCropCropPrimaryKey" type="hidden" name="cropPrimaryKey" value="$currCropID">		
	</form>
ASDFFDATE;
	}
	
	/*
	returns the time now,
	formatted 2017-11-11T14:39
	
	used in 
	public function allNotesForAllCropsThisSeedDate($resultSet){ 
	public function allNotesForAllCropsThisSeed($resultSet){
	*/
	public function customDateTime(){
		$currentDateTime = date("Y-m-d\TH:i:s");
		$currentDateTime = substr($currentDateTime, 0, -3);
		return $currentDateTime;
	}
	
	public function zeroNotesExistForThisCrop(){
		$currCropID = $_SESSION['cropPrimaryKey'];
		echo "0 notes exist for this crop<br>";
echo <<< ASDFFDATE
	<form id="addnotetocrop" action="cropController.php" method="POST">
		<label>Date and Time mm-dd-yyyy</label>
		<input id="dateandtimeInput" name="cropnotedateandtime" type="text" value="" required>
		<label>Note</label>
		<input id="noteInput" name="cropnotenote" type="text" value="" required>
		<button type="button" class="btn btn-primary addnotetocrop-date" id="addNoteToCropFormButton" name="a">Add Note To Crop</button>
		<input id="addNoteToCropCommand" type="hidden" name="commandForCropController" value="11">
		<input id="addNoteToCropCropPrimaryKey" type="hidden" name="cropPrimaryKey" value="$currCropID">		
	</form>
ASDFFDATE;
		
	}


	
	//filterlist with checkboxes and clear, copied from searchView.php generateFilterList
	public function generateFilterList($outerArray){
		for($i = 0; $i < (count($outerArray) - 1); $i=$i+2){ //-1 becuase every other element is name of veg
			$currentVeg = $outerArray[$i];
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
	
	/*
	$noteDate[10] = "T";//got notice 'array to string conversion'	
	*/
	public function generateEditNoteForm($row){
		$noteDate = $row['note_date'];
		$noteDescription = $row['note_description'];
		$currCropID = $row['crop_id'];
		$noteDate = substr($noteDate, 0, 10) . "T" . substr($noteDate, 11, 16); 
		echo '<form id="editnoteform" action="cropController.php" method="POST">';
		echo '<label>&emsp;&emsp;&emsp;&emsp;Date and Time mm-dd-yyyy</label>';
		echo '<input id="dateandtimeInput" name="cropnotedateandtime" type="datetime-local" value="' . $noteDate . '" min="1970-01-01T01:01" max="2020-01-01T01:01" required>';
		echo '<span id="spanofaction">';
		echo '<li><input type="radio" name="action" value="transplant" checked>Transplant<br></li>';
		echo '<li><input type="radio" name="action" value="discard">Discard<br></li>';
		echo '<li><input type="radio" name="action" value="harvest">Harvest</li>';
		echo '<li><input type="radio" name="action" value="sale">Sale</li>';
		echo '<li><input type="radio" name="action" value="germinate">Germinate</li>';
		echo '</span>';
		echo '<div id="extranotes">';
		echo '</div>';
		echo '<label>Note</label>';
		echo '<input id="noteInput" name="cropnotenote" type="text" value="' . $noteDescription . '" required>';
		echo '<button type="button" class="btn btn-primary" id="editnote" name="a">Edit Note</button>';
		echo '<input id="dateandtimeInputhidden" type="hidden" name="dateandtimeInputhidden" value="">';
		echo '<input id="addNoteToCropCommand" type="hidden" name="commandForCropController" value="18">';
		echo '<input id="addNoteToCropCropPrimaryKey" type="hidden" name="cropPrimaryKey" value="' . $currCropID . '">';
		echo '</form>';
	}

	public function whichSeedOnShowNotesPage($row){
		echo "<h4>Type: " . $row['type_name'] . "</h4>";
		echo "<h4>Name/Variety: " . $row['name'] . "</h4>";
		echo "<br>";
	}
	
	public function extraInputForAction(){
		$action = $_POST['action'];
		if($action === "sale"){
			echo 
			'<label>Sale Quantity</label>' .  
			'<input type="number" name="salequantity" id="salequantityinput" min="1" max="5">' . 
			'<br>';
		}
		else if($action === "germinate"){
			echo 
			'<label>Number of crop germinating at this time</label>' . 
			'<input type="number" name="germinatingquantity" id="germinatingquantityinput">' . 
			'<br>';
		}
		else if($action === "transplant"){
			echo 
			'<label>Location</label>' . 
			'<input type="text" name="location" id="locationinput">' . 
			'<label>Size</label>' . 
			'<input type="text" name="size" id="sizeinput">';
		}
		else {
			//echo "default<br>";
		}
		
	}
}
?>