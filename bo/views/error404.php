<?php require_once('language/common.php'); ?>
<html>
<head>
  <meta charset="UTF-8" />
  <title><?php echo $lang['name_app']; ?></title>
  <script src="views/assets/js/jquery.min.js"></script>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="views/assets/css/bootstrap.min.css" >
  <link rel="stylesheet" href="views/assets/css/bootstrap-theme.css" >

  <!-- Latest compiled and minified JavaScript -->
  <script src="views/assets/js/bootstrap.min.js" ></script>
  <link rel="stylesheet" href="views/assets/css/styles.css" />
</head>
<body>
<div class="jumbotron ">
  <div class="container">
  		<div class="fourOhFour">
  			<img src="views/assets/img/404.png" style="width:100%;max-width:600px;max-height:400px;">
  			<h1><?= $lang['Oops'] ?></h1>


  			<h3 class="pt"><?= $lang['pagenotfound'] ?></h3>
  			<h4><?= $lang['pagenotfounddescription'] ?></h4>
  			<h4><a onclick="window.history.back()" href=""><?= $lang['goback'] ?></a></h4>

  	  	</div>
  	</div>
</div>
</body>
</html>
