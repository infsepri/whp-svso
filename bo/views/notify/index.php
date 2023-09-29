<link href="views/assets/css/datatables.min.css" rel="stylesheet" />
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
					<button class="btn btn-xs btn-link panel-refresh" onclick="refreshtable('tableclient')"> <i class="fa fa-refresh"></i></button>
				</div>
			</div>
			<div class="panel-body">
				<div class="tableContainer" id="tableclient">


					<div class="table-responsive">
						<table id="example" class="display table_elementsdocument table table-striped table-bordered table-hover table-full-width table-condensed" style="width:100%">
							<thead>
								<tr>

									<th><?php echo $lang["spdate_start"]; ?></th>
									<th><?php echo $lang["spnHours"]; ?></th>
									<th><?php echo $lang["speventStart"]; ?></th>

									<th><?php echo $lang["speventEnd"]; ?></th>
									<th><?php echo $lang["spduration"]; ?></th>
									<th><?php echo $lang["spType"]; ?></th>
									<th><?php echo $lang["spresult"]; ?></th>
							
						

								</tr>
							</thead>
							<tbody class="tablebody">

								<?php
								if ($arr != null) {

									$aux = Common::target_dir . "no_image.png";
									foreach ($arr['body'] as $key => $value) {
										$color="";
										if ($value['spresult']==0){
											$color="danger";
										}elseif ($value['spresult']==1) {
											$color="success";
										}elseif ($value['spresult']==2) {
											$color="danger";
										}elseif ($value['spresult']==3) {
											$color="success";
										}else{
											$color="warning";
										}


								?>
										<tr>

										<?php   /* 
											<td><?php echo ((isset($value->EmpresaCodigo) && !empty($value->EmpresaCodigo)) ? $value->EmpresaCodigo : "N/D"); ?></td>
											<td> <?php echo ((isset($value->Estab_Nome)) && !empty($value->Estab_Nome) ? $value->Estab_Nome : "N/D"); ?></td>
											<td> <?php echo ((isset($value->Estab_sede) && $value->Estab_sede!= false) ? "S" : "F"); ?></td>
											<td> <?php echo ((isset($value->Estab_GPS_Latitude) && !empty($value->Estab_GPS_Latitude)) ? $value->Estab_GPS_Latitude : "N/D"); ?></td>
											<td> <?php echo ((isset($value->Estab_GPS_Longitude) && !empty($value->Estab_GPS_Longitude)) ? $value->Estab_GPS_Longitude : "N/D"); ?></td>  */ ?>

											<td><?php echo ((isset($value['spdate_start']) && !empty($value['spdate_start'])) ? date('d-m-Y', strtotime($value['spdate_start'])) : "N/D"); ?></td>
											
											<td><?php echo ((isset($value['spnHours']) && !empty($value['spnHours'])) ? date('H:i', strtotime($value['spnHours'])) : "N/D"); ?></td>
										
											<td><?php echo ((isset($value['speventStart'] ) && !empty($value['speventStart'] )) ? date('H:i', strtotime($value['speventStart'] )) : "N/D"); ?></td>
											<td><?php echo ((isset($value['speventEnd']) && !empty($value['speventEnd']) ? date('H:i', strtotime($value['speventEnd'])) : "N/D")); ?></td>
											<td><?php echo ((isset($value['spduration']) && !empty($value['spduration'])) ? date('H:i', strtotime($value['spduration'])) : "N/D"); ?></td>



											<td> <?php echo ((isset($value['spType']) && !empty($value['spType'])) ? $lang[$this->types[$value['spType']]] : "N/D"); ?></td>
											<td> <span class="label label-sm label-<?php echo $color ?>"> <?php echo ((isset($value['spresult']) && !empty($value['spresult'])) ? $lang[$this->reslts[$value['spresult']]] : "N/D"); ?></span></td>

										</tr>
								<?php }
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function() {
		$('#example').DataTable({
	
			"paging": true,
			dom: 'Bfrtip',
			
			buttons: [{
				extend: 'excelHtml5',
				autoFilter: true,
			}],
			"language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
			}

		});
	});
</script>


<script src="views/assets/js/datatables.min.js"></script>

<script src="views/assets/js/datatables/dataTables.buttons.min.js"></script>
<script src="views/assets/js/datatables/buttons.html5.min.js"></script>
<script src="views/assets/js/datatables/jszip.min.js"></script>
<script src="views/assets/js/datatables/pdfmake.min.js"></script>

<script src="views/assets/js/datatables/vfs_fonts.js"></script>
<script src="views/assets/js/datatables/buttons.print.min.js"></script>