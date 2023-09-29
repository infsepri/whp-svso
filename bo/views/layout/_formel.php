<!-- Date/Time picker  -->
<link href="views/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

<!-- SELECT2 -->
<link href="views/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />

<!-- Multi -->
<link href="views/assets/plugins/multiselected/css/bootstrap-multiselect.css" rel="stylesheet"/>

<!-- Switch -->
<link href="views/assets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch.css" rel="stylesheet"/>

<!-- Fileupload -->
<link href="views/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css" rel="stylesheet"/>

<!-- Colopalette -->
<link href="views/assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css" rel="stylesheet">

<div class="row">
	<div class="col-lg-10">
		<div class="row">
			<div class="col-md-7">
				<div class="form-group">
					<label class="control-label">Designação:<span class="symbol required"></span></label>
					<input type="text" class="form-control valRequired" id="catvnome" name="catvnome" value="">
				</div>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					<label class="control-label">Cód. IMT:<span class="symbol required"></span></label>
					<input type="text" class="form-control valRequired valNumber valUnique" id="catvimt" data-checktable="8" data-checkfield="1" name="catvimt" value="">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group">
					<label class="control-label">Etrto:</label>
					<div class="input-group">
						<div class="make-switch" data-on="success" data-off="danger" data-on-label="Sim" data-off-label="Não">
							<input type="checkbox" name="c_pneuetrto" id="c_pneuetrto"  value="1" >
						</div>
					</div>
				</div>
			</div>
		
			<div class="col-lg-4" >
				<div class="form-group"  >
					<label class="control-label" >Multiple</label>
					<select name="businessarea[incharge][]" class="select_multiple_init form-control" multiple="multiple" id="businessarea_incharge"  style="width:100%">
						<option data-id="1" value="1">OP1</option>
						<option data-id="2" value="2">OP2</option>
						<option data-id="3" value="3">OP3</option>
						<option data-id="4" value="4">OP4</option>
						<option data-id="5" value="5">OP5</option>
						<option data-id="6" value="6">OP6</option>
						<option data-id="7" value="7">OP7</option>
					</select>
				</div>
			</div>

			<div class="col-lg-3">
				<div class="form-group ">
					<label class="control-label" >Select2 SImple</label>
					<select class="form-control select2 select2_basic" data-placeholder="Placeholder para um select2" name="businessarealine[0][type]">
						<option value=""></option>
						<option value="1">OP1</option>
						<option value="2">OP2</option>
						<option value="3">OP3</option>
					</select>
				</div>
			</div>
				
			<div class="col-lg-3">
				<div class="form-group ">
					<label class="control-label" >Select2 SImple Search</label>
					<select class="form-control select2 select2_basic_search" name="businessarealine[0][type]">
						<option value="1">OP1</option>
						<option value="2">OP2</option>
						<option value="3">OP3</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
				<div class="form-group box">
					<label for="datetime_contact"   class="required bold">DATETIME</label>
					<input maxlength="100" value="" type="text" class="form-control elem_datetimepicker_datetime" id="datetime_contact"    >
				</div>
			</div>

			
			<div class="col-lg-4">
				<div class="form-group ">
					<label class="control-label" >Select2 SImple</label>
					<select class="form-control select2 select2_basic" data-placeholder="Placeholder para um select2" name="businessarealine[0][type]">
						<option value=""></option>
						<option value="1">OP1</option>
						<option value="2">OP2</option>
						<option value="3">OP3</option>
					</select>
				</div>
			</div>
			
			<div class="col-xs-4">
				<div class="form-group box">
					<label for="datenextstep"  class="required bold">DATE</label>
					<input maxlength="100" value="" type="text" class="form-control elem_datepicker_datetime" id="datenextstep_contact"    >
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">Cor:</label>
					<div class="input-group">
						<input type="text" class="form-control" style="background-color: #FFF" id="selected-color1" name="c_companycor">
						<span class="btn input-group-addon" id="show_color"></span>
						<span class="btn input-group-addon" data-toggle="dropdown">Cor</span>
						<ul class="dropdown-menu pull-right center">
							<li>
								<div class="color-palette"></div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">Total:<span class="symbol required"></span></label>
					<input class="form-control currency valRequired valNumberFloat" id="c_despvalor" name="c_despvalor" value="" type="text">
				</div>
			</div>
			<div class="col-md-2">
				<div class="awcheckbox valCheck">
					<input type="checkbox" class="styled">
					<label> Check </label>
				</div>
				<div class="awcheckbox checkbox-success">
					<input type="checkbox" onchange="console.log('change');" class="styled">
					<label> Check </label>
				</div>
			</div>
			<div class="col-md-2">
				<div class="awradio radio-info">
					<input type="radio" name="radio2" value="option1">
					<label> One </label>
				</div>
				<div class="awradio smallradio">
					<input type="radio" name="radio2" value="option2" checked>
					<label> Two </label>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-2">
		<label class="control-label">IMAGE</label>
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div style="width:100%;">
				<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 120px; max-height: 120px; line-height: 20px;"></div>
				<div class="fileupload-new thumbnail" style="width: 120px; height: 120px; line-height: 20px;">
					<img src="views/assets/images/no_image.png" alt=""/>
				</div>
			</div>
			<div class="user-edit-image-buttons">
				<span class="btn btn-primary btn-file btn-squared">
					<span class="fileupload-new"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Escolher</span>
					<span class="fileupload-exists"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Alterar</span>
					<input type="file" id="c_companylogo" name="c_companylogo" accept="image/png, image/jpeg, image/gif">
				</span>
				<a href="#" class="btn fileupload-exists btn-bricky btn-squared" data-dismiss="fileupload">
					<i class="fa fa-times"></i>&nbsp;&nbsp;Remover
				</a>
			</div>
		</div>
	</div>
