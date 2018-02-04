<?php 

require_once('../databaseConnection.php');

if($_POST['adminDatabaseCommand'] === '1'){
	dropTablesCreateTables();
}
else if($_POST['adminDatabaseCommand'] === '2'){
	addMinimalData();
}
else{//$_POST['adminDatabaseCommand'] === '3'
	addRealisticData();
}

/*
Executes when Reset Database' button is clicked on Admin page.
Drops all tables, Creates all tables.
*/
function dropTablesCreateTables(){
	$databaseConnection = new databaseConnection();
	$databaseConnection->setupDB();
	$databaseConnection->setupConnection();
	echo "session db name " . $databaseConnection->nameOfDatabase . "<br>";
	$contents = file_get_contents("dropTablesCreateTables.txt", "r");
	$explosion = explode(";", $contents); //removes ';', but whatever
	foreach($explosion as $item){
		echo $item . "<br>";
	}
	$databaseConnection->dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
	$databaseConnection->dbConnection->exec($contents);	
}

/*
Executes when Add Minimal Data button is clicked on Admin page.
Adds approximately 5 rows to every table.
*/
function addMinimalData(){
	$databaseConnection = new databaseConnection();
	$databaseConnection->setupDB();
	$databaseConnection->setupConnection();
	$contents = file_get_contents("addMinimalData.txt", "r");
	$explosion = explode(";", $contents); //removes ';', but whatever
	foreach($explosion as $item){
		echo $item . "<br>";
	}
	$databaseConnection->dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
	$databaseConnection->dbConnection->exec($contents);	
}

/*
Executes when Add Realistic Data is clicked on Admin page.
The text file is read, parsed, and every valid line that is not duplicate is inserted into the database.

Format of text file to read in:
	1st line:'tags'
	next line:comma separated list of tags with no spaces
	next line: blank line 
	next line:'types-tags'
	next lines:
	<name of vegetable><some number of tabs><comma separated list of tags for this type>
	next line:blank line 
	next line:'seed'
	next lines:<seedtype><some number of tabs><year><some number of tabs><origin><some number of tabs><days><some number of tabs><quantity><some number of tabs><comma separated list of tags for this seed>
*/
function addRealisticData(){
	$databaseConnection = new databaseConnection();
	$databaseConnection->setupDB();
	$databaseConnection->setupConnection();
	$handle = fopen("input.txt", "r");

	//process tags is 1, process types-tags is 2, process seed is 3
	$processStep = 1;
	$arrayFirstLine;
	//first line done asks if the single line before a list is finished processing
	//'tags 5' and 'types-tags 4' lines
	$firstLineDone = "no";
	$currTagLine = 0;//if and else need to see these
	$numTagLines = 0;
		
	$numTypeTagLines = 0;
	$currTypeTagLine = 0;
	
	$currSeedLine = 0;
	$numSeedLines = 0;
	while (($item = fgets($handle)) !== false){
		//skip blank lines
		//empty() parameter must be variable, not return value of trim()
		$temp = trim($item);
		echo "=======temp " . $temp . "<br>";
		if(empty($temp)){
			continue;
		}
		if($processStep === 1){
			if($firstLineDone == "no"){
				$arrayFirstLine = explode(" ", $item);
				print_r($arrayFirstLine);
				echo "<br>";
				echo "arrayFirstLine[1] " . $arrayFirstLine[1] . "<br>";
				$numTagLines = $arrayFirstLine[1];
				echo "numTagLines " . $numTagLines . "<br>";
				$firstLineDone = "yes";
				echo "firstLineDone " . $firstLineDone . "<br>";
			}
			else{
				//echo "in else<br>";
				echo "currTagLine " . $currTagLine . " numTagLines " . $numTagLines . "<br>"; 
				if($currTagLine < ($numTagLines - 1)){
					processTags($databaseConnection, $item);
					$currTagLine++;
				}
				else if($currTagLine == ($numTagLines - 1)){
					processTags($databaseConnection, $item);
					$currTagLine++;
					
					echo "done processing tags<br><br><br><br>";
					$firstLineDone = "no";
					$processStep = 2;
				}
			}
		}
		else if($processStep === 2){
			echo "in step 2<br>";
			if($firstLineDone === "no"){
				$arrayFirstLine = explode(" ", $item);
				echo "step 2 arrayFirstLine[1] " . $arrayFirstLine[1] . "<br>";
				$numTypeTagLines = $arrayFirstLine[1];
				$firstLineDone = "yes";
			}
			else{
				if($currTypeTagLine < ($numTypeTagLines - 1)){
					processTypesTags($databaseConnection, $item);
					$currTypeTagLine++;
				}
				else if($currTypeTagLine == ($numTypeTagLines - 1)){
					processTypesTags($databaseConnection, $item);
					$currTypeTagLine++;
					
					echo " done processing types-tags<br><br><br><br>";
					$firstLineDone = "no";
					$processStep = 3;					
				}
			}			
		}
		else if($processStep === 3){
			echo "in step 3<br>currSeedLine = " . $currSeedLine . "<br>";
			if($firstLineDone === "no"){
				$arrayFirstLine = explode(" ", $item);
				echo "arrayFirstLine[1] " . $arrayFirstLine[1] . "<br>";
				$numSeedLines = $arrayFirstLine[1];
				$firstLineDone = "yes";				
			}
			else{
				if($currSeedLine < $numSeedLines){
					processSeed($databaseConnection, $item);
					$currSeedLine++;
				}
				else if($currSeedLine == $numSeedLines){
					processSeed($databaseConnection, $item);
					$currSeedLine++;
				
					echo "done processing seeds<br>";
					$firstLineDone = "no";
				}
			}
		}
	}

	fclose($handle);
}

