<?php require_once('language/common.php');require_once("conf/globalconf.php"); ?>
		<!-- start: PAGE -->

		<div class="logo alert alert-info" align="center">
        
        <strong> <i class="clip-code"></i> <?php echo $lang["dep"]; ?>  <i class="clip-code"></i> </strong>

		</div>

		<div class="timer-area"  align="center">

			<h1><?php echo $lang["maintenance"]; ?>...</h1>

			<ul id="countdown">

				<li>

					<span class="days"> <?php echo date("d");?></span>

					<p class="timeRefDays">
                    <?php echo $lang["day"]; ?>
						

					</p>

				</li>

				<li>

					<span class="hours"> <?php echo date("m");?></span>

					<p class="timeRefHours">
                    <?php echo $lang["month_"]; ?>
                    </p>

				</li>

				<li>
					<span class="minutes"> <?php echo date("Y");?></span>
					<p class="timeRefMinutes">
                    <?php echo $lang["year"]; ?>
                    
					</p>
				</li>
			</ul>
		

		</div>

        </div>





