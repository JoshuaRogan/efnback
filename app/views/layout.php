<div id="efn_contaier" class="row text-center center-block"> 
	<div id="instructions_container" class="col-xs-12 hidden"> 
		<p id="instructions"> Touch or click the screen to begin the test!</p>
	</div>

	<!-- THIS SECTION FILLED VIA JS --> 

	<!-- <div id="block-no-face" class="row hidden">
		<div class="col-xs-12 char"> 
		
		</div>
	</div> -->

	<div id="block-face" class="row hiddens">
		<div class="col-xs-5 center-block text-center"> 
			<img class="face" src="/img/faces/fear/fear-1.jpg" width="300" alt="faceimage">
		</div>

		<div class="col-xs-2 char"> 
			M
		</div>

		<div class="col-xs-5 center-block text-center"> 
			<img class="face" src="/img/faces/fear/fear-1.jpg" width="300" alt="faceimage">
		</div>
	</div>

	<!-- PRELOAD ALL OF THE IMAGES -->
	<div class="hidden"> 
		<?php
			for($i=1; $i<17; $i++){
				echo "<img src='/img/faces/fear/fear-$i.jpg' alt='preloading'>";
			}

			for($i=1; $i<17; $i++){
				echo "<img src='/img/faces/happy/happy-$i.jpg' alt='preloading'>";
			}

			for($i=1; $i<17; $i++){
				echo "<img src='/img/faces/neautral/neautral-$i.jpg' alt='preloading'>";
			}
		?>
	</div>

	<!-- PRELOAD ALL OF THE IMAGES -->

	<!-- THIS SECTION GENERATED VIA JS --> 

	<!-- <div id="button"> 
		<button id="primary-action" type="button" class="btn btn-primary">Start</button>
	</div> -->
	

</div>