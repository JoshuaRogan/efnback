<?php 
/**
 *	Represents one block of tests (total of 8)
 *	Each block contains 12 character tests 
 *
 */
class block {
	const NUMTESTS = 12; //Total number of characters per block 
	const NUMTARGETS = 5; //Total number of targets
	

	public $face; //Fear, Happy, Neutral, None
	public $type; //0-back or 2 back 
	public $block_num;	//Block number

	public $tests = array(); //Each block contains 12 tests 

	public $two_back_sequence_order = array(); //Determine the positions of the targets in the two back sequences 
	public $two_back_sequence_chars = array(); //The target characters

	//List all of the face images CHANGE THE NAMING SCHEME
	public static $fearFaceImages = array("07fe_o_bw"); //List of all of the fear names names 
	public static $happyFaceImages = array("07ha_o_bw"); //List of all of the happy images names 
	public static $neutralFaceImages = array("07ne_o_bw"); //List of all of the neutral names 

	public $num_responses; //Total number of responses generated for this block so far 



	function __construct($face, $type, $block_num){ 
		$this->face = $face; 
		$this->type = $type; 
		$this->block_num = $block_num; 
		$this->num_responses = 0; 

		if($this->type == efnback::$block_type[1]){
			$this->generate_2back_seq_order();
			$this->generate_2back_seq_chars();
		} 

		
		//Generate all of the tests for this block 
		for($i=0; $i < self::NUMTESTS; $i++){
			$this->tests[] = new test($this, $i); 
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
	 *	Determine 5, non repeated, random positions to hold the targets 
	 *	Must be in a position after 2 
	 */
	private function generate_2back_seq_order(){
		for($i=0; $i<block::NUMTARGETS; $i++){
			$rand = -1; 
			while ($rand == -1 || in_array($rand, $this->two_back_sequence_order)){
				$rand = rand(2,block::NUMTESTS-1);
			}
			$this->two_back_sequence_order[$i] = $rand;
		}
		//Order the sequence from least to greatest 
		sort($this->two_back_sequence_order);

		$this->two_back_sequence_order = array(3,4,7,8,11);

	}

	/**
	 *	Determine 5 valid characters to put in the array
	 *
	 */
	private function generate_2back_seq_chars(){
		for($i=0; $i<block::NUMTARGETS; $i++){
			$this->two_back_sequence_chars[$this->two_back_sequence_order[$i]] = $this->generate_valid_uppercase_letter($i);
		}
		$this->two_back_sequence_chars = array(3 => "A", 4 => "F", 7=>"B", 8=>"C", 11=>"D");
	}

	/**
	 *	Generate a valid uppercase letter for the target letters in this 2-back block
	 *
	 */
	private function generate_valid_uppercase_letter($index){
		$potential_char = chr(rand(65,90));
		if($index < 2){
			//Don't have to do anything special 
		} 
		else{
			//If there is a target two back the characters must be the same 
			if(in_array($this->two_back_sequence_order[$index-2], $this->two_back_sequence_order)){
				
			}
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
	

	public $IMAGEWIDTH = 300;
	public $test_num; 
	public $letter; //Determine what letter 
	public $block;	//Block object associated with the test 
	// public $num_responses; //Initially zero ends up at five when there are 5 valid targets 
	public $respond; //True if this is the letter you are supposed to respond to 
	public $sandwich; 

	
	

	function __construct($block, $test_num){ 
		$this->block = $block;
		$this->test_num = $test_num; 
		$this->letter = $this->generate_letter();
		
	}
	/**
	 *	This will determine what type of face to render 
	 *
	 */
	public function render_html(){

	}

	/**
	 *	Generate a letter with the two faces for 0 back blocks
	 *
	 */
	private function zero_back_face_html(){
		return <<<HTML

		<div class="row center-block test" id="test-{$this->test_num}"> 
			<div class="col-xs-4"><img src="/img/test.bmp" alt="test" class="img-responsive center-block efn-face" width="{$this->IMAGEWIDTH}"></div>
			<div class="col-xs-4 efn-character faces" id="efn-character-{$this->test_num}">{$this->letter}</div>
			<div class="col-xs-4"><img src="/img/test.bmp" alt="test" class="img-responsive center-block efn-face" width="{$this->IMAGEWIDTH}"></div>
		</div>
HTML;
	}

	/**
	 *	Generate a letter for 0 back blocks
	 *
	 */
	private function zero_back_letter_html(){
		return <<<HTML
		<div class="row center-block test" id="test-{$this->test_num}"> 
			<div class="col-xs-12 efn-character no-faces" id="efn-character-{$this->test_num}">{$this->letter}</div>
		</div>
HTML;
	}	

	/**
	 *	Generate the plus sign waiting block 
	 *
	 */
	private function zero_back_plus_html(){

	}

	/**
	 *	Generate a letter with the two faces for 2 back blocks 
	 *
	 */
	private function two_back_face_html(){
		return <<<HTML

		<div class="row center-block test" id="test-{$this->test_num}"> 
			<div class="col-xs-4"><img src="/img/test.bmp" alt="test" class="img-responsive center-block efn-face" width="{$this->IMAGEWIDTH}"></div>
			<div class="col-xs-4 efn-character faces" id="efn-character-{$this->test_num}">{$this->letter}</div>
			<div class="col-xs-4"><img src="/img/test.bmp" alt="test" class="img-responsive center-block efn-face" width="{$this->IMAGEWIDTH}"></div>
		</div>
HTML;
	}

	/**
	 *	Generate a letter for 2 back blocks 
	 *
	 */
	private function two_back_letter_html(){
		return <<<HTML
		<div class="row center-block test" id="test-{$this->test_num}"> 
			<div class="col-xs-12 efn-character no-faces" id="efn-character-{$this->test_num}">{$this->letter}</div>
		</div>
HTML;
	}

	/**
	 *	Generate the plus sign waiting block 
	 *
	 */
	private function two_back_plus_html(){

	}


	/**
	 *	Generate a letter based on the type of block
	 *
	 */
	private function generate_letter(){	
		$cur_block = $this->block;
		$targets_past = $cur_block->num_responses; 


		$ret = "";
		if($this->block->type == efnback::$block_type[0]){	//0-Back letter
			if ($this->test_num < block::NUMTARGETS) {
				$this->respond = true; 
				$this->block->num_responses++;
				$ret =  "M"; 
			}
			else return $this->generate_random_uppercase_letter();
		}
		else{//Use the sequence generated in the block object to determine the letter order 
			
			//If this test is the target use that character 
			// $GLOBALS["_DEBUG"]["Current Num Responses-$this->test_num"] = $targets_past;
			// $GLOBALS["_DEBUG"]["TWO BACK SEQ[NUM_RES]-$this->test_num"] = $this->block->two_back_sequence_order[$targets_past];

			//Target Letter 
			if($cur_block->two_back_sequence_order[$targets_past] == $this->test_num){
				$this->respond = true;

				$ret = $cur_block->two_back_sequence_chars[$this->test_num];

				$cur_block->num_responses++;
			}
			//Two before the target letter
			elseif(($cur_block->two_back_sequence_order[$targets_past] - 2) == $this->test_num ){
				// $ret = $cur_block->two_back_sequence_chars[$cur_block->two_back_sequence_order[$targets_past]];
				$ret = $cur_block->two_back_sequence_chars[$this->test_num + 2]; //Indexed by the location
				$this->sandwich = true; 
			}
			//Sandwich letter that is on the next target 
			elseif($targets_past+1 < block::NUMTARGETS &&  (($cur_block->two_back_sequence_order[$targets_past+1] - 2) == $this->test_num) ){
				// $ret = $cur_block->two_back_sequence_chars[$cur_block->two_back_sequence_order[$targets_past+1]];

				$ret = "*";
				$ret = $cur_block->two_back_sequence_chars[$this->test_num + 2]; //Indexed by the location of the target

				$this->sandwich = true; 
			}
			// else return 
			else {
				
				$ret = $this->generate_random_uppercase_letter();
			}
		}
		return $ret; 
		
	}

	/**
	 *	Generate a letter based on the type of block besides M
	 *
	 */
	public function generate_random_uppercase_letter(){
		$potential_char = chr(rand(65,90));
		if($this->block->type == efnback::$block_type[0]){//0-back
			//Don't generate a M
			while($potential_char == "M"){
				$potential_char = chr(rand(65,90));
			}
		}
		elseif($this->block->type == efnback::$block_type[1]){// 2-back
			//Don't generate a character that is a target in two blocks
			while(in_array($potential_char, $this->block->two_back_sequence_chars)){
				$potential_char = chr(rand(65,90));
			}
		} 
		return $potential_char;
	}





	public function toString(){ 
		$string = "\n------TEST------\n"; 
		$string .= "Test Number: $this->test_num\n"; 
		$string .= "Letter $this->letter\n"; 
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
	 *	to make it more unique. 
	 */
	private static function generate_subject_id(){
		return rand(0,99999);
	}


	/**
	 *	Generate a short id that will be combined with the date in the database
	 *	to make it more unique. 
	 */
	private static function generate_session_id(){
		return rand(0,99999);
	}
}	


?>