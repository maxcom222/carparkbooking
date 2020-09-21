<?php
if(isset($tpl['price']))
{ 
	$STORE = @$_SESSION[$controller->defaultStore];
	?>
	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
		<div class="form-group">
			<label class="control-label"><?php __('front_promo_code_added');?>:</label>
		</div><!-- /.form-group -->
	</div><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 -->
	
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="form-group">
			<span><strong><?php echo isset($STORE['code']) ? $STORE['code'] : NULL;?></strong> <?php echo $tpl['price']['discount_text'];?> <a href="#" class="pjCpbBtnRemovePromoCode"><?php __('front_remove_promo_code')?></a></span>
		</div><!-- /.form-group -->
	</div><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 -->
	<?php
}else{
	echo '100';
} 
?>