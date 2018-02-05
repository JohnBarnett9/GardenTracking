$(document).ready(function(){
	$("#newuserbutton").on("click", function(event){
		var pswd = $("#passwordinput").val();
		if ( pswd.length < 8 ) {
			$("#validationErrors").append("Password must be at least 8 characters long.");
		}
		else{
			$("#newaccountform").submit();
		}
	});
});
