<?php 
/*		Convience class for alerts. Consider abstracting  
 *
 * 		Author: Josh Rogan
 *		Version: 1.0
 *		Date Last Modified: March 2014
 */
class mySQL{
	public static $connection = false; //One connection for all of the mySQL classes 

	public $numQueries; //Overall count of the number of queries 

	public function __construct(){
		//Using the credientials in the GLOBAL CONFIG page connecd to the database
		self::connect(config::$db_servername, config::$db_username, config::$db_password, config::$db_database);

		$this->numQueries = 0;
	}

	//Check to if the id on the given table exists
	public function exists($id, $table_name){
		$query = "SELECT id FROM $table_name WHERE id = $id";
		$results = self::query($query); 
		$this->numQueries++;

		return ($results->num_rows > 0);
	}

	//Perform select quries on the active database
	public function select($column_string, $table_name){
		$query = "SELECT $column_string FROM $table_name";
		$results = self::query($query); 
		$this->numQueries++;

		return $results; 
	}

	//Perform queries on the active database  
	public static function query($query){
		//Perform sanitization on ther query here



		return self::$connection->query($query); 
	}

	//Create Database with nothing 
	public function create_database($name){

	}



	//Finish quries 
	public function finish(){
		self::close_connection(); 
	}


	//Set connection to a databse that isn't from the config 
	public static function override_connection($servername, $username, $password, $database_name){

	}

	//Change the active database 
	public static function change_database(){

	}



	/********************************************PRIVATE METHODS********************************************/

	//Establish the connection with mySQL
	private static function connect($servername, $username, $password, $database_name){
		if(!self::$connection){
			self::$connection = new mysqli($servername, $username, $password, $database_name);
			if (self::$connection->connect_error) {
			    die("Connection failed: " . self::$connection->connect_error);
			} 
			if(config::$debug) echo "Connected successfully";
		}
	}

	//Close the connection 
	private static function close_connection(){
		if(self::$connection) self::$connection->close(); 
	}

	/********************************************PRIVATE METHODS********************************************/


}

?>