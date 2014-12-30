$(document).ready(function() {

	startListening(); 
	//startTimer(); 
	//stopListening();
	//StopTimer(); 


});

/**
 *	Listen to the keyboard for key presses.   
 *
 */
function startListening(){
	var charPressed = ""; 
	$("body").keypress(function( event ) {
		charPressed = String.fromCharCode(event.charCode)
		// console.log(charPressed);
		$("#char-pressed").html("You pressed " + charPressed);
	});

	return charPressed; 
}

/**
 *	Stop listening for keys 
 *
 */
function stopListening(){
	$("body").unbind('keypress', null);
}
