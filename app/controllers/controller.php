<?php 
/**
 *	The global controller that is alwayr inherited 
 *
 */

class controller {
	public static $data = array();

	public function push($itemName,$item){
		$this->$data[$itemName] = $item; 
	} 

	public static function render(){
		
	}
}

?>