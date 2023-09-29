<link href="views/assets/css/main1.css?v=2" rel="stylesheet">
<link href="views/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<link href="views/assets/plugins/multiselected/css/bootstrap-multiselect.css" rel="stylesheet" />
<link href="views/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<!-- Fileupload -->
<?php /*<link href="views/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css" rel="stylesheet"/>*/ ?>

<link href="views/assets/plugins/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="views/assets/plugins/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>

<script src="views/assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>

<script src="views/assets/plugins/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>

<script src="views/assets/plugins/bootstrap-fileinput/js/fileinput.js"></script>

<script src="views/assets/plugins/bootstrap-fileinput/themes/fa/theme.js"></script>

<script src="views/assets/plugins/bootstrap-fileinput/js/locales/<?= $lang_abbr ?>.js"></script>

<div class="row">
  <div class="col-xs-12">
    <?php require_once('conf/alertMessage.php'); ?>
  </div>
</div>
<?php if (($fieldblock) == true) { ?>
  <div class="row rowMargin">
    <div class="col-md-8"></div>

    <div class="col-md-4">
      <?php if ($_GET['controller'] != "personaldata") { 
        if  ($usertype[$_SESSION['defaultprofile']] == 1) {?>

        <a class="btn btn-primary btn-squared pull-right" role="button" href="?controller=admin&action=edit&id=<?php print $_GET['id'] ?>&a=1&t=<?php print $_GET['t'] ?>"><i class="fa fa-edit"></i> <?php echo $lang["MENU_EDIT"] ?></a>

      <?php }
        } else { ?>

        <a class="btn btn-blue btn-squared pull-right" role="button" href="?controller=personaldata&action=index&a=1"><i class="fa fa-edit"></i> <?php echo $lang["MENU_EDIT"] ?></a>
      <?php } ?>
    </div>
  </div>
<?php } ?>
<?php
$anchor = isset($_GET['tab']) ? $_GET['tab'] : 'panel_overview';
?>
<div class="tabbable">
  <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
    <li class="<?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview') ? 'active' : ''; ?> ">
      <a data-toggle="tab" href="#panel_overview">
        <?php echo $lang["identityadmin"]; ?>
      </a>
    </li>

  </ul>
  <div class="tab-content">

    <div id="panel_overview" class="tab-pane   <?php echo (!isset($anchor) || empty($anchor) || $anchor == 'panel_overview') ? 'in active' : ''; ?> ">

      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">

            <div class="col-lg-3  col-md-3 col-xs-12">
              <div class="form-group">
                <label class="control-label"><?php echo $lang["usertype"]; ?> <span class="symbol required"></span></label>
              
                  <select class="form-control selected2 selected2_p valRequired" id="identitytype"  onchange="openrole();"  data-url="?controller=entitytypes&action=getidentifytypes" <?php echo ($fieldblock) ? 'disabled' : '' ?> data-placeholder="<?php echo $lang["choose"]; ?>" autocomplete="off" name="admin[identitytype]" style="width: 100%;">
                    <option value=""></option>
                    <?php
                    if (isset($entitytype)) { ?>
                      <option value="<?php echo $entitytype->identitytype; ?>" selected='selected'><?php echo $entitytype->name; ?></option>
                    <?php
                    }
                    ?>
                  </select>
            
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
              <div class="form-group text-center">
                <?php if (($fieldblock) == false) { ?>
                  <label class="control-label"><?php echo $lang["photo"]; ?><sup><?= $lang['sizeimguser'] ?></sup></label>
                  <input id="manual" type="file" accept="jpg,png" class=" generateFile file_ form-control valRequired valIgnore  files1" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="entitylogo[adminlogo]" data-preview-file-type="text" data-repattr="name" />
                <?php  } else { ?>

                  <img src="<?php $aux = Common::target_dir . "no_image.png";
                            echo (isset($admin) && $admin->photo != NULL) ? Common::target_dir . $admin->photo :  $aux; ?>" class="img-perfil" alt="userPhoto">
                <?php } ?>

              </div>
            </div>

            <div class="col-lg-9 col-sm-12  col-md-8 col-xs-12">
              <div class="row">
                <div class="col-lg-5  col-sm-12  col-md-5 col-xs-12">
                  <div class="form-group required">
                    <label class="control-label"><?php echo $lang["name"]; ?> <span class="symbol required"></span></label>
                    <input type="text" class="form-control valRequired" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[name]" value="<?php echo (isset($admin)) ? htmlentities($admin->name, ENT_QUOTES) : ""; ?>" />
                  </div>
                </div>
                <div class="col-lg-3  col-md-3 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["mobilephone"]; ?> <span class="symbol required"></span></label>
                    <input type="text" class="form-control  valRequired valNumber" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[mobilephone]" value="<?php echo (isset($admin)) ? htmlentities($admin->mobilephone, ENT_QUOTES) : ""; ?>" />
                  </div>
                </div>
                <div class="col-lg-4  col-md-4 col-xs-12">
                  <div class="form-group required">
                    <label class="control-label"><?php echo $lang["email"]; ?> <span class="symbol required"></span></label>
                    <input type="email" class="form-control  valRequired valEmail" <?php echo ($fieldblockem) ? 'disabled' : '' ?> name="admin[email]" value="<?php echo (isset($admin)) ? htmlentities($admin->email, ENT_QUOTES) : ""; ?>" onchange="checkusername()" onkeyup="checkusername()" id="user_username" />
                    <label class="label label-danger messagedisplaynone" id="msgusernameexist"><?php echo $lang['msgusernameexist']; ?></label>

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4  col-md-4 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["address"]; ?><span class="symbol required"></span> </label>
                    <input type="text" class="form-control  valRequired" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[address]" value="<?php echo (isset($admin)) ? htmlentities($admin->address, ENT_QUOTES) : ""; ?>" />
                  </div>
                </div>
                <div class="col-lg-2  col-md-2 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["postalcode"]; ?></label>
                    <input type="text" id="postalcode" class="form-control  postalcode postalcode2" identify="2" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[postalcode]" value="<?php echo (isset($admin)) ? htmlentities($admin->postalcode, ENT_QUOTES) : ""; ?>" />
                  </div>
                </div>
                <div class="col-lg-3  col-md-3 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["locality"]; ?></label>
                    <input type="text" id="locality" class="form-control locality locality2  " identify="2" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[locality]" value="<?php echo (isset($admin)) ? htmlentities($admin->locality, ENT_QUOTES) : ""; ?>" />
                  </div>
                </div>
                <div class="col-lg-3  col-md-3 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["district"]; ?></label>
                    <select class="form-control selected2 selected2_p district_local district_local2  " identify="2" data-url="?controller=distritct&action=getdistritct" data-placeholder="<?php echo $lang["choose"]; ?>" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[iddistrict]" style="width: 100%;">
                      <option value=""></option>
                      <?php
                      if (isset($district)) { ?>
                        <option value="<?php echo $district->iddistrict; ?>" selected='selected'><?php echo $district->description; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">

                <div class="col-lg-3  col-md-3 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["country"]; ?> </label><br>
                    <select class="form-control selected2 selected2_p  country_local2" id="country" data-url="?controller=country&action=getcountry" data-placeholder="<?php echo $lang["choose"]; ?>" <?php echo ($fieldblock) ? 'disabled' : '' ?> name=" admin[idcountry]" style="width: 100%;">
                      <option value=""></option>
                      <?php
                      if (isset($country) && !empty($country)) { ?>
                        <option value="<?php echo $country->idcountry; ?>" selected='selected'><?php echo $country->description; ?></option>
                      <?php
                      } else { ?>
                        <option value="<?php echo $lang["ptcod"]; ?>" selected='selected'><?php echo $lang["pt"]; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-2  col-md-2 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["placeofbirth"]; ?></label>
                    <input maxlength="100" type="text" class="form-control elem_datepicker_datetime" id="placeofbirth" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[placeofbirth]" value="<?php echo (isset($admin) && !empty($admin->placeofbirth)) ? htmlentities(date('Y-m-d', strtotime($admin->placeofbirth)), ENT_QUOTES) : ""; ?>">
                  </div>
                </div>
                <div class="col-lg-2  col-md-2 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["numberhelth"]; ?> <span class="symbol required"></span></label>
                    <input type="text" class="form-control valRequired " <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[numberhelth]" id="numberhelth" onchange="checknumberhelth()" onkeyup="checknumberhelth()" value="<?php echo (isset($admin)) ? htmlentities($admin->numberhelth, ENT_QUOTES) : ""; ?>" />
                    <label class="label label-danger messagedisplaynone" id="msgnumberhelthexist"><?php echo $lang['msgnumberhelthexist']; ?></label>
                  </div>
                </div>
                <div class="col-lg-2  col-md-2 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["genre"]; ?></label><br>
                    <?php
                    foreach ($this->genre as $k => $ityp) {
                    ?>
                      <div class="awradio radio-inline radio-success">
                        <input <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[genre]" type="radio" value="<?php echo $k; ?>" <?php echo (isset($admin) && $admin->genre == $k) ? "checked='checked'" : "2"; ?>>
                        <label> <?php echo $lang[$ityp]; ?> </label>
                      </div>

                    <?php } ?>
                  </div>
                </div>

                <div class="col-lg-3 col-md-3 col-xs-12  role_other">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["role"]; ?></label>
                    <select data-allowclear="true" class="form-control select_multiple_init" data-placeholder="<?php echo $lang["choose"]; ?>" name="admin[idrole][]" id="idrole" multiple="multiple">
                      <?php
                      if (isset($role) && !empty($role)) {
                        foreach ($role as $r) {
                          $check = (isset($admin) && isset($adminrole) && is_array($adminrole) && in_array($r->idrole, $adminrole)) ? "selected='selected'" : "";
                      ?>
                          <option data-id="<?php echo $r->idrole; ?>" value="<?php echo $r->idrole; ?>" <?php echo $check; ?>><?php echo $r->name; ?></option>
                      <?php }
                      } ?>

                    </select>
                  </div>
                </div>

              </div>





              <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                  <div class="form-group">
                    <label class="control-label"><?php echo $lang["obsservation"]; ?> </label>
                    <textarea class="form-control limited autosize" maxlength="200" <?php echo ($fieldblock) ? 'disabled' : '' ?> name="admin[obs]"><?php echo (isset($admin)) ? htmlentities($admin->obs, ENT_QUOTES) : ""; ?></textarea>
                  </div>
                </div>
              </div>


            </div>








          </div>
        </div>
      </div>
      <!----- -->

      <?php if (($fieldblock) == false) { ?>
        <div class="row" type="hidden">
          <div class="col-md-12">
            <div class="form-group requireinfo ">
              <label class="control-label"><?php echo $lang["required_fields"]; ?> <span class="symbol required"></span> </label>
            </div>
          </div>
        </div>
      <?php } ?>

    
      <input type="hidden" class="form-control valIgnore" name="admin[identity]" value="<?php echo (isset($admin)) ? htmlentities($admin->identity, ENT_QUOTES) : ""; ?>" />
  

      <script src="views/assets/js/signup_nifcompany.js"></script>
      <script src="views/assets/js/validationVat.js"></script>
      <script src="views/assets/js/user_checkusername_password-in.js?v=4"></script>
      <script src="views/assets/js/postalcode.js?v=4"></script>

      

      

      <script src="views/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>
      <script src="views/assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.js"></script>
      <script src="views/assets/plugins/autosize/jquery.autosize.min.js"></script>
      <script src="views/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
      <script src="views/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.pt.js"></script>
      <script src="views/assets/plugins/multiselected/js/bootstrap-multiselect.js"></script>
      <script src="views/assets/plugins/select2/dist/js/select2.min.js"></script>
      <script src="views/assets/plugins/select2/dist/js/i18n/<?php echo $lang_abbr; ?>.js"></script>

      <script>
        var configs = {};
        configs.maxFileSize = 240;
        configs.initialPreviewAsData = false;
        configs.showUpload = false;
      </script>
      <?php if (isset($admin) && $admin->photo != null) { ?>
        <script>
          var files_input_load = [];
          var obj = {};
          obj.caption = '<?= $admin->photo ?>';
          obj.size = '<?= filesize(Common::target_dir . $admin->photo) ?>';
          obj.url = '<?= "?controller=admin&action=removephoto&id=" . $admin->identity ?>';
          obj.key = 1;

          files_input_load['manual'] = [configs, [
            ['<img src="<?= Common::target_dir . $admin->photo ?>" style="max-width:100%; ">', obj]
          ]];
        </script>
      <?php } else { ?>
        <script>
          var files_input_load = [];
          files_input_load['manual'] = [configs];
        </script>
      <?php } ?>
      <?php if (isset($role)) { ?>
        <script>
          var multiValues = [];
          multiValues["idrole"] = '<?= json_encode((array)$role)   ?>';
          multiValues["idrole"] = JSON.parse(multiValues["idrole"]);
        </script>
      <?php  } ?>
      <script src="views/assets/js/formElements.js"></script>
      <script src="views/assets/js/inputcall.js"></script>

      <script>
	       jQuery(document).ready(function() {
		<?php //Editar
	      if (isset($entitytype->identitytype) && $_GET['action'] == "edit" && $entitytype->identitytype == 1) { ?>
	      	$('.role_other').show();
	   
	      <?php	} else { ?>
	      	$('#idrole').removeClass("valRequired");
	      	$('.role_other').hide();
	      <?php } ?>
	   });
</script>