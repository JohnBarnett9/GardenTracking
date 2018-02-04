$( document ).ready(function(){
	$("body").on("click", "#editnote", function(){
		//alert("edit note button clicked");
		$("#editnoteform").submit();
	});
});