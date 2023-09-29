<link href="views/assets/css/main1.css?v=2" rel="stylesheet">
<link href="views/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<link rel="icon" href="views/assets/images/sepri_group.svg" type="image/x-icon">
<div class="row">
  <?php 
   
if(isset($userdata) && !empty($userdata->api)){?>
  <div class="col-md-12 col-lg-12 col-xs-12">
  <div class="alert alert-info">

    <i class="fa fa-info-circle"></i>
    <strong><?php echo $lang["note"]; ?> : </strong> <?php echo $lang["please_conf_password"]; ?> 
    </div>
     </div>
 <?php }?>

 <?php 
 if(isset($typuser)&&  $typuser!=1){ ?>
 <div class="col-md-4">
      <div class="form-group required">
      <label class="control-label"><?php echo $lang["passwordold"]; ?> <span class="symbol required"></span></label><br>
          <input type="password" class="form-control password"  name="entity[passwordold]" id="passwordold"  required data-toggle="popover"  placeholder="<?php echo $lang['passwordold'] ?>">
        </div>
  </div>

  <?php }?>
<div class="col-md-<?php echo isset($typuser)&& $typuser!=1 ? "4" : "6"?>">
      <div class="form-group required">
      <label class="control-label"><?php echo $lang["RESET_PASSWORD_PASS"]; ?> <span class="symbol required"></span></label><br>
          <input type="password" class="form-control password"  name="entity[password]" id="password"  required data-toggle="popover"  placeholder="<?php echo $lang['RESET_PASSWORD_PASS'] ?>">
        </div>
  </div>
  <div class="col-md-<?php echo isset($typuser)&& $typuser!=1 ? "4" : "6"?>">
      <div class="form-group required">
      <label class="control-label"><?php echo $lang["RESET_PASSWORD_CONFIRMPASS"]; ?> <span class="symbol required"></span></label><br>
         <input type="password" class="form-control password"  name="entity[confirm]" id="confirm"  required data-toggle="popover"  placeholder="<?php echo $lang['RESET_PASSWORD_CONFIRMPASS'] ?>">
         <label class="label label-danger messagedisplaynone" id="passwordinvalid" ><?php echo $lang['PASSWORD_NOT_SAME']; ?></label>
        </div>
  </div>


  

  
  </div>

 <input type="hidden" class="form-control valIgnore" name="entity[identity]" value="<?php echo (isset($identity)) ? htmlentities($identity, ENT_QUOTES) : ""; ?>" />
<script src="views/assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="views/assets/plugins/select2/dist/js/i18n/<?php echo $lang_abbr; ?>.js"></script>
<script src="views/assets/js/formElements.js"></script>
<script src="views/assets/js/checkPassword.js"></script>
