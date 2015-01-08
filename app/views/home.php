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
$efnback = new efnback(); 

?>

<div id="efn_contaier" class="row text-center center-block"> 


		<?php  
			//Generate the HTML for all of the tests (hiding all of them at first )
			// foreach($efnback->blocks as $block){
			// 	echo "<p> Block Type: $block->type </p>";
			// 	echo "<p> Face Type: $block->face </p>";
			// }
		?>
		<div id="instructions_container" class="col-xs-12 hidden"> 
			 <p id="instructions"> Press the button with your finger when you see the letter "M"</p>
		</div>

		<div id="blocks">
			<?php 
				foreach($efnback->blocks as $block):
					if($block->face != "none") {
						$block_class = "with-face";
						$char_rows = "col-xs-4"; 
						$face = true; 
					} 
					else {
						$block_class = "";
						$char_rows = "col-xs-12";
						$face = false; 
					}


			?>

			<!---BLOCK --> 
			<div id="block-<?php echo $block->id?>" class="row hidden block <?php echo $block_class . " " . $block->type;?> "> 
				

				<?php if($face): ?>
				<div class="col-xs-4 center-block text-center"> 
					<img class="face" src="/img/test.bmp" width="300" alt="faceimage">
				</div>
				<?php endif; ?>



				<!-- Generate the characters -->
				<div class="<?php echo $char_rows?>"> 
					<?php foreach($block->tests as $key => $test): ?>
					<div id="<?php echo "block-id-$block->id-$key"; ?>" class="hidden char <?php $test->echo_target()?>"><?php echo $test->letter; ?></div>
					<?php endforeach; ?>
				</div>
				<!-- Generate the characters --> 

				<?php if($face): ?>
				<div class="col-xs-4 center-block text-center"> 
					<img class="face" src="/img/test.bmp" width="300" alt="faceimage">
				</div>
				<?php endif; ?>


			</div>
			<!---BLOCK -->
			<?php
				endforeach; // End block
			?>
		</div>

<!-- 		
		<span id="subject-id">Subject Id: <?php echo  $efnback->subject_id ?></span> <br />
		<span id="session-id">Session Id: <?php echo  $efnback->session_id ?></span> <br />
 -->
		



	

</div>