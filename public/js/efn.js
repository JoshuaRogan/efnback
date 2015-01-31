/**
 *	EFNBack Class that contains the overall application. It contains block classes that contain the individual
 *	tests that records the actual time. The program runs by polling the state of blocks and sending start commands when
 *	they are finished running. 
 *	
 *	The task is a series of 8 blocks where each block contains a 12 letter sequence. Each letter showing for 500ms and a fixation
 *	cross showing for 3500ms after. It records accuracy and timing on hitting the appropriate letter. 
 *	
 *	TODO: 
 *		-Remove hard coded DOM manipulations
 */
 
var DEBUG = false; //Debugging mode 

 function efnback(json){
 	var efnback = efnback || {};

 	efnback.numTests = 12; 
 	efnback.numBlocks = 8; 
 	efnback.numTargets = 5; 
 	efnback.Blocktypes = ["0back", "2back"]

 	this.json = json;

 	this.started = false; //Has the task been started 
 	this.finished = false; //Has the task been finished 

 	this.blocks = Array(); //An array of blocks which contain the tests 
 	this.block_pointer = 0; 

 	this.sessionID = -1;
 	this.userID = -1;

 	//Build all of the blocks here json.blocks.length
 	for (var i = 0; i < efnback.numBlocks; i++) {
 		this.blocks[i] = new block(i, json.blocks[i]);
 	}


 	/**
 	 *	Run until all of the blocks are finished.
 	 * 		-Uses SetInterval to avoid blocking
 	 *
 	 */
 	 this.start = function(sessionID, userID){
 	 	this.started = true; 
 	 	this.sessionID = sessionID;
 	 	this.userID = userID;

		var self = this; //Loses scope in setInterval
		var myInterval = setInterval(function () {
 	 		//If the current block isn't started start it 
 	 		if(!self.blocks[self.block_pointer].started){
 	 			self.blocks[self.block_pointer].start(); 
 	 		}
 	 		//If the current block is finished move to the next and start it
 	 		else if(self.blocks[self.block_pointer].finished && self.block_pointer < (efnback.numBlocks - 1)){ 
 	 			self.block_pointer++;
				self.blocks[self.block_pointer].start();
 	 		}
 	 			
 	 		//If the last block is finished 7
 	 		if(self.block_pointer == (efnback.numBlocks - 1) && self.blocks[self.block_pointer].finished){
 	 			clearInterval(myInterval);//Stop the interval from repeating
 	 			self.finished = true; //Mark this block as finished  

 	 			if(DEBUG) console.log(self);

 	 			//Compute Stats 
 	 			var accuracy = self.getPercentCorrect();
 	 			var avgerageTime = self.getAverageTime();
 	 			$("#efn_contaier").html("Accuracy = " + accuracy +" <br/> Average Time = " + avgerageTime + "<br/> User Id: " + self.userID);
 	 		}


 	 	}, 1);

 	 }

 	/**
 	 *	Passes down the action to the appropraite block 
 	 *
 	 */
 	this.buttonPressed = function(){
 		this.blocks[this.block_pointer].buttonPressed(); 
 	}
 	

 	
 	/**
 	 *	Computer the average response time on blocks that were pushed and are targets. 
 	 *
 	 */
 	this.getAverageTime = function(){
 	 	var totalTime = 0;
 	 	var times = 0;  

 	 	for(var i = 0; i < this.blocks.length; i++){
 	 		for(var j = 0; j < this.blocks[i].tests.length; j++){
 	 			if(this.blocks[i].tests[j].isTarget && this.blocks[i].tests[j].pushed){
 	 				times++;
 	 				totalTime += this.blocks[i].tests[j].timeMS; 
 	 			}

 	 		}
 	 	}

 	 	if(times == 0 ){
 	 		return  "No targets hit";
 	 	}
 	 	else{
			var avgTime = totalTime/times; 
 	 		if(DEBUG) console.log(totalTime, times);

 	 		return avgTime.toFixed(2) + "ms (" + totalTime + "ms/" + times +")";
 	 	}

 	 	

 	}

 	/**
 	 *	Return the number of tests correct / total number of tests 
 	 *
 	 */
 	 this.getPercentCorrect = function(){
 	 	var correct = 0;
 	 	var total = 0;  

 	 	for(var i = 0; i < this.blocks.length; i++){
 	 		for(var j = 0; j < this.blocks[i].tests.length; j++){
 	 			if(this.blocks[i].tests[j].isTarget && this.blocks[i].tests[j].pushed) correct++;
 	 			else if(!this.blocks[i].tests[j].isTarget && !this.blocks[i].tests[j].pushed) correct++; 

 	 			total++;
 	 		}
 	 	}

 	 	if(DEBUG) console.log(correct, total); 

 	 	var percent = (correct / total) * 100; // Could just return this 
 	 	return percent.toFixed(2) + "% (" + correct + "/" + total +")";
 	}

 	/**
 	 *	Send the reuslts to the server to update into the database upon completion.  
 	 *
 	 */
 	 this.sendResults = function(){

 	 }


 }


