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
		$stats = new test_stats(); 

		$session_id = $data->sessionID;
		$user_id = $data->userID;
		
		//0back
		$stats->avg_reaction_time_m_no_faces = $data->stats->mNoFacesAvgTime;
		$stats->accuracy_m_no_faces = $data->stats->mNoFacesAccuracy;
		$stats->avg_reaction_time_m_faces = $data->stats->mFacesAvgTime;
		$stats->accuracy_m_faces = $data->stats->mFacesAccuracy;

		//2back
		$stats->avg_reaction_time_aba_no_faces = $data->stats->ABANoFacesAvgTime;
		$stats->accuracy_aba_no_faces = $data->stats->ABANoFacesAccuracy;
		$stats->avg_reaction_time_aba_faces = $data->stats->ABAFacesAvgTime;
		$stats->accuracy_aba_faces = $data->stats->ABAFacesAccuracy;

		//Faces-NoFaces
		$stats->avg_reaction_time_faces = $data->stats->facesAvgTime;
		$stats->accuracy_faces = $data->stats->facesAccuracy;
		$stats->avg_reaction_time_no_faces = $data->stats->noFacesAvgTime;
		$stats->accuracy_no_faces = $data->stats->noFacesAccuracy;

		$model = new efnbackModel($session_id, $user_id, $stats); 


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