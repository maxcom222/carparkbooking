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
	pjUtil::printNotice(__('infoExtraUpdateTitle', true), __('infoExtraUpdateDesc', true));
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionUpdate" method="post" id="frmUpdateExtra" class="pj-form form">
		<input type="hidden" name="extra_update" value="1" />
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
						<textarea id="i18n_description_<?php echo $v['id'];?>" name="i18n[<?php echo $v['id']; ?>][description]" class="pj-form-field w500 h150" lang="<?php echo $v['id']; ?>"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['description'])); ?></textarea>
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
						<?php endif; ?>
					</span>
				</p>
				<?php
			}
			?>
			<p>
				<label class="title"><?php __('lblPrice'); ?></label>
				<span class="inline_block">
					<span class="pj-form-field-custom pj-form-field-custom-before float_left r10">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
						<input type="text" id="price" name="price" class="pj-form-field required number w80" value="<?php echo pjSanitize::html($tpl['arr']['price']);?>"/>
					</span>
					<select name="per" id="per" class="pj-form-field foat_left">
						<?php
						foreach (__('extra_per', true) as $k => $v)
						{
							?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['per'] == $k ? ' selected="selected"' : null;?>><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</span>
			</p>
			<p>
				<label class="title"><?php __('lblType');?></label>
				<span class="inline_block">
					<select name="type" id="type" class="pj-form-field w100">
						<?php
						foreach (__('extra_types', true) as $k => $v)
						{
							?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['type'] == $k ? ' selected="selected"' : null;?>><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</span>
			</p>
			<p class="cpLimitedCount" style="display: <?php echo $tpl['arr']['type'] == 'unlimited' ? 'none' : 'block'; ?>">
				<label class="title"><?php __('lblCount');?></label>
				<span class="inline_block">
					<input type="text" id="cnt" name="cnt" class="pj-form-field digits w80 field-int" value="<?php echo pjSanitize::html($tpl['arr']['cnt']);?>"/>
				</span>
			</p>
			<p>
				<label class="title"><?php __('lblIsSingle');?></label>
				<span class="inline_block">
					<label class="content"><input type="checkbox" name="is_single" id="is_single" value="1"<?php echo $tpl['arr']['is_single'] == '1' ? ' checked="checked"' : null; ?>/></label>
				</span>
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
				<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminExtras&action=pjActionIndex';" />
			</p>
		</div>
	</form>
	
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
	myLabel.same_extra = "<?php __('lblSameExtra'); ?>";
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