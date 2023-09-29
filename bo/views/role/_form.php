<link href="views/assets/css/main1.css?v=2" rel="stylesheet">
<div class="row">
<div class="col-xs-12">
  <div class="form-group required">
 <label class="control-label " ><?php echo $lang['name']; ?><span class="symbol required"></span></label>
<input type="text" class="form-control valRequired" name="role[name]" value="<?php echo (isset($role)) ? htmlentities($role->name, ENT_QUOTES) : ""; ?>" />
</div>
</div>
</div>
<br/>
<?php if($operation!=null){ $s = 0;
  $countElements = count($operation);
   $middle = intval($countElements / 2);
   foreach ($operation as $key2 => $value2) {
     if($value2->level==1 && $key2<=$middle){
       $part = $key2;
     }
   }
  ?>
  <div class="row">
  <div class="col-md-6">
  <table class="paneltable" class="tr_hover">
    <tr class="tr_no_hover">
      <td class="paneltabletd"></td>
      <td class="paneltabletd"></td>
      <td class="paneltabletd1"><span class="panelspan"><?= $lang['toview'] ?></span></td>

      <td class="paneltabletd8"><?= $lang['create_edit_delete'] ?></td>
    </tr>

  <?php $order = array(); foreach ($operation as $key => $value) { array_push($order, $value->orderfather );
    $group = false;
    if($key==$part){
      ?>
      </table></div>
      <div class="col-md-6">
      <table style="width:100%; float:left;" class="tr_hover">
        <tr class="tr_no_hover">
          <td class="paneltabletd"></td>
          <td class="paneltabletd"></td>
          <td class="paneltabletd1"><span class="panelspan"><?= $lang['toview'] ?></span></td>

          <td class="paneltabletd8"><?= $lang['create_edit_delete'] ?></td>
        </tr>
      <?php
    }
    if(isset($operation[$key+1]) && $operation[$key+1]->level>$value->level){
      $group =true;
    }
    if($value->functionality!=null){
      $value->functionality = explode(";", $value->functionality);
      $countfun = count($value->functionality);
      $countfunsum = 0;
      while($countfunsum<$countfun){
        if($value->functionality!=null && !in_array($value->functionality[$countfunsum], $_SESSION['functionality'])){
          continue 2;
        }
        $countfunsum++;
      }
    }
     ?>
    <tr>
      <?php if($value->level==1){$s = 0; ?>
        <td class="first_level" colspan="3" class="paneltabletd2"><?= $lang[$value->description] ?><label class="panellabel1"><input orderfather="<?= $value->orderfather ?>" class="no-uniform group<?= $value->orderfather ?>" type="checkbox"  <?php if( isset($operationprofile) && in_array($value->idoperation, $operationprofile) ){print "checked";} ?>    onclick="$('.subgroup<?= $value->orderfather ?>').prop('checked', this.checked).change(); $('.father<?= $value->orderfather ?>').prop('checked', this.checked).change();$('.subfatherparent<?= $value->orderfather ?>').prop('checked', this.checked); $('#listitemb2b').prop('checked', false).change()  ;$('#sublistitemb2b').prop('checked', false) ; "></label>

<input   class="no-uniform father<?= $value->orderfather ?> " <?php if( isset($operationprofile) && in_array($value->idoperation, $operationprofile) ){print "checked";} ?> style="display:none" type="checkbox" name="operation[1][]" value="<?= $value->idoperation ?>"  >


  <input   class="no-uniform ECD_father<?= $value->orderfather ?> " <?php if( isset($operation2profile) && in_array($value->idoperation, $operation2profile) ){print "checked";} ?> style="display:none" type="checkbox" name="operation[2][]" value="<?= $value->idoperation ?>"  >

        </td>

        <td class="paneltabletd4">
          <label class="panellabel1"><input orderfather="<?= $value->orderfather ?>" class="no-uniform ECD_group<?= $value->orderfather ?>" type="checkbox"  <?php if( isset($operation2profile) && in_array($value->idoperation, $operation2profile) ){print "checked";} ?>    onclick="$('.ECD_subgroup<?= $value->orderfather ?>').prop('checked', this.checked).change(); $('.ECD_father<?= $value->orderfather ?>').prop('checked', this.checked).change();$('.ECD_subfatherparent<?= $value->orderfather ?>').prop('checked', this.checked); $('#listitemb2b').prop('checked', false).change()  ;$('#sublistitemb2b').prop('checked', false) ; "></label>
        </td>
      <?php }else ?>
      <?php if($value->level==2){$s = $value->idoperation; ?>
        <td class="paneltabletd4"></td>


        <td  colspan="2" class="paneltabletd5"><?= $lang[$value->description] ?><label class="panellabel1"><input <?php if($value->abbreviation=='listitemb2b'){ echo 'id="listitemb2b"'; }  ?> <?php if($value->abbreviation=='listitem'){ echo 'id="listitem"'; }  ?> orderfather="<?= $value->orderfather ?>" class="no-uniform subgroup<?= $value->orderfather ?>  group2<?= $value->orderfather ?><?= $s ?>" <?php if( isset($operationprofile) && in_array($value->idoperation, $operationprofile) ){print "checked";} ?>  type="checkbox"   onclick="$('.subsubgroup<?= $value->orderfather ?><?= $s ?>').prop('checked', this.checked); $('.father2<?= $value->orderfather ?><?= $s ?>').prop('checked', this.checked); verify(<?= $value->orderfather ?>); "  ></label>


<input <?php if($value->abbreviation=='listitemb2b'){ echo 'id="sublistitemb2b"'; }  ?>   class="no-uniform father2<?= $value->orderfather ?><?= $s ?> subfatherparent<?= $value->orderfather ?>" <?php if( isset($operationprofile) && in_array($value->idoperation, $operationprofile) ){print "checked";} ?> style="display:none" type="checkbox" name="operation[1][]" value="<?= $value->idoperation ?>"  >


<input <?php if($value->abbreviation=='listitemb2b'){ echo 'id="sublistitemb2b"'; }  ?>   class="no-uniform ECD_father2<?= $value->orderfather ?><?= $s ?> ECD_subfatherparent<?= $value->orderfather ?>" <?php if( isset($operation2profile) && in_array($value->idoperation, $operation2profile) ){print "checked";} ?> style="display:none" type="checkbox" name="operation[2][]" value="<?= $value->idoperation ?>"  >




</td>


<td class="paneltabletd4">
  <label class="panellabel1"><input <?php if($value->abbreviation=='listitemb2b'){ echo 'id="listitemb2b"'; }  ?> <?php if($value->abbreviation=='listitem'){ echo 'id="listitem"'; }  ?> orderfather="<?= $value->orderfather ?>" class="no-uniform ECD_subgroup<?= $value->orderfather ?>  ECD_group2<?= $value->orderfather ?><?= $s ?>" <?php if( isset($operation2profile) && in_array($value->idoperation, $operation2profile) ){print "checked";} ?>  type="checkbox"   onclick="$('.ECD_subsubgroup<?= $value->orderfather ?><?= $s ?>').prop('checked', this.checked); $('.ECD_father2<?= $value->orderfather ?><?= $s ?>').prop('checked', this.checked); ECD_verify(<?= $value->orderfather ?>); "  ></label>
</td>
      <?php }else ?>
      <?php if($value->level==3){  ?>
        <td class="paneltabletd6"></td>
        <td class="paneltabletd6"></td>
        <td class="paneltabletd7"><?= $lang[$value->description] ?><label style="float:right"><input type="checkbox" orderfather="<?= $value->orderfather ?>" level="<?= $value->orderfather ?>" class="no-uniform subgroup<?= $value->orderfather ?> subsubgroup<?= $value->orderfather ?><?= $s ?>" <?php if( isset($operationprofile) && in_array($value->idoperation, $operationprofile) ){print "checked";} ?> name="operation[1][]" value="<?= $value->idoperation ?>" onclick="verify2(<?= $value->orderfather ?>, <?= $s ?>)"></label>

        </td>


        <td class="paneltabletd4">
          <label style="float:right"><input type="checkbox" orderfather="<?= $value->orderfather ?>" level="<?= $value->orderfather ?>" class="no-uniform ECD_subgroup<?= $value->orderfather ?> ECD_subsubgroup<?= $value->orderfather ?><?= $s ?>" <?php if( isset($operation2profile) && in_array($value->idoperation, $operation2profile) ){print "checked";} ?> name="operation[2][]" value="<?= $value->idoperation ?>" onclick="ECD_verify2(<?= $value->orderfather ?>, <?= $s ?>)"></label>
        </td>
      <?php } ?>


    </tr>
  <?php } ?>
</table>
</div>
</div>
<?php }else{ ?>
<?= $lang['withoutdata'] ?>
<?php } ?>

