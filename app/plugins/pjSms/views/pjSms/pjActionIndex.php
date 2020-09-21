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
	include PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
	
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice($titles['PSS01'], $bodies['PSS01']);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	?>
	<fieldset class="fieldset white">
		<legend><?php __('plugin_sms_config'); ?></legend>
		<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjSms&amp;action=pjActionIndex" method="post" class="pj-form form">
			<input type="hidden" name="sms_post" value="1" />
			<p>
				<label class="title"><?php __('plugin_sms_api'); ?></label>
				<span class="left">
					<input type="text" name="plugin_sms_api_key" id="plugin_sms_api_key" class="pj-form-field w400" value="<?php echo htmlspecialchars(stripslashes(@$tpl['option_arr']['plugin_sms_api_key'])); ?>" />
				</span>
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button float_left align_middle" />
			</p>
		</form>
	</fieldset>
	
	<div class="b10">
		<form action="" method="get" class="pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
		</form>
	</div>
	
	<div id="grid"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.queryString = "";
	var myLabel = myLabel || {};
	myLabel.created = "<?php __('plugin_sms_created'); ?>";
	myLabel.number = "<?php __('plugin_sms_number'); ?>";
	myLabel.text = "<?php __('plugin_sms_text'); ?>";
	myLabel.status = "<?php __('plugin_sms_status'); ?>";
	</script>
	<?php
}
?>