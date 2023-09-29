<div class="main-navigation navbar-collapse collapse">
	<div class="navigation-toggler"><i class="clip-chevron-left"></i><i class="clip-chevron-right"></i></div>

	<ul class="main-navigation-menu">
		<?php 
		if(isset($_SESSION['entity']['menu'])){
		
			$s = 0; $order = array();
			//if(!is_array($_SESSION['entity']['permission'])){ $_SESSION['entity']['permission']=array();}
		
			foreach ($_SESSION['entity']['menu'] as $key => $value) {
				array_push($order, $value->orderfather );
				
			//	if(count($_SESSION['entity']['permission'])==0 || in_array($value->abbreviation, $_SESSION['entity']['permission'])){
						
					if($value->functionality!=null){
					
						$value_functionality = explode(";", $value->functionality);
						$countfun = count($value_functionality);
						$countfunsum = 0;
						while($countfunsum<$countfun){
							if($value_functionality!=null && !in_array($value_functionality[$countfunsum], $_SESSION['functionality'])){
								continue 2;
							}
							$countfunsum++;
						}
					}
					$icon = (!empty($value->menuclass)) ? '<i class="'.$value->menuclass.'"></i>' : "";
					
					$open = Common::getmenuActive(0, $value->menuative);
					if (!(isset($value->submenu) && $value->submenu!=null)) {
						
					?>
						<li class="<?php echo $open; ?>">
							<a href="<?php echo $value->url; ?>">
								<?php echo $icon;?>
								<span class="title"> <?php echo $lang[$value->description]; ?></span>
							</a>
						</li>
					<?php 
					}
					else {
					
					?>
						<li class="<?php echo $open; ?>">
							<a href="javascript:;">
								<?php echo $icon;?>
								<span class="title"><?php echo $lang[$value->description]; ?></span>
								<i class="icon-arrow"></i><span class="selected"></span>
							</a>
							<ul class="sub-menu noMargin">
								<?php 
								foreach ($value->submenu as $k => $v) {
									if(isset($v->functionality) && $v->functionality!=null && !empty($v->functionality)){
										
										$v_functionality = explode(";", $v->functionality);
										$countfun = count($v_functionality);
										$countfunsum = 0;
										while($countfunsum<$countfun){
											if($v_functionality!=null && !in_array($v_functionality[$countfunsum], $_SESSION['functionality'])){
												continue 2;
											}
											$countfunsum++;
										}
									}
									$open2 = Common::getmenuActive(0, $v->menuative);
									$open2_1 = Common::getmenuActive(1, $v->menuative);
									if(!(isset($v->submenu) && $v->submenu!=null )) {
										
									?>
										<li class="<?php echo $open2; ?>"><a href="<?php echo $v->url; ?>"><?php echo $lang[$v->description]; ?></a></li>
									<?php  
									}
									else {
									
									?>
										<li class="<?php echo $open2; ?>">
											<a href="javascript:;">
												<span class="title"><?php echo $lang[$v->description]; ?></span>
												<i class="icon-arrow"></i><span class="selected"></span>
											</a>
											<ul class="sub-menu" style="<?php echo $open2_1; ?>">
												<?php
												foreach ($v->submenu as $kv => $v1) {
													$open3 = Common::getmenuActive(0, $v1->menuative);
													
													if($v1->functionality!=null){
										
														$v1_functionality = explode(";", $v1->functionality);
														$countfun = count($v1_functionality);
														$countfunsum = 0;
														while($countfunsum<$countfun){
															if($v1_functionality!=null && !in_array($v1_functionality[$countfunsum], $_SESSION['functionality'])){
																continue 2;
															}
															$countfunsum++;
														}
													}
													?>
													<li class="<?php echo $open3; ?>"><a href="<?php echo $v1->url; ?>"><?php echo $lang[$v1->description]; ?></a></li>
												<?php
												}
												?>
											</ul>
										</li>
								<?php  
									}
								}
								?>
							</ul>
						</li>
					<?php
					}
				//}
			}
		} 
		?>
	</ul>
</div>
