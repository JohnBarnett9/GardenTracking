$( document ).ready(function() {

	//filterlist 
	$.ajax({
		url: "cropController.php",
		type: "POST",
		dataType : "html",
		data: {commandForCropController: 12},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {
		//console.log("data="+data);
		$("#filterlist").html(data);
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
	
	//$('input[type=radio]').change(function(){  });
	
	var currentRadioSortOrder = "type";
	//$('#foobar input[type=radio]').change(function(){ //works, but not needed
	$('input[type=radio]').change(function(){	
		//alert("radio event");
		var valueRadioButtonChecked = $("input[type=radio]:checked").val();
		if(valueRadioButtonChecked === 'bytype'){
			currentRadioSortOrder = "type";
		}
		else{
			currentRadioSortOrder = "date";
		}
		
		//ajax call
		$.ajax({
			url: "cropController.php",
			type: "POST",
			dataType : "html",
			data: {commandForCropController: 15, currentRadioSortOrder: currentRadioSortOrder, currentType: currentType, checkedStuff: checkedStuff, numCheckedBoxes: numCheckedBoxes},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {
			$("#croplist").html(data);
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
	});
	
	
	var currentType = "none";   // current type of veggie open
	$('body').on('click', '.type', function(event){
        prevType = currentType;
        currentType = $(event.target).attr('id');
		console.log("currentType = " + currentType + " prevType " + prevType);
        if (currentType!=prevType) { //true if I had clicked on bean, and now I click on pepper
			closeAllOpenTypes();
			allCropsOfType();
			
			//expand current type
			var tagsElement = $(event.target).next();//
			$(tagsElement).toggle();    // show/hide tags
			$("input:checkbox").prop('checked', false); // clear all checkboxes
			$("#checklist").empty(); // empty checklist (list of checked checkboxes)
			
        }
		else{
			closeAllOpenTypes();
			listAllCrops();
			currentType = 'none';
		}		
	});
	
	//$('body').change('input[type=checkbox]', function() {});
	
	var checkedStuff = "";
	var numCheckedBoxes = 0;
	//$('body').change('#filterlist input[type=checkbox]', function() {
	$('#filterlist').change('input[type=checkbox]', function() {
		//alert("checkbox event");
		var numCheckedBoxes = 0;
		console.log("in checked boxes");
        var boxes = $(":checkbox:checked");
        var checkedIds = ""; //for displaying in the webpage immediately
		var checkedTagNames = ""; //for sending to php
        boxes.each(function() {
			numCheckedBoxes++;
			console.log("numCheckedBoxes = " + numCheckedBoxes);
            checkedIds += $(this).attr("id") + " checked<br/>";
			checkedTagNames += $(this).attr("id") + "-";
        });
		
		checkedTagNames = checkedTagNames.slice(0, -1); //trim ',' from end of variable
		checkedStuff = checkedTagNames;
		console.log("checkedTagNames = " + checkedTagNames);
		console.log("numCheckedBoxes = " + numCheckedBoxes);

		//ajax call
		$.ajax({
			url: "cropController.php",
			type: "POST",
			dataType : "html",
			data: {commandForCropController: 15, currentRadioSortOrder: currentRadioSortOrder, currentType: currentType, checkedStuff: checkedStuff, numCheckedBoxes: numCheckedBoxes},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {
			$("#croplist").html(data);
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
	});
	

	$("body").on("click", ".clear", function(event){
		$(event.target).siblings().find("input:checkbox").prop('checked', false);
	});

	
	/*
	This ajax call must be after the 3 listeners of radio, type, tag.
	If not, currentRadioSortOrder is not initialized to "type".
	
	I'm just trying to confirm the listeners for Type and Date radio buttons work.
	This code is commented to try to simplify things.
	*/
	$("#radiotype").prop("checked", true);
	listAllCrops();

	/*
	regardless of which radio button is selected,
	When user clicked 'Show Notes' on crop.php,
	this listener is called.
	On the Server, PHP SESSION var tells the controller 
	to generate either the show notes for Type or show notes for Date.
	*/
	$("body").on("click", ".shownotes", function(){
		if(currentRadioSortOrder === "type"){
			var showNotes = $(this).attr("id");
			var showNotesArray = showNotes.split("-");
			var seedPrimaryKey = showNotesArray[2];
			
			$("#seedidtopass").val(seedPrimaryKey);
			$("#shownotesform").submit();			
		}
		else{
			var showNotes = $(this).attr("id");
			var showNotesArray = showNotes.split("-");
			var cropPrimaryKey = showNotesArray[2];
			console.log("cropPrimaryKey = " + cropPrimaryKey);
			$("#cropidtopass").val(cropPrimaryKey);
			//var temp = $("#cropidtopass").val();
			console.log("cropidtopass hidden value = " + $("#cropidtopass").val());
			$("#shownotesform").submit();
		}
	});	
	

	/*
	close all open types 
	*/
	function closeAllOpenTypes(){
		// close all open types
		$('.tag').each(function() {
			if ($(this).is(':visible')) {
				$(this).toggle();
			}
		});		
	}	
	
	/*
	default action of crop.php is to list all seeds, sorted by type 
		when page is loaded 
		when unclick open vegetable name 
			if sort order is Type, list all crops by Type 
			if sort order is Date, list add crops by Date
	*/
	function listAllCrops(){
		$.ajax({
			url: "cropController.php",
			type: "POST",
			dataType : "html",
			//data: {commandForCropController: 15, currentRadioSortOrder: currentRadioSortOrder, currentType: currentType, checkedStuff: checkedStuff, numCheckedBoxes: numCheckedBoxes},		
			data: {commandForCropController: 15, currentRadioSortOrder: currentRadioSortOrder, currentType: "none", checkedStuff: 0, numCheckedBoxes: 0},					
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {
			$("#croplist").html(data);
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
	
	function allCropsOfType(){
		$.ajax({
			url: "cropController.php",
			type: "POST",
			dataType : "html",
			data: {commandForCropController: 15, currentRadioSortOrder: currentRadioSortOrder, currentType: currentType, checkedStuff: 0, numCheckedBoxes: 0},					
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {
			$("#croplist").html(data);
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