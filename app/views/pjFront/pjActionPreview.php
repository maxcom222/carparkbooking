<div class="container-fluid pjCpbContainer">
	<div class="panel panel-default pjCpbPanel">
		<div class="panel-heading clearfix pjCpbHead">
			<h1 class="panel-title pull-left pjCpbTitle"><?php __('front_review_confirm')?></h1><!-- /.panel-title pull-left pjCpbTitle -->
			<div class="btn-group pull-right pjCpbLanguage">
				<?php
				include_once PJ_VIEWS_PATH . 'pjFront/elements/locale.php';
				?>
			</div><!-- /.btn-group pull-right pjCpbLanguage -->
		</div><!-- /.panel-heading clearfix pjCpbHead -->
		<?php
		if($tpl['status'] == 'OK')
		{ 
			?>
			<div class="panel-body pjCpbBody">
				<?php
				
				$STORE = @$_SESSION[$controller->defaultStore];
				$FORM = @$_SESSION[$controller->defaultForm];
				
				$from = date($tpl['option_arr']['o_date_format'], strtotime($STORE['from'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($STORE['from']));
				$to = date($tpl['option_arr']['o_date_format'], strtotime($STORE['to'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($STORE['to']));
							
				?>
				<p class="pjCpbFromPrev">
					<?php
					if($tpl['rental_days'] != 1)
					{ 
						echo sprintf(__('front_date_range', true), $tpl['rental_days'], $from, $to);
					}else{
						echo sprintf(__('front_date_range_singluar', true), $tpl['rental_days'], $from, $to);
					}
					?>
					<a href="#" class="btn-link pjCpbChangeDates"><?php __('front_change_dates');?></a>
				</p><!-- /.pull-left pjCpbFromPrev -->
				<p class="pjCpbFromPrev">
					<?php __('front_space');?>: <?php echo pjSanitize::html($tpl['space']['name']);?>
					<a href="#" class="btn-link pjCpbChangeSpace"><?php __('front_change');?></a>
				</p><!-- /.pjCpbFromPrev -->
				<?php
				if(!empty($tpl['extras']))
				{ 
					?>
					<p class="pjCpbFromPrev">
						<?php __('front_extras');?>: <?php echo join("; ", $tpl['extras']);?>
						<a href="#" class="btn-link pjCpbChangeExtras" data-space_id="<?php echo $tpl['space']['id'];?>"><?php __('front_change');?></a>
					</p><!-- /.pjCpbFromPrev -->
					<?php
				} 
				?>
			</div><!-- /.panel-body pjCpbBody -->
			<form id="pjCpbPreviewForm_<?php echo $_GET['index']?>" action="#" method="post">
				<input type="hidden" name="cp_preview" value="1" />
				<ul class="list-group pjCpbForm pjCpbFormConfirm">
					<li class="list-group-item pjCpbFormSection">
						<p class="pjCpbFormConfirmTitle"><?php __('front_read_booking_review')?></p><!-- /.pjCpbFormConfirmTitle -->
						
						<p class="pjCpbFormSectionTitle"><?php __('front_personal_details');?></p><!-- /.pjCpbFormSectionTitle -->
						
						<?php
						if (in_array((int) $tpl['option_arr']['o_bf_include_title'], array(2,3)))
						{
							$personal_titles = __('personal_titles', true);
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_title'); ?>:</dt>
								<dd><?php echo isset($FORM['c_title']) ? $personal_titles[$FORM['c_title']] : null;?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_name'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_name'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_name']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_email'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_email'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_email']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_phone'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_phone'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_phone']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_company'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_company'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_company']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_address'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_address'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_address']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_country'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_country'); ?>:</dt>
								<dd><?php echo isset($tpl['country_arr']) ? $tpl['country_arr']['country_title'] : null; ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_state'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_state'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_state']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_city'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_city'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_city']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_zip'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_zip'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_zip']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_notes'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_notes'); ?>:</dt>
								<dd><?php echo pjSanitize::html(nl2br(@$FORM['c_notes'])); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						ob_start();
						if (in_array((int) $tpl['option_arr']['o_bf_include_regno'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_regno'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_regno']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_model'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_model'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_model']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_make'], array(2,3)))
						{
							?>
							<dl class="dl-horizontal">
								<dt><?php __('front_make'); ?>:</dt>
								<dd><?php echo pjSanitize::html(@$FORM['c_make']); ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
						}
						$ob_fields = ob_get_contents();
						ob_end_clean();
						?>
					</li><!-- /.list-group-item pjCpbFormSection -->
					<?php
					if(!empty($ob_fields))
					{ 
						?>
						<li class="list-group-item pjCpbFormSection">
							<p class="pjCpbFormSectionTitle"><?php __('front_car_details');?></p><!-- /.pjCpbFormSectionTitle -->
			
							<dl class="dl-horizontal">
								<?php echo $ob_fields;?>
							</dl><!-- /.dl-horizontal -->
						</li><!-- /.list-group-item pjCpbFormSection -->
						<?php
					}
					if ($tpl['option_arr']['o_payment_disable'] == 'No')
					{ 
						$payment_methods = __('payment_methods', true);
						$cc_types = __('cc_types', true);
						?>
						<li class="list-group-item pjCpbFormSection">
							<p class="pjCpbFormSectionTitle"><?php __('front_payment_details');?></p><!-- /.pjCpbFormSectionTitle -->
			
							<dl class="dl-horizontal">
								<dt><?php __('front_payment_method')?></dt>
								<dd><?php echo @$payment_methods[$FORM['payment_method']]; ?></dd>
							</dl><!-- /.dl-horizontal -->
							<?php
							if($FORM['payment_method'] == 'bank')
							{
								?>
								<dl class="dl-horizontal">
									<dt><?php __('front_bank_account'); ?></dt>
									<dd><?php echo pjSanitize::html(nl2br($tpl['option_arr']['o_bank_account'])); ?></dd>
								</dl><!-- /.dl-horizontal -->
								<?php
							}
							if($FORM['payment_method'] == 'creditcard')
							{
								?>
								<dl class="dl-horizontal">
									<dt><?php __('front_cc_type'); ?></dt>
									<dd><?php echo @$cc_types[@$FORM['cc_type']]; ?></dd>
								</dl><!-- /.dl-horizontal -->
								<dl class="dl-horizontal">
									<dt><?php __('front_cc_num'); ?></dt>
									<dd><?php echo pjSanitize::html(@$FORM['cc_num']); ?></dd>
								</dl><!-- /.dl-horizontal -->
								<dl class="dl-horizontal">
									<dt><?php __('front_cc_code'); ?></dt>
									<dd><?php echo pjSanitize::html(@$FORM['cc_code']); ?></dd>
								</dl><!-- /.dl-horizontal -->
								<dl class="dl-horizontal">
									<dt><?php __('front_cc_exp'); ?></dt>
									<dd><?php printf("%s/%s", @$FORM['cc_exp_month'], @$FORM['cc_exp_year']); ?></dd>
								</dl><!-- /.dl-horizontal -->
								<?php
							} 
							?>
						</li><!-- /.list-group-item pjCpbFormSection -->
						<?php
					} 
					?>
					<li class="list-group-item pjCpbFormSection">
						<p class="pjCpbFormSectionTitle"><?php __('front_price');?>:</p><!-- /.pjCpbFormSectionTitle -->
						<?php
						$extra_price = isset($tpl['price']['extra_compilation']) ? (!empty($tpl['price']['extra_compilation']) ? $tpl['price']['extra_compilation']['extra_price'] : 0) : 0; 
						?>
						<dl class="dl-horizontal">
							<dt><?php __('front_car_space');?>:</dt>
							<dd><?php echo $tpl['price']['rental_price_formatted'];?></dd>
		
							<dt><?php __('front_extras');?>:</dt>
							<dd><?php echo pjUtil::formatCurrencySign(number_format($extra_price, 2), $tpl['option_arr']['o_currency']);?></dd>
							<?php
							if(isset($STORE['code']) && !empty($STORE['code']))
							{ 
								?>
								<dt><?php __('front_sub_total');?>:</dt>
								<dd><?php echo $tpl['price']['price_after_formatted'];?></dd>
								<?php
							}else{
								?>
								<dt><?php __('front_sub_total');?>:</dt>
								<dd><?php echo $tpl['price']['price_formatted'];?></dd>
								<?php
							}
							if($tpl['price']['tax'] > 0)
							{ 
								?>
								<dt><?php __('front_tax');?>:</dt>
								<dd><?php echo $tpl['price']['tax_formatted'];?></dd>
								<?php
							} 
							?>
							<dt><?php __('front_total');?>:</dt>
							<dd><?php echo $tpl['price']['total_formatted'];?></dd>
							<?php
							if($tpl['price']['deposit'] > 0)
							{ 
								?>
								<dt><?php __('front_deposit');?>:</dt>
								<dd><?php echo $tpl['price']['deposit_formatted'];?></dd>
								<?php
							} 
							?>
						</dl><!-- /.dl-horizontal -->
					</li><!-- /.list-group-item pjCpbFormSection -->
				</ul><!-- /.list-group pjCpbForm pjCpbFormConfirm -->
				<div class="panel-footer clearfix text-right pjCpbFooter">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
							<a href="#" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBackToCheckout" data-space_id="<?php echo $tpl['space']['id'];?>"><i class="fa fa-angle-double-left"></i> <?php __('front_btn_back');?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
							<input type="submit" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton" value="<?php __('front_btn_confirm_booking')?>" />
						</div>
					</div>
				</div><!-- /.panel-footer clearfix text-right pjCpbFooter -->
			</form>
			<?php
		}else{
			?>
			<div class="panel-body pjCpbBody">
				<p class="text-warning"><?php __('front_missing_parameters');?></p>
			</div>
			<div class="panel-footer clearfix text-right pjCpbFooter">
				<input type="button" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBtnStartOver" value="<?php __('front_btn_start_over')?>" />
			</div><!-- /.panel-footer clearfix text-right pjCpbFooter -->
			<?php 
		} 
		?>
	</div><!-- /.panel panel-default pjCpbPanel -->
</div>