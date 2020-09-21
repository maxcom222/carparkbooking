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
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/menu_spaces.php';
	pjUtil::printNotice(__('infoSpaceUpdateTitle', true), __('infoSpaceUpdateDesc', true));
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$jqTimeFormat = pjUtil::jqTimeFormat($tpl['option_arr']['o_time_format']);
	
	$today = date($tpl['option_arr']['o_date_format'], time());
	$next_year = date($tpl['option_arr']['o_date_format'], strtotime('+1 years'));
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSpaces&amp;action=pjActionUpdate" method="post" id="frmUpdateSpace" class="pj-form form">
		<input type="hidden" name="space_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id'];?>" />
		<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
		<div class="multilang"></div>
		<?php endif; ?>
		<div class="clear_both">
			<?php
			foreach ($tpl['lp_arr'] as $v)
			{
			?>
				<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
					<label class="title"><?php __('lblName'); ?></label>
					<span class="inline_block">
						<input type="text" id="i18n_name_<?php echo $v['id'];?>" name="i18n[<?php echo $v['id']; ?>][name]" class="pj-form-field w300<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" lang="<?php echo $v['id']; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['name'])); ?>"/>
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
						<?php endif; ?>
					</span>
				</p>
				<?php
			}
			foreach ($tpl['lp_arr'] as $v)
			{
			?>
				<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
					<label class="title"><?php __('lblDescription'); ?></label>
					<span class="inline_block">
						<textarea id="i18n_description_<?php echo $v['id'];?>" name="i18n[<?php echo $v['id']; ?>][description]" class="pj-form-field w500 h150<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" lang="<?php echo $v['id']; ?>"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['description'])); ?></textarea>
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
						<?php endif; ?>
					</span>
				</p>
				<?php
			}
			?>
			<div class="p">
				<label class="title"><?php __('lblSpacesAvailable'); ?></label>
				<div class="inline_block">
					<table class="pj-table b15" id="tblSpaces" cellpadding="0" cellspacing="0" style="width: 500px;">
						<thead>
							<tr>
								<th style="width: 150px;"><?php __('lblFrom'); ?></th>
								<th style="width: 150px;"><?php __('lblTo'); ?></th>
								<th style="width: 100px;"><?php __('lblSpaces'); ?></th>
								<th style="width: 24px;">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($tpl['sd_arr'] as $k => $v)
							{ 
								$index = $v['id'];
								?>
								<tr>
									<td>
										<span class="pj-form-field-custom pj-form-field-custom-after">
											<input type="text" name="date_from[<?php echo $index;?>]" class="pj-form-field pointer w90 datepick required" readonly="readonly" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($v['date_from']));?>" data-index="<?php echo $index; ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
											<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
										</span>
									</td>
									<td>
										<span class="pj-form-field-custom pj-form-field-custom-after">
											<input type="text" name="date_to[<?php echo $index;?>]" class="pj-form-field pointer w90 datepick required" readonly="readonly" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($v['date_to']));?>" data-index="<?php echo $index; ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
											<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
										</span>
									</td>
									<td>
										<input type="text" name="spaces[<?php echo $index;?>]" class="pj-form-field w70 field-int required digits" value="<?php echo pjSanitize::html($v['spaces']);?>"/>
									</td>
									<?php
									if($k == 0)
									{ 
										?><td>&nbsp;</td><?php
									}else{
										?><td class="align_center"><a href="#" class="pj-delete cpRemoveDate"></a></td><?php
									} 
									?>
								</tr>
								<?php
							} 
							?>
						</tbody>
					</table>
					<input type="button" value="<?php __('btnAddPeriod'); ?>" class="pj-button cpAddDate" />
				</div>
			</div>
			<p>
				<label class="title"><?php __('lblStatus'); ?></label>
				<span class="inline_block">
					<select name="status" id="status" class="pj-form-field required">
						<option value="">-- <?php __('lblChoose'); ?>--</option>
						<?php
						foreach (__('u_statarr', true) as $k => $v)
						{
							?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['status'] ? ' selected="selected"' : null;?>><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</span>
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
				<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminSpaces&action=pjActionIndex';" />
			</p>
		</div>
	</form>
	
	<?php
	include_once PJ_VIEWS_PATH . 'pjAdminSpaces/elements/clone.php';
	?>
	
	<script type="text/javascript">
	var locale_array = new Array(); 
	var myLabel = myLabel || {};
	myLabel.field_required = "<?php __('pj_field_required'); ?>";
	<?php
	foreach ($tpl['lp_arr'] as $v)
	{
		?>locale_array.push(<?php echo $v['id'];?>);<?php
	} 
	?>
	myLabel.locale_array = locale_array;
	myLabel.same_space = "<?php __('lblSameSpace'); ?>";
	(function ($) {
		$(function() {
			$(".multilang").multilang({
				langs: <?php echo $tpl['locale_str']; ?>,
				flagPath: "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/",
				select: function (event, ui) {
					
				}
			});
		});
	})(jQuery_1_8_2);
	</script>
	<?php
}
?>