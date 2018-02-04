/*
This is a clever idea I got from the internet.
If Sign In button is clicked with mouse, ajax call happens.
If Enter button on keyboard is pressed, jQuery makes the Sign In button be clicked.
*/
$(window).on('load', function() {
	$("#signinSubmit").on("click", function(event){
		var newusername = $("#signinUsername").val();
		var newpassword = $("#signinPassword").val();
		//alert("in login.js, before ajax to authenticateUser2.php");
		$.ajax({
			url: "authenticateUser2.php",
			type: "POST",
			//dataType : "html",
			dataType : "JSON",
			data: {newusername: newusername, newpassword: newpassword},
			success: function() {
				console.log("success function executed");
			}
		})
		.done(function( data ) {	//data is returned
			/*
			The responseData variable holds 2 JSON objects.
			The 1st JSON object is the status of the user being found or not.
			The 2nd JSON object is all the debugging info that happened in authenticateUser2.php.
			
			I used a JSON object instead of just returning a string held in variable data, because 
			the if() test will fail if I return any debugging info.
			I needed a way to have the if() be true, and return debugging info.
			*/
			var responseData = [];
			
			$.each(data, function(key, value){
				//console.log("key = " + key + " value = " + value);
				responseData.push(value);
			});
			console.log("value 0 = " + responseData[0] );
			console.log("value 1 = " + responseData[1] );
			if(responseData[0] == "userfound"){
				window.location.href = "filesForMainMenuPage/mainMenu.php";
			}
			else{
				$("#loginerror").html("user not found with this username and password");
			}
			/*
			if(data == "userfound"){
				window.location.href = "filesForMainMenuPage/mainMenu.php";
			}
			else{
				$("#loginerror").html("user not found with this username and password");
			}
			*/
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
	
	$(document).keypress(function(event){
		if(event.which == '13'){ //13 means enter key
			$("#signinSubmit").click();
		}
	});
});