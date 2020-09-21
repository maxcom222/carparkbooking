<p class="pjCpbFormSectionTitle"><?php __('front_price');?>:</p><!-- /.pjCpbFormSectionTitle -->
	<?php
	if(!isset($STORE))
	{
		$STORE = @$_SESSION[$controller->defaultStore];
	}
	?>
	<input type="hidden" name="rental_price" value="<?php echo $tpl['price']['rental_price'];?>" />
	<input type="hidden" name="extra_price" value="<?php echo isset($tpl['price']['extra_compilation']) ? (!empty($tpl['price']['extra_compilation']) ? $tpl['price']['extra_compilation']['extra_price'] : 0) : 0;?>" />
	<input type="hidden" name="sub_total" value="<?php echo !(isset($STORE['code']) && !empty($STORE['code'])) ? $tpl['price']['price'] : $tpl['price']['price_after_discount'];?>" />
	<input type="hidden" name="tax" value="<?php echo $tpl['price']['tax'];?>" />
	<input type="hidden" name="total" value="<?php echo $tpl['price']['total'];?>" />
	<input type="hidden" name="deposit" value="<?php echo $tpl['price']['deposit'];?>" />
	<input type="hidden" name="discount" value="<?php echo $tpl['price']['price_before_discount'] - $tpl['price']['price_after_discount'];?>" />
	
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<p>
				<strong><?php __('front_car_space');?>:</strong>
			</p>
		</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<p class="pjCpbFinalPrice">
				<strong><?php echo $tpl['price']['rental_price_formatted'];?></strong>
			</p>
		</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

	</div><!-- /.row -->
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<p>
				<span>&nbsp;&nbsp;&nbsp;&nbsp;<?php __('front_rental_days');?></span>
			</p>
		</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<p class="pjCpbFinalPrice">
				<span><?php echo $tpl['rental_days'];?></span>
			</p>
		</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

	</div><!-- /.row -->
	<?php
	if(!empty($tpl['extra_arr']))
	{
		?>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p>
					<strong><?php __('front_extras');?>:</strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->
		</div><!-- /.row -->
		<?php
		foreach($tpl['extra_arr'] as $extra)
		{
			?>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<p>
						<span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo pjSanitize::html($extra['name']);?></span>
					</p>
				</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<p class="pjCpbFinalPrice">
						<strong><?php echo pjUtil::formatCurrencySign(number_format($extra['price'], 2), $tpl['option_arr']['o_currency']);?><small> <?php echo pjSanitize::html($extra['per_text']);?><?php echo $extra['qty'] > 1 ? ' x ' . $extra['qty'] : null;?></small></strong>
					</p>
				</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

			</div><!-- /.row -->
			<?php
		}
		?>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p>
					<span>&nbsp;</span>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p class="pjCpbFinalPrice">
					<strong><?php echo pjUtil::formatCurrencySign(number_format($tpl['extra_price'], 2), $tpl['option_arr']['o_currency']);?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

		</div><!-- /.row -->
		<?php
	}
	if(isset($STORE['code']) && !empty($STORE['code']))
	{ 
		?>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p>
					<strong><?php __('front_sub_total');?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p class="pjCpbFinalPrice pjCpbPriceStrike">
					<strong><?php echo $tpl['price']['price_before_formatted'];?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->
		</div><!-- /.row -->
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p>
					<strong>&nbsp;</strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p class="pjCpbFinalPrice">
					<strong><?php echo $tpl['price']['price_after_formatted'];?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->
		</div><!-- /.row -->
		<?php
	}else{
		?>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p>
					<strong><?php __('front_sub_total');?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p class="pjCpbFinalPrice">
					<strong><?php echo $tpl['price']['price_formatted'];?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->
		</div><!-- /.row -->
		<?php
	}
	if($tpl['price']['tax'] > 0)
	{
		?>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p>
					<strong><?php __('front_tax');?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p class="pjCpbFinalPrice">
					<strong><?php echo $tpl['price']['tax_formatted'];?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->
		</div><!-- /.row -->
		<?php
	}
	?>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<p>
				<strong><?php __('front_total');?></strong>
			</p>
		</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<p class="pjCpbFinalPrice">
				<strong><?php echo $tpl['price']['total_formatted'];?></strong>
			</p>
		</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->
	</div><!-- /.row -->
	<?php
	if($tpl['price']['deposit'] > 0)
	{
		?>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p>
					<strong><?php __('front_deposit');?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<p class="pjCpbFinalPrice">
					<strong><?php echo $tpl['price']['deposit_formatted'];?></strong>
				</p>
			</div><!-- /.col-lg-4 col-md-4 col-sm-4 col-xs-12 -->
		</div><!-- /.row -->
		<?php
	}
	?>