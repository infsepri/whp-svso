<?php
require_once('language/common.php');
?>

<div style="display:none;" class="total"><?php echo $total; ?></div>
<div style="display:none;" class="totalLeg"><?php echo $lang["table_showingfrom"]." ".($start+1)." ".$lang["table_until"]." ".($start+$showing)." ".$lang["table_of"]." ".($totalReg)." ".$lang["table_results"]; ?></div>
<?php
	if ($arr != null){
		foreach ($arr as $key=>$value) {
			$title = "activate_reg";
			$title_desc = "activate_reg_text";


?>
			<tr >


                               <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4" ><?php echo $value->name; ?></td>
								<td class="col-xs-2 col-sm-4 col-md-1 col-lg-1" ><?php echo $value->userUpd; ?></td>
								<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1" ><?php echo $value->updatedat; ?></td>
							
									
										<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center hidden-sm hidden-xs"  style="width:5%;" onclick="golocationspecify(<?php echo $value->idrole; ?>, 'role', 'edit', 'id');"  style="width:10%;text-align:left;">
									   <span class="cPointer label label-primary hidden-sm hidden-xs" id="badgesSameSize"><i class="fa fa-edit"></i> <?php echo $lang['view_edit']; ?></span>
									   <button class="btn btn-squared btn-xs btn-primary pull-right hidden-lg hidden-md" title="<?php echo $lang['view_edit']; ?>"><i class="fa fa-edit"></i></button>
								       </td>
									  
								
									
							
								
								
			</tr>
<?php
		}
	}
	else {
?> 	
		<tr></tr>
		<tr></tr>
        <tr><td colspan="5"><?php echo $lang['table_withoutdata']; ?></td></tr>
<?php
	}
?>
