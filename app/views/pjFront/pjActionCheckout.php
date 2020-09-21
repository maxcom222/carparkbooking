<div class="container-fluid pjCpbContainer">
	<div class="panel panel-default pjCpbPanel">
		<div class="panel-heading clearfix pjCpbHead">
			<h1 class="panel-title pull-left pjCpbTitle"><?php __('front_payment')?></h1><!-- /.panel-title pull-left pjCpbTitle -->
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
				$front_required = __('front_required', true);
				
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
			<form id="pjCpbCheckoutform_<?php echo $_GET['index']?>" class="pjCpbCheckoutform" action="#" method="post" data-toggle="validator" role="form">
				<input type="hidden" name="cp_checkout" value="1" />
				<ul class="list-group pjCpbForm pjCpbFormConfirm">
					<li class="list-group-item pjCpbFormSection">
						<p class="pjCpbFormConfirmTitle"><?php __('front_form_confirm_title');?></p><!-- /.pjCpbFormConfirmTitle -->
						<p class="pjCpbFormSectionTitle"><?php __('front_personal_details');?></p><!-- /.pjCpbFormSectionTitle -->
						
						<?php
						ob_start();
						$number_of_cols = 0;
						if (in_array((int) $tpl['option_arr']['o_bf_include_title'], array(2,3)))
						{ 
							?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label for="" class="control-label"><?php __('front_title'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_title'] === 3 ? '*:' : ':';?></label>
		
									<select name="c_title" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_title'] === 3 ? ' required' : null;?>" data-msg-required="<?php echo $front_required['title']; ?>">
										<option value=""></option>
										<?php
										foreach(__('personal_titles', true) as $k => $v) 
										{
											?><option value="<?php echo $k;?>"<?php echo isset($FORM['c_title']) ? ($FORM['c_title'] == $k ? ' selected="selected"' : null) : null;?>><?php  echo $v;?></option><?php
										}
										?>
									</select>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div><!-- /.form-group -->
							</div><!-- /.col-lg-2 col-md-2 col-sm-4 col-xs-12 -->
							<?php
							$number_of_cols++;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_name'], array(2,3)))
						{ 
							?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_name'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_name'] === 3 ? '*:' : ':';?></label>
							    	<input type="text" name="c_name" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_name'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_name']); ?>" data-msg-required="<?php echo $front_required['name']; ?>">
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_email'], array(2,3)))
						{
							?>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_email'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_email'] === 3 ? '*:' : ':';?></label>
							    	<input type="text" name="c_email" class="form-control email<?php echo (int) $tpl['option_arr']['o_bf_include_email'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_email']); ?>" data-msg-required="<?php echo $front_required['email']; ?>" data-msg-email="<?php echo __('front_email_invalid'); ?>">
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_phone'], array(2,3)))
						{
							?>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_phone'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_phone'] === 3 ? '*:' : ':';?></label>
							    	<input type="text" name="c_phone" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_phone'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_phone']); ?>" data-msg-required="<?php echo $front_required['phone']; ?>" >
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_company'], array(2,3)))
						{
							?>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_company'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_company'] === 3 ? '*:' : ':';?></label>
							    	<input type="text" name="c_company" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_company'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_company']); ?>" data-msg-required="<?php echo $front_required['company']; ?>" >
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_address'], array(2,3)))
						{
							?>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_address'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_address'] === 3 ? '*:' : ':';?></label>
							    	<input type="text" name="c_address" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_address'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_address']); ?>" data-msg-required="<?php echo $front_required['address']; ?>">
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_country'], array(2,3)))
						{
							?>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_country'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_country'] === 3 ? '*:' : ':';?></label>
							    	<select name="c_country" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_country'] === 3 ? ' required' : null;?>" data-msg-required="<?php echo $front_required['country']; ?>">
										<option value="">-- <?php __('front_choose');?> --</option>
										<?php
										foreach($tpl['country_arr'] as $k => $v) 
										{
											?><option value="<?php echo $v['id'];?>"<?php echo isset($FORM['c_country']) ? ($FORM['c_country'] == $v['id'] ? ' selected="selected"' : null) : null;?>><?php  echo $v['country_title'];?></option><?php
										}
										?>
									</select>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_state'], array(2,3)))
						{
							?>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_state'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_country'] === 3 ? '*:' : ':';?></label>
							    	<input type="text" name="c_state" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_state'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_state']); ?>" data-msg-required="<?php echo $front_required['state']; ?>">
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_city'], array(2,3)))
						{
							?>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_city'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_city'] === 3 ? '*:' : ':';?></label>
							    	<input type="text" name="c_city" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_city'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_city']); ?>" data-msg-required="<?php echo $front_required['city']; ?>">
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_zip'], array(2,3)))
						{
							?>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  	<div class="form-group">
							    	<label class="control-label"><?php __('front_zip'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_zip'] === 3 ? '*:' : ':';?></label>
							    	<input type="text" name="c_zip" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_zip'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_zip']); ?>" data-msg-required="<?php echo $front_required['zip']; ?>">
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
							<?php
							$number_of_cols++;
						}
						if($number_of_cols == 2 || $number_of_cols == 1)
						{
							$ob_fields = ob_get_contents();
							ob_end_clean();
							?>
							<div class="row"><?php echo $ob_fields; ?></div>
							<?php
							ob_start();
							$number_of_cols = 0;
						}
						if (in_array((int) $tpl['option_arr']['o_bf_include_notes'], array(2,3)))
						{
							?>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
								    	<label class="control-label"><?php __('front_notes'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_notes'] === 3 ? '*:' : ':';?></label>
								    	<textarea name="c_notes" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_notes'] === 3 ? ' required' : NULL; ?>" rows="4" data-msg-required="<?php echo $front_required['notes']; ?>"><?php echo pjSanitize::html(@$FORM['c_notes']); ?></textarea>
								    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								  	</div>
								</div>
							</div>
							<?php
						}
						?>
					</li><!-- /.list-group-item pjCpbFormSection -->
					<?php
					ob_start();
					if (in_array((int) $tpl['option_arr']['o_bf_include_regno'], array(2,3)))
					{
						?>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						  	<div class="form-group">
						    	<label class="control-label"><?php __('front_regno'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_regno'] === 3 ? '*:' : ':';?></label>
						    	<input type="text" name="c_regno" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_regno'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_regno']); ?>" data-msg-required="<?php echo $front_required['regno']; ?>">
						    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						  	</div>
						</div>
						<?php
					}
					if (in_array((int) $tpl['option_arr']['o_bf_include_model'], array(2,3)))
					{
						?>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						  	<div class="form-group">
						    	<label class="control-label"><?php __('front_model'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_model'] === 3 ? '*:' : ':';?></label>
						    	<input type="text" name="c_model" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_model'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_model']); ?>" data-msg-required="<?php echo $front_required['model']; ?>">
						    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						  	</div>
						</div>
						<?php
					}
					if (in_array((int) $tpl['option_arr']['o_bf_include_make'], array(2,3)))
					{
						?>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						  	<div class="form-group">
						    	<label class="control-label"><?php __('front_make'); ?> <?php echo (int) $tpl['option_arr']['o_bf_include_make'] === 3 ? '*:' : ':';?></label>
						    	<input type="text" name="c_make" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_make'] === 3 ? ' required' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_make']); ?>" data-msg-required="<?php echo $front_required['make']; ?>">
						    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						  	</div>
						</div>
						<?php
					}
					$ob_fields = ob_get_contents();
					ob_end_clean();
					if(!empty($ob_fields))
					{
						?>
						<li class="list-group-item pjCpbFormSection">
							<p class="pjCpbFormSectionTitle"><?php __('front_car_details');?></p><!-- /.pjCpbFormSectionTitle -->
							
							<div class="row">
								<?php echo $ob_fields;?>
							</div><!-- /.row -->
						</li><!-- /.list-group-item pjCpbFormSection -->
						<?php
					}
					if ($tpl['option_arr']['o_payment_disable'] == 'No')
					{
						?>
						<li class="list-group-item pjCpbFormSection">
							<p class="pjCpbFormSectionTitle"><?php __('front_payment_details');?></p><!-- /.pjCpbFormSectionTitle -->
		
							<div class="row pjCpbFormSectionPromo">
								<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
									<div class="form-group">
										<label for="" class="control-label"><?php __('front_promo_code');?>:</label>
		
										<input type="text" name="code" class="form-control" />
									</div><!-- /.form-group -->
								</div><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 -->
		
								<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
									<div class="form-group">
										<button class="btn btn-default btn-block pjCpbBtn pjCpbBtnSecondary pjCpbSelectorButton pjCpbBtnAddPromoCode"><?php __('front_btn_apply_code');?></button>
									</div><!-- /.form-group -->
								</div><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 -->
							</div><!-- /.row -->
							<div id="pjCpbPromoContainer_<?php echo $_GET['index'];?>" class="row pjCpbFormSectionPromo" style="display:<?php echo isset($STORE['code']) ? ' block' : ' none';?>">
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
							</div><!-- /.row -->
							<div class="row" style="display:none;">
								<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
									<div class="form-group">
										<label class="control-label">&nbsp;</label>
									</div><!-- /.form-group -->
								</div><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 -->
		
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<span><strong><?php __('front_invalid_promo_code');?></strong></span>
									</div><!-- /.form-group -->
								</div><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 -->
							</div><!-- /.row -->
							<div class="row">
								<div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
									<div class="form-group">
										<label for="" class="control-label"><?php __('front_payment_method')?> *:</label>
								
										<select name="payment_method" class="form-control required" data-msg-required="<?php echo $front_required['payment']; ?>">
											<option value="">-- <?php __('front_choose');?> --</option>
											<?php
											foreach (__('payment_methods', true) as $k => $v)
											{
												if ($tpl['option_arr']['o_allow_' . $k] === "Yes")
												{
													?><option value="<?php echo $k; ?>"<?php echo @$FORM['payment_method'] != $k ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
												}
											}
											?>
										</select>
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									</div><!-- /.form-group -->
								</div><!-- /.col-lg-5 col-md-5 col-sm-4 col-xs-12 -->
								<div class="col-lg-5 col-md-5 col-sm-4 col-xs-12 pjCpbBankWrap" style="display: <?php echo @$FORM['payment_method'] != 'bank' ? 'none' : NULL; ?>">
									<div class="form-group">
								    	<label class="control-label"><?php __('front_bank_account'); ?></label>
								    	<div class="text-muted"><strong><?php echo pjSanitize::html(nl2br($tpl['option_arr']['o_bank_account'])); ?></strong></div>
								  	</div>
								</div>
							</div><!-- /.row -->
							<div class="row pjCpbCcWrap" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
								<div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
									<div class="form-group">
										<label class="control-label"><?php __('front_cc_type'); ?> *:</label>
								    	<select name="cc_type" class="form-control required" data-msg-required="<?php echo $front_required['cc_type']; ?>">
								    		<option value="">---</option>
								    		<?php
											foreach (__('cc_types', true) as $k => $v)
											{
												?><option value="<?php echo $k; ?>"<?php echo @$FORM['cc_type'] != $k ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
											}
											?>
								    	</select>
								    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									</div><!-- /.form-group -->
								</div><!-- /.col-lg-5 col-md-5 col-sm-4 col-xs-12 -->
								<div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
									<div class="form-group">
								    	<label class="control-label"><?php __('front_cc_num'); ?> *:</label>
								    	<input type="text" name="cc_num" class="form-control required" value="<?php echo pjSanitize::html(@$FORM['cc_num']); ?>"  autocomplete="off" data-msg-required="<?php echo $front_required['cc_num']; ?>"/>
								    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								  	</div>
								</div>
							</div><!-- /.row -->
							<div class="row pjCpbCcWrap" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
								<div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
								  	<div class="form-group">
								    	<label class="control-label"><?php __('front_cc_code'); ?> *:</label>
								    	<input type="text" name="cc_code" class="form-control required" value="<?php echo pjSanitize::html(@$FORM['cc_code']); ?>"  autocomplete="off" data-msg-required="<?php echo $front_required['cc_code']; ?>"/>
								    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								  	</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
								  	<div class="form-group required">
								    	<label class="control-label"><?php __('front_cc_exp'); ?></label>
								    	<?php
										$rand = rand(1, 99999);
										$time = pjTime::factory()
											->attr('name', 'cc_exp_month')
											->attr('id', 'cc_exp_month_' . $rand)
											->attr('class', 'form-control required')
											->prop('format', 'F');
										if (isset($FORM['cc_exp_month']) && !is_null($FORM['cc_exp_month']))
										{
											$time->prop('selected', $FORM['cc_exp_month']);
										}
										echo $time->month();
										?>
								  	</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
								  	<div class="form-group">
								    	<label>&nbsp;</label>
								    	<?php
										$time = pjTime::factory()
											->attr('name', 'cc_exp_year')
											->attr('id', 'cc_exp_year_' . $rand)
											->attr('class', 'form-control required')
											->prop('left', 0)
											->prop('right', 10);
										if (isset($FORM['cc_exp_year']) && !is_null($FORM['cc_exp_year']))
										{
											$time->prop('selected', $FORM['cc_exp_year']);
										}
										echo $time->year();
										?>
								  	</div>
								</div><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 -->
							</div><!-- /.row -->
						</li><!-- /.list-group-item pjCpbFormSection -->
						<?php
					} 
					?>
					<li class="list-group-item pjCpbFormSection">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							  	<?php
							  	if (in_array((int) $tpl['option_arr']['o_bf_include_captcha'], array(3)))
							  	{
							  		?>
		  							<div class="form-group">
		  							  	<label class="control-label"><?php __('front_captcha'); ?>  <?php echo (int) $tpl['option_arr']['o_bf_include_captcha'] === 3 ? '*:' : ':';?></label>
		  								<div class="row">
		  								  	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
		  								    	<input type="text" name="captcha" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_captcha'] === 3 ? ' required' : NULL; ?>" maxlength="6" autocomplete="off" data-msg-required="<?php echo $front_required['captcha']; ?>" data-msg-captcha="<?php __('front_incorrect_captcha');?>">
		  								    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
		  								  	</div>
		  								  	<div class="col-lg-6 col-md-6col-sm-4 col-xs-12">
		  								    	<img src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&amp;action=pjActionCaptcha&amp;rand=<?php echo rand(1, 99999); ?><?php echo isset($_GET['session_id']) ? '&session_id=' . $_GET['session_id'] : NULL;?>" alt="Captcha" style="vertical-align: middle" />
		  								  	</div>
		  								</div>
		  							</div>
		  							<?php
		  						} 
							  	?>
							</div>
						</div><!-- /.row -->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-6 col-md-6col-sm-4 col-xs-12">
										  	<input type="checkbox" id="pjCpbTerms" name="terms" value="1" class="required" data-msg-required="<?php echo $front_required['terms']; ?>">
										  	<?php
										  	if(!empty($tpl['terms_conditions']))
										  	{ 
											  	?>
										      	<a href="#" class="pjTbModalTrigger" data-toggle="modal" data-target="#pjCpbTermModal" data-title="<?php __('front_terms_title', false, true); ?>"><?php __('front_terms'); ?></a>
										      	<?php
										  	}else{
										  		?><label class="control-label" for="pjCpbTerms"><?php __('front_terms'); ?></label><?php
										  	} 
										    ?>
									      	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									     </div>
								     </div>
							     </div>
							</div>
							<br/><br/>
						</div><!-- /.row -->
					</li><!-- /.list-group-item pjCpbFormSection -->
					<li id="pjCpbPriceContainer_<?php echo $_GET['index'];?>" class="list-group-item pjCpbFormSection pjCpbFormSectionFinal">
						<?php
						include_once PJ_VIEWS_PATH . 'pjFront/pjActionGetPrices.php';
						?>
					</li><!-- /.list-group-item pjCpbFormSection pjCpbFormSectionFinal -->
				</ul><!-- /.list-group pjCpbForm pjCpbFormConfirm -->
				
				<div class="panel-footer clearfix text-right pjCpbFooter">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
							<a href="#" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBackToExtras" data-space_id="<?php echo $tpl['space']['id'];?>" data-extras="<?php echo $tpl['cnt_extras'];?>"><i class="fa fa-angle-double-left"></i> <?php __('front_btn_back');?></a>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
							<input type="submit" class="btn btn-default pjCpbBtn pjCpbSelectorButton pjCpbBtnPrimary" value="<?php __('front_btn_review_confirm')?>" />
						</div>
					</div>
				</div><!-- /.panel-footer clearfix text-right pjCpbFooter -->
			</form>
			
			<div class="modal fade pjTbModal" id="pjCpbTermModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php __('front_btn_close');?></span></button>
			        		<h4 class="modal-title pjTbModalTitle" id="myModalLabel"><?php __('front_terms_title', false, true); ?></h4>
			      		</div>
					    <div class="modal-body">
					    	<?php echo nl2br(stripslashes($tpl['terms_conditions'])); ?>
					    </div>
				      	<div class="modal-footer">
				        	<button type="button" class="btn btn-default pjTbBtn pjTbBtnPrimary" data-dismiss="modal"><?php __('front_btn_close');?></button>
				      	</div>
			    	</div>
			  	</div>
			</div>
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
	</div>
</div>