<div class="row">
	<div class="col-xs-12">
		<?php require_once('conf/alertMessage.php'); ?>
	</div>
</div>

<form action="?controller=personaldata&action=update" role="form" method="POST" onsubmit="return tryFormSubmit(this);">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<?php
						require_once("views/resetpassword/resetpassword.php");
					?>
				
					<div class="row">
						<div class="col-md-10"></div>
						<div class="col-md-2">
							<button class="btn btn-success btn-block btn-squared" id ="submit" type="submit"><i class="fa fa-save"></i> <?php echo $lang["save"]; ?></button>
						</div>
					</div>
				
				</div>

			</div>
		</div>
	</div>


</form>
