$(document).ready(function(){
	//while I am getting 'create new db for new user' working
	//asdf
	/*
	$('input[type=password]').keyup(function() {
		var pswd = $(this).val();
		
		//validate the length
		if ( pswd.length < 8 ) {
			$("#validationErrors").append("Password must be at least 8 characters long.");
			//$('#length').removeClass('valid').addClass('invalid');
		} else {
			//$('#length').removeClass('invalid').addClass('valid');
		}
		
		//validate letter
		if ( pswd.match(/[A-z]/) ) {
			$('#letter').removeClass('invalid').addClass('valid');
		} else {
			$('#letter').removeClass('valid').addClass('invalid');
		}

		//validate capital letter
		if ( pswd.match(/[A-Z]/) ) {
			$('#capital').removeClass('invalid').addClass('valid');
		} else {
			$('#capital').removeClass('valid').addClass('invalid');
		}

		//validate number
		if ( pswd.match(/\d/) ) {
			$('#number').removeClass('invalid').addClass('valid');
		} else {
			$('#number').removeClass('valid').addClass('invalid');
		}
		
		//validate space
		
		if ( pswd.match(/[^a-zA-Z0-9\-\/]/) ) {
			$('#space').removeClass('invalid').addClass('valid');
		} else {
			$('#space').removeClass('valid').addClass('invalid');
		}
		
		
		
	}).focus(function() {
		$('#pswd_info').show();
	}).blur(function() {
		$('#pswd_info').hide();
	});
	*/
	
	
	$("#newuserbutton").on("click", function(event){
		var pswd = $("#passwordinput").val();
		if ( pswd.length < 8 ) {
			$("#validationErrors").append("Password must be at least 8 characters long.");
			//$('#length').removeClass('valid').addClass('invalid');
		}
		else{
			$("#newaccountform").submit();
		}
		
	});
});
