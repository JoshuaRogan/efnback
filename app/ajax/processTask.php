<?php 
	/**
	 *	Processes the finished EFNBack task. 
	 *		-Validates that it was actually taken
	 *		-Adds it to the database 
	 */

	//Parse the JSON 
	
	if(isset($_POST['data'])){
		$json_string = $_POST['data'];

		$data = json_decode($json_string);

		//Entire test data 
		$avg_time_m = $data->avgTimeM;
		$avg_time_aba = $data->avgTimeABA;
		$accuracy_m = $data->accuracyM;
		$accuracy_aba = $data->accuracyABA;
		$session_id = $data->sessionID;
		$user_id = $data->userID;

		$model = new efnbackModel($session_id, $user_id, $accuracy_m, $avg_time_m,$accuracy_aba, $avg_time_aba); 


		foreach($data->blocks as $block){
			$block_type = $block->type; 
			$face_type = $block->faceType; 
			$type = $block_type . "-" . $face_type;

			foreach($block->tests as $test){
				if($test->isTarget) $target = "target";
				else $target = "control"; 
				$time = $test->timeMS;
				if($test->correct) $is_correct = 1;
				else $is_correct = 0;

				$model->add_task($type, $target, $is_correct, $time);
			}
		}
		echo "Completed"; 
	}
	else{
		echo "Failed";
	}




?>