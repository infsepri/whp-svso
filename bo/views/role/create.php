<form action="?controller=role&action=create" role="form" method="POST" onsubmit="return tryFormSubmit(this);">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
			<div class="panel-heading">
                <i class="clip-list"></i> <?php echo $lang["MENU_ROLE"]; ?>
                <div class="panel-tools">
                    <!--  <button class="btn btn-xs btn-link panel-refresh" onclick="refreshtable()"> <i class="fa fa-refresh"></i></button>-->
                </div>
            </div>
				<div class="panel-body">
					<?php
						require_once("views/role/_form.php");
					?>
				
				</div>
            </div>
						<div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-10  col-lg-10 col-xl-10"></div>
					    <div class="col-xs-12 col-sm-12 col-md-2  col-lg-2 col-xl-2">
								<button class="btn  btn-success btn-block btn-squared" type="submit"><i class="fa fa-save"></i> <?php echo $lang["save"]; ?></button>
							</div>
						</div>
        </div>
    </div>
</form>

