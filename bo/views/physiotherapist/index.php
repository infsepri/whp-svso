<!-- Date/Time picker  -->
<link href="views/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

<!-- SELECT2 -->
<link href="views/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />

<!-- Multi -->
<link href="views/assets/plugins/multiselected/css/bootstrap-multiselect.css" rel="stylesheet" />

<!-- Switch -->
<link href="views/assets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch.css" rel="stylesheet" />

<!-- Fileupload -->
<link href="views/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css" rel="stylesheet" />

<!-- Colopalette -->
<link href="views/assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css" rel="stylesheet">
<div class="row">
    <div class="col-xs-12">
        <?php require_once('conf/alertMessage.php'); ?>
    </div>
</div>
<div class="row">

	<div class="col-xs-12">
		<div class="panel panel-default">


			<div class="panel-heading">
				<i class="clip-list"></i> <?php echo $lang["MENU_NOTIFICATION"]; ?>
				<div class="panel-tools">
					<button class="btn btn-xs btn-link panel-refresh" onclick="refreshtable()"> <i class="fa fa-refresh"></i></button>
				</div>
			</div>


			<div class="panel-body">
				<div class="row">
					<div class="col-sm-2">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-group"></i>
							Users <span class="badge badge-primary"> 4 </span>
						</button>
					</div>
					<div class="col-sm-4">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-comments-o"></i>
							Comments <span class="badge badge-danger"> 4 </span>
						</button>
					</div>
					<div class="col-sm-3">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-shopping-cart"></i>
							Orders <span class="badge badge-danger"> 4 </span>
						</button>
					</div>
					<div class="col-sm-3">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-barcode"></i>
							Products <span class="badge badge-warning"> 4 </span>
						</button>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-calendar"></i>
							Calendar <span class="badge badge-success"> 4 </span>
						</button>
					</div>
					<div class="col-sm-3">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-heart-o"></i>
							Favorites <span class="badge badge-danger"> 4 </span>
						</button>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-thumbs-up"></i>
							Likes <span class="badge badge-warning"> 4 </span>
						</button>
					</div>
					<div class="col-sm-4">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-exclamation-triangle"></i>
							Warning <span class="badge badge-success"> 4 </span>
						</button>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-tasks"></i>
							Tasks <span class="badge badge-danger"> 4 </span>
						</button>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-check"></i>
							To Do <span class="badge badge-success"> 4 </span>
						</button>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-list"></i>
							Tickets <span class="badge badge-warning"> 4 </span>
						</button>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-picture-o"></i>
							Pictures <span class="badge badge-danger"> 4 </span>
						</button>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-upload"></i>
							Upload <span class="badge badge-success"> 4 </span>
						</button>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-icon btn-block">
							<i class="fa fa-tags"></i>
							Tags <span class="badge badge-danger"> 4 </span>
						</button>
					</div>

				</div>
			</div>

		</div>
		</div>

		</div>

		<!-- Masks -->
		<script src="views/assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
		<script src="views/assets/plugins/jquery-maskmoney/jquery.maskMoney.js"></script>

		<!-- Limiter -->
		<script src="views/assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.js"></script>

		<!-- Autosize -->
		<script src="views/assets/plugins/autosize/jquery.autosize.min.js"></script>

		<!-- Fileupload -->
		<script src="views/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>

		<!-- Colopalette -->
		<script src="views/assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js"></script>

		<!-- Switch -->
		<script src="views/assets/plugins/bootstrap-switch/static/js/bootstrap-switch.js"></script>

		<!-- Date/Time picker -->
		<script src="views/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
		<script src="views/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.pt.js"></script>

		<!-- Multi -->
		<script src="views/assets/plugins/multiselected/js/bootstrap-multiselect.js"></script>

		<!-- SELECT2 -->
		<script src="views/assets/plugins/select2/dist/js/select2.min.js"></script>
		<script src="views/assets/plugins/select2/dist/js/i18n/<?php echo $lang_abbr; ?>.js"></script>

		<!-- Multi -->
		<script src="views/assets/plugins/ckeditor/ckeditor.js"></script>
		<script src="views/assets/plugins/ckeditor/start.js"></script>

		<script src="views/assets/js/formElements.js"></script>
		<script src="views/assets/js/formBlock.js"></script>