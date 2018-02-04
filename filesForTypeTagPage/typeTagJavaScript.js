/*
When the page first loads, these Ajax calls happen:
Get list of Types, for first column.
Get list of Tags, for middle column.
Get list of Types, for the dropdown menu in the middle column. This is different from the ajax call to get the list of Type, because the dropdown menu in the middle column will not show Type '000'.
*/
$( document ).ready(function() {
	//list of types
	$.ajax({
		url: "typeTagController.php",
		type: "POST",
		dataType : "html",
		data: {commandForTypeTagController: 1},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {	//data is returned
		console.log("data="+data);
		$("#listoftypes").html(data);
	})
	.fail(function( xhr, status, errorThrown ) {
		alert( "Sorry, there was a problem!" );
		console.log( "Error: " + errorThrown );
		console.log( "Status: " + status );
		console.dir( xhr );
	})
	.always(function( xhr, status ) {
		console.log("The request is complete!");
	});	

	//list of tags
	$.ajax({
		url: "typeTagController.php",
		type: "POST",
		dataType : "html",
		data: {commandForTypeTagController: 2},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {	//data is returned
		console.log("data="+data);
		$("#listoftags").html(data);
	})
	.fail(function( xhr, status, errorThrown ) {
		alert( "Sorry, there was a problem!" );
		console.log( "Error: " + errorThrown );
		console.log( "Status: " + status );
		console.dir( xhr );
	})
	.always(function( xhr, status ) {
		console.log("The request is complete!");
	});

	//dropdown menu for middle column
	$.ajax({
		url: "typeTagController.php",
		type: "POST",
		dataType : "html",
		data: {commandForTypeTagController: 3},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {	//data is returned
		console.log("data="+data);
		$("#typetagdropdownbox").html(data);
	})
	.fail(function( xhr, status, errorThrown ) {
		alert( "Sorry, there was a problem!" );
		console.log( "Error: " + errorThrown );
		console.log( "Status: " + status );
		console.dir( xhr );
	})
	.always(function( xhr, status ) {
		console.log("The request is complete!");
	});

	//assigned tags for this type, middle column
	$("body").on("change", "#assignTagsToTypeDropdownSelect", function(){
		var value = $(this).val();
		var typeNameID = value.split("-");
		console.log("typeNameID array " + typeNameID);
		var typeName = typeNameID[0];
		var typeID = typeNameID[typeNameID.length - 1];
		console.log("typeName = " + typeName + " typeID = " + typeID);
		//var value = $('[name=options]').val();
		console.log("value = " + value);
		
		if((typeName !== '000') && (typeName !== 'null')){
			console.log("does not equal 000");
			
			//if Type selected in 2nd column is anything other than 
			//'Select A Type'
			//then update 2nd column
			//		assigned tags 
			//		available tags
			var typeVal = $("#assignTagsToTypeDropdownSelect").find(":selected").val();
			if(typeVal !== "null"){ //if 'Select A Type' is selected, do nothing
				var typeNameID = typeVal.split("-");
				var typeID = typeNameID[1];					
				regenerateMiddleColumn(typeID);
			}
		}
		else if(typeName === 'null'){
			console.log("typeName === null");
			$("#assignedTagsForType").empty();
			$("#availabletagsfortype").empty();
		}
		else{
			console.log("does equal 000");
			$("#assignedTagsForType").html("this type does not exist");
			$("#availabletagsfortype").html("this type does not exist");			
		}
	});
	
	
	var tagID = "";
	
	//submit add tag to type
	$("#addtagtotypeform").submit(function(){
		//which tag to add to the type
		$("#hiddentagtoadd").val(tagID);
		$("#addtagtotypeform").attr("action", "filesForTypeTagPage/typeTagController.php");
		$("#addtagtotypeform").attr("method", "POST");
		$("#addtagtotypeform").submit();
	});
	
	/*
	Deletes a Tag from a Type.
	
	
	*/
	$("#deletetagfromtype").on("click", function(){
		var typeDisplayed = $("#assignTagsToTypeDropdownSelect").find(":selected").text();
		var typeVal = $("#assignTagsToTypeDropdownSelect").find(":selected").val();
		if(typeVal !== "null"){ //if 'Select A Type' is selected, do nothing
			console.log("typeVal !== null");
			var typeNameID = typeVal.split("-");
			var typeID = typeNameID[1];
			console.log("typeDisplayed = " + typeDisplayed);
			console.log("typeVal = " + typeVal);
			var boxes = $("#assignedTagsForType input:checked");
			var checkedIds = "";
			boxes.each(function(){
				var currBox = $(this).attr("id"); //get box
				var currBoxArray = currBox.split("-"); //need number after 3rd '-'
				var currBoxID = currBoxArray[2]; //get number after 3rd '-'
				checkedIds += currBoxID + "-"; //can handle more than 1 checked for now
				//checkedIds += $(this).attr("id") + "-";
			});
			//trim '-' from end of checkedIds
			checkedIds = checkedIds.slice(0,-1);
			console.log("checkedIds = " + checkedIds);
			
			//delete specific row from typetag table
			$.ajax({
				//url: "filesForTypeTagPage/typeTagController.php",
				url: "typeTagController.php",
				type: "POST",
				dataType : "html",
				data: {commandForTypeTagController: 7, typeID: typeID, tagID: checkedIds},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				console.log("data="+data);
				
				//if Type selected in 2nd column is anything other than 
				//'Select A Type'
				//then update 2nd column
				//		assigned tags 
				//		available tags
				var typeVal = $("#assignTagsToTypeDropdownSelect").find(":selected").val();
				if(typeVal !== "null"){ //if 'Select A Type' is selected, do nothing
					var typeNameID = typeVal.split("-");
					var typeID = typeNameID[1];					
					regenerateMiddleColumn(typeID);
				}				
			})
			.fail(function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem!" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			})
			.always(function( xhr, status ) {
				console.log("The request is complete!");
			});			
		}
		else{
			console.log("typeVal === null");
		}
	});

	$("#addtagtotype").on("click", function(){
		var typeDisplayed = $("#assignTagsToTypeDropdownSelect").find(":selected").text();
		var typeVal = $("#assignTagsToTypeDropdownSelect").find(":selected").val();
		if(typeVal !== "null"){ //if 'Select A Type' is selected, do nothing
			var typeNameID = typeVal.split("-");
			var typeID = typeNameID[1];
			console.log("typeDisplayed = " + typeDisplayed);
			console.log("typeVal = " + typeVal);

			var boxes = $("#availabletagsfortype input:checked");
			var checkedIds = "";
			boxes.each(function(){
				var currBox = $(this).attr("id"); //get box
				var currBoxArray = currBox.split("-"); //need number after 3rd '-'
				var currBoxID = currBoxArray[2]; //get number after 3rd '-'
				checkedIds += currBoxID + "-"; //can handle more than 1 checked for now
				//checkedIds += $(this).attr("id") + "-";
			});
			//trim '-' from end of checkedIds
			checkedIds = checkedIds.slice(0,-1);
			console.log("checkedIds = " + checkedIds);
			
			//add row to typetag table
			$.ajax({
				url: "typeTagController.php",
				type: "POST",
				dataType : "html",
				data: {commandForTypeTagController: 8, typeID: typeID, tagID: checkedIds},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				console.log("data="+data);
				
				//if Type selected in 2nd column is anything other than 
				//'Select A Type'
				//then update 2nd column
				//		assigned tags 
				//		available tags
				var typeVal = $("#assignTagsToTypeDropdownSelect").find(":selected").val();
				if(typeVal !== "null"){ //if 'Select A Type' is selected, do nothing
					var typeNameID = typeVal.split("-");
					var typeID = typeNameID[1];					
					regenerateMiddleColumn(typeID);
				}				
				
			})
			.fail(function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem!" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			})
			.always(function( xhr, status ) {
				console.log("The request is complete!");
			});				
		}
		else{
			console.log("no type selected");
		}
	
	});
	
	$("#deletetypebutton").on("click", function(){
		//delete type
		var boxes = $("#listoftypes input:checked");
		var typeID = "";
		var nameOfType = "";
		boxes.each(function(){
			var currBox = $(this).attr("id"); //get box
			var currBoxArray = currBox.split("-"); //need number after 3rd '-'
			var currBoxID = currBoxArray[2]; //get number after 3rd '-'
			nameOfType += currBoxArray[1];
			typeID += currBoxID + "-"; //can handle more than 1 checked for now
			//checkedIds += $(this).attr("id") + "-";
		});
		//trim '-' from end of checkedIds
		typeID = typeID.slice(0,-1);
		console.log("type ID = " + typeID);
		/*		
		Delete Type
			Let's discuss how this should work. Now it gives a warning and doesn't do it.
			I added a type of Cucumber. It worked. Then I deleted this type. It worked, it was deleted.
			Delete type should make the type 000.
			Delete tags assigned to that type in typetag table.
			Sorting https://stackoverflow.com/questions/5417980/mysql-sql-specific-item-to-be-first-and-then-to-sort-the-rest-of-the-items
				DONE
		*/		
		$.ajax({
			//url: "filesForTypeTagPage/typeTagController.php",
			url: "typeTagController.php",
			type: "POST",
			dataType : "html",
			data: {commandForTypeTagController: 9, typeID: typeID},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			console.log("data="+data);
			$("#listoftypes").html(data);
		})
		.fail(function( xhr, status, errorThrown ) {
			alert( "Sorry, there was a problem! in regenerate available" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		})
		.always(function( xhr, status ) {
			console.log("The request is complete!");
		});
		
		//empty the textfield of 'edit type'
		$("#edittypetextfield").val('');
		
		//reload the typetag dropdown menu
		//dropdown menu for middle column
		$.ajax({
			url: "typeTagController.php",
			type: "POST",
			dataType : "html",
			data: {commandForTypeTagController: 3},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			console.log("data="+data);
			$("#typetagdropdownbox").html(data);
		})
		.fail(function( xhr, status, errorThrown ) {
			alert( "Sorry, there was a problem!" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		})
		.always(function( xhr, status ) {
			console.log("The request is complete!");
		});		
		//what happens if user tries to select '000'?
	});


	$("body").on("change", "#listoftypes :checkbox", function(){
		/*
		one checkbox at most is checked at a time
		goes through all list of checkboxes and if the checkbox is not 'this',
		then 'checked' is set to false
		*/
		$('input[type="checkbox"]').not(this).prop('checked', false);
		if($(this).is(':checked')){ //checked
			console.log("checked");
			//erase content of 'edit type text field'
			$("#edittypetextfield").val('');
			
			//get information from checked box
			console.log("list of types checkboxes changed");
			var boxes = $("#listoftypes input:checked");
			var typeID = "";
			var nameOfType = "";
			boxes.each(function(){
				var currBox = $(this).attr("id"); //get box
				var currBoxArray = currBox.split("-"); //need number after 3rd '-'
				var currBoxID = currBoxArray[2]; //get number after 3rd '-'
				nameOfType += currBoxArray[1];
				typeID += currBoxID + "-"; //can handle more than 1 checked for now
				//checkedIds += $(this).attr("id") + "-";
			});
			//trim '-' from end of checkedIds
			typeID = typeID.slice(0,-1);
			console.log("type ID = " + typeID);
			
			//make the selected type show up in the 'edit' textfield
			$("#edittypetextfield").val(nameOfType);			
		}
		else {	//not checked
			console.log("not checked");
			
			$("#edittypetextfield").val('');
		}
		
	});

	//edit type name
	$("#edittypebutton").on("click", function(){
		var newTypeName = $("#edittypetextfield").val();
		
		if(newTypeName !== 'null' && newTypeName !== '' && onlyAlphaNumericChars(newTypeName)){

			//get id and name of type checkedboxed
			var checkedType = $("#listoftypes input:checked");
			var checkedTypeIDAttribute = checkedType.attr("id");
			console.log("checkedTypeIDAttribute = " + checkedTypeIDAttribute);
			var checkedTypeArray = checkedTypeIDAttribute.split("-");
			var checkedTypeID = checkedTypeArray[2];
			
			newTypeName = newTypeName.toLowerCase();//lowercase name 
			newTypeName = newTypeName.substr(0,1).toUpperCase() + newTypeName.substr(1);//make 1st letter uppercase 
			
			//change database to reflect edit
			$.ajax({
				url: "typeTagController.php",
				type: "POST",
				dataType : "html",
				data: {commandForTypeTagController: 10, typeName: newTypeName, typeID: checkedTypeID},
				success: function() {
					//console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				//console.log("data="+data);
				$("#listoftypes").html(data);
				
				//regenerate list of types
				$.ajax({
					url: "typeTagController.php",
					type: "POST",
					dataType : "html",
					data: {commandForTypeTagController: 1},
					success: function() {
						//console.log("success function executed");
					}
				})
				.done(function( data ) {	//data is returned
					//console.log("data="+data);
					$("#listoftypes").html(data);
				})
				.fail(function( xhr, status, errorThrown ) {
					alert( "Sorry, there was a problem!" );
					console.log( "Error: " + errorThrown );
					console.log( "Status: " + status );
					console.dir( xhr );
				})
				.always(function( xhr, status ) {
					//console.log("The request is complete!");
				});
			})
			.fail(function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem!" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			})
			.always(function( xhr, status ) {
				//console.log("The request is complete!");
			});
			
			$("#edittypetextfield").val(''); //clear edit type textfield
		}
		else{
			alert("new Type must be all lowercase and not blank or null, must be only alphanumeric chars");
		}
		
	});
	
	$("#addtypebutton").on("click", function(){
		var name = $("#addtypetextfield").val();
		//lowercase name
		name = name.toLowerCase();
		if(name !== 'null' && name !== '' && onlyAlphaNumericChars(name) ){
			//make 1st letter uppercase
			name = name.substr(0,1).toUpperCase() + name.substr(1);
			console.log("name = " + name);
			$.ajax({
				url: "typeTagController.php",
				type: "POST",
				dataType : "html",
				data: {commandForTypeTagController: 11, newTagName: name},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				console.log("data="+data);
				if(data === "true"){
					//regenerate list of types
					$.ajax({
						url: "typeTagController.php",
						type: "POST",
						dataType : "html",
						data: {commandForTypeTagController: 1},
						success: function() {
							console.log("success function executed");
						}
					})
					.done(function( data ) {	//data is returned
						console.log("data="+data);
						$("#listoftypes").html(data);
					})
					.fail(function( xhr, status, errorThrown ) {
						alert( "Sorry, there was a problem!" );
						console.log( "Error: " + errorThrown );
						console.log( "Status: " + status );
						console.dir( xhr );
					})
					.always(function( xhr, status ) {
						console.log("The request is complete!");
					});
					
					//regenerate dropdown menu of types
					$.ajax({
						url: "typeTagController.php",
						type: "POST",
						dataType : "html",
						data: {commandForTypeTagController: 3},
						success: function() {
							console.log("success function executed");
						}
					})
					.done(function( data ) {	//data is returned
						console.log("data="+data);
						$("#typetagdropdownbox").html(data);
					})
					.fail(function( xhr, status, errorThrown ) {
						alert( "Sorry, there was a problem!" );
						console.log( "Error: " + errorThrown );
						console.log( "Status: " + status );
						console.dir( xhr );
					})
					.always(function( xhr, status ) {
						console.log("The request is complete!");
					});						
				}
				else{
					alert("Type is duplicate");
				}
				
		
			})
			.fail(function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem!" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			})
			.always(function( xhr, status ) {
				console.log("The request is complete!");
			});
			$("#addtypetextfield").val(''); //clear textbox of for 'add type'
		}
		else{
			alert("type cannot be empty or null, must be only alphanumeric chars");
		}		
	});

	/*
	3rd column
	*/
	
	//1 checked box at a time
	$("body").on("change", "#listoftags input:checked", function(){
		$('input[type="checkbox"]').not(this).prop('checked', false);		
	});

	$("body").on("change", "#listoftags input:checked", function(){
		//erase content of 'edit tag' text field
		$("#edittagtextfield").val('');
		
		//get info from checked box
		var box = $("#listoftags input:checked");
		var boxID = box.attr("id");
		console.log("boxID = " + boxID);
		var boxNameIDArray = boxID.split("-");
		var tagName = boxNameIDArray[1];
		var tagID = boxNameIDArray[2];
		
		//put tag name in 'edit tag' textfield
		$("#edittagtextfield").val(tagName);
	});
	
	//delete tag button
	$("#deletetagbutton").on("click", function(){
		//get info from checked box
		var box = $("#listoftags input:checked");
		if(box.length > 0){ //tag checkbox has been checked
			var boxID = box.attr("id");
			console.log("boxID = " + boxID);
			var boxNameIDArray = boxID.split("-");
			var tagName = boxNameIDArray[1];
			var tagID = boxNameIDArray[2];
			
			$.ajax({
				url: "typeTagController.php",
				type: "POST",
				dataType : "html",
				data: {commandForTypeTagController: 12, tagID: tagID},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				console.log("data="+data);
				
				//regenerate list of tags
				$.ajax({
					url: "typeTagController.php",
					type: "POST",
					dataType : "html",
					data: {commandForTypeTagController: 2},
					success: function() {
						console.log("success function executed");
					}
				})
				.done(function( data ) {	//data is returned
					console.log("data="+data);
					$("#listoftags").html(data);
				})
				.fail(function( xhr, status, errorThrown ) {
					alert( "Sorry, there was a problem!" );
					console.log( "Error: " + errorThrown );
					console.log( "Status: " + status );
					console.dir( xhr );
				})
				.always(function( xhr, status ) {
					console.log("The request is complete!");
				});			
			})
			.fail(function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem! in regenerate available" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			})
			.always(function( xhr, status ) {
				console.log("The request is complete!");
			});
			
			//empty 'add tag' text field
			$("#edittagtextfield").val("");			
		}
		else{
			alert("a tag must be checkboxed");
		}

	});
	
	
	/* 
	addTagFormButton
	
	to check for duplicates, addTag is fundamentally different from editTag 
	
	edit tag does an entire ajax just to determine if tag is duplicate,
	then if not duplicate, another ajax call to insert 
	
	add tag does 1 ajax query to add tag, and tagModel.php 
	determines if the tag is duplicate 
	
	summary of function 
		if(newTag !== 'null' && newTag !== '' && onlyAlphaNumericChars(newTag))
			command 14
				when command 14 is done()
					if data is true
						command 2, regenereate list of tags for 3rd column
						get the Type currently selected in the middle column dropdown menu
						if currently selected Type from middle column dropdown menu is not ""Select A Type"
							command 5, available Tags for this Type
					else
						tag is duplicate
			empty textfield of 'add tag'
		else 
			alert("new tag must be all lowercase and not blank or null, must be only alphanumeric chars");	
	*/
	$("#addTagFormButton").on("click", function(){
		var newTag = $("#addtagtextfield").val();
		newTag = newTag.toLowerCase();
		
		/*
		Edit a Tag
			If tag in tag textfield is empty, blank, or null, ignore edit with message.
		*/		
		if(newTag !== 'null' && newTag !== '' && onlyAlphaNumericChars(newTag)){
			console.log("newTag does not equal null and does not equal '' ");
			
			//add a tag
			$.ajax({
				url: "typeTagController.php",
				type: "POST",
				dataType : "html",
				data: {commandForTypeTagController: 14, newTag: newTag},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				console.log("data="+data);
				if(data === "true"){
					//regenereate list of tags for 3rd column
					$.ajax({
						url: "typeTagController.php",
						type: "POST",
						dataType : "html",
						data: {commandForTypeTagController: 2},
						success: function() {
							console.log("success function executed");
						}
					})
					.done(function( data ) {	//data is returned
						console.log("data="+data);
						$("#listoftags").html(data);
					})
					.fail(function( xhr, status, errorThrown ) {
						alert( "Sorry, there was a problem!" );
						console.log( "Error: " + errorThrown );
						console.log( "Status: " + status );
						console.dir( xhr );
					})
					.always(function( xhr, status ) {
						//alert( "The request is complete!" );
						console.log("The request is complete!");
					});
					
					//a type (other than 'Select A Type') is 
					//chosen from the dropdown list in the 2nd column
					var typeDisplayed = $("#assignTagsToTypeDropdownSelect").find(":selected").text();
					var typeVal = $("#assignTagsToTypeDropdownSelect").find(":selected").val();
					var typeValNameIDArray = typeVal.split("-");
					var typeID = typeValNameIDArray[1];
					/*
					if a type has been selected in the dropdown list of types,
					I must update the available tags with the tag I added.
					*/
					if(typeDisplayed !== "Select A Type"){
						//available tags for this type
						$.ajax({
							url: "typeTagController.php",
							type: "POST",
							dataType : "html",
							data: {commandForTypeTagController: 5, typeID: typeID},
							success: function() {
								console.log("success function executed");
							}
						})
						.done(function( data ) {	//data is returned
							console.log("data="+data);
							$("#availabletagsfortype").html(data);
						})
						.fail(function( xhr, status, errorThrown ) {
							alert( "Sorry, there was a problem!" );
							console.log( "Error: " + errorThrown );
							console.log( "Status: " + status );
							console.dir( xhr );
						})
						.always(function( xhr, status ) {
							//alert( "The request is complete!" );
							console.log("The request is complete!");
						});
					}
										
				}
				else{
					alert("tag is duplicate");
				}
			})
			.fail(function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem!" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			})
			.always(function( xhr, status ) {
				console.log("The request is complete!");
			});
			
			//empty textfield of 'add tag'
			$("#addtagtextfield").val("");			
		}
		else{
			console.log("newTag equals null or '' ");
			alert("new tag must be all lowercase and not blank or null, must be only alphanumeric chars");
		}

	});
	
	
	
	/* editTagFormButton
	function summary:
		if(currTagName !== 'null' && currTagName !== '' && onlyAlphaNumericChars(currTagName))
			get info from checked box
			command 13, is the tag duplicate
			when command 13 is done()
				if === "true"
					command 2, regenereate list of tags for 3rd column
					if Type currently selected in middle column is not "Select A Type"
						regenerate middle column
				else 
					alert("tag is duplicate");
		else 
			alert("new tag must be all lowercase and not blank or null, must be only alphanumeric chars");	
	*/
	$("#editTagFormButton").on("click", function(){
		//get tag name from textfield
		var currTagName = $("#edittagtextfield").val();
		currTagName = currTagName.toLowerCase();		
		
		if(currTagName !== 'null' && currTagName !== '' && onlyAlphaNumericChars(currTagName)){

			//get info from checked box
			var box = $("#listoftags input:checked");
			var boxID = box.attr("id");
			console.log("boxID = " + boxID);
			var boxNameIDArray = boxID.split("-");
			var tagName = boxNameIDArray[1];
			var tagID = boxNameIDArray[2];
		
			//edit the tag, the .done tells javascript if tag was duplicate or not
			$.ajax({
				url: "typeTagController.php",
				type: "POST",
				dataType : "html",
				data: {commandForTypeTagController: 13, tagID: tagID, currTagName: currTagName},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				console.log("data="+data);
				if(data === "true"){
					
					//regenereate list of tags for 3rd column
					$.ajax({
						url: "typeTagController.php",
						type: "POST",
						dataType : "html",
						data: {commandForTypeTagController: 2},
						success: function() {
							console.log("success function executed");
						}
					})
					.done(function( data ) {	//data is returned
						console.log("data="+data);
						$("#listoftags").html(data);
					})
					.fail(function( xhr, status, errorThrown ) {
						alert( "Sorry, there was a problem!" );
						console.log( "Error: " + errorThrown );
						console.log( "Status: " + status );
						console.dir( xhr );
					})
					.always(function( xhr, status ) {
						//alert( "The request is complete!" );
						console.log("The request is complete!");
					});
					
					//if Type selected in 2nd column is anything other than 
					//'Select A Type'
					//then update 2nd column
					//		assigned tags 
					//		available tags
					var typeVal = $("#assignTagsToTypeDropdownSelect").find(":selected").val();
					if(typeVal !== "null"){ //if 'Select A Type' is selected, do nothing
						var typeNameID = typeVal.split("-");
						var typeID = typeNameID[1];					
						regenerateMiddleColumn(typeID);
					}

				}
				else{
					alert("tag is duplicate");
				}
			})
			.fail(function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem! in regenerate available" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			})
			.always(function( xhr, status ) {
				console.log("The request is complete!");
			});
		}
		else{
			alert("new tag must be all lowercase and not blank or null, must be only alphanumeric chars");
		}
	});
	
	/*
	string can only have alphanumeric chars
	used by 
	$("#addTagFormButton").on("click", function(){
	*/
	function onlyAlphaNumericChars(currentString){
		var onlyAN = false;
		var regexAlphaNum = /^[A-Za-z0-9]+$/;
		if(regexAlphaNum.test(currentString)){
			onlyAN = true;
		}
		return onlyAN;
	}
	
	
	
	/*
	regenerate 
	assigned tags 
	available tags
	
	example use case 
		if Type selected in 2nd column is anything other than 'Select A Type'
		then update 2nd column
				assigned tags 
				available tags
		var typeVal = $("#assignTagsToTypeDropdownSelect").find(":selected").val();
		if(typeVal !== "null"){ //if 'Select A Type' is selected, do nothing
			var typeNameID = typeVal.split("-");
			var typeID = typeNameID[1];					
			regenerateMiddleColumn(typeID);
		}

	The 2 Ajax calls in this function can be at same level, becuase one does not depend on the other finishing.
	*/
	function regenerateMiddleColumn(typeID){
		//tags for a type
		$.ajax({
			url: "typeTagController.php",
			type: "POST",
			dataType : "html",
			data: {commandForTypeTagController: 4, typeID: typeID},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			console.log("data="+data);
			$("#assignedTagsForType").html(data);
		})
		.fail(function( xhr, status, errorThrown ) {
			alert( "Sorry, there was a problem!" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		})
		.always(function( xhr, status ) {
			console.log("The request is complete!");
		});

		//available tags for this type
		$.ajax({
			url: "typeTagController.php",
			type: "POST",
			dataType : "html",
			data: {commandForTypeTagController: 5, typeID: typeID},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			console.log("data="+data);
			$("#availabletagsfortype").html(data);
		})
		.fail(function( xhr, status, errorThrown ) {
			alert( "Sorry, there was a problem!" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		})
		.always(function( xhr, status ) {
			console.log("The request is complete!");
		});
	}
});