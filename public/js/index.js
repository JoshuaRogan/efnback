$(document).ready(function() {
	$.getJSON( "/get_blocks", function( data ) {
		efnback = new efnback(data);
		console.log(data); 
		console.log(efnback);
		
		 


		//Bind the button to the handler 
		$("#primary-action").click(function() {
			if(efnback.started){
				efnback.buttonPressed();
			}
			else{
				efnback.started = true; 
				$("#primary-action").html("Primary Action"); 
				// $("#instructions_container").removeClass("hidden"); 
				efnback.start(); 
			}
			
		});
	});









});


