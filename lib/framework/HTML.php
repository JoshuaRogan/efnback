<?php
class HTML{
	
	public static $html = array(); 

	public static function add($variableName, $data){
		self::$html[$variableName] = $data; 
	}


}


?>