<?php 
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

*/

/**
 *	The current UID will be combined with a date to make it more unique but memorable. 
 *	
 *
 */
function generateUID(){
	return rand(0,99999);
}


$uid = generateUID(); 
?>

<div id="efn_contaier" class="row text-center center-block"> 

	<div class="col-xs-12">
		<span id="user-id">Potential UID: <?php echo $uid ?></span> <br />

		<span id="char-pressed">Keyboard listening...</span> <br />

		<span id="efn-character">a + b</span> <br />


		<h3>100</h3>
		<img src="/img/test.bmp" alt="test" class="img-responsive center-block" width="100"> 

		<h3>150</h3>
		<img src="/img/test.bmp" alt="test" class="img-responsive center-block" width="150"> 

		<h3>200</h3>
		<img src="/img/test.bmp" alt="test" class="img-responsive center-block" width="200"> 

		<h3>250</h3>
		<img src="/img/test.bmp" alt="test" class="img-responsive center-block" width="250"> 

		<h3>300</h3>
		<img src="/img/test.bmp" alt="test" class="img-responsive center-block" width="300">

		<h3>300</h3>
		<img src="/img/test.bmp" alt="test" class="img-responsive center-block" width="300"> 

		<h3>350</h3>
		<img src="/img/test.bmp" alt="test" class="img-responsive center-block" width="350"> 

		<h3>Original (400)</h3>
		<img src="/img/test.bmp" alt="test" class="img-responsive center-block">
	</div>

</div>