</div>

<form id="formRep">
	<div class="row">
		<div class="col-md-12">
			<h4>Replicar Bloco</h4>
		</div>
		<div class="col-md-12">
			<hr style="margin-top:0px; margin-bottom:15px;">
			<div class="repContainer">
				<div class="repItem original">
					<div class="row">
						<input type="hidden" class="form-control repUpd" data-repattr="name" name="[0][idceversion]"/>
						<div class="col-md-3">
							<select class="form-control repUpd generateSelect2_search" data-repattr="name" name="[0][version]">
								<option value="1">OP 1</option>
								<option value="2">OP 2</option>
								<option value="3">OP 3</option>
							</select>
						</div>
						<div class="col-md-3">
							<select class="form-control repUpd generateMulti" data-repattr="name" multiple="multiple" name="[0][mult]">
								<option value="1">OP 1</option>
								<option value="2">OP 2</option>
								<option value="3">OP 3</option>
								<option value="4">OP 4</option>
							</select>
						</div>
						<div class="col-md-3">
							<div class="">
								<input type="checkbox" name="[0][check]" data-repattr="name" class="styled repUpd generateCheck">
								<label>
									Check
								</label>
							</div>
							<div class="checkbox-success">
								<input type="checkbox" name="[0][check]" data-repattr="name" class="styled repUpd generateCheck">
								<label>
									Check
								</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="radio-info smallradio">
								<input type="radio" name="[0][radio]" data-repattr="name" value="option1" class="repUpd generateRadio">
								<label> One </label>
							</div>
							<div class="smallradio">
								<input type="radio" name="[0][radio]" data-repattr="name" value="option2" class="repUpd generateRadio" checked>
								<label> Two </label>
							</div>
						</div>
						<div class="col-md-2">
						   <input type="text" value="" placeholder="Texto" class="form-control repUpd" data-repattr="name" name="[0][code]">
						</div>
						<div class="col-md-2">
						   <input type="text" value="" class="form-control generateDate repUpd" data-repattr="name" name="[0][date]">
						</div>

						<div class="col-md-1 pull-right">
							<div class="form-group">
							<a class="btn btn-bricky btn-squared repRemBtn" role="button" onclick="removeRepItem(this);"><i class="far fa-trash-alt "></i></a>
							</div>
						</div>
					</div>
					<hr style="margin-top:0px; margin-bottom:15px;">
				</div>

				<div class="row">
					<div class="col-md-3">
						<a class="btn btn-primary btn-squared" onclick="addRepItem(this,'ceversions');"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?="Adicionar Linha";?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="row">
	<div class="col-md-12"><div class="form-group"><span class="symbol required"></span>Campos de preenchimento obrigatório</div></div>
</div>


<div class="row">
	<div class="col-md-12">
		<h4>CKEditor</h4>
	</div>
	<div class="col-md-12">
		<textarea class="ckeditorelem ckeditorfull" id="text1"></textarea>
	</div>
	<div class="col-md-12">
		<textarea class="ckeditorelem ckeditornormal" id="text4"></textarea>
	</div>
	<div class="col-md-12">
		<textarea class="ckeditorelem ckeditorbasic" id="text2"></textarea>
	</div>
	<div class="col-md-12">
		<textarea class="ckeditorelem ckeditorsimple" id="text3"></textarea>
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
