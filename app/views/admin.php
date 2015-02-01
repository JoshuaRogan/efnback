<?php
/**
 *	Handles all of the database pages and creation of sessions etc. 
 *
 */

$admin = new efnbackAdmin(); 
$allTests = $admin->get_all_tests();


// if($allTests){
// 	foreach($allTests as $row){
// 		echo "<pre class='pre-scrollable col-md-12'>";
// 		var_dump($row);
// 		echo "</pre>";
// 	}
// }
// else{
// 	echo "returned false"; 
// }


$allTests = $admin->get_all_tests();
if($allTests){
	$prev_session = false; 
	$table = 0; //Manage the amount of tables already created 

	foreach($allTests as $row){
		if($prev_session == false) $prev_session = $row['session_id']; //Initialize prev session tracker 

		//Generate a table for each session id
		if($prev_session != $row['session_id'] || $table == 0){
			if($table != 0){//End the previous table if isn't the first table 
				echo "</tbody></table>";
			}


			echo "
			<h3> Session ID : {$row['session_id']} </h3>
			<table class='table table-responsive'>
				<thead>
					<tr>
						<th>User</th>
						<th>Block</th>
						<th>Letter Level</th>
						<th>Reaction Time</th>
						<th>Accuracy</th>
					</tr>
				</thead>

				<tbody>";

			$table++;
		}


		echo "<tr>
				<td> {$row['user_id']} </td>
				<td> {$row['block_type']} </td>
				<td> {$row['letter_type']} </td>
				<td> {$row['reaction_time']} </td>
				<td> {$row['is_correct']} </td>
			</tr>
		";

		$prev_session = $row['session_id'];
	}	
}



?>