<script>
function verify(order){
  if($(".subgroup"+order+":checked").length == $(".subgroup"+order).length && $(".subgroup"+order+":checked").length!=0){
    $(".group"+order).prop("checked", true);
    $(".father"+order).prop("checked", true);
  }else if($(".subgroup"+order+":checked").length==0 && $(".subgroup"+order).length>0){
    $(".group"+order).prop("checked", false);
    $(".father"+order).prop("checked", false);
  }else if($(".subgroup"+order).length==0){

  }else{
    $(".group"+order).prop("checked", false);
    $(".father"+order).prop("checked", true);
  }
}


function ECD_verify(order){
  if($(".ECD_subgroup"+order+":checked").length == $(".ECD_subgroup"+order).length && $(".ECD_subgroup"+order+":checked").length!=0){
    $(".ECD_group"+order).prop("checked", true);
    $(".ECD_father"+order).prop("checked", true);
  }else if($(".ECD_subgroup"+order+":checked").length==0 && $(".ECD_subgroup"+order).length>0){
    $(".ECD_group"+order).prop("checked", false);
    $(".ECD_father"+order).prop("checked", false);
  }else if($(".ECD_subgroup"+order).length==0){

  }else{
    $(".ECD_group"+order).prop("checked", false);
    $(".ECD_father"+order).prop("checked", true);
  }
}



