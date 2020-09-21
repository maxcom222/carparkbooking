<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$jqTimeFormat = pjUtil::jqTimeFormat($tpl['option_arr']['o_time_format']);
	
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('menuBookings'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionExport"><?php __('tabExport'); ?></a></li>
		</ul>
	</div>
	<?php
	pjUtil::printNotice(__('infoUpdateBookingTitle', true, false), __('infoUpdateBookingDesc', true, false)); 
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate" method="post" class="form pj-form" id="frmUpdateBooking">
		<input type="hidden" name="booking_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
		<input type="hidden" name="tab_id" value="<?php echo isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'tabs-1'; ?>" />
		
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><?php __('tabBookingDetails');?></a></li>
				<li><a href="#tabs-2"><?php __('tabClientDetails');?></a></li>
			</ul>
			<div id="tabs-1" class="pj-loader-outer">
				<div class="pj-loader"></div>
				<div class="w400 float_left r30">
					<p>
						<label class="title"><?php __('lblBookingID'); ?></label>
						<span class="inline-block">
							<input type="text" name="uuid" id="uuid" value="<?php echo pjSanitize::html($tpl['arr']['uuid']);?>" class="pj-form-field w150 required" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('lblFrom'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="from" class="pj-form-field pointer w140 required datetimepick" value="<?php echo $tpl['from'];?>" readonly="readonly" data-date="<?php echo $tpl['from'];?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" lang="<?php echo $jqTimeFormat; ?>"/>
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
					</p>
					<p>
						<label class="title"><?php __('lblTo'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="to" class="pj-form-field pointer w140 required datetimepick" value="<?php echo $tpl['to'];?>" readonly="readonly" data-date="<?php echo $tpl['to'];?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" lang="<?php echo $jqTimeFormat; ?>"/>
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
					</p>
				</div>
				<div class="w300 float_left">
					<p>
						<label class="title"><?php __('lblDateTimeMade'); ?></label>
						<span class="inline-block">
							<label class="content b10">
								<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['created'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['created']));?>
							</label>
						</span>
					</p>
					<p>
						<label class="title"><?php __('lblIp'); ?></label>
						<span class="inline-block">
							<label class="content b10">
								<?php echo $tpl['arr']['ip'];?>
							</label>
						</span>
					</p>
					<p>
						<label class="content"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionSend" class="cpSendConfirm"><?php __('lblSendConfirmation'); ?></a></label>
					</p>
				</div>
				<div class="clear_both"></div>
				<p>
					<label class="title"><?php __('lblRentalDays'); ?></label>
					<span id="cpRentalDays" class="inline-block">
						<label class="content"><?php echo $tpl['rental_days'];?> <?php echo $tpl['rental_days'] == 1 ? __('lblSingularDay') : __('lblPluralDays');?></label>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblSpace'); ?></label>
					<span id="cpSpaceContainer" class="inline_block">
						<select id="space_id" name="space_id" class="pj-form-field w250 float_left r10 required">
							<option value="">-- <?php __('lblChoose'); ?>--</option>
							<?php
							foreach($tpl['space_arr'] as $space)
							{
								?><option value="<?php echo $space['id'];?>"<?php echo $tpl['arr']['space_id'] == $space['id'] ? ' selected="selected"' : null;?> data-days="<?php echo $tpl['rental_days'];?>" data-price="<?php echo $space['price'];?>"><?php echo pjSanitize::html($space['name']);?></option><?php
							} 
							?>
						</select>
					</span>
				</p>
				<div class="p">
					<label class="title"><?php __('lblExtra'); ?></label>
					<div class="inline_block">
						<table class="pj-table b15" id="tblExtras" cellpadding="0" cellspacing="0" style="width: 550px;">
							<thead>
								<tr>
									<th style="width: 210px;"><?php __('lblExtra'); ?></th>
									<th style="width: 140px;"><?php __('lblPrice'); ?></th>
									<th style="width: 100px;"><?php __('lblQty'); ?></th>
									<th style="width: 24px;">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($tpl['be_arr'] as $k => $be)
								{
									$price_per = '';
									$qty = '';
									?>
									<tr>
										<td>
											<span class="inline-block">
												<select name="extra_id[<?php echo $be['id'];?>]" data-index="<?php echo $be['id'];?>" class="pj-form-field w200 required cpExtraSelector">
													<option value="">-- <?php __('lblChoose'); ?>--</option>
													<?php
													foreach ($tpl['extra_arr'] as $k => $v)
													{
														$cnt = $v['cnt'] - $v['bookings'];
														if($cnt > 0 || $v['type'] == 'unlimited')
														{	
															if($be['extra_id'] == $v['id'])
															{
																$price_per = $v['price_format'];
																switch ($v['is_single']) {
																	case 1:
																		$qty = '<input type="checkbox" checked="checked" name="qty['.$be['id'].']" data-index="'.$be['id'].'" class="required cpExtraPrice"/>';
																	break;
																	
																	case 0:
																		if($v['type'] == 'limited')
																		{
																			$qty = '<select name="qty['.$be['id'].']" data-index="'.$be['id'].'" class="pj-form-field w60 required cpExtraPrice">';
																			for($i = 0; $i <= $cnt; $i++)
																			{
																				if($be['cnt'] == $i)
																				{
																					$qty .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
																				}else{
																					$qty .= '<option value="'.$i.'">'.$i.'</option>';
																				}
																			}
																			$qty .= '</select>';
																		}else{
																			$qty = '<input type="text" name="qty['.$be['id'].']" value="'.$be['cnt'].'" data-index="'.$be['id'].'" class="pj-form-field w60 required cpExtraPrice field-int"/>';
																		}
																	break;
																}
																
															}
															
															?><option value="<?php echo $v['id']; ?>"<?php echo $be['extra_id'] == $v['id'] ? ' selected="selected"' : null;?> data-per="<?php echo $v['per'];?>" data-price="<?php echo $v['price'];?>" data-type="<?php echo $v['type'];?>" data-cnt="<?php echo $cnt; ?>" data-is_single="<?php echo $v['is_single'];?>" data-price_format="<?php echo pjSanitize::html($v['price_format']);?>"><?php echo pjSanitize::html($v['name']); ?></option><?php
														}
													}
													?>
												</select>
												<input type="hidden" name="price[<?php echo $be['id'];?>]" value="<?php echo $be['price'];?>"/>
											</span>
										</td>
										<td><?php echo $price_per;?></td>
										<td><?php echo $qty;?></td>
										<td class="align_center"><a href="#" class="pj-delete cpRemoveExtra"></a></td>
									</tr>
									<?php
								} 
								?>
							</tbody>
						</table>
						<input type="button" value="<?php __('btnAddExtra', false, true); ?>" class="pj-button cpAddExtra" />
					</div>
				</div>
				<?php
				$is_apply = true;
				if(!empty($tpl['arr']['discount_code']) && !empty($tpl['arr']['discount']))
				{
					$is_apply = false;
				} 
				?>
				<p>
					<label class="title"><?php __('lblDiscountCode'); ?></label>
					<span class="inline-block">
						<input type="text" id="discount_code" name="discount_code" data-type="<?php echo !empty($tpl['code_arr']) ? $tpl['code_arr']['type'] : NULL;?>" data-discount="<?php echo !empty($tpl['code_arr']) ? $tpl['code_arr']['discount'] : NULL;?>" value="<?php echo pjSanitize::html($tpl['arr']['discount_code']);?>" class="pj-form-field w100 float_left r10"/>
						<input type="button" value="<?php __('btnApply', false, true); ?>" class="pj-button cpBtnCode cpApplyCode" style="display: <?php echo $is_apply==true? 'block' : 'none';?>"/>
						<input type="button" value="<?php __('btnRemove', false, true); ?>" class="pj-button cpBtnCode cpRemoveCode" style="display: <?php echo $is_apply==false? 'block' : 'none';?>"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblRentalPrice'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" id="rental_price" name="rental_price" value="<?php echo pjSanitize::html($tpl['arr']['rental_price']);?>" class="pj-form-field number w80 required" readonly="readonly"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblExtraPrice'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" id="extra_price" name="extra_price" value="<?php echo pjSanitize::html($tpl['arr']['extra_price']);?>" class="pj-form-field number w80" readonly="readonly"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblDiscount'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" id="discount" name="discount" value="<?php echo pjSanitize::html($tpl['arr']['discount']);?>" class="pj-form-field number w80" readonly="readonly"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblSubTotal'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" id="sub_total" name="sub_total" value="<?php echo pjSanitize::html($tpl['arr']['sub_total']);?>" class="pj-form-field number w80" readonly="readonly"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblTax'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" id="tax" name="tax" value="<?php echo pjSanitize::html($tpl['arr']['tax']);?>" class="pj-form-field number w80" readonly="readonly"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblTotal'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" id="total" name="total" value="<?php echo pjSanitize::html($tpl['arr']['total']);?>" class="pj-form-field number w80" readonly="readonly"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblDeposit'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" id="deposit" name="deposit" value="<?php echo pjSanitize::html($tpl['arr']['deposit']);?>" class="pj-form-field number w80" readonly="readonly"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblPaymentMethod');?></label>
					<span class="inline-block">
						<select name="payment_method" id="payment_method" class="pj-form-field w150 required">
							<option value="">-- <?php __('lblChoose'); ?>--</option>
							<?php
							foreach (__('payment_methods', true, false) as $k => $v)
							{
								?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['payment_method'] == $k ? ' selected="selected"' : null;?>><?php echo $v; ?></option><?php
							}
							?>
						</select>
					</span>
				</p>
				<p class="boxCC" style="display: none;">
					<label class="title"><?php __('lblCCType'); ?></label>
					<span class="inline-block">
						<select name="cc_type" class="pj-form-field w150">
							<option value="">---</option>
							<?php
							foreach (__('cc_types', true, false) as $k => $v)
							{
								?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['cc_type'] == $k ? ' selected="selected"' : null;?>><?php echo $v; ?></option><?php
							}
							?>
						</select>
					</span>
				</p>
				<p class="boxCC" style="display: none;">
					<label class="title"><?php __('lblCCNum'); ?></label>
					<span class="inline-block">
						<input type="text" name="cc_num" id="cc_num" value="<?php echo pjSanitize::html($tpl['arr']['cc_num']);?>" class="pj-form-field w136" />
					</span>
				</p>
				<p class="boxCC" style="display: none;">
					<label class="title"><?php __('lblCCExp'); ?></label>
					<span class="inline-block">
						<select name="cc_exp_month" class="pj-form-field">
							<option value="">---</option>
							<?php
							$month_arr = __('months', true, false);
							ksort($month_arr);
							foreach ($month_arr as $key => $val)
							{
								?><option value="<?php echo $key;?>"<?php echo $tpl['arr']['cc_exp_month'] == $key ? ' selected="selected"' : null;?>><?php echo $val;?></option><?php
							}
							?>
						</select>
						<select name="cc_exp_year" class="pj-form-field">
							<option value="">---</option>
							<?php
							$y = (int) date('Y');
							for ($i = $y; $i <= $y + 10; $i++)
							{
								?><option value="<?php echo $i; ?>"<?php echo $tpl['arr']['cc_exp_year'] == $i ? ' selected="selected"' : null;?>><?php echo $i; ?></option><?php
							}
							?>
						</select>
					</span>
				</p>
				<p class="boxCC" style="display: none">
					<label class="title"><?php __('lblCCCode'); ?></label>
					<span class="inline-block">
						<input type="text" name="cc_code" id="cc_code" value="<?php echo pjSanitize::html($tpl['arr']['code']);?>" class="pj-form-field w100" />
					</span>
				</p>
				<p>
					<label class="title"><?php __('lblStatus'); ?></label>
					<span class="inline_block">
						<select name="status" id="status" class="pj-form-field w150 required">
							<option value="">-- <?php __('lblChoose'); ?>--</option>
							<?php
							foreach (__('booking_statuses', true, false) as $k => $v)
							{
								?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['status'] == $k ? ' selected="selected"' : null;?>><?php echo stripslashes($v); ?></option><?php
							}
							?>
						</select>
					</span>
				</p>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
					<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBookings&action=pjActionIndex';" />
				</p>
			</div>
			
			<div id="tabs-2">
				<?php
				if (in_array((int) $tpl['option_arr']['o_bf_include_title'], array(2,3)))
				{
					?>
					<p>
						<label class="title"><?php __('lblBookingTitle'); ?></label>
						<span class="inline-block">
							<select name="c_title" id="c_title" class="pj-form-field w150<?php echo $tpl['option_arr']['o_bf_include_title'] == 3 ? ' required' : NULL; ?>">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php
								foreach ( __('personal_titles', true, false) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['c_title'] == $k ? ' selected="selected"' : null;?>><?php echo $v; ?></option><?php
								}
								?>
							</select>
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_name'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingName'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_name" id="c_name" value="<?php echo pjSanitize::html($tpl['arr']['c_name']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_name'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_email'], array(2,3)))
				{
					?>
					<p>
						<label class="title"><?php __('lblBookingEmail'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_email" id="c_email" value="<?php echo pjSanitize::html($tpl['arr']['c_email']);?>" class="pj-form-field email w400<?php echo $tpl['option_arr']['o_bf_include_email'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_phone'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingPhone'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_phone" id="c_phone" value="<?php echo pjSanitize::html($tpl['arr']['c_phone']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_phone'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_notes'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingNotes'); ?></label>
						<span class="inline-block">
							<textarea name="c_notes" id="c_notes" class="pj-form-field w500 h120<?php echo $tpl['option_arr']['o_bf_include_notes'] == 3 ? ' required' : NULL; ?>"><?php echo pjSanitize::html($tpl['arr']['c_notes']);?></textarea>
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_company'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingCompany'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_company" id="c_company" value="<?php echo pjSanitize::html($tpl['arr']['c_company']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_company'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_address'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingAddress'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_address" id="c_address" value="<?php echo pjSanitize::html($tpl['arr']['c_address']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_address'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_city'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingCity'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_city" id="c_city" value="<?php echo pjSanitize::html($tpl['arr']['c_city']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_city'] == 3 ? ' required' : NULL; ?>"/>
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_state'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingState'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_state" id="c_state" value="<?php echo pjSanitize::html($tpl['arr']['c_state']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_state'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_zip'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingZip'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_zip" id="c_zip" value="<?php echo pjSanitize::html($tpl['arr']['c_zip']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_zip'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_country'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingCountry'); ?></label>
						<span class="inline-block">
							<select name="c_country" id="c_country" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_country'] == 3 ? ' required' : NULL; ?>">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php
								foreach ($tpl['country_arr'] as $v)
								{
									?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['c_country'] == $v['id'] ? ' selected="selected"' : null;?>><?php echo stripslashes($v['country_title']); ?></option><?php
								}
								?>
							</select>
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_regno'], array(2,3)))
				{
					?>
					<p>
						<label class="title"><?php __('lblBookingRegNo'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_regno" id="c_regno" value="<?php echo pjSanitize::html($tpl['arr']['c_regno']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_regno'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_make'], array(2,3)))
				{
					?>
					<p>
						<label class="title"><?php __('lblBookingMake'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_make" id="c_make" value="<?php echo pjSanitize::html($tpl['arr']['c_make']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_make'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				if (in_array((int) $tpl['option_arr']['o_bf_include_model'], array(2,3)))
				{ 
					?>
					<p>
						<label class="title"><?php __('lblBookingModel'); ?></label>
						<span class="inline-block">
							<input type="text" name="c_model" id="c_model" value="<?php echo pjSanitize::html($tpl['arr']['c_model']);?>" class="pj-form-field w400<?php echo $tpl['option_arr']['o_bf_include_model'] == 3 ? ' required' : NULL; ?>" />
						</span>
					</p>
					<?php
				}
				?>
				
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
					<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBookings&action=pjActionIndex';" />
				</p>
			</div>
		</div>
	</form>
	<table id="tblExtrasClone" style="display: none">
		<tbody>
			<tr>
				<td>
					<span class="inline-block">
						<select name="extra_id[{INDEX}]" data-index="{INDEX}" class="pj-form-field w200 required cpExtraSelector">
							<option value="">-- <?php __('lblChoose'); ?>--</option>
							<?php
							foreach ($tpl['extra_arr'] as $k => $v)
							{
								$cnt = $v['cnt'] - $v['bookings'];
								if($cnt > 0 || $v['type'] == 'unlimited')
								{	
									?><option value="<?php echo $v['id']; ?>" data-per="<?php echo $v['per'];?>" data-price="<?php echo $v['price'];?>" data-type="<?php echo $v['type'];?>" data-cnt="<?php echo $cnt; ?>" data-is_single="<?php echo $v['is_single'];?>" data-price_format="<?php echo pjSanitize::html($v['price_format']);?>"><?php echo pjSanitize::html($v['name']); ?></option><?php
								}
							}
							?>
						</select>
						<input type="hidden" name="price[{INDEX}]"/>
					</span>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="align_center"><a href="#" class="pj-delete cpRemoveExtra"></a></td>
			</tr>
		</tbody>
	</table>
	
	<div id="dialogConfirm" title="<?php __('lblSendConfirmation'); ?>" style="display: none">
		<form action="" method="post" class="form pj-form dialogForm">
			<input type="hidden" name="form_send" value="1" />
			<input type="hidden" name="from" value="<?php echo pjAppController::getAdminEmail(); ?>" />
			<p>
				<label class="block b5"><?php __('lblSendTo'); ?>:</label>
				<input type="text" name="to"  value="<?php echo @$tpl['arr']['c_email']; ?>" readonly class="pj-form-field w550 required"/>
			</p>
			<p>
				<label class="block b5"><?php __('lblSubject'); ?>:</label>
				<input type="text" id="confirm_subject" name="subject" value="<?php echo pjSanitize::html(@$tpl['subject']); ?>" class="pj-form-field w550 required"/>
			</p>
			<p>
				<label class="block b5"><?php __('lblMessage'); ?>:</label>
				<textarea id="confirm_message" name="message" class="pj-form-field mceEditor required"><?php echo nl2br(stripslashes(@$tpl['message'])); ?></textarea>
			</p>
		</form>
	</div>
	
	<div style="display: none">
		<input type="hidden" id="clone_subject" name="clonesubject" value="<?php echo pjSanitize::html(@$tpl['subject']); ?>" />
		<textarea id="clone_message" name="clone_message" class="pj-form-field w550 h300"><?php echo nl2br(stripslashes(@$tpl['message'])); ?></textarea>
	</div>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.tax_percent = <?php echo $tpl['option_arr']['o_tax_payment'];?>;
	myLabel.deposit_percent = <?php echo (float) $tpl['option_arr']['o_deposit_payment']?>;
	</script>
	<?php
	if (isset($_GET['tab_id']) && !empty($_GET['tab_id']))
	{		
		$tab_id = $_GET['tab_id'];
		$tab_id = $tab_id < 0 ? 0 : $tab_id;
		?>
		<script type="text/javascript">
		(function ($) {
			$(function () {
				$("#tabs").tabs("option", "selected", <?php echo $tab_id; ?>);
			});
		})(jQuery);
		</script>
		<?php
	}
}
?>