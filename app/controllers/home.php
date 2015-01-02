<?php 
/**
 *	The home controller to interact with the model 
 *
 */

class home_controller extends controller {

	public static function render(){
		$this->push("uid", "test"); 
		$_DEBUG[] = "REMDER";
	}
	
}

?>