/*
mymod uses the Module Pattern.
the variables available from mymod are
	types, contains available types
	origins, contains available origins 
	tags, contains available tags 

mymod returns these name value pairs, which make the variables and functions within mymod available to the rest of the code in this file 
	desc: desc,
	types: types,
	origins: origins,
	tags: tags,
	init: init,
	validateType: validateType,
	typeAutocomplete: typeAutocomplete,
	tagsAutocomplete: tagsAutocomplete,
	availSeedType: availSeedType,
	availSeedOrigin: availSeedOrigin,
	availSeedTags: availSeedTags	
	
*/
var mymod = (function () {
	
	var desc = "IIFE variable";
	var types = [];
	var origins = [];
	var tags = [];

	var init = function() {
		document.getElementById("content").innerHTML="init executed";
	};

	var validateType = function(){
		$("#seedTypeInput").blur(function(){
			$('#errorsOfType').empty();
			var enteredType = $('#seedTypeInput').val();
			
			//was the entered type, a valid type?
			if(types.indexOf(enteredType) === -1){
				console.log(enteredType + " does not exist in types array");
				$("#errorsOfType").append("please enter an available type");
				$("#errorsOfType").addClass("seedFormError");
			}
			else{
				$("#errorsOfType").append("valid");
				$("#errorsOfType").addClass("seedFormSuccess");
				console.log(enteredType + " does exist in types array");

				/*
				now that we know the type is valid, get list of tags for that type
				put that list of tags in the right column					
				*/
				var typeValue = $("#seedTypeInput").val();
				console.log("type = " + typeValue);
				$.ajax({
					type: "POST",
					url: "searchController.php",
					data: {commandForSearchController: 11, seedType : typeValue},
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
				})
				.fail(function(){
					console.log("fail");
				})
				.always(function(){
					console.log("always");
				});
			} //else
		});
	};

	var typeAutocomplete = function() {
		$( "#seedTypeInput" ).autocomplete({
			source: types
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
		console.log("in availSeedTye");
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
	
	//calls getAvailableOrigins() in seedModel.php
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
				//mymod.origins.push(val)
				$("#dropdownlistseedorigins").append("<option value='" + val + "' id='" + key + "'>");
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
		init: init,
		validateType: validateType,
		typeAutocomplete: typeAutocomplete,
		tagsAutocomplete: tagsAutocomplete,
		availSeedType: availSeedType,
		availSeedOrigin: availSeedOrigin,
		availSeedTags: availSeedTags
	}
})();


/*
After the window finishes loading, I need to initialize the types, origins, and tags variables in mymod. I do this by calling functions with mymod, using the '.' operator.

var listTagIDs = "";, needs to be at the same level as the event listeners in window.on(load)


*/
$(window).on('load', function() {
		
	mymod.tagsAutocomplete();
	mymod.validateType();
	mymod.availSeedType();
	mymod.availSeedOrigin();
	mymod.availSeedTags();

	var listTagIDs = "";
	var listOfValidationErrors = "";
	var successMessage = "Seed INSERTed successfully.";

	/*
	rebuilds the listTagIDs variable whenever a checkbox is checked
	*/
	$('body').change('input[type=checkbox]', function() {
        var boxes = $(":checkbox:checked");
		console.dir(boxes);
		listTagIDs = ""; //remake listTagIDs every time a checkbox becomes checked or unchecked
        boxes.each(function() {
            var currID = $(this).attr("id");
			console.log("currID = " + currID);
			currID = currID.split("-");
			currID = currID[1];
			listTagIDs += currID + "-";
			//listTagIDs += currID + ","; //can't have ',' in id
			//checkedTagNames += $(this).attr("id") + "-";
        });
		console.log("before remove last char" + listTagIDs);
		listTagIDs = listTagIDs.slice(0,-1);//remove last "-" from string
		console.log("after remove last char" + listTagIDs);
	});
	
	/*
	using .submit() callback becuase I want to use html 'required'
	do I ever return true?
	returning true means the form is submitted 
	I need to do processing before the form is submitted.
	
	high level algorithm used 
	if all inputs are valid and not empty 
		if seed is unique 
			INSERT seed 
			allow user to enter another seed 
		else 
			print error string of "this seed is duplicate"
	else
		print error string
	*/
	$("#addSeedFormButton").on("click", function(event){
		listOfValidationErrors = ""; //need to reset before testing input again
		$("#successMessage").empty();
		if(validateAllInputs() === true){
			var serializedData = $("#addSeedForm").serialize();
			$.ajax({ //is seed unique
				url: "searchController.php",
				type: "POST",
				dataType : "html",
				data: {commandForSearchController: 17, serializedData: serializedData},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				if(data == "unique"){
					var listTags = listTagIDs;
					$.ajax({ //add new seed
						url: "searchController.php",
						type: "POST",
						dataType : "html",
						data: {commandForSearchController: 9, serializedData: serializedData, listTags: listTags},
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
						console.log( "Sorry, there was a problem! command 9" );
						console.log( "Error: " + errorThrown );
						console.log( "Status: " + status );
						console.dir( xhr );
					})
					.always(function( xhr, status ) {
						console.log(" 9 The request is complete!");
					});
				}
				else{
					var currType = $("#seedTypeInput").val();
					var currName = $("#seedNameInput").val();
					var currOrigin = $("#seedOriginInput").val();
					var currYear = $("#seedYearInput").val();
					listOfValidationErrors = "this is a duplicate seed, change one of these 3 values, Name " + currName + " Origin " + currOrigin + " Year " + currYear;
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

	//$("body").on("click", "#submiteditseedform", function(event){
	$("#addSeedFormButtonOLD").on("click", function(event){
		$("#listTagsForSeed").val(listTagIDs); //must use id attribute of hidden field
		console.log("listTagIDs = " + listTagIDs);
		listOfValidationErrors = ""; //need to reset before testing input again
		if(validateAllInputs() === true){
			var data = $("#addSeedForm").serialize();
			var currName = $("#seedNameInput").val();
			var currOrigin = $("#seedOriginInput").val();
			var currYear = $("#seedYearInput").val();
			
			var listTags = listTagIDs;
			$.ajax({
				url: "searchController.php",
				type: "POST",
				dataType : "html",
				data: {commandForSearchController: 9, data: data, listTags: listTags},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				if(data == "inserted"){
					window.location.href = "searchSeeds.php";
				}
				else{
					alert("this is a duplicate seed, change one of these 3 values, Name " + currName + " Origin " + currOrigin + " Year " + currYear);
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
			$("#validationErrors").empty();
			$("#validationErrors").html(listOfValidationErrors);
		}
	});

	function validateAllInputs(){
		var returnValue = true;

		/*
		none of the textfields can be empty 
		These textfields do not need an explicit test of empty, 
		because they require specific input 
		Type, Name, Year, Days
		
		These textfields require an explicit test of empty 
		Origin, Quantity, Note
		*/
		
		//used by origin, quantity, note
		var regexPrintableChars = /^[A-Za-z0-9!"#$%&'()*+,.\/:;<=>?@\[\] ^_`{|}~-]*$/;
		
		var enteredType = $("#seedTypeInput").val();
		var typeFound = mymod.types.includes(enteredType);
		if(typeFound){
			//alert("type found");
			if($("#seedTypeInput").hasClass("formInputError")){
				$("#seedTypeInput").removeClass("formInputError");
			}
		}
		else{
			//alert("type not found");
			/*
			need to empty Type textfield if user tries to submit seed with not valid Type.
			
			Add a seed. Put in fake type of "dog". Correctly caught bad type.
			But now Type drop down doesn't work.
			*/
			$("#seedTypeInput").val('');
			$("#seedTypeInput").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Type must be chosen from drop down menu<br>";
			returnValue = false;
		}

		
		//var str = $("#seedFname").val();
		var str = $("#seedNameInput").val();
		var nameRegex = /^[a-zA-Z0-9\s]+$/;
		if(nameRegex.test(str)){
			if($("#seedNameInput").hasClass("formInputError")){ //if input has red border, remove red border 
				$("#seedNameInput").removeClass("formInputError");
			}
		}
		else{
			$("#seedNameInput").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Name: " + str + " is not valid<br>";
			returnValue = false;
		}
		
		var enteredYear = $("#seedYearInput").val();
		var currentYear = new Date().getFullYear();
		if((enteredYear >= 1980) && (enteredYear <= currentYear)){
			if($("#seedYearInput").hasClass("formInputError")){
				$("#seedYearInput").removeClass("formInputError")
			}
		}
		else{
			$("#seedYearInput").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Year: please enter a year between 1980 and " + currentYear + "<br>";
			returnValue = false;
		}
		
		var enteredOrigin = $("#seedOriginInput").val();
		if(enteredOrigin !== ''){
			if(regexPrintableChars.test(enteredOrigin)){
				if($("#seedOriginInput").hasClass("formInputError")){
					$("#seedOriginInput").removeClass("formInputError")
				}
			}
			else{
				$("#seedOriginInput").addClass("formInputError");
				listOfValidationErrors = listOfValidationErrors + "Origin: please enter only printable characters<br>";
				returnValue = false;
			}			
		}
		else{
			$("#seedOriginInput").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Origin: cannot be empty<br>";
		}
		
		/*
		Days textfield cannot be empty, enforced with dayLength.
		*/
		var enteredDays = $("#seedDaysInput").val();
		var dayLength = enteredDays.length;
		if((enteredDays >= 0) && (enteredDays <= 199) && (dayLength !== 0)){
			if($("#seedDaysInput").hasClass("formInputError")){
				$("#seedDaysInput").removeClass("formInputError");
			}
		}
		else{
			$("#seedDaysInput").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Days: please enter number >= 0 and <= 199<br>";
			returnValue = false;
		}
		
		
		var enteredQuantity = $("#seedQuantityInput").val();
		if(enteredQuantity !== ''){
			if((enteredQuantity.length <= 20) && (regexPrintableChars.test(enteredQuantity))){
				if($("#seedQuantityInput").hasClass("formInputError")){
					$("#seedQuantityInput").removeClass("formInputError");
				}
			}
			else{
				$("#seedQuantityInput").addClass("formInputError");
				listOfValidationErrors = listOfValidationErrors + "Quantity: must be <= 20 characters in length, must be only printable characters<br>";
				returnValue = false;
			}			
		}
		else{
			$("#seedQuantityInput").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Quantity: cannot be empty<br>";
			returnValue = false;
		}
		
		var enteredNote = $("#seedNoteInput").val();
		if(enteredNote !== ''){
			if(regexPrintableChars.test(enteredNote)){
				if($("#seedNoteInput").hasClass("formInputError")){
					$("#seedNoteInput").removeClass("formInputError");
				}
			}
			else{
				$("#seedNoteInput").addClass("formInputError");
				listOfValidationErrors = listOfValidationErrors + "Note: enter only printable chars<br>";
			}
		}
		else{
			$("#seedNoteInput").addClass("formInputError");
			listOfValidationErrors = listOfValidationErrors + "Note: cannot be empty<br>";
			returnValue = false;
		}
		
		return returnValue;
	}	
});
