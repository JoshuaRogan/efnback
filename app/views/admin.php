<?php

/**
 *	Contains the aggregate stats from the first table 
 *		-Aggregate stats (AS) from exactly one test, one user, and one session 
 *		-One row table 
 */
class ASTable{
	public $session_id, $user_id;
	public $rows = array(); //Stores all of the rows 

	public static $tables = array(); //Holds all of the AS tables 
	public static $num_tables = 0; 

	public function __construct($session_id, $user_id){
		$this->session_id = $session_id;
		$this->user_id = $user_id;
		self::$num_tables++;
		self::$tables[] = $this; 
	}


	public function add_row($avg_reaction_time_m, $accuracy_m, $avg_reaction_time_aba, $accuracy_aba){
		$this->rows[] = array(
			"avg_reaction_time_m" => $avg_reaction_time_m,
			"accuracy_m" =>  $accuracy_m,
			"avg_reaction_time_aba" =>  $avg_reaction_time_aba,
			"accuracy_aba" =>  $accuracy_aba,
		);
	}

	public function gen_table(){

		//Being the table 
		echo "
		<h3> Aggregate Stats</h3>
			<table class='as-table table table-hover table-striped table-responsive'>
				<thead>
					<tr>
						<th>Average Reaction Time M</th>
						<th>Accuracy M </th>
						<th>Average Reaction Time ABA</th>
						<th>Accuracy ABA </th>
					</tr>
				</thead>

			<tbody>";

		//Print all of the rows
		foreach($this->rows as $row){
			echo "<tr>
				<td> {$row['avg_reaction_time_m']}ms</td>
				<td> {$row['accuracy_m']}%</td>
				<td> {$row['avg_reaction_time_aba']}%</td>
				<td> {$row['accuracy_aba']}ms</td>
			</tr>
		";
		}

		//Close the table 
		echo "</tbody></table>";

		
	}

	public static function gen_all_tables(){

	}
}

//Class to build the SUTA(Session User Test all) data table 
class sutaTable{
	
	public $session_id, $user_id, $test_id, $session_desc; 
	public $ASTable; 
	public $rows = array(); //Stores all of the rows 

	public static $tables = array(); //Holds all of the suta tables 
	public static $num_tables = 0; 

	public function __construct($session_id, $user_id, $test_id){
		$this->session_id = $session_id;
		$this->user_id = $user_id;
		$this->test_id = $test_id;
		$this->ASTable = new ASTable($session_id, $user_id);
		self::$num_tables++;
		self::$tables[] = $this; 
	}

	//Adds a row to this table 
	public function add_row($block_type, $letter_type, $reaction_time, $is_correct){
		$this->rows[] = array(
			"block_type" => $block_type,
			"letter_type" =>$letter_type,
			"reaction_time" => $reaction_time,
			"is_correct" =>  $is_correct);
	}

	//Generate this table 
	public function gen_table(){
		
		//Being the table 
		echo "
		<h3> <i class='fa fa-table'></i> Session ID = $this->session_id User ID = $this->user_id - Test ID = $this->test_id</h3>
		<div class='session-user'>
			<table class='suta-table table table-hover table-striped table-responsive'>
				<thead>
					<tr>
						<th>Block</th>
						<th>Letter Level</th>
						<th>Reaction Time</th>
						<th>Accuracy</th>
					</tr>
				</thead>

			<tbody>";

		//Print all of the rows
		foreach($this->rows as $row){
			echo "<tr>
				<td> {$row['block_type']}</td>
				<td> {$row['letter_type']}</td>
				<td> {$row['reaction_time']}</td>
				<td> {$row['is_correct']}</td>
			</tr>
		";
		}

		//Close the table 
		echo "</tbody></table>";

		$this->ASTable->gen_table();

		echo "</div>";
	}

	//Generate all of the tables 
	public static function gen_all_tables(){
		echo "<div id='sessions'>";
		$prev_session = -1; 
		$first = true; 
		foreach(self::$tables as $table){
			//If this is new session table start and end the previous div 
			if($table->session_id != $prev_session){
				if($first){//Don't need to close the previous div
					// echo "<div class='session_container'>";
					// echo "<h3> Session ID = $table->session_id";
					// $first = false; 
				}
				else{//Close and open a new div 
					// echo "</div>";
					// echo "<div class='session_container'>";
					// echo "<h3> Session ID = $table->session_id";
				}

			}
			
				$table->gen_table();
				//If there isn't a next item close the div 
				if(!next(self::$tables) === false ) {
					// echo "</div>"; 
				}
				prev(self::$tables); //reset the pointer 

			$prev_session = $table->session_id;
		}
		echo "</div>"; //End id=sessions 
	}
}



$admin = new efnbackAdmin(); 
$allTests = $admin->get_all_tests();


$allTests = $admin->get_all_tests();


if($allTests){
	$prev_session = false; //Session of the last row
	$prev_user = false;	//User of the last row
	$prev_test_id = false; //Id of the last test 

	$sutaTable = false; //Suta table
	$ASTable = false; //As table 
	foreach($allTests as $row){
		//Initialize prev session tracker and start a new table 
		if($prev_session == false){ // First time 
			$prev_session = $row['session_id']; 
			$prev_user = $row['user_id']; 
			$prev_test_id = $row['test_id']; 


			$sutaTable = new sutaTable($row['session_id'],$row['user_id'], $row['test_id']);
			$sutaTable->add_row($row['block_type'],$row['letter_type'],$row['reaction_time'],$row['is_correct']);
			$sutaTable->ASTable->add_row($row['avg_reaction_time_m'],$row['accuracy_m'], $row['avg_reaction_time_aba'], $row['accuracy_aba']);
		}
		else{
			
			//New Session 
			if($prev_session != $row['session_id']){
				$sutaTable = new sutaTable($row['session_id'],$row['user_id'], $row['test_id']);
				$sutaTable->ASTable->add_row($row['avg_reaction_time_m'],$row['accuracy_m'], $row['avg_reaction_time_aba'], $row['accuracy_aba']);
			}
			//New Test 
			else if($prev_test_id != $row['test_id']){
				$sutaTable = new sutaTable($row['session_id'],$row['user_id'], $row['test_id']);
				$sutaTable->ASTable->add_row($row['avg_reaction_time_m'],$row['accuracy_m'], $row['avg_reaction_time_aba'], $row['accuracy_aba']);
			}
			//New User
			else if($prev_user != $row['user_id']){
				$sutaTable = new sutaTable($row['session_id'],$row['user_id'], $row['test_id']);
				$sutaTable->ASTable->add_row($row['avg_reaction_time_m'],$row['accuracy_m'], $row['avg_reaction_time_aba'], $row['accuracy_aba']);
			}

			$sutaTable->add_row($row['block_type'],$row['letter_type'],$row['reaction_time'],$row['is_correct']);
		}


		//Move the previous trackers to the next one 
		$prev_session = $row['session_id']; 
		$prev_user = $row['user_id']; 
		$prev_test_id = $row['test_id'];
		

	}	
	
}

// echo "<div id='sessions'>"; 

// echo "</div>";
?>

<div class="container">
	<h1> Admin Page </h1> 
	<?php sutaTable::gen_all_tables(); ?>
</div>