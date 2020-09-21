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
	pjUtil::printNotice(__('infoPricesTitle', true), __('infoPricesDesc', true));
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$jqTimeFormat = pjUtil::jqTimeFormat($tpl['option_arr']['o_time_format']);
	
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPrices&amp;action=pjActionIndex" method="post" id="frmUpdatePrice" class="pj-form form">
		<input type="hidden" name="price_update" value="1" />
		
		<table class="pj-table b15" id="tblPrices" cellpadding="0" cellspacing="0" style="width: 100%;">
			<thead>
				<tr>
					<th style="width: 34%;"><?php __('lblSpace'); ?></th>
					<th style="width: 21%;"><?php __('lblFromDate'); ?></th>
					<th style="width: 21%;"><?php __('lblToDate'); ?></th>
					<th style="width: 18%;"><?php __('lblPrice'); ?></th>
					<th style="width: 60px;">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!empty($tpl['price_arr']))
				{
					foreach ($tpl['price_arr'] as $price)
					{
						?>
						<tr data-id="<?php echo $price['id']; ?>">
							<td>
								<span class="inline-block">
									<input type="hidden" name="id[<?php echo $price['id']; ?>]" value="<?php echo $price['id']; ?>" />
									<select name="space_id[<?php echo $price['id'];?>]" class="pj-form-field w230 required">
										<option value="">-- <?php __('lblChoose'); ?>--</option>
										<?php
										foreach ($tpl['space_arr'] as $space)
										{
											?><option value="<?php echo $space['id']; ?>"<?php echo $space['id'] == $price['space_id'] ? ' selected="selected"' : null;?>><?php echo pjSanitize::html($space['name']); ?></option><?php
										}
										?>
									</select>
								</span>
							</td>
							<td>
								<span class="pj-form-field-custom pj-form-field-custom-after float_left">
									<input type="text" name="date_from[<?php echo $price['id'];?>]" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($price['date_from']));?>" class="pj-form-field pointer w90 datepick required" readonly="readonly" data-index="<?php echo $price['id'];?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
									<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
								</span>
							</td>
							<td>
								<span class="pj-form-field-custom pj-form-field-custom-after float_left">
									<input type="text" name="date_to[<?php echo $price['id'];?>]" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($price['date_to']));?>" class="pj-form-field pointer w90 datepick required" readonly="readonly" data-index="<?php echo $price['id'];?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
									<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
								</span>
							</td>
							<td>
								<span class="pj-form-field-custom pj-form-field-custom-before float_left r10">
									<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
									<input type="text" name="price[<?php echo $price['id'];?>]" value="<?php echo $price['price'];?>" class="pj-form-field required number w70"/>
								</span>
							</td>
							<td class="align_center"><a href="#" class="pj-delete cpRemovePrice"></a></td>
						</tr>
						<?php
					}
				}else{ 
					?>
					<tr>
						<td colspan="5" class="cpNoPrices"><?php __('lblNoPricesDefined');?></td>
					</tr>
					<?php
				} 
				?>
			</tbody>
		</table>
		<input type="button" value="<?php __('btnAdd'); ?>" class="pj-button b15 cpAddPrice" />
		<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
	</form>
	
	<table id="tblPricesClone" style="display: none">
		<tbody>
			<tr data-id="{INDEX}">
				<td>
					<span class="inline-block">
						<select name="space_id[{INDEX}]" class="pj-form-field w230 required">
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
					<span class="pj-form-field-custom pj-form-field-custom-after float_left">
						<input type="text" name="date_from[{INDEX}]" class="pj-form-field pointer w90 datepick required" readonly="readonly" data-index="{INDEX}" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
						<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					</span>
				</td>
				<td>
					<span class="pj-form-field-custom pj-form-field-custom-after float_left">
						<input type="text" name="date_to[{INDEX}]" class="pj-form-field pointer w90 datepick required" readonly="readonly" data-index="{INDEX}" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
						<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					</span>
				</td>
				<td>
					<span class="pj-form-field-custom pj-form-field-custom-before float_left r10">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" name="price[{INDEX}]" class="pj-form-field required number w70"/>
					</span>
				</td>
				<td class="align_center"><a href="#" class="pj-delete cpRemovePrice"></a></td>
			</tr>
		</tbody>
	</table>
	
	<div id="dialogDuplicate" style="display: none" title="<?php __('lblDuplicatedPeriodTitle');?>"><?php __('lblDuplicatedPeriodDesc');?></div>
	<div id="dialogEmptyPrice" style="display: none" title="<?php __('lblPricesSavedTitle');?>"><?php __('lblPricesSavedDesc');?></div>
	
	<script type="text/javascript">
		var myLabel = myLabel || {};
		myLabel.no_prices_defined = "<?php __('lblNoPricesDefined');?>";
	</script>
	<?php
}
?>