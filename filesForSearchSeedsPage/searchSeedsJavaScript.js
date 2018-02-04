$( document ).ready(function() {

	$.ajax({
		url: "searchController.php",
		type: "POST",
		dataType : "html",
		data: {commandForSearchController: 2},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {	//data is returned
		//console.log("data="+data);//can be a lot of output
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
	
	/*
	default action of searchSeeds.php is to list all seeds 
		when page is loaded 
		when unclick open vegetable name 
	*/
	listAllSeeds();
	
    var currentType = '';   // current type of veggie open

	$('body').on('click', '.type', function(event) {
        prevType = currentType;
        currentType = $(event.target).attr('id');
		console.log("currentType = " + currentType + " prevType " + prevType);
        if (currentType!=prevType) { //true if I had clicked on bean, and now I click on pepper
			closeAllOpenTypes();//if any veg name like Pepper is expanded, close it
			searchControllerCommand3(currentType); //ajax call when I want to display only a specific type of vegetable 
			
			var tagsElement = $(event.target).next();
			$(tagsElement).toggle(); //expands the current veg name 
        }
		else{
			listAllSeeds(); //calls .toggle(), so don't need to call .toggle() here
			currentType = '';
		}
    });
		
	$("#addASeed").on("click", function(){
		window.location.href = "addSeed.php";
	});
	
    // clear checkboxes for a type
	$("body").on("click", ".clear", function(event){
        $(event.target).siblings().find("input:checkbox").prop('checked', false);// Uncheck all checkboxes in this type
        $("#checklist").empty();    // empty list of checkboxes
		var currentType = $(event.target).parent().prev().attr('id');
		searchControllerCommand3(currentType); //ajax call when I want to display only a specific type of vegetable 
    });
	
	/*
	ajax call when I want to display only a specific type of vegetable 
	used by 
	$("body").on("click", ".clear", function(event){
	$('body').on('click', '.type', function(event) {
	*/
	function searchControllerCommand3(currentType){
		$.ajax({ //$.ajax and .done are from what was originally index.html
			url: "searchController.php",
			type: "POST",
			dataType : "html",
			data: {commandForSearchController: 3, currentType: currentType},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			console.log("data="+data);
			$("#output").html(data);
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
	
	
	
	
	$('body').on('click', '.editbutton', function(event){
		console.log("edit Button clicked");
		console.log(event.target.id);
		var seedID = event.target.id; //the number I want is at the end of the event.target.id string.
		var matches = seedID.match(/\d+$/); //separate the number from the rest of the id "orange-9", get the 9
		var seedPrimaryKey;
		if(matches){
			seedPrimaryKey = matches[0];
			console.log("seedPrimaryKey = " + seedPrimaryKey);
		}
		$("#keytopass").val(seedPrimaryKey);
		$('#myform').submit();
		
	});
	
	/*
	//delete a seed
	$("body").on("click", ".deletebutton", function(event){
		console.log("delete button clicked");
		
		$( function() { //the pop up confirmation window
			//not working, name of seed show in delete confirmation box
			//$('#dialog-confirm').dialog('option', 'title', 'aaaaaaaaaaaaaaaaaaaaa');
			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height: "auto",
				width: 400,
				modal: true,
				buttons: {
					"Delete": function() { //the stuff that happens when you click 'Delete' on the popup window
						$( this ).dialog( "close" );
						console.log("Delete clicked");
						console.log(event.target.id);
						var seedID = event.target.id; //the number I want is at the end of the event.target.id string.
						var matches = seedID.match(/\d+$/); //separate the number from the rest of the id "orange-9", get the 9
						var seedPrimaryKey;
						if(matches){
							seedPrimaryKey = matches[0];
							console.log("seedPrimaryKey = " + seedPrimaryKey);
						}
						
						//do the delete of the seed
						$.ajax({
							url: "searchController.php",
							type: "POST",
							dataType : "html",
							data: {commandForSearchController: 8, seedPrimaryKey: seedPrimaryKey},
							success: function() {
								console.log("success function executed");
							}
						})
						.done(function( data ) {	//data is returned
							console.log("data from delete click ajax ="+data);
							$("#output").append(data);
							$.ajax({ //redisplay the seeds of a certain type, with a seed having been deleted
								url: "searchController.php",
								type: "POST",
								dataType : "html",
								data: {commandForSearchController: 3, currentType: currentType},
								success: function() {
									console.log("success function executed");
								}
							})
							.done(function( data ) {	//data is returned
								console.log("data="+data);
								$("#output").empty();
								$("#output").html(data);
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
							alert( "Sorry, there was a problem!" );
							console.log( "Error: " + errorThrown );
							console.log( "Status: " + status );
							console.dir( xhr );
						})
						.always(function( xhr, status ) {
							console.log("The request is complete!");
						});						
					},
					Cancel: function() {
					  $( this ).dialog( "close" );
					}
				}
			});
		  });
	});
	*/
	/*
	I am refactoring the database connection that seedmodel uses.
	In the process of doing this, I need to c onfirm Delete Seed works.
	The dialog box is not working, so isolate the ajax calls and confirm the seedmodel function is working.
	*/
	$("body").on("click", ".deletebutton", function(event){
		console.log("delete button clicked");
		console.log("Delete clicked");
		console.log(event.target.id);
		var seedID = $(event.target).attr("id");
		var seedName = seedID.split("-")[1];
		
		if(confirm("Are you sure you want to delete " + seedName + "?") == true){
			console.log("deleting");
			var seedID = event.target.id; //the number I want is at the end of the event.target.id string.
			var matches = seedID.match(/\d+$/); //separate the number from the rest of the id "orange-9", get the 9
			var seedPrimaryKey;
			if(matches){
				seedPrimaryKey = matches[0];
				console.log("seedPrimaryKey = " + seedPrimaryKey);
			}
			
			//do the delete of the seed
			$.ajax({
				url: "searchController.php",
				type: "POST",
				dataType : "html",
				data: {commandForSearchController: 8, seedPrimaryKey: seedPrimaryKey},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {	//data is returned
				console.log("data from delete click ajax ="+data);
				$("#output").append(data);
				
				//if no veg Type has been selected
				if(currentType == ''){
					//alert("no type selected");
					//reregenerate list of all seeds 
					listAllSeeds();
				}
				else{
					//no checkboxes have been checked
					if(numCheckedBoxes == 0){
						//alert("type selected, no checkboxes");
						searchControllerCommand3(currentType); //ajax call when I want to display only a specific type of vegetable 
					}
					else{
						//alert("type selected, yes checkboxes");
						
						var dataToSendToPHP;
						dataToSendToPHP = {commandForSearchController: 4, currentType: currentType, numCheckedBoxes: numCheckedBoxes, checkedTagNames: checkedTagNames };					
						searchControllerCommand4(dataToSendToPHP);
					}
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
			console.log("do not delete");
		}
	});	

	$("body").on("click", ".cropsforthisseed", function(){
		var currID = $(this).attr("id");
		console.log("currID = " + currID);
		var currSeedArray = currID.split("-");
		var seedName = currSeedArray[1];
		var seedID = currSeedArray[2];
		$("#seedidofcrop").val(seedID);
		$("#cropbuttonform").submit();
	});
	
	var numCheckedBoxes = 0;
	var checkedTagNames = "";
	
    // List checkboxes that are checked
	$('body').change('input[type=checkbox]', function() { 
		numCheckedBoxes = 0;
		console.log("in checked boxes");
        var boxes = $(":checkbox:checked");
        var checkedIds = ""; //for displaying in the webpage immediately
		checkedTagNames = ""; //for sending to php
        boxes.each(function() {
			numCheckedBoxes++;
			console.log("numCheckedBoxes = " + numCheckedBoxes);
            checkedIds += $(this).attr("id") + " checked<br/>";
			checkedTagNames += $(this).attr("id") + "-";
        });
		
		checkedTagNames = checkedTagNames.slice(0, -1); //trim ',' from end of variable
		console.log("checkedTagNames = " + checkedTagNames);
		
		/*
		when I uncheck a checkbox and the number of checked checkboxes becomes 0,
		I need to display the table of all the seeds of the type.
		If the number of checked boxes is greater than 0, send the number of checked boxes and
		the checkedTagNames.
		*/
		var dataToSendToPHP;
		if(numCheckedBoxes === 0){
			dataToSendToPHP = {commandForSearchController: 3, currentType: currentType};
		}
		else{
			dataToSendToPHP = {commandForSearchController: 4, currentType: currentType, numCheckedBoxes: numCheckedBoxes, checkedTagNames: checkedTagNames };
		}
		
		searchControllerCommand4(dataToSendToPHP);
    });
	
	
	/*
	call to php to build html table
	if a veg type is selected and one or more checkboxes have been checked
	*/
	function searchControllerCommand4(dataToSendToPHP){
		$.ajax({ //$.ajax and .done are from what was originally index.html
			url: "searchController.php",
			type: "POST",
			dataType : "html",
			data: dataToSendToPHP,
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			console.log("data="+data);
			$("#output").html(data);
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
	
	
	//add crop to seed 
	$("body").on("click", ".addcroptoseed", function(event){
		var seedAttrID = $(event.target).attr("id");
		var seedIDArray = seedAttrID.split("-");
		var seedPrimaryKey = seedIDArray[2];
		console.log("seedPrimaryKey = " + seedPrimaryKey);
		$("#seedprimarykey").val(seedPrimaryKey);
		$("#addcroptoseedform").submit();
	});
	
	$("#downloadtxtfilebutton").on("click", function(event){
		$("#downloadtxtfile").submit();
	});
	
	
	/*
	close all open types
	*/
	function closeAllOpenTypes(){	
		$('.tag').each(function() {
			if ($(this).is(':visible')) {
				$(this).toggle();
			}
		});
	}

	/*
	default action of searchSeeds.php is to list all seeds 
		when page is loaded 
		when unclick open vegetable name 
	*/	
	function listAllSeeds(){
		closeAllOpenTypes();//if any veg name like Pepper is expanded, close it
		$.ajax({
			url: "searchController.php",
			type: "POST",
			dataType : "html",
			data: {commandForSearchController: 1},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			//console.log("data="+data); //if the table has more than a few rows, can become much output
			$("#output").html(data);
			
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


