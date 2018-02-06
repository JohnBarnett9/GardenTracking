$( document ).ready(function() {
	//allNotesForAllCropsThisSeed()
	$.ajax({
		url: "cropController.php",
		type: "POST",
		dataType : "html",
		data: {commandForCropController: 10},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {
		$("#existingnotesforseed").append(data);
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

	//whichSeedOnShowNotesPage()
	$.ajax({
		url: "cropController.php",
		type: "POST",
		dataType : "html",
		data: {commandForCropController: 19},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {
		$("#seedtypeseedname").html(data);
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
	This event handler processes a new Note being added to a Crop, when sort order is Type.
	one of the classes of the 'Add Note to Crop' button with radio Sort order Type, is addnotetocrop-type
	*/
	$("body").on("click",".addnotetocrop-type", function(event){
		var fivePrevId = $(event.target).prev().prev().prev().prev().prev().attr("id");
		console.log("fivePrevId = " + fivePrevId);
		var idthing = $(event.target).prev().prev().prev().prev().prev().attr("id");
		console.log("idthing = " + idthing);
		var unformattedDateTime = $(event.target).prev().prev().prev().prev().prev().val();
		console.dir(unformattedDateTime);
		var formattedDateTime = formatDateTimeLocal(unformattedDateTime);
		
		//set hidden input to formatted date time 
		$(event.target).next().val(formattedDateTime);
		
		/*
		Sales Page is not production ready yet.
		This line will get the value of the id of the sales text field, 
		that is to the left of 'Add Note to Crop' button.
		*/
		var salesID = $(event.target).parent().find("#extranotes").find("salequantityinput").attr("id");
		
		console.log("salesID = " + salesID);
		
		//submit form
		$(event.target).parent().submit();
	});
	
	$("body").on("click",".addnotetocrop-date", function(event){
		//var unformattedDateTime = $("#dateandtimeInput").val();//2017-01-01T01:01
		var unformattedDateTime = $(event.target).prev().prev().prev().prev().prev().val();
		var formattedDateTime = formatDateTimeLocal(unformattedDateTime);
		console.log("formattedDateTime = " + formattedDateTime);
		$("#dateandtimeInputhidden").val(formattedDateTime);
		$("#addnotetocrop").submit();		
	});
	
	/*
	used by 
	$("body").on("click",".addnotetocrop-type", function(event){
	$("body").on("click",".addnotetocrop-date", function(event){
	$("body").on("click", "#editnote", function(event){
	
	this function also exists in addCropJavaScript.js
	*/
	function formatDateTimeLocal(unformattedDateTime){
		console.log("unformattedDateTime " + unformattedDateTime);
		var bothTimeDate = unformattedDateTime.split("T");
		var datePart = bothTimeDate[0];
		var timePart = bothTimeDate[1];
		var timeHoursMins = timePart.split(":");
		var hours = timeHoursMins[0]
		var mins = timeHoursMins[1];
		var seconds = "00";
		
		var formattedDateTime = datePart + " "  + hours + ":" + mins + ":" + seconds;
		return formattedDateTime;
	}

	//click Edit button in a row of the html table
	var currNotePrimaryKey = "0"; //may have trouble with type
	var editnoteformhidden = "no"; //no means not hidden, yes means hidden
	var doAjaxCall = "no";
	$("body").on("click", ".editbutton", function(event){
		doAjaxCall = "no";
		var idAttribute = $(event.target).attr("id");
		console.log("edit button clicked id = " + idAttribute);
		var idAttributeArray = idAttribute.split("-");
		var cropPrimaryKey = idAttributeArray[2];
		var notePrimaryKey = idAttributeArray[4];
		/*
		if same edit clicked 2 times in a row,
		hide then unhide edit form
		*/
		console.log("currNotePrimaryKey = " + currNotePrimaryKey + " notePrimaryKey = " + notePrimaryKey);
		if(notePrimaryKey === currNotePrimaryKey){
			if(editnoteformhidden === "no"){ //editform is currently displayed
				$("#editnoteform").empty();
				editnoteformhidden = "yes";
				//currNotePrimaryKey = "0"; commenting this removed a tricky error where 
				//after page refresh, every 3rd click of same edit button, did not hide form
			}
			else{ //editform is currently hidden
				editnoteformhidden = "no";
				console.log("inner else");
				doAjaxCall = "yes";//control flow goes to ajax call
			}
		}
		else{
			currNotePrimaryKey = notePrimaryKey;
			doAjaxCall = "yes";
		}
		
		if(doAjaxCall === "yes"){
			//editNoteButtonClicked()
			$.ajax({
				url: "cropController.php",
				type: "POST",
				dataType : "html",
				data: {commandForCropController: 17, cropprimarykey: cropPrimaryKey, noteprimarykey: notePrimaryKey},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {
				$("#editnoteform").html(data);
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
		console.log(" ");
		console.log(" ");
	});
	
	//submitting the edited Note
	$("body").on("click", "#editnote", function(event){
		var unformattedDateTime = $(event.target).prev().prev().prev().prev().prev().val();
		var formattedDateTime = formatDateTimeLocal(unformattedDateTime);
		console.log("formattedDateTime = " + formattedDateTime);
		$("#dateandtimeInputhidden").val(formattedDateTime);
		$(event.target).parent().submit();
	});
	
	$("body").on("click", ".deletenotebutton", function(event){
		var noteIDstuff = $(event.target).attr("id");
		var notePrimaryKey = noteIDstuff.split("-")[2];
		console.log("notePrimaryKey = " + notePrimaryKey);
		//alert("Are you sure you want to delete this note?");
		if (confirm("Are you sure you want to delete this note?")) {
			//delete the note
			$.ajax({
				url: "cropController.php",
				type: "POST",
				dataType : "html",
				data: {commandForCropController: 20, notePrimaryKey: notePrimaryKey},
				success: function() {
					console.log("success function executed");
				}
			})
			.done(function( data ) {
				$("#existingnotesforseed").empty();
				
				//regenerate all notes for all seeds if radio Type
				//regenerate all notes for specific crop if radio Date
				$.ajax({
					url: "cropController.php",
					type: "POST",
					dataType : "html",
					data: {commandForCropController: 10, notePrimaryKey: notePrimaryKey},
					success: function() {
						console.log("success function executed");
					}
				})
				.done(function( data ) {
					$("#existingnotesforseed").append(data);
					//$("#editnoteform").html(data);
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
			$("#editnoteform").empty();
		}		
	});
	

	$("body").on("change", "#spanofaction", function(event){
		//clear out div 
		$(event.target).parent().parent().next().empty();
		var action = $(event.target).val();
		console.log("action = " + action);
		var dataForAjax = {};
		//var currCropNumber = $(event.target).parent().attr("id").split("-")[1];
		//var currCropNumber = $(event.target).parent().parent().parent().attr("id").split("-")[1];
		//console.log("currCropNumber = " + currCropNumber);
		//dataForAjax = ;
		$.ajax({
			url: "cropController.php",
			type: "POST",
			dataType : "html",
			data: {commandForCropController: 23, action: action},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {
			//$("#existingnotesforseed").append(data);
			//$("#extranotes-" + currCropNumber).html(data);
			//$(event.target).after();
			console.log("data = " + data);
			//$(data).insertAfter("#spanofaction"); appends to wrong crop 
			//$(data).insertAfter(event.target); almost
			//$(data).insertAfter($(event.target).parent().parent());//find span, put after span
			$(event.target).parent().parent().next().html(data);//insert into div
			
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
	
	/*
	container totals is invisible button with btnx
	*/
	$("body").on("click", ".containertotalstext", function(event){
		//var description = $(event.target).val();
		var description = $(event.target).text().substring(12,$(event.target).text().length);
		var cropid = $(event.target).attr("id").split("-")[1];
		//alert("cropid = " + cropid);
		//alert("description = " + description);
		$("#divbuttoncontainertotals").empty();
		var newHTML = '<input type="text" name="" value="'+description+'" style="width:500px">';
		newHTML = newHTML + '<button type="button" class="btn savetotals" id="cropid-'+cropid+'">Save</button>';
		$("#divbuttoncontainertotals").html(newHTML);
	});
	
	/*
	container totals is textbox with save button
	*/
	$("body").on("click", ".savetotals", function(event){
		var cropPrimaryKey = $(event.target).attr("id").split("-")[1];
		var containerTotalsString = $(event.target).prev().val();
		console.log("containerTotalsString = " + containerTotalsString + ", cropPrimaryKey = " + cropPrimaryKey);
		$.ajax({
			url: "cropController.php",
			type: "POST",
			dataType : "html",
			data: {commandForCropController: 24, cropPrimaryKey: cropPrimaryKey, containerTotalsString: containerTotalsString},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {
			//nothing happens here, so technically this could be a 
			//POST form with hidden fields, but I decided on ajax
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
		
		var newHTML = '<button type="button" class="btnx containertotalstext" id="cropid-' + cropPrimaryKey + '">' + 'Crop Totals:' + containerTotalsString + '</button>';
		newHTML = newHTML + '&emsp;&emsp;<span class="glyphicon glyphicon-pencil">';
		$("#divbuttoncontainertotals").empty();
		$("#divbuttoncontainertotals").html(newHTML);
		
		
	});
	
	
});