$('#listitemb2b').change(function(e){
  if (e.originalEvent !== undefined)
  {
    if($("#listitem").is(":checked")){
      swal(lang['SELECT_ONE_LIST']);
      $("#listitemb2b").click()  ;
    }

  }
});


$('#listitem').change(function(e){
  if (e.originalEvent !== undefined)
  {
    if($("#listitemb2b").is(":checked")){
      swal(lang['SELECT_ONE_LIST']);
      $("#listitem").click()  ;
    }
  }
});



function verify2(order, s){
  if($(".subsubgroup"+order+s+":checked").length == $(".subsubgroup"+order+s).length && $(".subsubgroup"+order+s+":checked").length!=0){
    $(".group2"+order+s).prop("checked", true);
    $(".father2"+order+s).prop("checked", true);
  }else if($(".subsubgroup"+order+s+":checked").length==0 && $(".subsubgroup"+order+s).length>0){
    $(".father2"+order+s).prop("checked", false);
    $(".group2"+order+s).prop("checked", false);

  }else if($(".subsubgroup"+order+s).length==0){

  }else{
    $(".father2"+order+s).prop("checked", true);
    $(".group2"+order+s).prop("checked", false);

  }
  verify(order);
}



function ECD_verify2(order, s){
  if($(".ECD_subsubgroup"+order+s+":checked").length == $(".ECD_subsubgroup"+order+s).length && $(".ECD_subsubgroup"+order+s+":checked").length!=0){
    $(".ECD_group2"+order+s).prop("checked", true);
    $(".ECD_father2"+order+s).prop("checked", true);
  }else if($(".ECD_subsubgroup"+order+s+":checked").length==0 && $(".ECD_subsubgroup"+order+s).length>0){
    $(".ECD_father2"+order+s).prop("checked", false);
    $(".ECD_group2"+order+s).prop("checked", false);

  }else if($(".ECD_subsubgroup"+order+s).length==0){

  }else{
    $(".ECD_father2"+order+s).prop("checked", true);
    $(".ECD_group2"+order+s).prop("checked", false);

  }
  ECD_verify(order);
}

$( document ).ready(function() {
  var order = '<?php print json_encode($order); ?>';
  order = JSON.parse(order);
  for (var i = 0; i < order.length; i++) {
    verify(order[i]);
    ECD_verify(order[i]);
  }

});

/*
$("tr").hover(function() {
 var elemThis = this;
 if(!$(elemThis).children(":first-child").hasClass("first_level")){
   $(elemThis).closest('tr');
 }
});
*/
</script>
<div class="row" type="hidden" >
			<div class="col-md-12">
				<div class="form-group requireinfo ">
					<label class="control-label"><?php echo $lang["required_fields"]; ?> <span class="symbol required"></span></label>
				</div>
			</div>
	</div>
<input type="hidden" class="form-control valIgnore" name="role[idrole]" value="<?php echo (isset($role)) ? htmlentities($role->idrole, ENT_QUOTES) : ""; ?>" />


<script src="views/assets/js/formElements.js"></script>
