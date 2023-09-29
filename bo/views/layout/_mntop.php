<div class="navbar-header">
	<a class="navbar-brand" href="#"><?php echo $lang["name_app_logo"]; ?></a>
</div>
<?php $aux=Common::target_dir."no_image.png"; ?>
<div class="navbar-tools ">
	<ul class="nav navbar-right">
		<li class="dropdown current-user">
			<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
				<img src="<?php echo (isset($_SESSION['entity']["photo"])!=NULL) ? Common::target_dir.$_SESSION['entity']["photo"] :  $aux ?>" class="circle-img" style="height: 30px" alt="userPhoto">
				<span class="username"><?php echo $_SESSION['entity']["username"]; ?></span>
				<i class="clip-chevron-down"></i>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href="?controller=home&action=changepass">
						<i class="clip-key"></i> <?php echo $lang["MENU_CHANGE_PASS"]; ?>
					</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="?controller=home&action=destroy">
						<i class="clip-exit"></i> <?php echo $lang["MENU_LOGOUT"]; ?>
					</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
