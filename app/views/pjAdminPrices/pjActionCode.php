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
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/menu_spaces.php';
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/menu_prices.php';
	pjUtil::printNotice(__('infoCodesTitle', true), __('infoCodesDesc', true));
		
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPrices&amp;action=pjActionCode" method="post" id="frmUpdateCode" class="pj-form form">
		<input type="hidden" name="code_update" value="1" />
		
		<table class="pj-table b15" id="tblCodes" cellpadding="0" cellspacing="0" style="width: 100%;">
			<thead>
				<tr>
					<th style="width: 180px;"><?php __('lblSpace'); ?></th>
					<th style="width: 200px;"><?php __('lblDateRange'); ?></th>
					<th style="width: 70px;"><?php __('lblPromoCode'); ?></th>
					<th style="width: 90px;"><?php __('lblDiscount'); ?></th>
					<th style="width: 90px;"><?php __('lblType'); ?></th>
					<th style="width: 25px;">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!empty($tpl['code_arr']))
				{
					foreach ($tpl['code_arr'] as $code)
					{
						?>
						<tr>
							<td>
								<span class="inline-block">
									<input type="hidden" name="id[<?php echo $code['id']; ?>]" value="<?php echo $code['id']; ?>" />
									<select name="space_id[<?php echo $code['id'];?>]" class="pj-form-field w180 required cpSpace" data-index="<?php echo $code['id'];?>">
										<option value="">-- <?php __('lblChoose'); ?>--</option>
										<?php
										foreach ($tpl['space_arr'] as $space)
										{
											?><option value="<?php echo $space['id']; ?>"<?php echo $space['id'] == $code['space_id'] ? ' selected="selected"' : null;?>><?php echo pjSanitize::html($space['name']); ?></option><?php
										}
										?>
									</select>
								</span>
							</td>
							<td>
								<span id="cpPrice_<?php echo $code['id'];?>" class="inline-block">
									<select name="price_id[<?php echo $code['id'];?>]" class="pj-form-field w200 required">
										<option value="">-- <?php __('lblChoose'); ?>--</option>
										<?php 
										foreach ($tpl['price_arr'] as $price)
										{
											if ($price['space_id'] == $code['space_id'])
											{
												?><option value="<?php echo $price['id']; ?>"<?php echo $code['price_id'] == $price['id'] ? ' selected="selected"' : NULL; ?>><?php echo date($tpl['option_arr']['o_date_format'], strtotime($price['date_from'])); ?> - <?php echo date($tpl['option_arr']['o_date_format'], strtotime($price['date_to'])); ?></option><?php
											}
										}
										?>
									</select>
								</span>
							</td>
							
							<td>
								<span class="inline-block">
									<input type="text" name="code[<?php echo $code['id'];?>]" value="<?php echo pjSanitize::html($code['code']);?>" class="pj-form-field w60 required"/>
								</span>
							</td>
							<td>
								<span class="pj-form-field-custom<?php echo $code['type'] == 'amount' ? ' pj-form-field-custom-before' : ' pj-form-field-custom-after'?> float_left cpDiscount_<?php echo $code['id'];?>">
									<span class="pj-form-field-before" style="display: <?php echo $code['type'] == 'amount' ? 'block' : 'none'?>"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
									<input type="text" name="discount[<?php echo $code['id'];?>]" value="<?php echo pjSanitize::html($code['discount']);?>" class="pj-form-field align_right required number w40"/>
									<span class="pj-form-field-after" style="display: <?php echo $code['type'] == 'percent' ? 'block' : 'none'?>"><abbr class="pj-form-field-icon-text">%</abbr></span>
								</span>
							</td>
							<td>
								<select name="type[<?php echo $code['id'];?>]" class="pj-form-field w90 cpType required" data-index="<?php echo $code['id'];?>">
									<?php
									foreach (__('promo_types') as $k => $v)
									{
										?><option value="<?php echo $k; ?>"<?php echo $code['type'] == $k ? ' selected="selected"' : null;?>><?php echo pjSanitize::html($v); ?></option><?php
									}
									?>
								</select>
							</td>
							<td class="align_center"><a href="#" class="pj-delete cpRemoveCode"></a></td>
						</tr>
						<?php
					}
				}else{ 
					?>
					<tr>
						<td colspan="6" class="cpNoCodes"><?php __('lblNoCodesDefined');?></td>
					</tr>
					<?php
				} 
				?>
			</tbody>
		</table>
		
		<input type="button" value="<?php __('btnAdd'); ?>" class="pj-button b15 cpAddCode" />
		<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
	</form>
	
	<table id="tblCodesClone" style="display: none">
		<tbody>
			<tr>
				<td>
					<span class="inline-block">
						<select name="space_id[{INDEX}]" class="pj-form-field w180 required cpSpace" data-index="{INDEX}">
							<option value="">-- <?php __('lblChoose'); ?>--</option>
							<?php
							foreach ($tpl['space_arr'] as $k => $v)
							{
								?><option value="<?php echo $v['id']; ?>"><?php echo pjSanitize::html($v['name']); ?></option><?php
							}
							?>
						</select>
					</span>
				</td>
				<td>
					<span id="cpPrice_{INDEX}" class="inline-block">
						<select name="price_id[{INDEX}]" class="pj-form-field w200 required">
							<option value="">-- <?php __('lblChoose'); ?>--</option>
						</select>
					</span>
				</td>
				<td>
					<span class="inline-block">
						<input type="text" name="code[{INDEX}]" class="pj-form-field w60 required"/>
					</span>
				</td>
				<td>
					<span class="pj-form-field-custom pj-form-field-custom-before float_left cpDiscount_{INDEX}">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" name="discount[{INDEX}]" class="pj-form-field align_right required number w40"/>
						<span class="pj-form-field-after" style="display: none;"><abbr class="pj-form-field-icon-text">%</abbr></span>
					</span>
				</td>
				<td>
					<select name="type[{INDEX}]" class="pj-form-field w90 cpType required" data-index="{INDEX}">
						<?php
						foreach (__('promo_types') as $k => $v)
						{
							?><option value="<?php echo $k; ?>"><?php echo pjSanitize::html($v); ?></option><?php
						}
						?>
					</select>
				</td>
				<td class="align_center"><a href="#" class="pj-delete cpRemoveCode"></a></td>
			</tr>
		</tbody>
	</table>
	
	<script type="text/javascript">
		var myLabel = myLabel || {};
		myLabel.no_codes_defined = "<?php __('lblNoCodesDefined');?>";
	</script>
	<?php
}
?>