/*
Processes one line of the tags section of the text file.
The format is a line with comma separated tags.
*/
function processTags($databaseConnection, $item){
	echo "1st line processingTags() item " . $item . " strlen item " . strlen($item) . "<br>";
	$arrayTags = explode(",", $item);
	for($i = 0; $i < count($arrayTags); $i++){
		$arrayTags[$i] = trim($arrayTags[$i]);
		$sql0 = 
		"SELECT *, COUNT(*) AS duplicateTag
		FROM tag 
		WHERE tag.tag_name = :possibleTagName
		LIMIT 1";
		$query0 = $databaseConnection->dbConnection->prepare($sql0);
		$query0->bindParam(':possibleTagName', $arrayTags[$i]);
		$query0->execute();
		$rowTag = $query0->fetch();
		if($rowTag['duplicateTag'] === '0'){ //This Tag is not already in db.
			$sql1 =
			"INSERT INTO 
			tag (tag_id, tag_name)
			VALUES (null, :newTag)";
			$query1 = $databaseConnection->dbConnection->prepare($sql1);
			$query1->bindParam(':newTag', $arrayTags[$i]);
			$query1->execute();			
		}
		else{
			echo "not 0 of " . $arrayTags[$i] . "<br>";
		}
	}
}

/*
This function processes a single line from the TypesTags section of the text file.

Example:
Bean			pole,bush,broad

This was a tricky function to write because 
	a Type may or may not have been INSERTed already 
	all tags must already have been INSERTed in the processTags() function 
	must INSERT all tags for a Type that does not currently appear in db 
	trim() before using variables in queries
*/
function processTypesTags($databaseConnection, $item){
	echo "in processTypesTag() item " . $item . "<br>";
	
	$item = trim($item);
	$item = preg_replace("/\t\t+/","\t", $item); //replace more than 2 tabs with 1 tab
	$tokensOfLine = explode("\t", $item);
	if(count($tokensOfLine) > 1){ //type and tags
		echo "type and tags<br>";

		$currTypeName = $tokensOfLine[0];
		$currTypeName = trim($currTypeName);
		$currTypeName = strtolower($currTypeName); //all alphabetic chars to lowercase 
		$currTypeName = ucfirst($currTypeName); //capitalize first letter
		echo "currTypeName " . $currTypeName . "<br>";
		
		$currTypePK = "";
		$tagName = "";
		$tagPK = "";

		$sql1 = 
		"SELECT *, count(*) AS duplicateType
		FROM type 
		WHERE type.type_name = :possibleTypeName 
		LIMIT 1";
		$query1 = $databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':possibleTypeName', $currTypeName);
		$query1->execute();
		$currTypeExistsRow = $query1->fetch();
		
		if($currTypeExistsRow['duplicateType'] === "0"){
			echo "Type does not exist<br>";
			$sql2 = 
			"INSERT INTO 
			type (type_id, type_name) 
			VALUES (null, :newTypeName)";
			$query2 = $databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':newTypeName', $currTypeName);
			$query2->execute();
			$currTypePK = $databaseConnection->dbConnection->lastInsertID();

			//for every tag attached to this Type 
			$tagsForType = $tokensOfLine[1];
			$tagsForType = trim($tagsForType);
			$arrayTags = explode(",", $tagsForType);
			echo "arrayTags count " . count($arrayTags) . "<br>";
			for($currTagIndex = 0; $currTagIndex < count($arrayTags); $currTagIndex++){
				$tagName = $arrayTags[$currTagIndex];
				$tagName = trim($tagName);			
				$tagPK = getTagPK($databaseConnection, $tagName); //assume tag is already in tag table 
				
				insertIntoTypeTag($databaseConnection, $currTypePK, $tagPK);
			}
		}
		else{ //Type already exists
			//type is [0]
			//tags is [1]
		
			echo "Type already exists<br>";
			$currTypePK = $currTypeExistsRow['type_id'];
			//for every tag attached to this Type 
			$tagsForType = $tokensOfLine[1];
			$tagsForType = trim($tagsForType);
			$arrayTags = explode(",", $tagsForType);
			echo "arrayTags count " . count($arrayTags) . "<br>";
			for($currTagIndex = 0; $currTagIndex < count($arrayTags); $currTagIndex++){
				$tagName = $arrayTags[$currTagIndex];
				$tagName = trim($tagName);
				echo "tagName " . $tagName . "  TypePK " . $currTypePK .  "strlen tagName " . strlen($tagName) . " insert into typetag<br>";
				$tagPK = getTagPK($databaseConnection, $tagName); //assume tag is already in tag table 
				
				$sql5 =
				"SELECT *, COUNT(*) as rowcount 
				FROM typetag 
				WHERE typetag.type_id= :currTypePK AND typetag.tag_id = :currTagPK";
				$query5 = $databaseConnection->dbConnection->prepare($sql5);
				$query5->bindParam(':currTypePK', $currTypePK);
				$query5->bindParam(':currTagPK', $tagPK);
				$query5->execute();
				$rowIsTagAttachedType = $query5->fetch();
				
				if($rowIsTagAttachedType['rowcount'] === "0"){
					echo "INSERTing typetag" . $tagPK . "<br>";
					insertIntoTypeTag($databaseConnection, $currTypePK, $tagPK);
				}
				else{
					echo "do not insert into typetag<br>";
					continue;
				}
			}
		}		
	}
	else{ //only type
		echo "only type<br>";
		//trim

		$currTypeName = $item;
		$currTypeName = trim($currTypeName);
		$currTypeName = strtolower($currTypeName); //all alphabetic chars to lowercase 
		$currTypeName = ucfirst($currTypeName); //capitalize first letter
		echo "currTypeName " . $currTypeName . "<br>";
		
		$sql1 = 
		"SELECT *, count(*) AS duplicateType
		FROM type 
		WHERE type.type_name = :possibleTypeName 
		LIMIT 1";
		$query1 = $databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':possibleTypeName', $currTypeName);
		$query1->execute();
		$currTypeExistsRow = $query1->fetch();
		
		if($currTypeExistsRow['duplicateType'] === "0"){
			echo "Type does not exist<br>";
			$sql2 = 
			"INSERT INTO 
			type (type_id, type_name) 
			VALUES (null, :newTypeName)";
			$query2 = $databaseConnection->dbConnection->prepare($sql2);
			$query2->bindParam(':newTypeName', $currTypeName);
			$query2->execute();
		}			
	}
}

