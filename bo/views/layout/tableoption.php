<div class="row">
	<div class="col-xs-12">
		<form class="form-inline">
		<div class="tableSelect form-group pull-left ">
			<label><?php echo $lang["table_show"];?></label>
			<select class="form-control select2 select2_basic_search elementshowtable" onchange="tablenumbers(this)" style="width: 100%;" >
				<option <?php if($_SESSION['entity']['numberresults']==5){print "selected";} ?> val="5">5</option>
				<option <?php if($_SESSION['entity']['numberresults']==10){print "selected";} ?> val="10">10</option>
				<option <?php if($_SESSION['entity']['numberresults']==25){print "selected";} ?> val="25">25</option>
				<option <?php if($_SESSION['entity']['numberresults']==50){print "selected";} ?> val="50">50</option>
				<option  <?php if($_SESSION['entity']['numberresults']==100){print "selected";} ?> val="100">100</option>
			</select>
			<label><?php echo $lang["table_results"];?></label>
		</div>
		<?php if(!isset($input_query)){ ?>
		<div  class=" pull-right">
			<div class="form-group">
				<label><?php echo $lang["search"];?></label>
				<input class="query form-control" name="text" type="text" value="" >
			</div>
		</div>
		<?php } ?>
		</form>
		
	</div>
</div>
<br/>
