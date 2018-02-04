$( document ).ready(function() {

	$("#droptablescreatetables").on("click", function(){
		$.ajax({
			url: "adminDatabaseFunctions.php",
			type: "POST",
			dataType : "html",
			data: { adminDatabaseCommand: 1 },
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			$("#droptablescreatetablesoutput").html(data);
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
	
	
	$("#addminimaldata").on("click", function(){
		$.ajax({
			url: "adminDatabaseFunctions.php",
			type: "POST",
			dataType : "html",
			data: { adminDatabaseCommand: 2 },
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			$("#addminimaldataoutput").html(data);
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
	
	
	$("#realisticdataset").on("click", function(){
		$.ajax({
			url: "adminDatabaseFunctions.php",
			type: "POST",
			dataType : "html",
			data: { adminDatabaseCommand: 3 },
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			$("#realisticoutput").html(data);
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

	
});