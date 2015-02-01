<?php 
	/**
	 *	Processes the finished EFNBack task. 
	 *		-Validates that it was actually taken
	 *		-Adds it to the database 
	 */

	//Parse the JSON 

	$model = new efnbackModel(1, 1532120); //session id, userid

?>