/*
Given the name of a Tag, returns the Primary Key of that Tag.
Used in processTypesTags().
*/
function getTagPK($databaseConnection, $xtagName){
	$tagPK="";
	try{
		$sql3 =
		"SELECT * 
		FROM tag 
		WHERE tag.tag_name = :currTagName";
		$query3 = $databaseConnection->dbConnection->prepare($sql3);
		$query3->bindParam(':currTagName', $xtagName);
		$query3->execute();
		$rowTag = $query3->fetch();
		$tagPK = $rowTag['tag_id'];
	}
	catch (PDOException $ex) {
		echo "Error: " . $ex->getMessage();
	}
	return $tagPK;
}

/*
Given the Primary Key of the current Type, and the Primary Key of the tag, adds Tag to Type by inserting into the typetag table.
Used in processTypesTags().
*/
function insertIntoTypeTag($databaseConnection, $currTypePK, $tagPK){
	$sql4 =
	"INSERT INTO 
	typetag (type_id, tag_id) 
	VALUES (:typePrimaryKey, :tagPrimaryKey)";
	$query4 = $databaseConnection->dbConnection->prepare($sql4);
	$query4->bindParam(':typePrimaryKey', $currTypePK);
	$query4->bindParam(':tagPrimaryKey', $tagPK);
	$query4->execute();
}

