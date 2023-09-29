<?php
require_once('language/common.php');
$actual_link =  isset($_SERVER['QUERY_STRING'])   ? "?" . $_SERVER['QUERY_STRING'] : '';
$full_link =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$js = json_encode($_GET);

/**
 * @name        AMP MVC
 * @package 	amp-PHP
 * @version 	1.0.0
 * @copyright	amp.pt
 * @license		GPL v1.0
 */
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<!--<![endif]-->

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?php echo $lang["description_app"]; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="application-name" content="<?php echo $lang["name_app"]; ?>" />
	<meta name="author" content="<?php echo $lang["sepri"]; ?>" />

	<meta property="og:locale" content="pt_PT" />
	<meta property="og:title" content="<?php echo $lang["name_app"]; ?>" />
	<meta property="og:url" content="https://www.sepri.pt" />


	<link rel="icon" href="views/assets/img/favicon.png" type="image/x-icon">

	<link rel="stylesheet" href="views/assets/plugins/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="views/assets/plugins/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="views/assets/plugins/lineicons/style.css">
	<link rel="stylesheet" href="views/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css">
	<link rel="stylesheet" href="views/assets/plugins/swal/sweetalert2.min.css" />
	<link rel="stylesheet" href="views/assets/css/fonts/style.css">
	<link rel="stylesheet" href="views/assets/css/main.css">
	<link rel="stylesheet" href="views/assets/css/checkbox.css">
	<link rel="stylesheet" href="views/assets/css/custom.css">
	<link rel="stylesheet" href="views/assets/css/main-responsive.css">
	<link rel="stylesheet" href="views/assets/css/theme_light.css" type="text/css" id="skin_color">
	<link rel="stylesheet" href="views/assets/css/print.css" type="text/css" media="print" />

	<!--[if IE 7]>
		<link rel="stylesheet" href="views/assets/plugins/font-awesome/css/font-awesome-ie7.min.css">
		<![endif]-->


	<script>
		var lang_abbr = '<?php echo $lang_abbr; ?>';
	</script>
	<script>
		window.onpopstate = function(event) {
			if (document.referrer == '<?php echo $full_link; ?>') {
				history.go(-2);
			} else {
				history.go(-1);
			}
		};
		history.pushState(<?php echo $js; ?>, document.title, "<?php echo $actual_link; ?>");
	</script>
	<script>
		var lang = '<?php echo json_encode($lang) ?>';
		lang = JSON.parse(lang);
		var month_names = '<?php echo json_encode($month_names); ?>';
		month_names = JSON.parse(month_names);
	</script>
</head>

<body class="footer-fixed ">
	<script type="text/javascript" src="views/assets/plugins/jquery/jquery-3.1.0.min.js"></script>
	<script type="text/javascript" src="views/assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
	<script type="text/javascript" src="views/assets/plugins/bootstrap/js/bootstrap.js"></script>


	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<?php

			require_once("views/layout/_top.php");
			?>
		</div>
	</div>
	<div class="main-container">
		<div class="navbar-content">
			<?php require_once("views/layout/_menu.php"); ?>
		</div>
		<div class="main-content">
			<div class="container">
				<?php
				require_once("views/layout/breadcrumbs.php");
				?>
				<div class="paddingDiv">
					<?php
					if (!empty($view)) {
						require_once($view);
					}
					?>
				</div>
			</div>
		</div>
	</div>


	<div class="footer clearfix">
		<div class="footer-inner">
			<div class="col-lg-12 col-md-12 hidden-sm hidden-xs"><?php echo date("Y"); ?> &copy; <?php echo $lang["name_app_logo"]; ?> | Desenvolvido por <a href="https://www.sepri.pt" target="_blank">sepri.pt</a> - TODOS OS DIREITOS RESERVADOS</div>
			<div class="col-sm-12 col-xs-12 hidden-lg hidden-md "><?php echo date("Y"); ?> &copy; <?php echo $lang["name_app_logo"]; ?></div>
			<div class="col-sm-12 hidden-lg  hidden-md hidden-xs ">Desenvolvido por <a href="https://www.sepri.pt" target="_blank">sepri.pt</a> - TODOS OS DIREITOS RESERVADOS </div>
			<div class="col-xs-12 hidden-lg hidden-md hidden-sm"><a href="https://www.sepri.pt" target="_blank">sepri.pt</a> - TODOS OS DIREITOS RESERVADOS </div>
		</div>
		<div class="footer-items"><span class="go-top"><i class="clip-chevron-up"></i></span></div>
	</div>


	<div id="loading" style="display:none; opacity: 0.9;">
		<div class="mybox" id="loading-image">
			<span id="loadingGeral" style="display:none;"><i class='fa fa-spinner fa-spin '></i> <span id="txtLoad"><?php echo $lang["processing"]; ?></span></span>
		</div>
	</div>

	<script src="views/assets/js/functions.js"></script>
	<script src="views/assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js"></script>
	<script src="views/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js"></script>
	<script src="views/assets/plugins/swal/sweetalert2.min.js"></script>
	<script src="views/assets/js/main.js"></script>
	<script src="views/assets/js/formValidator.js"></script>
	<script type="text/javascript" src="views/assets/js/websocket_invoice.js"></script>


	<script>
		jQuery(document).ready(function() {
			Main.init();
		});


		function runcron(option) {

			Swal.fire({
				title: lang['cronjob_runn'],
				text: lang['cronjob_runn_inf'],
				type: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: lang['yes'],
				cancelButtonText: lang['no']
			}).then((result) => {
				if (result.value) {
					location.href = "?controller=home&action=runcron&option=" + option;
				}
			});

		}
	</script>

</body>

</html>