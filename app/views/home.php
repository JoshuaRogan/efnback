<div id="efn_contaier" class="row text-center center-block"> 
	<div id="loading"> 
		<i class="fa fa-spinner fa-spin fa-5x"></i>
		<p> Building the task </p>
	</div>

	<div id="instructions_container" class="col-xs-12"> 
		<p id="instructions">Please add your session and ID number to the form below. If this is the first time taking the test click "Generate User ID". <br/> <strong> Make sure to remember the user id! </strong></p>
	</div>

	<div id="test-variables"> 
		<div id="form-errors"> </div>
		<form class="form-inline">
		<div class="form-group">
			<label for="sessionID">Session ID</label>
			<input type="text" class="form-control" id="sessionID" name="sessionID" placeholder="Session ID" required >
		</div>
		<div class="form-group">
			<label for="userID">User ID</label>
			<input type="text" class="form-control" id="userID" placeholder="User ID" required >
		</div>
		<br/>
			<button type="button" class="btn btn-default" id="start-test">Start the Test!</button>
			<button type="button" class="btn btn-success" id="generate-uid">Generate User ID</button>
		</form>
	</div>

	<!-- THIS SECTION FILLED VIA JS --> 

	<div id="block-no-face" class="row hidden">
		<div class="col-xs-12 char"> 
		
		</div>
	</div>

	<div id="block-face" class="row hidden">
		<div class="col-xs-5 center-block text-center"> 
			<img class="face" src="/img/faces/fear/fear-1.jpg" width="300" alt="faceimage">
		</div>

		<div class="col-xs-2 char"> 
			
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