<form action="?controller=admin&action=create" role="form" method="POST" enctype="multipart/form-data" onsubmit="return tryFormSubmit(this);">
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="clip-list"></i> <?php echo $lang["MENU_MANAGEMENT_ADMIN"]; ?>
                <div class="panel-tools">
                    <!--  <button class="btn btn-xs btn-link panel-refresh" onclick="refreshtable()"> <i class="fa fa-refresh"></i></button>-->
                </div>
            </div>
            <div class="panel-body">
							<?php
								require_once("views/admin/_form.php");
							?>
            </div>
            </div>
						<div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-10  col-lg-10 col-xl-10"></div>
					    <div class="col-xs-12 col-sm-12 col-md-2  col-lg-2 col-xl-2">
								<button class="btn  btn-dark-grey btn-block btn-squared" id="user-submit" type="submit"><i class="fa fa-save"></i> <?php echo $lang["save"]; ?></button>
							</div>
						</div>
        </div>
    </div>
</form>