/*
 *	One block of tests that contains 12 tests objects with 5 targets 
 *		-Two Types 0back or 2back
 *	
 * 
 *
 */
function block(id, json_block){
	block.numTests = efnback.numTests;

	this.id = id; 
	this.type = json_block.type; 
	this.faceType = json_block.face;

	this.sequence = Array(); //REMOVE IF POSSIBLE 
	
	this.tests = Array(); 
	this.testPointer = 0; //Pointer to the correct test 

	//Status of this block 
	this.started = false; 
	this.finished = false; 


	//Using the JSON build all of the test objects
	for(var i = 0; i < json_block.tests.length; i++){
		this.tests[i] = new test(i, json_block.tests[i].letter, json_block.tests[i].is_target, this.type, this.faceType);
	}


 	/**
 	 *	Interval that constantly checks the tests to see if they are finished 
 	 *
 	 */
 	 this.start = function() {
 	 	
 	 	this.started = true; 
 	 	var self = this; 
 	 	var myInterval = setInterval(function () {

 	 		if(self.tests[self.testPointer].state == 0){//If it is zero start this test
 	 			self.tests[self.testPointer].start();
 	 		}
 	 		else if(self.tests[self.testPointer].state == 5){
 	 			self.testPointer++;
 	 		}

 	 		if(self.testPointer == 12){
 	 			clearInterval(myInterval);
 	 			self.finished = true; //Mark this block as finished 
 	 			if(DEBUG) console.log(self.tests); 
 	 		} 

 	 	}, 1);




 	 }

 	/**
 	 *	Button press cascasded down from efnback. Pass it to the correct test object. 
 	 *
 	 */
 	this.buttonPressed = function(){
 		if(this.testPointer < 12){
 			this.tests[this.testPointer].buttonPressed(); //Pass it on to the test
 		}
 	}


}

/*
 *	One individual test of one letter. 
 *	
 * 	
 *
 */
