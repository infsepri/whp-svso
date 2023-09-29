<!-- /**
 * @name        SEPRI
 * @version 	v1.0.x
 * @copyright	SEPRI.pt
 */ -->
 <!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<?php
 require_once('language/common.php');

  ?>

	<head>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SEPRI.PT">
    <meta name="author" content="sepri.pt">
    <meta name="keyword" content="Software, Worker Health and Perfomance">

    <title>Worker Health and Perfomance</title>

    <!-- Bootstrap core CSS -->
    <link href="views/assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="views/assets/plugins/font-awesome/css/all.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="views/assets/css/main.css" rel="stylesheet">
    <link href="views/assets/css/styles.css" rel="stylesheet">
    <link href="views/assets/css/main-responsive.css" rel="stylesheet">
    <script src="views/assets/js/jquery.js"></script>
    <link rel="icon" href="views/assets/img/favicon.png" type="image/x-icon">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	</head>
	<body  >






 <div id="login-page">

<div class="container">

  <form class="form-login" action="?controller=home&action=changepassword" method="POST"  role="form" >
      <h2 class="form-login-heading"><?php echo $lang['creat_new_passa']; ?></h2>
            <?php require_once('conf/alertMessage.php'); ?>
      <div class="login-wrap">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                      <label class="control-label"><?php echo $lang["RESET_PASSWORD_PASS"]; ?></label>
                          <span class="input-icon">
                            <input type="password" class="form-control password" name="password" id="password"  required data-toggle="popover"  placeholder="<?php echo $lang['RESET_PASSWORD_PASS'] ?>">
                          </span>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                      <label class="control-label"><?php echo $lang["RESET_PASSWORD_CONFIRMPASS"]; ?></label>
                          <span class="input-icon">
                              <input type="password" class="form-control password" name="confirm" id="confirm"  required data-toggle="popover"  placeholder="<?php echo $lang['RESET_PASSWORD_CONFIRMPASS'] ?>">
                          </span>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-6">
                      <a href="index.php">
                          <button class="btn btn-danger btn-block"  type="button"><i class="fa fa-angle-double-left"></i> <?php echo $lang["cancel"];?></button>
                      </a>

                  </div>
                  <div class="col-md-6">
                  <button class="btn btn-theme btn-block" id="submit" type="submit"><i class="fa fa-angle-double-right"></i> <?php echo $lang["RESET_PASSWORD_SUBMIT"];?></button>
                   <input type="hidden" class="form-control" name="token" id="token"  value="<?php echo $_GET['password_reset'] ?>">
                  </div>
              </div>
      </div>


      <div class="copyright text-center"><?php echo date("Y");?> &copy; Worker Health and Perfomance by <a href="https://www.sepri.pt" target="_blank">sepri.pt</a>
    </form>

</div>
</div>









 <script src="views/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>-->
<script src="views/assets/js/checkPassword.js"></script>


	</body>
</html>
