<?php 
/*		Class that updates the efnback database 
 *
 * 		Author: Josh Rogan
 *		Version: 1.0
 *		Date Last Modified: Jan 2015
 */
class efnbackModel{

	public $mysql; //One connection for all of the mySQL classes 
	public $session_id; 
	public $user_id; 
	public $test_id; //Id for this test 

	public $accuracy_m; // The overall accuracy from all the the individual tests 
	public $accuracy_aba; // The overall accuracy from all the the individual tests 
	public $avg_time_m; //The overal average response time in ms
	public $avg_time_aba; //The overal average response time in ms

	public function __construct($session_id, $user_id, $accuracy_m, $avg_reaction_time_m, $accuracy_aba, $avg_reaction_time_aba){
		$this->mysql = new mySQL(); //establish connection to mysql

		$this->session_id = $session_id;
		$this->user_id = $user_id;

		$this->accuracy_m = $accuracy_m;
		$this->accuracy_aba = $accuracy_aba;
		$this->avg_time_m = $avg_reaction_time_m;
		$this->avg_time_aba = $avg_reaction_time_aba;

		//Check if there is a valid session if not create one 
		if(!$this->checkSession()){
			$this->createSession();
			echo "SESSION JUST CREATED\n"; 
		}
		else {
			echo "SESSION ALREADY CREATED\n"; 
		}
	

		//Check if there is a valid user if not create one 
		if(!$this->checkUser()){
			$this->createUser(); 
			echo "USER JUST CREATED\n"; 
		}
		else{
			echo "USER ALREADY CREATED\n";
		}


		//Check to get the valid test id 
		$this->test_id = $this->get_test_id(); 
		$this->create_test(); //Create the new test into the databse 

	}

	/**
	 *	Check to see if the session id corresponds to a valid session
	 *		-Returns truw or false 
	 */
	public function checkSession(){
		return $this->mysql->exists($this->session_id, 'sessions'); 
	}

	//Create a new session
	public function createSession(){
		$query = "INSERT INTO sessions VALUES($this->session_id, NOW(), 0, NULL)";
		$this->mysql->query($query); 
	}

	/**
	 *	Check to see if the session id corresponds to a valid session
	 *		-Returns truw or false 
	 */
	public function checkUser(){
		$query = "SELECT * FROM users WHERE id = $this->user_id AND session_id = $this->session_id";
		return $this->mysql->has_results($query); 
	}

	//Create a new session
	public function createUser(){
		$query = "INSERT INTO users VALUES($this->user_id ,$this->session_id,NULL)";
		$this->mysql->query($query);
	}

	/**
	 *	Determine the test id. If the user has already created a test for this session the id 
	 *	should be the next number test. 
	 *		-Return the corret test id
	 */
	public function get_test_id(){
		$query ="SELECT MAX(id) from tests where user_id = $this->user_id";
		$results = $this->mysql->query($query);

		if($results->num_rows > 0){
			$row = $results->fetch_assoc();
			if ($row['MAX(id)'] != NULL) return $row['MAX(id)'] + 1; 
			else return 0; 
		}
		else{
			return 0; 
		}
	}		

	/**
	 *	Create a new test for this user 
	 *
	 */
	public function create_test(){
		if(!is_numeric($this->avg_time_aba)) $this->avg_time_aba = -1;
		if(!is_numeric($this->avg_time_m)) $this->avg_time_m = -1;


		$query = "INSERT INTO tests VALUES($this->test_id, $this->user_id, NOW(), $this->avg_time_m, $this->accuracy_m, $this->avg_time_aba, $this->accuracy_aba)";
		echo $query; 
		$results = $this->mysql->query($query);
	}

	/**
	 *	Insert the actual 
	 *
	 */
	public function add_task($block_type, $letter_type, $is_correct, $reaction_time = -1){
		$query = "INSERT INTO tasks VALUES(NULL, $this->user_id, $this->test_id, '$block_type', '$letter_type', $is_correct, $reaction_time)";
		echo $query; 
		
		$results = $this->mysql->query($query);

	}

	/********************************************PRIVATE METHODS********************************************/



	/********************************************PRIVATE METHODS********************************************/


}

?>