<div id="efn_contaier" class="row text-center center-block"> 
	<div id="loading"> 
		<i class="fa fa-spinner fa-spin fa-5x"></i>
		<p> Building the task </p>
	</div>

	<div id="instructions_container" class="col-xs-12"> 

		<div id="instructions">
		<img class='img-responsive center-block' src='/img/faces/happy/happy-<?php echo rand(1,16); ?>.jpg' alt='preloading' width='150'>

		<p>Psychology application for <a href="http://www.upmc.com/Pages/default.aspx" target="_blank">UPMC</a> to test reaction time compared to viewing emotional stimuli on screen. </p>

		<p><strong>Full Test </strong>- Each individual test lasts 3.5s and the target letter appears for 0.5s.  Results will be displayed at the end of the test. The entire test lasts about 20 minutes.</p>
		<p><strong>Quick Test</strong> - The test is simulated at <em>hyper speed</em> to quickly see how the application works.</p>
		
		<p><small class='text-warning'>This is for demonstration only</small></p>
	</div>

	<div id="test-variables" class='center-block'> 
		<div id="form-errors"> </div>
		<form class="form-inline">
		<div class="form-group">
			<label for="sessionID">Session ID</label>
			<input type="text" class="form-control" id="sessionID" name="sessionID" placeholder="Session ID" value='1234567' disabled>
		</div>
		<div class="form-group">
			<label for="userID">User ID</label>
			<input type="text" class="form-control" id="userID" placeholder="User ID" value='1234567' disabled>
		</div>
		<div class='center-block' style='padding-top: 10px;'>
			<button type="button" class="btn btn-success" id="quick-test">High Speed Test</button>
			<button type="button" class="btn btn-danger" id="start-test">Take the Test (~20 Min)</button>
			<button type="button" class="btn btn-success hidden" id="generate-uid">Full Test (~20 Min)</button>
		</div>
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