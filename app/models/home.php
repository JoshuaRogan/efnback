<?php 
/**
 *	Data model for the page. The controller interacts with this. 
 *	The name needs to be the same name as the page and controller.
 */

class home extends model {
	/**
	 *	Generate a short id that will be combined with the date in the database
	 *	to make it more unique. 
	 */
	public static function generateUID(){
		return rand(0,99999);
	}
}
?>