/*
Uses regular expressions.
1, find 2 or more consecutive spaces and remove the spaces
2, find 1 or more consecutive tabs and replace with single tab
3, then use explode on tab 
4, count elements in array
The order of the preg_replace() matters.
*/
function processSeed($databaseConnection, $line){
	$line = trim($line);
	echo "before preg_replace()" . $line . "<br>";
	$line = preg_replace("/[ ]{2,}/","",$line); //replace more than 2 spaces with nothing	
	$line = preg_replace("/\t\t+/","\t",$line); //replace more than 2 tabs with 1 tab
	$tokensOfLine = explode("\t", $line);
	echo "tokensOfLine = " . print_r($tokensOfLine)  . "<br>";
	echo "after preg_replace()" . $line . "<br>";
	
	$tokenCount = count($tokensOfLine);
	
	$errorOfSeed = "";
	
	$seedType = $tokensOfLine[0];
	$seedName = $tokensOfLine[1];
	$seedYear = $tokensOfLine[2];
	if(($seedYear < 1980) || ($seedYear > 2018)){
		$errorOfSeed = $errorOfSeed . "seed must be between 1980 - 2018";
	}
	
	$seedOrigin = $tokensOfLine[3];
	$seedDays = $tokensOfLine[4];
	$seedQuantity = $tokensOfLine[5];
	$seedNote = $tokensOfLine[6];
	
	if($errorOfSeed === ""){ //if no errors, process Seed 
		/*
		duplicate means all 3 of these fields are the same
		unique means at least 1 of the fields is different
		fields are: name, year, origin 

		do NOT insert if seed is duplicate 
		*/
		if(isSeedUnique($databaseConnection, $seedName, $seedYear, $seedOrigin)){
			$seedLastInsertID = insertSingleSeed($databaseConnection, 
												$seedType, 
												$seedName, 
												$seedYear, 
												$seedOrigin, 
												$seedDays, 
												$seedQuantity,
												$seedNote);
			
			//1 or more tags 
			if($tokenCount > 7){ 
				echo "1 or more tags<br>";
				$arrayOfTags = explode(",", $tokensOfLine[7]);
				$currTag = "";
				for($i = 0; $i < count($arrayOfTags); $i++){
					$currTag = $arrayOfTags[$i];
					$currTag = trim($currTag);
					echo "currTag = " . $currTag . "<br>";

					try{
						$sql1 = 
						"SELECT tag.tag_id, tag.tag_name
						FROM tag 
						WHERE tag.tag_name='" . $currTag . "';";
						//echo "sql1 " . $sql1 . "<br>";
						$query1 = $databaseConnection->dbConnection->prepare($sql1);
						$query1->execute();
						$row1 = $query1->fetch();
						$tagPK = $row1['tag_id'];
						
						//insert into seedtag table
						$sql2 =
						"INSERT INTO `seedtag` (`seed_id`, `tag_id`) 
						VALUES (" . $seedLastInsertID . "," . $tagPK . ");";

						$query2 = $databaseConnection->dbConnection->prepare($sql2);
						//echo print_r($query4) . "<br>";
						$query2->execute();				
					}
					catch (PDOException $ex) {
						echo "Error: " . $ex->getMessage();
					}				
				}			
			}
			else {
				echo "0 tags<br>";
			}
		}
		else{
			echo "seed is duplciate, skipping";
		}		
	}
	else {
		echo "Errors of Seed: " . $errorOfSeed . " skipping <br>";
	}

	
}

