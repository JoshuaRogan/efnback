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
	public $test_id; //Id for this test ()


	public function __construct($session_id, $user_id){
		$this->mysql = new mySQL(); //establish connection to mysql

		$this->session_id = $session_id;
		$this->user_id = $session_id;

		//Check if there is a valid session if not create one 
		if(!$this->checkSession()){
			$this->createSession();
		}
		


		//Check if there is a valid user if not create one 
		if(!$this->checkUser()){
			$this->createUser(); 
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
		//Perform query using $this->session_id
		return $this->mysql->exists(5, 'sessions'); 
	}

	//Create a new session
	public function createSession(){
		
	}

	/**
	 *	Check to see if the session id corresponds to a valid session
	 *		-Returns truw or false 
	 */
	public function checkUser(){
		//Perform query using $this->session_id
	}

	//Create a new session
	public function createUser(){

	}

	/**
	 *	Determine the test id. If the user has already created a test for this session the id 
	 *	should be the next number test. 
	 *		-Return the corret test id
	 */
	public function get_test_id(){

	}

	public function create_test(){

	}

	/**
	 *	Insert the actual 
	 *
	 */
	public function add_task($block_type, $letter_type, $is_correct, $reaction_time = NULL){

	}


	/********************************************PRIVATE METHODS********************************************/



	/********************************************PRIVATE METHODS********************************************/


}

?>