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
	pjUtil::printNotice(__('infoDiscountsTitle', true), __('infoDiscountsDesc', true));
	
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPrices&amp;action=pjActionDiscount" method="post" id="frmUpdateDiscount" class="pj-form form">
		<input type="hidden" name="discount_update" value="1" />
		
		<table class="pj-table b15" id="tblDiscounts" cellpadding="0" cellspacing="0" style="width: 100%;">
			<thead>
				<tr>
					<th style="width: 200px;"><?php __('lblSpace'); ?></th>
					<th style="width: 200px;"><?php __('lblDateRange'); ?></th>
					<th style="width: 70px;"><?php __('lblMinDays'); ?></th>
					<th style="width: 70px;"><?php __('lblMaxDays'); ?></th>
					<th style="width: 90px;"><?php __('lblPricePerDay'); ?></th>
					<th style="width: 30px;">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!empty($tpl['discount_arr']))
				{
					foreach ($tpl['discount_arr'] as $discount)
					{
						?>
						<tr data-id="<?php echo $discount['id']; ?>">
							<td>
								<span class="inline-block">
									<input type="hidden" name="id[<?php echo $discount['id']; ?>]" value="<?php echo $discount['id']; ?>" />
									<select name="space_id[<?php echo $discount['id'];?>]" class="pj-form-field w200 required cpSpace" data-index="<?php echo $discount['id'];?>">
										<option value="">-- <?php __('lblChoose'); ?>--</option>
										<?php
										foreach ($tpl['space_arr'] as $space)
										{
											?><option value="<?php echo $space['id']; ?>"<?php echo $space['id'] == $discount['space_id'] ? ' selected="selected"' : null;?>><?php echo pjSanitize::html($space['name']); ?></option><?php
										}
										?>
									</select>
								</span>
							</td>
							<td>
								<span id="cpPrice_<?php echo $discount['id'];?>" class="inline-block">
									<select name="price_id[<?php echo $discount['id'];?>]" class="pj-form-field w200 required">
										<option value="">-- <?php __('lblChoose'); ?>--</option>
										<?php 
										foreach ($tpl['price_arr'] as $price)
										{
											if ($price['space_id'] == $discount['space_id'])
											{
												?><option value="<?php echo $price['id']; ?>"<?php echo $discount['price_id'] == $price['id'] ? ' selected="selected"' : NULL; ?>><?php echo date($tpl['option_arr']['o_date_format'], strtotime($price['date_from'])); ?> - <?php echo date($tpl['option_arr']['o_date_format'], strtotime($price['date_to'])); ?></option><?php
											}
										}
										?>
									</select>
								</span>
							</td>
							<td>
								<span class="inline-block">
									<input type="text" name="min_days[<?php echo $discount['id'];?>]" value="<?php echo pjSanitize::html($discount['min_days']);?>" class="pj-form-field w50 field-int required digits"/>
								</span>
							</td>
							<td>
								<span class="inline-block">
									<input type="text" name="max_days[<?php echo $discount['id'];?>]" value="<?php echo pjSanitize::html($discount['max_days']);?>" class="pj-form-field w50 field-int required digits"/>
								</span>
							</td>
							<td>
								<span class="pj-form-field-custom pj-form-field-custom-before float_left r10">
									<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
									<input type="text" name="price[<?php echo $discount['id'];?>]" value="<?php echo pjSanitize::html($discount['price']);?>" class="pj-form-field align_right required number w40"/>
								</span>
							</td>
							<td class="align_center"><a href="#" class="pj-delete cpRemoveDiscount"></a></td>
						</tr>
						<?php
					}
				}else{ 
					?>
					<tr>
						<td colspan="6" class="cpNoDiscounts"><?php __('lblNoDiscountsDefined');?></td>
					</tr>
					<?php
				} 
				?>
			</tbody>
		</table>
		
		<input type="button" value="<?php __('btnAdd'); ?>" class="pj-button b15 cpAddDiscount" />
		<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
	</form>
	
	<table id="tblDiscountsClone" style="display: none">
		<tbody>
			<tr data-id="{INDEX}">
				<td>
					<span class="inline-block">
						<select name="space_id[{INDEX}]" class="pj-form-field w200 required cpSpace" data-index="{INDEX}">
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
						<input type="text" name="min_days[{INDEX}]" class="pj-form-field w50 field-int required digits"/>
					</span>
				</td>
				<td>
					<span class="inline-block">
						<input type="text" name="max_days[{INDEX}]" class="pj-form-field w50 field-int required digits"/>
					</span>
				</td>
				<td>
					<span class="pj-form-field-custom pj-form-field-custom-before float_left r10">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" name="price[{INDEX}]" class="pj-form-field align_right required number w40"/>
					</span>
				</td>
				<td class="align_center"><a href="#" class="pj-delete cpRemoveDiscount"></a></td>
			</tr>
		</tbody>
	</table>
	
	<div id="dialogDuplicateDiscount" style="display: none" title="<?php __('lblDuplicatedDiscountTitle');?>"><?php __('lblDuplicatedDiscountDesc');?></div>
	
	<script type="text/javascript">
		var myLabel = myLabel || {};
		myLabel.no_discounts_defined = "<?php __('lblNoDiscountsDefined');?>";
	</script>
	<?php
}
?>