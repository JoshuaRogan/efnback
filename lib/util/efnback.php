<?php 
/**
 *	Represents one block of tests (total of 8)
 *	Each block contains 12 character tests 
 *
 */
class block {
	const NUMTESTS = 12; //Total number of characters per block 
	const NUMTARGETS = 5; //Total number of targets
	
	//List all of the face images CHANGE THE NAMING SCHEME
	private static $fearFaceImages = array("07fe_o_bw"); //List of all of the fear names names 
	private static $happyFaceImages = array("07ha_o_bw"); //List of all of the happy images names 
	private static $neutralFaceImages = array("07ne_o_bw"); //List of all of the neutral names 

	public $face_type; //Fear, Happy, Neutral, None
	public $block_type; //0-back or 2 back 
	public $block_num;	//Block number

	public $tests = array(); //12 Test objects 

	//Hold the sequence of letters used for the test 
	private $targets = array(); //Associative array [target_index]=>Letter
	private $twoback_sandwiches = array(); //Associative array [sandwhich_index]=>letter

	private $num_responses; //Total number of responses generated for this block so far 


	function __construct($face, $type, $block_num){ 
		$this->face = $face; 
		$this->type = $type; 
		$this->block_num = $block_num; 
		$this->num_responses = 0; 

		if($this->type == efnback::$block_type[0]){
			$this->generate_zeroback_letter_sequence();
		}

		//Generate the 2back specfic targets 
		if($this->type == efnback::$block_type[1]){
			$this->generate_twoback_letter_sequence(); 
		} 

		

		//Shuffle the order of the tests for 0 back
		if($this->type == efnback::$block_type[0]) shuffle($this->tests);

		

	}

	/**
	 *	Return a proper face image for this block
	 *
	 */
	public function get_face_image(){
		return "07fe_o_bw";
	}

	/**
	 *	Generate the sequcne order and the targeres
	 *
	 */
	public function generate_twoback_letter_sequence(){
		//Draw five random numbers between 2-11 
		$rands = array(); 
		for($i=0; $i < block::NUMTARGETS; $i++){
			$rand = rand(2,11); 
			while(in_array($rand, $rands)){	//Consider adding another clause to make it numbers + 2 appear less often 
				$rand = rand(2,11); 
			}
 			$rands[$i] = $rand;
		}
		sort($rands); 

		$targets_added = 0;
		$sands_added = 0;
		// Generate the entire sequence 
		for($i=0; $i<block::NUMTESTS; $i++){
			//This is a target
			if(in_array($i, $rands)){
				@$this->targets[$i] = $this->twoback_sandwiches[$i-2]; //Set it to the sandwich letter 
				@$this->tests[$i] = new test($this->twoback_sandwiches[$i-2], true, false);
				$targets_added++;

				//Target and a sandwich 
				if(in_array($i+2, $rands)){
					@$this->twoback_sandwiches[$i] = @$this->targets[$i]; //Use the same letter 
					@$this->tests[$i]->is_sandwich = true; //Modify the test object 
					$sands_added++;
				}
			}
			//This is a sandwich 
			elseif(in_array($i+2, $rands)){
				@$this->twoback_sandwiches[$i] = $this->generate_valid_uppercase_letter(); 
				@$this->tests[$i] = new test($this->twoback_sandwiches[$i], false, true);
				$sands_added++;
			}
			else{
				$this->tests[$i] = new test($this->generate_valid_uppercase_letter(), false, false);
			}
		}
	}

	/**
	 *	Generate 5 targets that are M's and no M's in any other position 
	 *
	 */
	public function generate_zeroback_letter_sequence(){ 
		for($i=0; $i<block::NUMTARGETS; $i++){
			$this->targets[$i] = "M";
			$this->tests[$i] = new test("M", true, false); 
		}

		for($i=block::NUMTARGETS; $i<block::NUMTESTS; $i++){
			//Generate a capital random character that isn't M
			while(($potential_char = chr(rand(65,90))) == "M")
			$this->targets[$i] = "$potential_char";
			$this->tests[$i] = new test("$potential_char", false, false); 
		}
	}


	/**
	 *	Generate a valid uppercase letter for the target letters in this 2-back block
	 *
	 */
	private function generate_valid_uppercase_letter($avoid = "0"){
		$potential_char = chr(rand(65,90));

		while(in_array($potential_char, $this->twoback_sandwiches)){
			$potential_char = chr(rand(65,90));
		}

		return $potential_char;
	}

