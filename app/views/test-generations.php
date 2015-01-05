<?php

$efnback = new efnback(); 
$efnback->generateSequence(); 
// $_DEBUG[] = $efnback->toString(); 

?>

<div id="efn_contaier" class="row"> 
<h1> Sequence Generations Test Page </h1>
	
	<div class="col-xs-12">
		<h2> 0-Back Test Generation </h2>
		<p> Example page of what the test generations will be in the actual application. The highlighted letter is the correct response letter. M's will not appear more than five times. </p>
		<p> By refreshing the page you will see different test sequences.</p>

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
					if($test->respond) $class = "color: yellow";
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
	<h2> 2-Back Test Generation <small> Currently Developing <i> Displaying Contrived Example (Unless I'm Working on it)</i></small></h2>
	<h3> Edge Cases Fixed: </h3>
		<ul>
			<li> Targets must appear after index 1 but "2-backs" can be at 0</li>
			<li> Randomly generating characters that would lead to more than 5 targets. </li>
			<li> Two "2-back" characters in a row </li>
		</ul>

	<h3> Not Yet Fixed </h3>
		<ul> 
			<li> Proper pre-generation of target characters </li>
		</ul>



	</div>

		<?php 

		foreach($efnback->blocks as $block){
			if($block->type == efnback::$block_type[1]){
				$_DEBUG[] = $block->two_back_sequence_order;
				$_DEBUG[] = $block->two_back_sequence_chars;

				echo "<p>Sequence Positions:"; 
				foreach($block->two_back_sequence_order as $pos){
					echo " $pos ";
				}
				echo "</p>";

				echo "<p>Sequence Target Characters:"; 
				foreach($block->two_back_sequence_chars as $char){
					echo " $char ";
				}
				echo "</p>";
			

				$string = "";
				$string .= "
				<div class='col-xs-12'>
					<h3> Block: $block->type ($block->face)</h3>
					<div class='col-xs-12'>
						<h3> Tests: </h3>
						<ul class='list-inline'> 
				";


				foreach($block->tests as $test){ 
					if($test->respond) $class = "color: yellow";
					elseif($test->sandwich) $class = "color: rgb(176, 252, 176)";
					else $class = ""; 
					$string .= "<li style='$class'> $test->letter </li>";
				}


				$string .= "</ul></div></div>";

				echo $string;
			}
		}

	?>
		

		



	</div>

</div>