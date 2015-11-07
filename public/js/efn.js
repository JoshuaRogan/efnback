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

//Timer Quick Override 
var letterShowTimeMS = 500; //Time that each letter appears on the screen
var plusSignTimeMS = 3500; //Time that the + sign appears on the screen
var directionsTimeMS = 3500; //Show the directions for 3.5s


 function efnback(json){
 	var efnback = efnback || {};

 	efnback.numTests = 12; 
 	efnback.numBlocks = 8; 
 	efnback.numTargets = 5; 
 	efnback.blocktypes = ["0back", "2back"];
 	efnback.processTaskURL = "/processTask";	//URL that handles the submission of the result and puts it into the database 

 	//Aggregate statistics on this run 
 	this.stats = {
 		mFacesAvgTime: -1.0, 
 		mFacesAccuracy: -1.0, 

 		mNoFacesAvgTime: -1.0, 
 		mNoFacesAccuracy: -1.0,

 		ABAFacesAvgTime: -1.0, 
 		ABAFacesAccuracy: -1.0, 

 		ABANoFacesAvgTime: -1.0, 
 		ABANoFacesAccuracy: -1.0,

 		facesAccuracy: -1.0, 
 		facesAvgTime: -1.0,

 		noFacesAccuracy: -1.0,
 		noFacesAvgTime: -1.0
 	}

 	this.avgTime = -1; 
 	this.accuracy = -1; 

 	//Final Stats for the 0 back sequences
 	this.avgTimeM = -1;
 	this.accuracyM = -1;

 	//Final Stats for the 2 back sequences 
 	this.avgTimeABA = -1;
 	this.accuracyABA = -1;

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
 	 this.start = function(sessionID, userID, quickTest){
 	 	this.started = true; 
 	 	this.sessionID = sessionID;
 	 	this.userID = userID;

 	 	if(quickTest){
 	 		letterShowTimeMS = 5; //Time that each letter appears on the screen
			plusSignTimeMS = 35; //Time that the + sign appears on the screen
			directionsTimeMS = 35;
 	 	}

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
 	 			$('html').unbind(); //Unbind the events here 
 	 			clearInterval(myInterval);//Stop the interval from repeating
 	 			self.finished = true; //Mark this block as finished  

 	 			if(DEBUG) console.log(self);

 	 			
 	 			//Set all of the stats values 
 	 			self.computeStats(); 
 	 			
 	 			$("#efn_contaier").html(self.printFinalStats());
 	 			$("#efn_contaier").append("<h3 class='text-center'> Session ID = " + self.sessionID + "</h3>");
 	 			$("#efn_contaier").append("<h3 class='text-center'> User ID = " + self.userID + "</h3>");
 	 			$("#efn_contaier").append("<br/> <a class='btn btn-default' href='javascript:void(0)' onclick='window.location.reload()' role='button'>Take Test Again</a>");
 	 			self.sendResults();
 	 		}


 	 	}, 1);

 	 }

 	/**
 	 *	Passes down the action to the appropriate block 
 	 *
 	 */
 	this.buttonPressed = function(){
 		this.blocks[this.block_pointer].buttonPressed(); 
 	}
 	
 	/**
 	 *	Print all of the final statistics 
 	 *
 	 */
 	 this.printFinalStats = function(){
 	 	//Build the HTML table (Messy looking)


 	 	//Way to messy probably a better way to build the table 
 	 	var htmlTable = "<table class='table table-condensed'><thead><tr><th>Type</th><th>Accuracy</th><th>Reaction Time (ms)</th></tr></thead><tbody><tr><td>M Faces</td><td>" +
 	 		this.stats.mFacesAccuracy.toFixed(2) + "%</td> <td> " + isFixedIfNumeric(this.stats.mFacesAvgTime, 2)  + "</td> </tr> <tr> <td>M No Faces</td> <td> " + this.stats.mNoFacesAccuracy.toFixed(2) + "%</td> <td> " +
 	 		isFixedIfNumeric(this.stats.mNoFacesAvgTime,2) + "</td></tr><tr> <td>ABA Faces</td> <td> " + this.stats.ABAFacesAccuracy.toFixed(2) + "%</td><td>" + isFixedIfNumeric(this.stats.ABAFacesAvgTime,2) + "</td></tr><tr> <td>ABA No Faces</td> <td> " +
 	 		this.stats.ABANoFacesAccuracy.toFixed(2) + "%</td><td>" + isFixedIfNumeric(this.stats.ABANoFacesAvgTime,2) + "</td></tr> <tr> <td>Faces</td> <td> " + this.stats.facesAccuracy.toFixed(2) + "%</td><td>" + isFixedIfNumeric(this.stats.facesAvgTime,2) + 
 	 		"</td></tr><tr> <td>No Faces</td> <td> " + this.stats.noFacesAccuracy.toFixed(2) + "%</td><td>" + isFixedIfNumeric(this.stats.noFacesAvgTime,2) + "</td></tr></tbody></table>";

 		return htmlTable; 
 	 }

 	/**
 	 *	Compute all of the statistics for this trial 
 	 *
 	 *
 	 */
 	 this.computeStats = function (){
		this.getAverages(); //Get all of the average stats
		this.getAccuracy(); //Compute all of the accuracy
 	 }



 	
 	/**
 	 *	Compute the average reaction time for: 
 	 *		-M No Faces
 	 *		-M Faces
 	 *		-ABA No Faces
 	 *		-ABA Faces
 	 */
 	this.getAverages = function(){  
		//0back
		var numMFaces 		= 0;
		var timeMFaces		= 0; 
		var numMNoFaces 	= 0; 
		var timeMNoFaces 	= 0; 
		//2Back
		var numABAFaces 	= 0; 
		var timeABAFaces 	= 0; 
		var numABANoFaces	= 0; 
		var timeABANoFaces 	= 0; 

		//totals
		var numFaces 		= 0; 
		var timeFaces 		= 0; 
		var numNoFaces 		= 0; 
		var timeNoFaces 	= 0; 


 	 	for(var i = 0; i < this.blocks.length; i++){
 	 		for(var j = 0; j < this.blocks[i].tests.length; j++){
 	 			if(this.blocks[i].tests[j].isTarget && this.blocks[i].tests[j].pushed){
 	 				
 	 				//Compute the 0back times this.blocks[i].faceType == "none"
 	 				if(this.blocks[i].type == "0-back"){
 	 					
 	 					//Check to see if there was a face
 	 					if(this.blocks[i].faceType == "none"){
 	 						numMNoFaces++;
 	 						timeMNoFaces += this.blocks[i].tests[j].timeMS;

 	 						numNoFaces++;
 	 						timeNoFaces += this.blocks[i].tests[j].timeMS;
 	 					}
 	 					else{
 	 						numMFaces++;
 	 						timeMFaces += this.blocks[i].tests[j].timeMS;

 	 						numFaces++;
 	 						timeFaces += this.blocks[i].tests[j].timeMS;
 	 					}

 	 				}
 	 				else{ //2 bback times
 	 					//Check to see if there was a face
 	 					if(this.blocks[i].faceType == "none"){
 	 						numABANoFaces++;
 	 						timeABANoFaces += this.blocks[i].tests[j].timeMS;

 	 						numNoFaces++;
 	 						timeNoFaces += this.blocks[i].tests[j].timeMS;
 	 					}
 	 					else{
 	 						numABAFaces++;
 	 						timeABAFaces += this.blocks[i].tests[j].timeMS;

 	 						numFaces++;
 	 						timeFaces += this.blocks[i].tests[j].timeMS;
 	 					}
 	 				}
 	 			}
 	 		}
 	 	}

 	 	console.log(numMNoFaces, timeMNoFaces);

 		//Set all of the stats 
 	 	if(numMNoFaces == 0) this.stats.mNoFacesAvgTime = "No Hits"; 
 	 	else this.stats.mNoFacesAvgTime = timeMNoFaces / numMNoFaces;

 	 	if(numMFaces == 0) this.stats.mFacesAvgTime = "No Hits"; 
 	 	else this.stats.mFacesAvgTime = timeMFaces / numMFaces;

 	 	if(numABANoFaces == 0) this.stats.ABANoFacesAvgTime = "No Hits"; 
 	 	else this.stats.ABANoFacesAvgTime = timeABANoFaces / numABANoFaces;

 	 	if(numABAFaces == 0) this.stats.ABAFacesAvgTime = "No Hits"; 
 	 	else this.stats.ABAFacesAvgTime = timeABAFaces / numABAFaces;

 	 	if(numFaces == 0) this.stats.facesAvgTime = "No Hits";
 	 	else this.stats.facesAvgTime = timeFaces / numFaces;

 	 	if(numNoFaces == 0) this.stats.noFacesAvgTime = "No Hits"; 
 	 	else this.stats.noFacesAvgTime = timeNoFaces / numNoFaces;


 	}

 	/**
 	 *	Compute the average reaction time for: 
 	 *		-M No Faces
 	 *		-M Faces
 	 *		-ABA No Faces
 	 *		-ABA Faces
 	 */
 	 this.getAccuracy = function(){
 	 	//0back
 	 	var totalMFaces 		= 0;
 	 	var accurateMFaces 		= 0;
 	 	var totalMNoFaces 		= 0; 
 	 	var accurateMNoFaces 	= 0;

 	 	//2back
 	 	var totalABAFaces	 	= 0;
 	 	var accurateABAFaces	= 0;
 	 	var totalABANoFaces		= 0; 
 	 	var accurateABANoFaces 	= 0; 

		//totals
		var numFaces 			= 0; 
		var accurateFaces 		= 0; 
		var numNoFaces 			= 0; 
		var accurateNoFaces 	= 0; 



 	 	for(var i = 0; i < this.blocks.length; i++){
 	 		for(var j = 0; j < this.blocks[i].tests.length; j++){
 	 			//Pushed target = Correct || Not Pushed Target = Correct 
 	 			if((this.blocks[i].tests[j].isTarget && this.blocks[i].tests[j].pushed) || (!this.blocks[i].tests[j].isTarget && !this.blocks[i].tests[j].pushed)){
 	 				 
 	 				 //0Back (M)
 	 				 if(this.blocks[i].type == "0-back"){
 	 				 	//Check to see if there was a face
 	 					if(this.blocks[i].faceType == "none"){
 	 						 accurateMNoFaces++;
 	 						 totalMNoFaces++;

 	 						 numNoFaces++;
 	 						 accurateNoFaces++;
 	 					}
 	 					else{
 	 						accurateMFaces++;
 	 						totalMFaces++;

 	 						numFaces++;
 	 						accurateFaces++;
 	 					}
 	 				 }
 	 				 else{ //2back (ABA)
 	 				 	//Check to see if there was a face
 	 					if(this.blocks[i].faceType == "none"){
 	 						accurateABANoFaces++;
 	 						totalABANoFaces++;

 	 						numNoFaces++;
 	 						accurateNoFaces++;
 	 					}
 	 					else{
 	 						accurateABAFaces++;
 	 						totalABAFaces++;

 	 						numFaces++;
 	 						accurateFaces++;
 	 					}
 	 				 }
 	 			}
 	 			else{ //Incorrect just add to the totals 
 	 					//0Back (M)
 	 				if(this.blocks[i].type == "0-back"){
 	 				 	//Check to see if there was a face
 	 					if(this.blocks[i].faceType == "none"){
 	 						 totalMNoFaces++;
 	 						 numNoFaces++;

 	 					}
 	 					else{
 	 						totalMFaces++;
 	 						numFaces++;
 	 					}
 	 				}
 	 				else{ //2back (ABA)
 	 				 	//Check to see if there was a face
 	 					if(this.blocks[i].faceType == "none"){
 	 						totalABANoFaces++;
 	 						numNoFaces++;

 	 					}
 	 					else{
 	 						totalABAFaces++;
 	 						numFaces++;
 	 					}
 	 				}
 	 			}	
 	 		}
 	 	}

 	 	//Set the values for the stats object 
 	 	this.stats.mFacesAccuracy = (accurateMFaces / totalMFaces) * 100;
 	 	this.stats.mNoFacesAccuracy = (accurateMNoFaces / totalMNoFaces) * 100;
 	 	this.stats.ABAFacesAccuracy = (accurateABAFaces / totalABAFaces) * 100;
 	 	this.stats.ABANoFacesAccuracy = (accurateABANoFaces / totalABANoFaces) * 100;
 	 	this.stats.facesAccuracy = (accurateFaces / numFaces) * 100;
 	 	this.stats.noFacesAccuracy = (accurateNoFaces / numNoFaces) * 100;


 	}

 	/**
 	 *	Send the results to the server to update into the database upon completion.  
 	 *
 	 */
 	 this.sendResults = function(){
 		this.json = null; //Don't need to send this 

 	 	$.ajax({
			url: "/processTask",
			type: "POST", 
			data: {data: JSON.stringify(this)}, //Send this entire object as JSON
			// contentType: "application/json", 
			complete: function(html){
				console.log(html.responseText);
			}
		});

		
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
 	 *	Button press cascaded down from efnback. Pass it to the correct test object. 
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
					},plusSignTimeMS);

				}, letterShowTimeMS);

			}, directionsTimeMS);

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
				},plusSignTimeMS);

			}, letterShowTimeMS);

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


function isFixedIfNumeric(value, fixedTo){
	if($.isNumeric(value)) return value.toFixed(fixedTo) + "ms"; 
	else return value; 
}