function test(id, letter, isTarget, blockType, faceType){ 
	var test = test || {};

	test.letterShowTimeMS = 50; //Time that each letter appears on the screen
	test.plusSignTimeMS = 35; //Time that the + sign appears on the screen
	test.directionsTimeMS = 35; //Show the directions for 3.5s

	this.id = id; //A unique identifier for this test (order of the items)

	this.letter = letter; //The letter for this test 
	this.isTarget = isTarget; //True or false value representing if this letter is the target 

	//Current state of this test  
	this.state = 0; //(not active = 0,  letter_showing = 1, plus_sign = 2, wrong = 3, correct = 4, completed = 5)

	this.correct = null; //If this test was correct 
	this.pushed = false; //If the user actually pressed the button in time

	this.timeMS = -1; //The total time to complete the test
	this.startTimeMS = -1; //Used to calculate the time to complete
	this.stopTimeMS = -1; //Used to calculate the time to complete 

	//Used to determine the instructions and generate the correct face image 
	this.blockType = blockType;
	this.faceType = faceType;


	/**
	 *	Control the timing of the states
	 *
	 */
	this.start = function (){
		var self = this; 

		
		if(this.id == 0){//First test show the instructions 
			//Show the directions based on the type 
			self.changeState(6); //Move to the show instructions state 

			$("#instructions").removeClass("hidden");
			$("#instructions").html(self.getInstructions()); //Update the DOM with the instructions  
			self.switchToNoFace(); 



			setTimeout(function(){	

				$("#instructions").addClass("hidden");
				//Show the letter for 500ms
				self.changeState(1); //Move to the show letter state
				setTimeout(function(){
					self.changeState(2); //Move to the showing plus sign state
					//Show the plus sign for 3500ms
					setTimeout(function(){
						//Time limit has expired 
						if(self.state == 2){ 
							if(self.isTarget){ //If this was the target it should have been pressed
								self.correct = false; 
								self.pushed = false; 
							}
							else{//This was a control so it is correct 
								self.correct = true; 
								self.pushed = false;
							}
						}
						self.stop(); //Finish the test
					},test.plusSignTimeMS);

				}, test.letterShowTimeMS);

			}, test.directionsTimeMS);

		}
		else{//All other tests 

			//Show the letter for 500ms
			self.changeState(1); //Move to the show letter state
			setTimeout(function(){
				self.changeState(2); //Move to the showing plus sign state
				//Show the plus sign for 3500ms
				setTimeout(function(){
					//The test is over 
					if(self.state == 2){ //If it isn't already wrong (No Press)
						if(self.isTarget){ //If this was the target is should have been pressed
							self.correct = false; 
							self.pushed = false; 
						}
						else{//This was a control so it is correct 
							self.correct = true; 
							self.pushed = false;
						}
					}

					self.stop(); //Finish the test
				},test.plusSignTimeMS);

			}, test.letterShowTimeMS);

		}

	}




	/**
 	 *	Button press cascasded down from block
 	 *
 	 */
 	this.buttonPressed = function(){
 		if(this.state == 0){ //Not active
 			if(DEBUG) console.log("State == 0 NOT ACTIVE");	//Don't do anything 
 		}
 		else if(this.state == 1){//Showing the letter
 			this.pushed = true;	//This was pushed 
 			if(this.isTarget){
 				if(DEBUG) console.log("State == 1 AT CHAR and isTarget");
 				this.stopTime(); //Stop the timer and set the results 
 				this.state = 4; //Correct 
 				this.correct = true; 
 			}
 			else{
 				if(DEBUG) console.log("State == 1 AT CHAR but is control");
 				this.state = 3; //Wrong
 				this.correct = false; 
 			}
 		}
 		else if(this.state == 2){//Showing the plus sign 
 			if(DEBUG) console.log("State == 2 AT PLUS SIGN");
 			//If this is a target 
 			if(this.isTarget){	//Target hit on the plus sign is valid but late
 				this.stopTime(); //Stop the timer 
 				this.correct = true;
 				this.state = 4; //Move to correct state 
 			}
 			else{
 				this.correct = false; 	
 				this.state = 3; //Move to incorrect state  
 			}


 			this.pushed = true; 
 			
 			

 			
 		}
 		else if(this.state == 3){//Wrong State
 			//Do nothing
 		} 		
 		else if(this.state == 4){//Already correctly pressed 
 			//Do nothing
 		}
 	}

 	/**
	 *	
	 *
	 */
	this.changeState = function(stateNumber){
		if(stateNumber == 0){	//Not active 
			this.state = 0; 
		}
		else if(stateNumber == 1){	//Letter Showing
			this.state = 1; 
			

			if(this.faceType == "none") {
				this.switchToNoFace();
			}
			else {
				this.switchToFace();
			}

			this.updateImage();

			$(".char").html(this.letter); //Update the DOM with the actual letter
			this.startTime(); //Start the timer (After it actually shows in the DOM)
		}
		else if(stateNumber == 2){	//Plus sign showing
			this.state = 2; 
			$(".char").html('+'); //Update the DOM with the actual letter
		}
		else if(stateNumber == 3){	//Incorrect 
			this.state = 3;
		}
		else if(stateNumber == 4){	//Correct 
			this.state = 4;
		}
		else if(stateNumber == 5){	//Finished 
			this.state = 5;
		}		
		else if(stateNumber == 6){	//Show instructions  
			this.state = 6;
		}
	}



	/**
	 *	Abnormal stop on user press 
	 *		
	 */
	this.stop = function(){
		this.changeState(5);
	}


	//Start the timer
	this.startTime = function(){
		this.startTimeMS = Date.now(); 
	}

	//Stop the timer and set the different to timeMS
	this.stopTime = function(){	
		//Only compute the time on the first press 
		if(this.timeMS == -1){
			this.stopTimeMS = Date.now(); 
			this.timeMS = this.stopTimeMS - this.startTimeMS; 
		}
		
	}

	//Return the proper instructions (this.blockType)
	this.getInstructions = function(){
		if(this.blockType == "0-back"){
			return "Touch or click anywhere on the screen when you see the letter 'M'"; 
		}
		else if(this.blockType == "2-back"){
			return "Touch or click anywhere on the screen when you see a 2-back letter series <br/> (That's the 'A - B - A' - type series)";
		}
		
	}

	//Based on the type update the images on the page with the new face 
	this.updateImage = function (){
		if(this.faceType == "none") return

		var imgsrc = "";
		var img_index = Math.floor(Math.random() * (16 - 1)) + 1; //Random index between 0-16
		if(this.faceType == "neutral") imgsrc = "img/faces/neautral/neautral-" + img_index + ".jpg";
		else if(this.faceType == "fear") imgsrc = "img/faces/fear/fear-" + img_index + ".jpg";
		else if(this.faceType == "happy") imgsrc ="img/faces/happy/happy-" + img_index + ".jpg";

		$(".face").attr("src", imgsrc);
	}

	//Switch to the no face hidden
	this.switchToNoFace = function(){
		$("#block-no-face").removeClass("hidden");
		$("#block-face").addClass("hidden");
	}

	this.switchToFace = function(){
		$("#block-no-face").addClass("hidden");
		$("#block-face").removeClass("hidden");
	}




}