/*
Determines if a seed should be inserted, by determining if the seed is unique.
If seed is duplicate, return false.
If seed is NOT duplicate, return true.
*/
function isSeedUnique($databaseConnection, $seedName, $seedYear, $seedOrigin){
	//$row1;
	try{
		$sql1 = "SELECT *, COUNT(*) as rowcount 
		FROM seed 
		WHERE 
			seed.name = :possibleSeedName 
				AND 
			seed.origin = :possibleSeedOrigin 
				AND 
			seed.year = :possibleSeedYear";
		$query1 = $databaseConnection->dbConnection->prepare($sql1);
		$query1->bindParam(':possibleSeedName', $seedName);
		$query1->bindParam(':possibleSeedYear', $seedYear);
		$query1->bindParam(':possibleSeedOrigin', $seedOrigin);
		$query1->execute();
		$row1 = $query1->fetch();
	}
	catch (PDOException $ex) {
		echo "Error: " . $ex->getMessage();
	}
	$isSeedDuplicate = true;
	if($row1['rowcount'] !== "0"){
		$isSeedDuplicate = false;
	}
	else{
		echo "not dup<br>";
	}
	return $isSeedDuplicate;
}

/*
Inserts a single seed.

step 1, trim the attributes of the seed 
step 2, get the primary key of the type of this seed 
step 3, insert seed 
step 4, return the Primary Key of the seed INSERTed
*/
function insertSingleSeed($databaseConnection, 
							$seedType, 
							$seedName, 
							$seedYear, 
							$seedOrigin, 
							$seedDays, 
							$seedQuantity,
							$seedNote){
	//step 1
	$seedType = trim($seedType);
	$seedName = trim($seedName);
	$seedYear = trim($seedYear);
	$seedOrigin = trim($seedOrigin);
	$seedDays = trim($seedDays);
	$seedQuantity = trim($seedQuantity);
	$seedNote = trim($seedNote);
	
	echo "=" . $seedType . "=" . $seedName . "=" . $seedYear . "=" . $seedOrigin . "=" . $seedDays . "=" . $seedQuantity . "=" . $seedNote . "<br>";
	
	$seedPK;
	try{
		//step 2
		//query db to get type primary key of $typeName
		$sql1 = 
		"SELECT type.type_id 
		FROM type
		WHERE type.type_name='" . $seedType . "'";
		
		$query1 = $databaseConnection->dbConnection->prepare($sql1);
		$query1->execute();
		$row = $query1->fetch();
		
		$typePrimaryKey = $row['type_id'];
		
		//step 3
		//insert seed 
		$sql2 = 
		"INSERT INTO 
		seed (seed_id, type_id, name, year, origin, days, quantity, note) 
		VALUES (null, :typePK, :seedName, :seedYear, :seedOrigin, :seedDays, :seedQuantity, :seedNote)";
		$query2 = $databaseConnection->dbConnection->prepare($sql2);
		$query2->bindParam(':typePK', $typePrimaryKey);
		$query2->bindParam(':seedName', $seedName);
		$query2->bindParam(':seedYear', $seedYear);
		$query2->bindParam('seedOrigin', $seedOrigin);
		$query2->bindParam(':seedDays', $seedDays);
		$query2->bindParam(':seedQuantity', $seedQuantity);
		$query2->bindParam(':seedNote', $seedNote);
		$query2->execute();
		
		//step 4, return the Primary Key of the seed INSERTed
		$seedPK = $databaseConnection->dbConnection->lastInsertID();
	}
	catch (PDOException $ex) {
		echo "Error: " . $ex->getMessage();
	}
	
	return $seedPK;
}




?>