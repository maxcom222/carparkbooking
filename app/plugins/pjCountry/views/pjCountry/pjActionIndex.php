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
	$plugin_menu = PJ_VIEWS_PATH . sprintf('pjLayouts/elements/menu_%s.php', $controller->getConst('PLUGIN_NAME'));
	if (is_file($plugin_menu))
	{
		include $plugin_menu;
	}
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['PCY12'], @$bodies['PCY12']);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	$statuses = __('plugin_country_statuses', true);
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
		<input type="hidden" name="controller" value="pjCountry" />
		<input type="hidden" name="action" value="pjActionCreate" />
		<input type="submit" class="pj-button" value="<?php __('plugin_country_btn_add'); ?>" />
		<p>&nbsp;</p>
	</form>
	
	<div class="b10">
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('plugin_country_btn_search'); ?>" />
		</form>
		<div class="float_right t5">
			<a href="#" class="pj-button btn-all"><?php __('plugin_country_btn_all'); ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="T"><?php echo $statuses['T']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="F"><?php echo $statuses['F']; ?></a>
		</div>
		<br class="clear_both" />
	</div>
	
	<div id="grid"></div>
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.country = "<?php __('plugin_country_name'); ?>";
	myLabel.alpha_2 = "<?php __('plugin_country_alpha_2'); ?>";
	myLabel.alpha_3 = "<?php __('plugin_country_alpha_3'); ?>";
	myLabel.status = "<?php __('plugin_country_status'); ?>";
	myLabel.revert_status = "<?php __('plugin_country_revert_status'); ?>";
	myLabel.active = "<?php echo $statuses['T']; ?>";
	myLabel.inactive = "<?php echo $statuses['F']; ?>";
	myLabel.delete_confirmation = "<?php __('plugin_country_delete_confirmation'); ?>";
	myLabel.delete_selected = "<?php __('plugin_country_delete_selected'); ?>";
	</script>
	<?php
}
?>