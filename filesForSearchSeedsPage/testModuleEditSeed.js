var mymod = (function () {
	
	var desc = "IIFE variable";
	var types = [];
	var origins = [];
	var tags = [];

	var validateType = function(){

	};	
	
	
	
	
	
	var typeAutocomplete = function() {
		$( "#seedTypeInput" ).autocomplete({
			source: types
		});
	};
	
	/* why is this not a function?, gives error in console
	needed jquery ui script library
	*/
	var originAutocomplete = function() {
		//$("#seedOriginInput").autocomplete({
		$("#seedForigin").autocomplete({
			source: origins,
			messages: {
				noResults: '',
				results: function(){}
			},
			change: function(event, ui){
				if(ui.item == null){
					$("#seedOriginInput").val('');
					$("#seedOriginInput").val();
				}
			}
		});
	};

	var tagsAutocomplete = function(){
		$("#tagsForSeedPacket").autocomplete({
			source: tags,
			messages: {
				noResults: '',
				results: function() {}
			}			
		});
	};
	
	//calls getAvailableSeedTypes() in typeModel.php
	var availSeedType = function(){
		$.ajax({
			url: "searchController.php",
			type: "POST",
			dataType : "JSON",
			data: {commandForSearchController: 15},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			$.each(data, function(key, val){
				mymod.types.push(val)
				typesout = "<option value='" + val + "' id='" + key + "'>";
				console.log("typesout = "  + typesout);
				$("#dropdownlistseedtypes").append(typesout);				
			})
			console.log("types = " + mymod.types);
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
	};
	
	/*
	calls getAvailableOrigins() in seedModel.php
	This is not dropdown menu like Type, this is autocomplete, start typing 
	and the options appear.
	*/
	
	var availSeedOrigin = function(){
		$.ajax({
			url: "searchController.php",
			type: "POST",
			dataType : "JSON",
			data: {commandForSearchController: 13},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			$.each(data, function(key, val){
				mymod.origins.push(val)
			})
			console.log("origins = " + mymod.origins);
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
	};
	
	//called on editSeed.php
	//calls getAvailableSeedTags in tagModel.php
	var availSeedTags = function(){
		$.ajax({
			url: "searchController.php",
			type: "POST",
			dataType : "JSON",
			data: {commandForSearchController: 14},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			$.each(data, function(key, val){
				mymod.tags.push(val)
			})
			console.log("tags = " + mymod.tags);
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
	};
	
	// make the variable and function available in a JS object
	return {
		desc: desc,
		types: types,
		origins: origins,
		tags: tags,
		originAutocomplete: originAutocomplete,
		tagsAutocomplete: tagsAutocomplete,
		availSeedType: availSeedType,
		availSeedOrigin: availSeedOrigin,
		availSeedTags: availSeedTags
	}
})();


$(window).on('load', function() {
	
	$.ajax({ 
		type: "POST",
		url: "searchController.php",
		data: { commandForSearchController: 25},
		dataType: "JSON",
		success: function(){
		}
	})
	.done(function(data){
		var items = [];
		$.each(data, function(key, value){
			//alert("key = " + key + " value " + value);
			items.push(value);
		});
		//alert(items);
		//$("#seedTypeInput").val(items[0]);
		$("#seedTypeInput").attr("value", items[0]);
		$("#seedFname").attr("value", items[1]);
		$("#seedFyear").attr("value", items[2]);
		$("#seedForigin").attr("value", items[3]);
		$("#seedFdays").attr("value", items[4]);
		$("#seedFquantity").attr("value", items[5]);
		$("#seedFnote").attr("value", items[6]);
		
		/*
		can't have ';' at end of ajaxGetListOfTags(), because 
		it is a parameter
		
		This $.when().done(); is modeled from the code found here:
		https://stackoverflow.com/questions/5280699/jquery-when-understanding
		
		originally this was at the same level as var successMessage =....,
		however Type had not been initialized when ajaxGetListOfTags() was called.
		
		*/
		$.when(
			ajaxGetListOfTags()
		).done(function(a1){
			checkboxesChanged();
		});		
		
	})
	.fail(function(){
		console.log("fail");
	})
	.always(function(){
		console.log("always");
	});
	
	mymod.availSeedType();
	mymod.originAutocomplete();
	mymod.tagsAutocomplete();
	mymod.availSeedOrigin();
	mymod.availSeedTags();

	var listTagIDs = "";	
	var listOfValidationErrors = "";
	var successMessage = "Seed UPDATEd successfully.";
	

	
	function ajaxGetListOfTags(){
		/*
		get list of tags for this seed
		put that list of tags in the right column	
		
		data '1' does not matter, that POST variable simply has to be set
		
		https://stackoverflow.com/questions/16784895/what-does-an-asynchronous-ajax-call-return
		$.ajax() also implements and returns a Promise / Deferred interface.
		
		So the thing returned becomes the parameter to the $.when().
		*/
		return $.ajax({ 
			type: "POST",
			url: "searchController.php",
			data: { commandForSearchController: 12, seedType : $("#seedTypeInput").val(), checkedVsUnchecked: 1, seedName: $("#seedFname").val()},
			dataType: "html",
			success: function(){
			}
		})
		.done(function(data){
			console.log("done");
			$("#typeTagCheckboxes").html(data);
		})
		.fail(function(){
			console.log("fail");
		})
		.always(function(){
			console.log("always");
		});	
	}	

	function validateAllInputs(){
		var returnValue = true;

		var str = $("#seedFname").val();
		var nameRegex = /^[a-zA-Z0-9\s]+$/;
		if(nameRegex.test(str)){
			if($("#seedFname").hasClass("formInputError")){ //if input has red border, remove red border 
				$("#seedFname").removeClass("formInputError");
			}
		}
		else{
			$("#seedFname").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Name: " + str + " is not valid<br>";
			returnValue = false;
		}
		
		var enteredYear = $("#seedFyear").val();
		var currentYear = new Date().getFullYear();
		if((enteredYear >= 1980) && (enteredYear <= currentYear)){
			if($("#seedFyear").hasClass("formInputError")){
				$("#seedFyear").removeClass("formInputError")
			}
		}
		else{
			$("#seedFyear").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Year: please enter a year between 1980 and " + currentYear + "<br>";
			returnValue = false;
		}
		
		var enteredOrigin = $("#seedForigin").val();
		var regexPrintableChars = /^[A-Za-z0-9!"#$%&'()*+,.\/:;<=>?@\[\] ^_`{|}~-]*$/;
		if(regexPrintableChars.test(enteredOrigin)){
			if($("#seedForigin").hasClass("formInputError")){
				$("#seedForigin").removeClass("formInputError")
			}
		}
		else{
			$("#seedForigin").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Origin: please enter only printable characters<br>";
			returnValue = false;
		}
		
		var enteredDays = $("#seedFdays").val();
		if((enteredDays >= 1) && (enteredDays <= 199)){
			if($("#seedFdays").hasClass("formInputError")){
				$("#seedFdays").removeClass("formInputError");
			}
		}
		else{
			$("#seedFdays").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Days: please enter number >= 1 and <= 199<br>";
			returnValue = false;
		}
		
		var enteredQuantity = $("#seedFquantity").val();
		if((enteredQuantity.length <= 20) && (regexPrintableChars.test(enteredQuantity))){
			if($("#seedFquantity").hasClass("formInputError")){
				$("#seedFquantity").removeClass("formInputError");
			}
		}
		else{
			$("#seedFquantity").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Quantity: must be <= 20 characters in length, must be only printable characters<br>";
			returnValue = false;
		}
		
		return returnValue;
	}	

	
	//$('body').change('input[type=checkbox]', function() {
	$('body').on("click", "input[type=checkbox]", function() {
		checkboxesChanged();
	});	
	

	/*
	refactored version
	summary of function
	
	these vars are outside $.when().done();
	var seedIsUnique = false;
	var listTagsUpdated = false;
	var daysQtyUpdated = false;

	
	$.when(
		command 18,
		command 20,
		command 22
	).done(
		if(seedIsUnique){
			display message "seed updated"
		}
		else{
			display message "Edit Seed: Duplicate seed. Not changed.";
		}
		
		if(daysQtyUpdated){
			display "days and or quantity was updated"
		}
		else{
			display "days and or quantity did not change"
		}
		
		if(listTagsUpdated){
			display message "list of tags updated"
		}
		else{
			display message "list of tags did not change"
		}	
	);
	
	even though not using all 7 values, serializing values to send in command 18 and 19
	*/
	$("body").on("click", "#submiteditseedform", function(event){
		listOfValidationErrors = ""; //need to reset before testing input again
		$("#successMessage").empty(); //reset the success message
		if(validateAllInputs() === true){
			var serializedData = $("#myform").serialize();
			
			var currType = $("#seedTypeInput").val();
			var currName = $("#seedFname").val();
			var currOrigin = $("#seedForigin").val();
			var currYear = $("#seedFyear").val();
			var currNote = $("#seedFnote").val();

			var fiveFieldsChanged = false;
			var seedIsUnique = false;
			var listTagsUpdated = false;
			var daysQtyUpdated = false;

			
			$.ajax({ 
				type: "POST",
				url: "searchController.php",
				data: { commandForSearchController: 24, serializedData:serializedData },
				dataType: "html",
				success: function(){
				}
			})
			.done(function(data){
				//alert("done with command 24, did 5 main fields change?");
			})
			.fail(function(){
				console.log("fail");
			})
			.always(function(){
				console.log("always");
			})
			.then(function(data0){
				if(data0 == "changed"){
					//alert("5 fields changed");
					//command 18
					//send 5 main fields, is the combination of those 5 fields duplicate?			
					return $.ajax({ 
						type: "POST",
						url: "searchController.php",
						data: { commandForSearchController: 18, serializedData:serializedData },
						dataType: "html",
						success: function(){
						}
					})
					.done(function(data){
						//alert("done with command 18 is seed unique");
						fiveFieldsChanged = true;
					})
					.fail(function(){
						console.log("fail");
					})
					.always(function(){
						console.log("always");
					})
				}
				else{
					//alert("5 fields NOT changed");
				}
			})
			.then(function(data){
				if(data == "unique"){ //unique, in ""
					//alert("in if");
					//return $.ajax and $.ajax both appear to work
					$.ajax({ //command 19, update the 5 main fields
						type: "POST",
						url: "searchController.php",
						data: { commandForSearchController: 19, serializedData:serializedData },
						dataType: "html",
						success: function(){
						}
					})
					.done(function(data){
						//alert("seedIsUnique set = true");
						seedIsUnique = true;
					})
					.fail(function(data){
						console.log("fail");
					})
					.always(function(){
						console.log("always");
					});						
				}
				else{ //duplicate
					//alert("seed is duplicate, not updating 5 fields");
				}					
			})
			.then(function(data2){ //noitce data2
				return $.ajax({ //command 20, did days or quantity change?
					type: "POST",
					url: "searchController.php",
					data: { commandForSearchController: 20, serializedData:serializedData },
					dataType: "html",
					success: function(){
					}
				})
				.done(function(data){
					//alert("done with command 20 did days or qty change");
				})
				.fail(function(data){
					console.log("fail");
				})
				.always(function(){
					console.log("always");
				});					
			})
			.then(function(data3){
				//alert("data3 " + data3);
				if(data3 == "changed"){
					//alert("days or qty changed");
					$.ajax({ //command 21, if days or quantity changed, update days and quantity
						type: "POST",
						url: "searchController.php",
						data: { commandForSearchController: 21, serializedData:serializedData },
						dataType: "html",
						success: function(){
						}
					})
					.done(function(data){
						//alert("daysQtyUpdated = true");
						daysQtyUpdated = true;
					})
					.fail(function(data){
						console.log("fail");
					})
					.always(function(){
						console.log("always");
					});						
				}
				else{
					//alert("days and qty not changed, not updating 2 fields");
				}
			}).then(function(data4){
				return 	$.ajax({ //command 22, did the list of tags change?
					type: "POST",
					url: "searchController.php",
					data: { commandForSearchController: 22, listTagIDs:listTagIDs },
					dataType: "html",
					success: function(){
					}
				})
				.done(function(data){
					//alert("command 22, did the list of tags change? data = " + data);
				})
				.fail(function(data){
					console.log("fail");
				})
				.always(function(){
					console.log("always");
				});
			})
			.then(function(data5){
				//alert("has list tags changed? " + data5);
				if(data5 == "changed"){
					return $.ajax({ //command 23, list of tags changed, update list of tags
						type: "POST",
						url: "searchController.php",
						data: { commandForSearchController: 23, listTagIDs:listTagIDs },
						dataType: "html",
						success: function(){
						}
					})
					.done(function(data){
						//alert("listTagsUpdated updated = true");
						var a = "yes";
						listTagsUpdated = true;
					})
					.fail(function(data){
						console.log("fail");
					})
					.always(function(){
						console.log("always");
					});					
				}
				else{
					//alert("list of tags unchanged");
				}
			}).then(function(data6){
				/*
				Did 1 of the 5 main fields change?
				*/
				if(fiveFieldsChanged){
					$("#successMessage").append("1 of 5 fields changed<br>");
					if(seedIsUnique){
						$("#successMessage").append("seed is unique<br>");
					}
					else{
						$("#successMessage").append("seed is duplicate<br>");
					}					
				}
				else{
					$("#successMessage").append("1 of 5 fields NOT changed<br>");
				}
				
				/*
				If one of the 5 main fields changed, and seed was duplicate, output error.
				*/
				
				/*
				If one of the 5 main fields changed, and seed was unique, output INSERT Success.
				*/
				
				/*
				messages about Days and Quantity
				*/
				if(daysQtyUpdated){
					$("#successMessage").append("Days and/or Quantity updated<br>");
				}
				else{
					$("#successMessage").append("Days and/or Quantity not changed<br>");
				}
				
				/*
				list of tags messages
				*/
				if(listTagsUpdated){ //if true
					$("#successMessage").append("list of tags updated<br>");
				}
				else {
					$("#successMessage").append("list of tags not updated<br>");
				}
			});
			

		}
		else{			
			$("#validationErrors").empty();
			$("#validationErrors").html(listOfValidationErrors);
		}
	});

	
	//idea from testModuleEditSeed.js
	//OLD
	$("body").on("click", "#submiteditseedformOLD", function(event){
		listOfValidationErrors = ""; //need to reset before testing input again
		$("#successMessage").empty();
		if(validateAllInputs() === true){
			var serializedData = $("#myform").serialize();
			
			var currType = $("#seedTypeInput").val();
			var currName = $("#seedFname").val();
			var currOrigin = $("#seedForigin").val();
			var currYear = $("#seedFyear").val();
			
			/*
			throws the seed at the db.
			If duplicate, not inserted and do alert.
			If unique, INSERT and redirect to searchSeeds.php.
			The decision about duplicate or unique happens in the seedModel.php.
			*/
			$.ajax({ //is seed unique
				url: "searchController.php",
				type: "POST",
				dataType : "html",
				data: {commandForSearchController: 18, serializedData: serializedData, listTagIDs: listTagIDs},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				if(data == "unique"){
					$.ajax({ //update seed
						url: "searchController.php",
						type: "POST",
						dataType : "html",
						data: {commandForSearchController: 6, serializedData: serializedData, listTagIDs: listTagIDs},
						success: function() {
							console.log("success function executed");
						}
					})
					.done(function( data ) {	//data is returned
						listOfValidationErrors = ""; //reset list of errors 
						$("#validationErrors").empty();	//put empty list of errors in red box at top of form 
						$("#validationErrors").html(listOfValidationErrors);
						$("#successMessage").html(successMessage); //put success message in green at top of form
						$("#addSeedForm").find("input[type=text]").val("");	//clear all textfields of form 
					})
					.fail(function( xhr, status, errorThrown ) {
						console.log( "Sorry, there was a problem!" );
						console.log( "Error: " + errorThrown );
						console.log( "Status: " + status );
						console.dir( xhr );
					})
					.always(function( xhr, status ) {
						console.log("The request is complete!");
					});					
				}
				else{
					listOfValidationErrors = "Edit Seed: Duplicate seed. Not changed.";
					$("#validationErrors").html(listOfValidationErrors);
				}
			})
			.fail(function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem! command 17" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			})
			.always(function( xhr, status ) {
				console.log("The request is complete!");
			});
		}
		else{
			
			$("#validationErrors").empty();
			$("#validationErrors").html(listOfValidationErrors);
		}
	});
	
	function checkboxesChanged(){
        var boxes = $(":checkbox:checked");
		console.dir(boxes);
		listTagIDs = ""; //remake listTagIDs every time a checkbox becomes checked or unchecked
        boxes.each(function() {
            var currID = $(this).attr("id");
			console.log("currID = " + currID);
			currID = currID.split("-");
			currID = currID[1];
			listTagIDs += currID + "-";
        });
		console.log("before remove last char" + listTagIDs);
		listTagIDs = listTagIDs.slice(0,-1);//remove last "-" from string
		console.log("after remove last char" + listTagIDs);		
	}
	
	$("body").on("change", "#seedTypeInput", function(event){
		//alert("seed input changed");
		var enteredType = $('#seedTypeInput').val();
		
		//was the entered type, a valid type?
		if(mymod.types.indexOf(enteredType) === -1){
			/*
			console.log(enteredType + " does not exist in types array");
			$("#errorsOfType").append("please enter an available type");
			$("#errorsOfType").addClass("seedFormError");
			*/
			listOfValidationErrors = "Type is not valid.<br>";
			$("#validationErrors").html(listOfValidationErrors);
			$("#seedTypeInput").addClass("formInputError");
		}
		else{
			if($("#seedTypeInput").hasClass("formInputError")){ //if input has red border, remove red border 
				$("#seedTypeInput").removeClass("formInputError");
			}
			$("#validationErrors").empty();
			
			/*
			$("#errorsOfType").append("valid");
			$("#errorsOfType").addClass("seedFormSuccess");
			console.log(enteredType + " does exist in types array");
			*/
			/*
			now that we know the type is valid, get list of tags for that type
			put that list of tags in the right column					
			*/
			//var typeValue = $("#seedTypeInput").val();
			//console.log("type = " + typeValue);
			

			$.ajax({
				type: "POST",
				url: "searchController.php",
				data: {commandForSearchController: 11, seedType : enteredType},
				dataType: "JSON",
				success: function(data){
					//build list of checkboxes, one checkbox for every tag
					var listOfCheckboxes = "";
					$.each(data, function(key, val){
						console.log("key = " + key + " val = " + val);
						var arrayNameID = val.split("-");
						var tagName = arrayNameID[0];
						var tagID = arrayNameID[1];
						listOfCheckboxes += '<input type="checkbox" name="typeTag" value="' + val + '" id="' + val + '">' + tagName;
						listOfCheckboxes += "<br>";
					});
					console.log(listOfCheckboxes);
					$("#typeTagCheckboxes").html(listOfCheckboxes);
				}
			})
			.done(function(){
				console.log("done");
				checkboxesChanged();
			})
			.fail(function(){
				console.log("fail");
			})
			.always(function(){
				console.log("always");
			});

			
			/*
			resetting listTagIDs covers the case where 
			1, user edits seed that has at least 1 tag 
			2, user changes type 
			3, user selects no tags of new type 
			4, user submits edit 
			the listTagIDs are still the tags from the previous type
			
			listTagIDs = "";
			*/
		} //else		
	});
});
