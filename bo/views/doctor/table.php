<?php
require_once('language/common.php');
?>

<div style="display:none;" class="total"><?php echo $total; ?></div>
    <div style="display:none;" class="totalLeg"><?php echo $lang["table_showingfrom"]." ".($start+1)." ".$lang["table_until"]." ".($start+$showing)." ".$lang["table_of"]." ".($totalReg)." ".$lang["table_results"]; ?></div>
<?php

	if ($arr != null){
		 $aux=Common::target_dir."no_image.png";
		foreach ($arr as $key=>$value) {
			$title = "activate_reg";
			$title_desc = "activate_reg_text";

			if($value->state==1){
				$title = "inactivate_reg";
				$title_desc = "inactivate_reg_text";
			}
			$aux1 = "6Le12lkUAAAAALb37HIbWkbnjFIpt3yeD3GEbUij";
		    $aux2 = "6Le12lkUAAAAALb37HIbWkbnjFIpt3yeD3GEbUij";
		    $id_hash = Common::encrypt_decrypt("encrypt", $value->identity, $aux1, $aux2);

			$id_hash1 = Common::encrypt_decrypt("encrypt", $value->identitytype, $aux1, $aux2);
		//<i class="clip-user-5"></i>
		if($value->identitytype==1){
		  $icon="clip-user-5";
	    }else {
			$icon="clip-user-4";
		}
		
			
?>

			<tr >

			<td style="width:1%;"class="hidden-sm hidden-xs"> <i class="<?php echo $icon; ?>"></i></td>
			     <td class=" text-center" style="width:1%;"onclick="golocationspecifyurl('?controller=admin&action=edit&id=<?php echo $id_hash; ?>&t=<?php echo $id_hash1; ?>');"><img class="img-circle" src="<?php echo (isset($value->photo)!=NULL) ? Common::target_dir.$value->photo :  $aux; ?>" width="35px" height="35px" alt=""/></td>
				<td class="col-xs-3 col-sm-3 col-md-3 col-lg-3"onclick="golocationspecifyurl('?controller=admin&action=edit&id=<?php echo $id_hash; ?>&t=<?php echo $id_hash1; ?>');"> <?php echo ((isset($value->nameentity) && !empty($value->nameentity)) ? $value->nameentity : "N/D"); ?></td>
				<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1"onclick="golocationspecifyurl('?controller=admin&action=edit&id=<?php echo $id_hash; ?>&t=<?php echo $id_hash1; ?>');"> <?php echo ((isset($value->numberhelth) && !empty($value->numberhelth)) ? $value->numberhelth : "N/D"); ?></td>
				<td class="col-xs-2 col-sm-2 col-md-2 col-lg-2"onclick="golocationspecifyurl('?controller=admin&action=edit&id=<?php echo $id_hash; ?>&t=<?php echo $id_hash1; ?>');"> <?php echo ((isset($value->email) && !empty($value->email)) ? $value->email : "N/D"); ?></td>
				<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1"onclick="golocationspecifyurl('?controller=admin&action=edit&id=<?php echo $id_hash; ?>&t=<?php echo $id_hash1; ?>');"><?php echo ((isset($value->mobilephone) && !empty($value->mobilephone)) ? $value->mobilephone : "N/D"); ?></td>
				<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1"onclick="golocationspecifyurl('?controller=admin&action=edit&id=<?php echo $id_hash; ?>&t=<?php echo $id_hash1; ?>');"><?php echo $value->userUpd; ?></td>
				<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1"onclick="golocationspecifyurl('?controller=admin&action=edit&id=<?php echo $id_hash; ?>&t=<?php echo $id_hash1; ?>');"><?php echo $value->updatedatentity; ?></td>

				<?php if ($value->identity==$identity) { ?>
					<td style="width:10%;">
				<?php if ($value->state==1) { ?>
					<span class="cPointer label label-green hidden-sm hidden-xs" id="badgesSameSize"><?php echo $lang['active_state']; ?></span>
					<span class='cPointer hidden-lg hidden-md circle_ative' title="<?php echo $lang['active_state']; ?>"><i class='fa fa-circle circle'></i></span>

				<?php } else {  ?>
					<span class="cPointer label label-danger hidden-sm hidden-xs" id="badgesSameSize"><?php echo $lang['inactive_state']; ?></span>
					<span class='cPointer hidden-lg hidden-md circle_inative' title="<?php echo $lang['inactive_state']; ?>"><i class='fa fa-circle circle'></i></span>

				<?php } ?>
				</td>
				<?php } else {  ?>
					<td style="width:10%;" onclick="confirm_Swal('1', ['<?php echo $title; ?>', '<?php echo $title_desc;?>', '?controller=admin&action=changestate&tab=1&id=<?php echo $value->identity; ?>'])">
				<?php if ($value->state==1) { ?>
					<span class="cPointer label label-green hidden-sm hidden-xs" id="badgesSameSize"><?php echo $lang['active_state']; ?></span>
					<span class='cPointer hidden-lg hidden-md circle_ative' title="<?php echo $lang['active_state']; ?>"><i class='fa fa-circle circle'></i></span>

				<?php } else {  ?>
					<span class="cPointer label label-danger hidden-sm hidden-xs" id="badgesSameSize"><?php echo $lang['inactive_state']; ?></span>
					<span class='cPointer hidden-lg hidden-md circle_inative' title="<?php echo $lang['inactive_state']; ?>"><i class='fa fa-circle circle'></i></span>

				<?php } ?>
				</td>
				<?php } ?>
				
			</tr>
<?php
		}
	}
	else {
?>
		<tr></tr>
		<tr></tr>
        <tr><td colspan="9"><?php echo $lang['table_withoutdata']; ?></td></tr>
<?php
	}
?>