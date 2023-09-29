
<div class="row">
	<div class="col-xs-12">
		<?php require_once('conf/alertMessage.php'); ?>
	</div>
</div>
  <?php if($_GET['controller']!="personaldata"){ ?>
<form action="?controller=admin&action=update" role="form" method="POST" onsubmit="return tryFormSubmit(this);">
  <?php }else{ ?>
  <form action="?controller=admin&action=update&pd=1" role="form" method="POST" onsubmit="return tryFormSubmit(this);">
<?php }?>				
				<div class="panel-body">
					<?php
						require_once("views/admin/_form.php");
					?>	
				</div>
				<?php  if(($fieldblock)==false) { ?>
				<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-10  col-lg-10 col-xl-10"></div>
				<div class="col-xs-12 col-sm-12 col-md-2  col-lg-2 col-xl-2">
						<button class="btn  btn-dark-grey btn-block btn-squared" id="user-submit" type="submit"><i class="fa fa-save"></i> <?php echo $lang["save"]; ?></button>
					</div>
				</div>
				<?php }?>
</form>
