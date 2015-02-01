$(document).ready(function() {
	$.getJSON( "/get_blocks", function( data ) {
		$("#loading").addClass("hidden"); 
		$("#loading").html(""); 

		//Suggest what the session and user id's may be
		suggestIDs();



		efnback = new efnback(data);
		console.log(data); 
		
		
		var valid = false; //Make sure the form is valid before starting the task 
		//Bind the button to the handler 
		$('html').on('vmousedown tap ',function(event) {
			if(valid) event.preventDefault(); //Only prevent the default action if the form isn't showing 
			if(efnback.started){
				efnback.buttonPressed();
			}
			else{
				//Validate the form first 
				var sessionID = $("#sessionID").val();
				var userID = $("#userID").val();
				

				if(valid) {
					efnback.start(sessionID, userID); //Start the task 
					$("#test-variables").addClass("hidden");
					$("#test-variables").html("");

					//Store the session and userid variables 

					console.log(efnback);
				}
			}
		});

		//Submit the form 
		$("#start-test").on('vmousedown', function(event){
			console.log("test submitted");

			var sessionID = $("#sessionID").val();
			var userID = $("#userID").val();

			if(validForm(sessionID, userID)) valid = true; 


		}); 

		//Generate a unique user id 
		$("#generate-uid").on('vmousedown', function(event){
			$("#userID").val(generateUID()); 
		}); 

	});	
});

/**
 *	Check cookies/web datastore for the user and session ids
 *
 */
function suggestIDs(){
	
}


/**
 *	Return a random 7 digit number 
 *
 */
function generateUID(){
	return Math.floor(Math.random() * 9000000) + 1000000;
}

/** 
 *	Current validation rules is just to make sure they are numeric and 7 digits 	
 *
 */
 function validForm(sessionID, userID){
 	var isValid = true; 
 	// console.log(sessionID, userID);

 	//Validate the SESSION ID
 	if(!$.isNumeric(sessionID) || sessionID < 1000000 || sessionID > 9000000){
 		isValid = false; 
 		$("#sessionID").parent().addClass("has-error");
 	} 
 	else{
 		$("#sessionID").parent().removeClass("has-error");
 		$("#sessionID").parent().addClass("has-success");
 	}

 	//Validate the USER ID
 	if(!$.isNumeric(userID) || userID < 1000000 || userID > 9000000) {
 		isValid = false; 
 		$("#userID").parent().addClass("has-error");
 	}
 	else{
 		$("#userID").parent().removeClass("has-error");
 		$("#userID").parent().addClass("has-success");
 	}

 	//Update the DOM with error messages 
 	if(!isValid){
 		$("#form-errors").html("<p class='text-danger'> Please make sure you have a valid 7 digit session and user id! </p>");
 	}
 	else{
 		$("#form-errors").html(""); // Clear the errors 
 	}

 	return isValid; 
 }