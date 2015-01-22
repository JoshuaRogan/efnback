/**
 *	Overall program 
 *	
 *	
 */
 function efnback(json){
 	efnback.numTests = 12; 
 	efnback.numBlocks = 8; 
 	efnback.numTargets = 5; 
 	efnback.Blocktypes = ["0back", "2back"]

 	this.json = json;
 	this.started = false; //Has the task been started 
 	this.finished = false; //Has the task been finished 

 	this.blocks = Array(); //An array of blocks which contain the tests 

 	this.block_pointer = 0; 

 	//Build all of the blocks here 
 	for (var i = 0; i < json.blocks.length; i++) {
 		this.blocks[i] = new block(i,json.blocks[i].type, json.blocks[i]);
 	}

 	/**
 	 *	Start the block
 	 *
 	 */
 	 this.start = function(){
 	 	console.log("Start EFN");


		var self = this; 
		var myInterval = setInterval(function () {



 	 		if(!self.blocks[self.block_pointer].started){//If the current block isn't started start it 
 	 			self.blocks[self.block_pointer].start(); 
 	 		}
 	 		else if(self.blocks[self.block_pointer].finished && self.block_pointer < 7){//If the current block is finished move to the next and start it 
 	 			self.block_pointer++;
				self.blocks[self.block_pointer].start();
 	 		}
 	 		
 	 		if(self.blocks[self.block_pointer].finished && self.block_pointer == 7){
 	 			clearInterval(myInterval);
 	 			self.finished = true; //Mark this block as finished  
 	 			console.log("Task Finished");
 	 			$(".char").html("Task Finished"); //Update the DOM with the actual letter
 	 			console.log(self); 

 	 			var accuracy = self.getPercentCorrect();
 	 			var avgerageTime = self.getAverageTime();
 	 			$("#efn_contaier").html("Accuracy = " + accuracy +" <br/> Average Time = " + avgerageTime);
 	 		}


 	 	}, 1);

 	 }

 	/**
 	 *	Determine what action to take when the button is pressed 
 	 *
 	 */
 	this.buttonPressed = function(){
 		this.blocks[this.block_pointer].buttonPressed(); 
 	}
 	

 	
 	/**
 	 *	Compute the average time if it needs to be recomputed 
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
 	 		console.log(totalTime, times) 

 	 		return avgTime + "ms (" + totalTime + "ms/" + times +")";
 	 	}

 	 	

 	}

 	/**
 	 *	Compute the number of tests that were correct 
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

 	 	console.log(correct, total); 
 	 	var percent = (correct / total) * 100
 	 	return percent + "% (" + correct + "/" + total +")";
 	}



 }


/*
 *	One block that contains 12 tests where the correct letter 
 *	
 *	
 * 
 *
 */
function block(id, type, json_block){
	block.numTests = efnback.numTests;
	this.id = id; 
	this.type = type; 
	this.sequence = Array(); 
	this.tests = Array(); 
	this.finished = false; 
	this.started = false; 

	this.testPointer = 0; //Pointer to the correct test 

	//Using the JSON Build the tests 
	for(var i = 0; i < json_block.tests.length; i++){
		this.tests[i] = new test(i, json_block.tests[i].letter, json_block.tests[i].is_target) 
	}


 	/**
 	 *	
 	 *
 	 */
 	 this.start = function() {
 	 	//Show the instructions 
		// setTimeout(function(){

		// 	self.finished = true; //Mark this block as finished 

		// },5000);



 	 	console.log("Block Starting"); 
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
 	 			console.log("Block Finished");
 	 			$(".char").html("Block " + (self.id + 1) + " finished"); //Update the DOM with the actual letter
 	 			
 	 			setTimeout(function(){

					self.finished = true; //Mark this block as finished 

				},5000);


 	 			console.log(self.tests); 
 	 		} 

 	 	}, 1);




 	 }

 	/**
 	 *	Button press cascasded down from efnback
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
function test(id, letter, isTarget){ 
	test.letterShowTimeMS = 500; //Time that each letter appears on the screen
	test.plusSignTimeMS = 3500; //Time that the + sign appears on the screen
	this.id = id; //A unique identifier for this test 
	this.letter = letter; //The letter for this test 
	this.isTarget = isTarget; //True or false value representing if this letter is the target 

	this.state = 0; //What is the current state of this task (not active = 0, plus_sign = 1, letter_showing = 2, wrong = 3, correct = 4, completed = 5)

	this.correct = null; //If this test was correct 
	this.pushed = false; //If the user actually pressed the button in time

	this.timeMS = 0; //The total time to complete the test
	this.startTimeMS = 0; //Used to calculate the time to complete
	this.stopTimeMS = 0; //Used to calculate the time to complete 

	/**
 	 *	Button press cascasded down from block
 	 *
 	 */
 	this.buttonPressed = function(){
 		if(this.state == 0){ //Not active
 			//Do nothing
 			console.log("State == 0 NOT ACTIVE");
 		}
 		else if(this.state == 1){//Showing plus sign
 			console.log("State == 1 AT PLUS SIGN");
 			this.pushed = true; 
 			this.state = 3; //Wrong state
 			this.correct = false; 
 		}
 		else if(this.state == 2){//The Letter is showing
 			this.pushed = true;
 			if(this.isTarget){
 				console.log("State == 2 AT CHAR and isTarget");
 				this.stopTime(); //Stop the timer 
 				this.state = 4; //Correct 
 				this.correct = true; 
 			}
 			else{
 				console.log("State == 2 AT CHAR but is control");
 				this.state = 3; //Wrong
 				this.correct = false; 
 			}		
 		}
 		else if(this.state == 3){//Wrong
 			//Do nothing
 		} 		
 		else if(this.state == 4){//Correctly pressed (Shouldn't ever get here)
 			//Do nothing
 		}
 	}

	/**
	 *	Start the actual test 
	 *
	 */
	this.start = function (){
		this.state = 1; //Show the plus sign
		$(".char").html("+"); //Update the DOM

		//Change it to the letter after 3500ms
		var self = this; 
		setTimeout(function(){
			$(".char").html(self.letter); //Update the DOM with the actual letter
			self.state = 2; //Letter is showing 
			self.startTime(); //start the timer

			//-------------Button presses should happen here-----------//


			//Show the letter for 500ms and then end the task
			setTimeout(function(){
				if(self.state == 2){ //If the user didn't press anything 
					if(self.isTarget){ //This test should have been pressed and at state 4
						self.correct = false; 
						self.pushed = false; 
					}
					else{//This was a control so it is correct 
						self.correct = true; 
						self.pushed = false;
					}
				}
				else{

				}
			
				self.stop();//Move to completed state
			},test.letterShowTimeMS);


		},test.plusSignTimeMS);
	}
	/**
	 *	Abnormal stop on user press 
	 *		
	 */
	this.stop = function(){
		this.state = 5; //Move to completed state 
	}

	/**
	 *	Based on the type look at the EFN array to 
	 *
	 */
	this.getImage = function (){
		return "/img/test.jpg"; 
	}

	//Start the timer
	this.startTime = function(){
		this.startTimeMS = Date.now(); 
	}

	//Stop the timer and set the different to timeMS
	this.stopTime = function(){	
		this.stopTimeMS = Date.now(); 
		this.timeMS = this.stopTimeMS - this.startTimeMS; 
	}

	/****PRIVATE METHODS****/

	//Wait for ms millseconds 
	test.prototype.wait = function(ms){
		setTimeout(function(){},ms);
	}

}
