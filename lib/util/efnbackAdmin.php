<?php 
/*		Class that reads from the efnback database
 *
 * 		Author: Josh Rogan
 *		Version: 1.0
 *		Date Last Modified: Jan 2015
 */
class efnbackAdmin{
	public $mysql; //One connection for all of the mySQL queries  

	public function __construct(){
		$this->mysql = new mySQL(); //establish connection to mysq
	}

	/**
	 *	Get all of the tests from all of the sesions that are not deleted 
	 *		-Block, Letter Level, Reaction Time, Accuracy (True, False) (Per row)
	 *		-Accuracy (percent), Avg. Reaction Time 
	 */
	public function get_all_tests(){
		
		$query = "SELECT * 
			FROM tasks 
				JOIN users 
					ON users.id = tasks.user_id
				JOIN sessions 
					on users.session_id = sessions.id
				JOIN tests
					ON tasks.user_id = tests.user_id AND tasks.test_id = tests.id
			WHERE
				sessions.isDeleted != 1
			ORDER
				BY session_id, tasks.user_id, tasks.test_id, tasks.id"; 

		$return_val = array(); 

		$results = $this->mysql->query($query); 

		if($results->num_rows > 0){
			while($row = $results->fetch_assoc()){
				$return_val[] = $row; 
			}
		}
		else{
			return false; 
		}


		return $return_val; 
	}

	/**
	 *	Get all of the tests from one session and one user
	 *		-Block, Letter Level, Reaction Time, Accuracy (True, False)
	 */
	public function get_all_tests_user($session_id, $user_id){

	}

	//Get all of the sessions 
	public function get_all_session(){

	}


	//Return the data of one session using it's id 
	public function get_session($id){

	}





}
?>