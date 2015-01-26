/**
 *	Overall program 
 *		-JSON file from PHP class 
 *	
 */
 function efnback(json){
 	efnback.numTests = 12; 
 	efnback.numBlocks = 8; 
 	efnback.numTargets = 5; 
 	efnback.Blocktypes = ["0back", "2back"];

 	this.json = json;
 	this.started = false; //Has the entire task been started 
 	this.finished = false; //Has the entire task been finished 

 	this.blocks = Array(); //An array of all of the block objects  
 	this.blockPointer = 0; //Index into the block array 
 	
 	//Build the blocks using the JSON data 
 	for (var i = 0; i < json.blocks.length; i++) {
 		this.blocks[i] = new block(i,json.blocks[i].type, json.blocks[i]);
 	}

 	/**
 	 *	Run all through all of the blocks 
 	 *
 	 */
 	var self = this; 
 	var myInterval = setInterval(function () {

 	 		if(!self.blocks[self.block_pointer].started){//If the current block isn't started start it 
 	 			self.blocks[self.block_pointer].start(); 
 	 		}
 	 		else if(self.blocks[self.block_pointer].finished && self.block_pointer < 7){//If the current block is finished move to the next and start it 
 	 			self.block_pointer++;
				self.blocks[self.block_pointer].start();
 	 		}
 	 		
 	 		//If the last block is finished 
 	 		if(self.block_pointer == 7 && self.blocks[self.block_pointer].finished){
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



 	//Pass the button press down to the correct block 
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
 	 	var percent = (correct / total) * 100; // Could just return this 
 	 	return percent + "% (" + correct + "/" + total +")";
 	}






 }

 /* 
 *	
 *	
 *
 */
 function block()