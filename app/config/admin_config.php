<?php 
/**
 *	This is where you store the metadata for each page. 
 *	Included before the objectd and view. 
 *	You can store constants in the GLOBAL_CONFIG file that can be accessed 
 *	the config class too. 
 *
 *	All static variables can be accessed by config::$variable name
 */

class config extends global_config{
	public static $pageTitle 			= "EFNBack | Admin Page"; 
	public static $pageDescription 		= "Handle sessions data and inspect results from all trial runs. "; 

	public static $stylesheets 			= array("styles/admin.css"); //Include stylesheets 
	public static $javascript 			= array("admin"); //Include javascript files

	public static $header				= false; //False if no header file otherwise the file name in the /app/views/includes/foo.php 
	public static $footer 				= false;	//Same as header 



}
?>