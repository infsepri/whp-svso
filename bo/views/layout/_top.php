<?php
$aux1 = Common::target_dir . "logo.png";
$usertype = $_SESSION['entity']['userT'];
$companyname = $_SESSION['entity']['companyname'];
$version = $_SESSION['entity']['version'];
$optiondev = array(1 => "version_prod", 2 => "version_dev");
$identity = $_SESSION['entity']['identity'];
$alluserdev = $_SESSION['entity']['alluserdev'];




if (isset($_SESSION["entity"]["username"])) {
	$nome = $_SESSION["entity"]["username"];
	$temp = explode(" ", $nome);
	$nomeNovo = $temp[0] . " " . $temp[count($temp) - 1];
}
?>
<div class="navbar-header">
	<?php if ($usertype[$_SESSION['defaultprofile']] == 1) { ?>
		<a class="navbar-brand hidden-sm hidden-xs" href="?controller=home&action=index"><?php echo $lang["description_app3"]; ?>
			<?php if (isset($companyname) && !empty($companyname)) { ?>
				<i class="clip-brightness-high"> <?php echo $companyname; ?></i>
			<?php } else { ?>
				<i class="clip-brightness-high"> <?php echo $lang["description_app3"]; ?></i>
			<?php } ?>
		</a>
		<a class="navbar-brand hidden-lg hidden-md" href="?controller=home&action=index"><img alt="" class="iconsm" src="<?php echo $aux1; ?>"></a>
	<?php } elseif ($usertype[$_SESSION['defaultprofile']] == 2) { ?>
		<a class="navbar-brand hidden-sm hidden-xs" href="?controller=doctor&action=index"><?php echo $lang["name_app_logo"]; ?></a>
		<a class="navbar-brand hidden-lg hidden-md" href="?controller=doctor&action=index"><img alt="" class="iconsm" src="<?php echo $aux1; ?>"></a>
	<?php } elseif ($usertype[$_SESSION['defaultprofile']] == 3) { ?>
		<a class="navbar-brand hidden-sm hidden-xs" href="?controller=physiotherapist&action=index"><?php echo $lang["name_app_logo"]; ?></a>
		<a class="navbar-brand hidden-lg hidden-md" href="?controller=physiotherapist&action=index"><img alt="" class="iconsm" src="<?php echo $aux1; ?>"></a>
	<?php } else { ?>


		<a class="navbar-brand hidden-sm hidden-xs" href="?controller=employee&action=index"><?php echo $lang["name_app_logo"]; ?></a>
		<a class="navbar-brand hidden-lg hidden-md" href="?controller=employee&action=index"><img class="iconsm" alt="" src="<?php echo $aux1; ?>"></a>
	<?php } ?>
	<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
		<span class="clip-list-2"></span>
	</button>
</div>

