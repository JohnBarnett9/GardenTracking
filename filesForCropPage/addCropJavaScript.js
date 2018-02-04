$( document ).ready(function(){
	//get seed id and put in textbox
	$.ajax({
		url: "cropController.php",
		type: "POST",
		dataType : "html",
		data: {commandForCropController: 22},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {	//data is returned
		console.log("data="+data);
		$("#seedidfornewcropinput").val(data);
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
	
	//set text for Initial Note
	$("#initialnoteinput").val("Initial note for this crop.");
	
	//this works anywhere
	var cropStartDate = new Date();
	var tzoffset = cropStartDate.getTimezoneOffset() / 60;
	cropStartDate.setHours(cropStartDate.getHours() - tzoffset);
	cropStartDate = cropStartDate.toJSON();
	cropStartDate = cropStartDate.slice(0,16);
	$("#cropstartdateinput").val(cropStartDate);
	
	//submit new crop 
	$("#submitnewcropbutton").on("click", function(){
		$("#addcropform").submit();
	});
	
	/*
	this function also exists in showNotesForSeedJavaScript.js
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
	
});