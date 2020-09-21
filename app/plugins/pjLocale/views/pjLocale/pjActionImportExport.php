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
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
	include dirname(__FILE__) . '/elements/menu.php';
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	pjUtil::printNotice(__('plugin_locale_ie_title', true), __('plugin_locale_ie_body', true));
	?>
	
	<fieldset class="fieldset white">
		<legend><?php __('plugin_locale_import'); ?></legend>
		<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjLocale&amp;action=pjActionImportConfirm" method="post" class="form pj-form" enctype="multipart/form-data">
			<input type="hidden" name="import" value="1" />
			<p>
				<label class="title"><?php __('plugin_locale_separator'); ?></label>
				<select name="separator" class="pj-form-field">
				<?php
				foreach (__('plugin_locale_separators', true) as $k => $v)
				{
					?><option value="<?php echo $k; ?>"><?php echo pjSanitize::html($v); ?></option><?php
				}
				?>
				</select>
			</p>
			<p>
				<label class="title"><?php __('plugin_locale_browse'); ?></label>
				<input type="file" name="file" class="pj-form-field" />
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" value="<?php __('plugin_locale_import'); ?>" class="pj-button" />
			</p>
		</form>
	</fieldset>
	
	<fieldset class="fieldset white">
		<legend><?php __('plugin_locale_export'); ?></legend>
		<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjLocale&amp;action=pjActionExport" method="post" class="form pj-form">
			<input type="hidden" name="export" value="1" />
			<p>
				<label class="title"><?php __('plugin_locale_separator'); ?></label>
				<select name="separator" class="pj-form-field">
				<?php
				foreach (__('plugin_locale_separators', true) as $k => $v)
				{
					?><option value="<?php echo $k; ?>"><?php echo pjSanitize::html($v); ?></option><?php
				}
				?>
				</select>
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" value="<?php __('plugin_locale_export'); ?>" class="pj-button" />
			</p>
		</form>
	</fieldset>
	<?php
}
?>