$( document ).ready(function(){
	//existingnotesforcrop
	$.ajax({
		url: "cropController.php",
		type: "POST",
		dataType : "html",
		data: {commandForCropController: 8},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {
		$("#listofnotes").append(data);
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
	
	//crop information , example: Pepper Early Jalapeno Fedco 2015
	$.ajax({
		url: "cropController.php",
		type: "POST",
		dataType : "html",
		data: {commandForCropController: 7},
		success: function() {
			console.log("success function executed");
		}
	})
	.done(function( data ) {
		//console.log("data = " + data);
		$("#cropinfo").html(data);
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
	
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd < 10){
      dd='0'+dd;
    }
    if(mm < 10){
       mm="0"+mm;
    } 
    today = mm + '-' + dd + '-' + yyyy;	
	$("#dateandtimeInput").val(today);
	
	$("#addNoteToCropFormButton").on("click", function(){
		console.log("in submit clicked");
		$("#addSeedForm").attr("action", "cropController.php");
		$("#addSeedForm").attr("method", "POST");
		$("#addSeedForm").submit();
	});
});