<div class="navbar-tools">







	<ul class="nav navbar-right">

		<?php if (($_SESSION['defaultprofile'] == 0 && Common::checkpermission("management_user", 2) == 2) || $identity == 2) { ?>

			<?php if ((isset($identity) && $identity == 2)) {  ?>
				<li id="header_inbox_bar2" class="dropdown current-user hidden-sm hidden-xs" style="padding-top: 12px!important;">

					<form id="modifyversion_select" method="post" action="?controller=home&action=modifyversion">
						<select onchange="$('#modifyversion_select').submit()" name="version" class="form-control " style="height: 33px!important;">

							<?php foreach ($optiondev as $key => $value) {  ?>

								<option value="<?php echo $key; ?>" <?php echo (isset($version) && $version == $key) ? "selected='selected'" : ""; ?>><?php echo $lang[$value]; ?></option>
								<?php if ($version == 1) {
									echo $lang['version_prod'];
								} else {
									echo $lang['version_dev'];
								}
								?>

								</option>
							<?php } ?>
						</select>

					</form>

				</li>
			<?php } ?>


		<?php } ?>

		<!-- start: MESSAGE DROPDOWN -->
		<li class="dropdown">
			<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#" aria-expanded="false">
				<i class="clip-list-5"></i>
				<span class="badge"> 12</span>
			</a>
			<ul class="dropdown-menu todo">
				<li>
					<span class="dropdown-menu-title"> You have 12 pending tasks</span>
				</li>
				<li>
					<div class="drop-down-wrapper ps-container ps-theme-default" data-ps-id="d8eda92d-db3d-ae71-e71e-f18081263810">
						<ul>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc" style="opacity: 1; text-decoration: none;">Staff Meeting</span>
									<span class="label label-danger" style="opacity: 1;"> today</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc" style="opacity: 1; text-decoration: none;"> New frontend layout</span>
									<span class="label label-danger" style="opacity: 1;"> today</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc"> Hire developers</span>
									<span class="label label-warning"> tommorow</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc">Staff Meeting</span>
									<span class="label label-warning"> tommorow</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc"> New frontend layout</span>
									<span class="label label-success"> this week</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc"> Hire developers</span>
									<span class="label label-success"> this week</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc"> New frontend layout</span>
									<span class="label label-info"> this month</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc"> Hire developers</span>
									<span class="label label-info"> this month</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc" style="opacity: 1; text-decoration: none;">Staff Meeting</span>
									<span class="label label-danger" style="opacity: 1;"> today</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc" style="opacity: 1; text-decoration: none;"> New frontend layout</span>
									<span class="label label-danger" style="opacity: 1;"> today</span>
								</a>
							</li>
							<li>
								<a class="todo-actions" href="javascript:void(0)">
									<i class="fa fa-square-o"></i>
									<span class="desc"> Hire developers</span>
									<span class="label label-warning"> tommorow</span>
								</a>
							</li>
						</ul>
						<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;">
							<div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
						</div>
						<div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px;">
							<div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
						</div>
					</div>
				</li>
				<li class="view-all">
					<a href="javascript:void(0)">
						See all tasks <i class="clip-arrow-right"></i>
					</a>
				</li>
			</ul>


		</li>
		<!-- start: NOTIFICATION DROPDOWN -->
		<li class="dropdown">
			<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
				<i class="clip-notification-2"></i>
				<span class="badge"> 11</span>
			</a>
			<ul class="dropdown-menu notifications">
				<li>
					<span class="dropdown-menu-title"> You have 11 notifications</span>
				</li>
				<li>
					<div class="drop-down-wrapper ps-container">
						<ul>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-primary"><i class="fa fa-user"></i></span>
									<span class="message"> New user registration</span>
									<span class="time"> 1 min</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-success"><i class="fa fa-comment"></i></span>
									<span class="message"> New comment</span>
									<span class="time"> 7 min</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-success"><i class="fa fa-comment"></i></span>
									<span class="message"> New comment</span>
									<span class="time"> 8 min</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-success"><i class="fa fa-comment"></i></span>
									<span class="message"> New comment</span>
									<span class="time"> 16 min</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-primary"><i class="fa fa-user"></i></span>
									<span class="message"> New user registration</span>
									<span class="time"> 36 min</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-warning"><i class="fa fa-shopping-cart"></i></span>
									<span class="message"> 2 items sold</span>
									<span class="time"> 1 hour</span>
								</a>
							</li>
							<li class="warning">
								<a href="javascript:void(0)">
									<span class="label label-danger"><i class="fa fa-user"></i></span>
									<span class="message"> User deleted account</span>
									<span class="time"> 2 hour</span>
								</a>
							</li>
							<li class="warning">
								<a href="javascript:void(0)">
									<span class="label label-danger"><i class="fa fa-shopping-cart"></i></span>
									<span class="message"> Transaction was canceled</span>
									<span class="time"> 6 hour</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-success"><i class="fa fa-comment"></i></span>
									<span class="message"> New comment</span>
									<span class="time"> yesterday</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-primary"><i class="fa fa-user"></i></span>
									<span class="message"> New user registration</span>
									<span class="time"> yesterday</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-primary"><i class="fa fa-user"></i></span>
									<span class="message"> New user registration</span>
									<span class="time"> yesterday</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-success"><i class="fa fa-comment"></i></span>
									<span class="message"> New comment</span>
									<span class="time"> yesterday</span>
								</a>
							</li>
							<li>
								<a href="javascript:void(0)">
									<span class="label label-success"><i class="fa fa-comment"></i></span>
									<span class="message"> New comment</span>
									<span class="time"> yesterday</span>
								</a>
							</li>
						</ul>
						<div class="ps-scrollbar-x-rail" style="width: 270px; display: none; left: 0px; bottom: 3px;">
							<div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div>
						</div>
						<div class="ps-scrollbar-y-rail" style="top: 0px; height: 250px; display: none; right: 3px;">
							<div class="ps-scrollbar-y" style="top: 0px; height: 0px;"></div>
						</div>
					</div>
				</li>
				<li class="view-all">
					<a href="javascript:void(0)">
						See all notifications <i class="clip-arrow-right"></i>
					</a>
				</li>
			</ul>
		</li>
		<!-- end: NOTIFICATION DROPDOWN -->
		<!-- start: MESSAGE DROPDOWN -->
		<li class="dropdown">
			<a class="dropdown-toggle" data-close-others="true" data-hover="dropdown" data-toggle="dropdown" href="#">
				<i class="clip-bubble-3"></i>
				<span class="badge"> 9</span>
			</a>
			<ul class="dropdown-menu posts">
				<li>
					<span class="dropdown-menu-title"> You have 9 messages</span>
				</li>
				<li>
					<div class="drop-down-wrapper ps-container">
						<ul>
							<li>
								<a href="javascript:;">
									<div class="clearfix">
										<div class="thread-image">
											<img alt="" src="./assets/images/avatar-2.jpg">
										</div>
										<div class="thread-content">
											<span class="author">Nicole Bell</span>
											<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
											<span class="time"> Just Now</span>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;">
									<div class="clearfix">
										<div class="thread-image">
											<img alt="" src="./assets/images/avatar-1.jpg">
										</div>
										<div class="thread-content">
											<span class="author">Peter Clark</span>
											<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
											<span class="time">2 mins</span>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;">
									<div class="clearfix">
										<div class="thread-image">
											<img alt="" src="./assets/images/avatar-3.jpg">
										</div>
										<div class="thread-content">
											<span class="author">Steven Thompson</span>
											<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
											<span class="time">8 hrs</span>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;">
									<div class="clearfix">
										<div class="thread-image">
											<img alt="" src="./assets/images/avatar-1.jpg">
										</div>
										<div class="thread-content">
											<span class="author">Peter Clark</span>
											<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
											<span class="time">9 hrs</span>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;">
									<div class="clearfix">
										<div class="thread-image">
											<img alt="" src="./assets/images/avatar-5.jpg">
										</div>
										<div class="thread-content">
											<span class="author">Kenneth Ross</span>
											<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
											<span class="time">14 hrs</span>
										</div>
									</div>
								</a>
							</li>
						</ul>
						<div class="ps-scrollbar-x-rail" style="width: 270px; display: none; left: 0px; bottom: 3px;">
							<div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div>
						</div>
						<div class="ps-scrollbar-y-rail" style="top: 0px; height: 250px; display: none; right: 3px;">
							<div class="ps-scrollbar-y" style="top: 0px; height: 0px;"></div>
						</div>
					</div>
				</li>
				<li class="view-all">
					<a href="pages_messages.html">
						See all messages <i class="clip-arrow-right"></i>
					</a>
				</li>
			</ul>
		</li>
		<!-- end: MESSAGE DROPDOWN -->
		<!-- end: MESSAGE DROPDOWN -->


		<li class="dropdown">
			<?php if ($usertype[$_SESSION['defaultprofile']] == 1) { ?>
				<a href="#" class="dropdown-toggle sb-toggle" data-toggle="tooltip" data-placement="bottom" title="">
				<?php } else { ?>
					<a href="#" class="dropdown-toggle sb-toggle">
					<?php } ?>
					<i class="clip-bubble-3"></i>
					<?php if ($usertype[$_SESSION['defaultprofile']] == 1) { ?>
						<span id="chatMsgBadge" class="badge"></span>
					<?php } else { ?>
						<span id="chatMsgBadge" class="badge">0</span>
					<?php } ?>
					</a>
		</li>

		<li class="dropdown current-user">
			<?php

			$imgUrl = Common::target_dir . "no_image.png";


			if (!empty($_SESSION['entity']["photo"])) {
				$imgUrl = Common::target_dir . $_SESSION['entity']["photo"];
			}
			?>
			<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle " data-close-others="true" href="#">
				<img src="<?php echo $imgUrl; ?>" class="circle-img" style="height: 30px" alt="userPhoto">
				<span class="username"><?php echo $nomeNovo; ?></span>
				<i class="clip-chevron-down"></i>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href="?controller=personaldata&action=changepass">
						<i class="clip-key"></i> <?php echo $lang["MENU_CHANGE_PASS"]; ?>
					</a>
				</li>

				<li class="divider"></li>


				<li>
					<a href="?controller=home&action=destroy">
						<i class="clip-exit"></i> <?php echo $lang["MENU_LOGOUT"]; ?>
					</a>
				</li>
				<?php if (isset($usertype) && $usertype == (1)) { ?>
					<li>
						<a onclick="runcron('1')">
							<i class="fa fa-list"></i> <?php echo $lang["reloadpermission"]; ?>
						</a>
					</li>
				<?php	} ?>
			</ul>
		</li>
	</ul>
</div>


<!-- Modal  ?controller=storeorder&action=create
-->





<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">New message</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Recipient:</label>
						<input type="text" class="form-control" id="recipient-name">
					</div>
					<div class="form-group">
						<label for="message-text" class="col-form-label">Message:</label>
						<textarea class="form-control" id="message-text"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Send message</button>
			</div>
		</div>
	</div>
</div>