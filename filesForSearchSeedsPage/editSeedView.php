<?php 
class editSeedView {
	public function generateForm($resultSet){
		echo "in editSeedView name = " . $resultSet['name'] . "<br>";
		$seedType = $resultSet['type_name'];
		$formName = $resultSet['name'];
		$formYear = $resultSet['year'];
		$formOrigin = $resultSet['origin'];
		$formDays = $resultSet['days'];
		$formQuantity = $resultSet['quantity'];
		$formNote = $resultSet['note'];

		$formTags = $resultSet['tags'];
$generatedEditForm = <<<HTML
<form class="form-horizontal" id="myform">
	<div class="form-group">
		<label class="control-label col-sm-2">Type</label>
		<div class="col-sm-10">
			<input class="form-control" id="seedTypeInput" name="Ftype" type="text" value="$seedType" list="dropdownlistseedtypes" required>
		</div>
		<datalist id="dropdownlistseedtypes">
		</datalist>		
	</div>

	<div class="form-group">
		<label class="control-label col-sm-2" for="Fname">Name</label>
		<div class="col-sm-10">
			<input class="form-control" id="seedFname" name="Fname" type="text" value="$formName"	required/>
		</div>
	</div>
	<span>
		<div id="errorsOfName">
		</div>
	</span>
	
	<br>
	<div class="form-group">
		<label class="control-label col-sm-2" for="Fyear">Year</label>
		<div class="col-sm-10">
			<input class="form-control" id="seedFyear" name="Fyear" type="number" value="$formYear" />
		</div>
	</div>
	
	<br>
	<div class="form-group">
		<label class="control-label col-sm-2" for="Forigin">Origin</label>
		<div class="col-sm-10">
			<input class="form-control" id="seedForigin" name="Forigin" type="text" value="$formOrigin" />
		</div>
	</div>
	<span>
		<div id="errorsOfOrigin">
		</div>
	</span>
	
	<br>
	<div class="form-group">
		<label class="control-label col-sm-2" for="Fdays">Days</label>
		<div class="col-sm-10">
			<input class="form-control" id="seedFdays" name="Fdays" type="number" min="0" max="200" value="$formDays"/>
		</div>
	</div>
	<span>
		<div id="errorsOfDays">
		</div>
	</span>
	<br>
	
	<div class="form-group">
		<label class="control-label col-sm-2" for="Fquantity">Quantity</label>
		<div class="col-sm-10">
			<input class="form-control" id="seedFquantity" name="Fquantity" value="$formQuantity"/>
		</div>
	</div>
	<span>
		<div id="errorsOfQuantity">
		</div>
	</span>
	<br>	
	
	<div class="form-group">
		<label class="control-label col-sm-2" for="Fnote">Note</label>
		<div class="col-sm-10">
			<input class="form-control" id="seedFnote" name="Fnote" value="$formNote"/>
		</div>
	</div>	
	

	<div class="form-group">
		<button type="button" class="btn btn-primary" id="submiteditseedform" name="s">Submit Changes of Seed</button>
	</div>

	<a href="searchSeeds.php" class="btn btn-primary">Cancel</a>
</form>
HTML;

		
		return $generatedEditForm;
	}
}
?>