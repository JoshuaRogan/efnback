/** 
 *	Generate sequences for the efn task
 *
 *
 */
var SEQUENCER = SEQUENCER || {};

SEQUENCER = {
	numItems: 12, //The length of the sequence,
	numTargets: 5, //The number of targets 

	/**
	 *	
	 *
	 *
	 */
	zeroBack: function (){
		var zeroBackSequence = new Object(); 
		zeroBackSequence.letters = Array();
		zeroBackSequence.isTarget = Array();

		//Add four M's to the letters 
		zeroBackSequence.letters[0] = "M"; 
		zeroBackSequence.letters[1] = "M"; 
		zeroBackSequence.letters[2] = "M"; 
		zeroBackSequence.letters[3] = "M"; 
		zeroBackSequence.letters[4] = "M"; 

		for(var i = 5; i < this.numItems; i++){
			zeroBackSequence.letters[i] = String.fromCharCode(this.generateRandomBetween(65,90));
		}

		return zeroBackSequence;
	},

	/**
	 *	
	 *
	 *
	 */
	twoBack: function (){
		var twoBackSequence = new Object();



		return twoBackSequence; 
	},

	//Generate a random number between 
	generateRandomBetween: function(min, max){
		 return Math.floor(Math.random() * (max - min + 1)) + min;
	}

}