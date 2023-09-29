<div class="row">
	<div class="col-sm-12">
	<?php if(isset($breadcrumbs)){ ?>
		<ol class="breadcrumb">
			<?php 
			foreach ($breadcrumbs as $key => $value) { 
				$bread=(object)$value;
				$lastClass =""; $icon="";$txt = "";$breadTxt = "";
				if($key+1==count($breadcrumbs) || (isset($bread->active) && $bread->active=='1')){
					$lastClass ="active";
				}
				
				if($key==0 && isset($bread->icon)) {
					$icon="<i class='".$bread->icon."'></i> ";
				}
				
				$breadTxt = (empty($bread->msg)) ? $bread->text : $lang[$bread->msg];
				if(!empty($bread->msg) && !empty($bread->text)) {
					$breadTxt .= ": ".$bread->text;
				}
				if(!empty($bread->msg) && !empty($bread->textOf)) {
					$breadTxt .= " ".$lang["of"]." ".$bread->textOf;
				}
				if(!empty($bread->url)) {
					$breadTxt = '<a href="'.$bread->url.'">'.$breadTxt.'</a>';
				}
				
			?>
				<li class="<?php echo $lastClass; ?>"><?php echo $icon.$breadTxt; ?></li>
			<?php
			}
			?>	
		</ol>
	<?php } ?>
	</div>
</div>