	public function toString(){ 
		$string = "Block Type: $this->type \n";
		$string .=  "Block Face: $this->face \n";
		$string .=  "Block Num: $this->block_num \n";

		foreach($this->tests as $test){ 
			$string .= $test->toString();
		}
		return $string; 
	}
}

/**
 *	The content for each task 
 *
 */
class test {
	const IMAGEWIDTH = 300;	//Size of the image 
	public $letter; //The letter of this test 

	public $is_target; 
	public $is_sandwich; 

	function __construct($letter, $is_target, $is_sandwich){  
		$this->letter = $letter;
		$this->is_target = $is_target;	
		$this->is_sandwich = $is_sandwich;	//Can be become a sandwich after initialization too  
	}

	public function toString(){ 
		$string = "\n------TEST------\n"; 
		$string .= "Letter $this->letter\n"; 
		$string .= "Target $this->is_target\n"; 
		$string .= "Sandwich $this->is_target\n"; 
		$string .= "------TEST------\n"; 

		return $string;
	}

}


/*
 
Here are a few more clarifications in case you need them:
We should have 8 total blocks with 12 letters  appearing in each block (the letters are separated by a +). The blocks are separated by instructions to identify which block is coming up(“ Press the button to the letter “M”” and  “Press the button to the A-B-A pattern”)
 
Each letter shows for 500 milliseconds and the + shows for 3500 milliseconds.  The whole task will end up being 6.4 minutes.
 
For the 0 back blocks: respond to M; there are 5 Ms and the rest of the letters are random
0 back no face block
0 back fear face block
0 back happy face block
0 back neutral face block
For the 2 back blocks: respond to the A – B –A pattern (but using different letters); there are 5 letter sandwiches and the rest of the letters are random. (this is an example of the letters with the correct targets in bold and italics: Q + C + N + N + O + A + O + A + O + R + O + R)
2 back no face block
2 back fear face block
2 back happy face block
2 back neutral face block
 
The task should always start with 0 back no face block but the rest of them should appear randomly.
 
Please do let me know if you have any questions or need any more information.

Naming Scheme of images ##[fe,ne,ha]_o_bw
*/
class efnback{
	public $subject_id = -1; //Id for this subject 
	public $session_id = -1; //Given out id for this session 

	//All of the potential faces and block types to create all 8 blocks 
	public static $faces = array("none", "happy", "neutral", "fear"); 
	public static $block_type = array("0-back", "2-back"); 
	// public static $block_type = array("0-back"); 
	public $blocks = array(); //Array of block objects representing each block  
	
	function __construct(){ 
		$this->subject_id = self::generate_subject_id(); 
		$this->session_id = self::generate_session_id(); 

		//Create all of the blocks
		$block_counter = 0;
		for($i=0; $i < count(self::$block_type); $i++){
			for($j=0; $j < count(self::$faces); $j++){
				$this->blocks[] = new block(self::$faces[$j], self::$block_type[$i],$block_counter);
				$block_counter++;
			}
		}
		
	}

	/**
	 *	Generate the sequence of blocks that will be used for this run
	 *
	 */
	public function generateSequence(){
		$sequence = array(); 
		$sequence[] = $this->blocks[0]; //Always start the tests with
		unset($this->blocks[0]);
		shuffle($this->blocks); //Shuffle the order of the blocks 
		$this->blocks = array_merge($sequence,$this->blocks); 

	}



	public function toString(){ 
		$string = "\n\n\n"; 
		foreach($this->blocks as $block){ 
			$string .= "-------BLOCK-------\n";
			$string .= $block->toString();
			$string .= "-------BLOCK-------\n\n";
		}



		return $string; 

	}


	/**********************************STATIC METHODS**********************************/

	/**
	 *	Generate a short id that will be combined with the date in the database
	 *	to make it more unique. Probably will interact with a database 
	 */
	private static function generate_subject_id(){
		return rand(0,99999);
	}


	/**
	 *	Generate a short id that will be combined with the date in the database
	 *	to make it more unique. Probably will interact with a database
	 */
	private static function generate_session_id(){
		return rand(0,99999);
	}
}	


?>