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
	pjUtil::printNotice(@$titles['PBU01'], @$bodies['PBU01']);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	?>
	<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjBackup&amp;action=pjActionIndex" method="post" class="pj-form form">
		<input type="hidden" name="backup" value="1" />
		<fieldset class="fieldset white overflow">
			<legend><?php __('plugin_backup_menu_backup'); ?></legend>
			<div class="float_left w200">
				<p>
					<label class="title"><?php __('plugin_backup_database'); ?></label>
					<span class="left">
						<input type="checkbox" name="db" value="1" checked="checked" />
					</span>
				</p>
				<p>
					<label class="title"><?php __('plugin_backup_files'); ?></label>
					<span class="left">
						<input type="checkbox" name="files" value="1" checked="checked" />
					</span>
				</p>
			</div>
			<p class="float_left">
				<input type="submit" value="<?php __('plugin_backup_btn_backup'); ?>" class="pj-button" />
			</p>
		</fieldset>
	</form>
	
	<div id="grid"></div>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.datetime = "<?php __('plugin_backup_datetime'); ?>";
	myLabel.type = "<?php __('plugin_backup_type'); ?>";
	myLabel.file = "<?php __('plugin_backup_file'); ?>";
	myLabel.size = "<?php __('plugin_backup_size'); ?>";
	myLabel.delete_confirmation = "<?php __('plugin_backup_delete_confirmation'); ?>";
	myLabel.delete_selected = "<?php __('plugin_backup_delete_selected'); ?>";
	</script>
	<?php
}
?>