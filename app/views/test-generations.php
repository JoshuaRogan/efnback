<?php

$efnback = new efnback(); 



?>

<div id="efn_contaier" class="row"> 
<h1> Sequence Generations Test Page </h1>
<p> Example page of what the test generations will be in the actual application. The highlighted letter is the correct response letter. M's will not appear more than five times. These are just the entire sequence pre generated. The user will see the letters in the with the pause, "+", faces, etc. This is just a page to make sure the sequences of letters are correct. </p>
<p> By refreshing the page you will see different test sequences.</p>
	
	<div class="col-xs-12">
		<h2> 0-Back Test Generation </h2>
		

		<?php  

		foreach($efnback->blocks as $block){
			if($block->type == efnback::$block_type[0]){
				$string = "";
				$string .= "
				<div class='col-xs-12'>
					<h3> Block: $block->type ($block->face)</h3>
					<div class='col-xs-12'>
						<h3> Tests: </h3>
						<ul class='list-inline'> 
				";


				foreach($block->tests as $test){ 
					if($test->is_target) $class = "color: yellow";
					else $class = ""; 
					$string .= "<li style='$class'> $test->letter </li>";
				}


				$string .= "</ul></div></div>";

				echo $string;
			}
		}

	?>
	</div>

	<div class="col-xs-12">
	<h2> 2-Back Test Generation</small></h2>
	<p> Working but the generations favor sequences with longer A - B - A - B Type patterns (this means there will be more repeated letters). It currently chooses the <i>positions</i> of the targets randomly and determines what character it needs to make that work. The reason it happens is because it is very likely that a position two spots from it will be chosen, which forces a duplicate letter situation. </p>

	</div>

		<?php 

		foreach($efnback->blocks as $block){
			if($block->type == efnback::$block_type[1]){		
			

				$string = "";
				$string .= "
				<div class='col-xs-12'>
					<h3> Block: $block->type ($block->face)</h3>
					<div class='col-xs-12'>
						<h3> Tests: </h3>
						<ul class='list-inline'> 
				";


				for($i=0; $i<block::NUMTESTS; $i++){ 
					// $_DEBUG[$block->]
					$test = $block->tests[$i];
					if($test->is_target) $class = "color: yellow";
					elseif($test->is_sandwich) $class = "color: rgb(176, 252, 176)";
					else $class = ""; 
					$string .= "<li style='$class'> $test->letter </li>";
				}
				echo "</ul>";

				// echo "<p>Sequence Positions:"; 
				// foreach($block->two_back_sequence_order as $pos){
				// 	echo " $pos ";
				// }
				// echo "</p>";

				// echo "<p>Sequence Target Characters:"; 
				// foreach($block->two_back_sequence_chars as $char){
				// 	echo " $char ";
				// }
				// echo "</p>";
				$string .= "</div></div>";

				echo $string;
			}
		}

	?>
		

		



	</div>

</div>