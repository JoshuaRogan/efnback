/**
 *	Overall program 
 *
 *
 */
 function efnback(numTests, JSON){
 	this.numTests = numTests; 
 	this.tests = Array(); //Contains all of the tests 
 	this.accuracy = 0; //Percent accuracty of the total completed so far
 	this.totalTime = 0; //Total time for all answeres

 	//Build all of the tests using the JSON data
 	for (var i = 0; i <numTests; i++) {
 		
 	}








 	/****************PUBLIC FUNCTIONS****************/

 	avgTime = -1; //Private variable
 	/**
 	 *	Compute the average time if it needs to be recomputed 
 	 *
 	 */
 	this.getAverageTime = function(){
 		if(avgTime == -1 || tests.length == this.numTests){
 			//Compute average time 
 		}
 		else{
 			return avgTime; 
 		}
 	}
 }

/*
 *	One individual test.
 *	Letter is the correct letter 
 * 	Type is the type of test (0back or 2back)
 *
 */
function test(id, letter,type){ 
	this.id = id; //A unique identifier for this test 
	this.letter = letter; 
	this.type = type; 

	this.correct = NULL; //If this test was correct 
	this.time = 0; //The total time to complete the test
}
