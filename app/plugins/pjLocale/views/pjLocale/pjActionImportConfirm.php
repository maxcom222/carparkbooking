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
		if (isset($tpl['tm_text']))
		{
			pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']] . ' ' . $tpl['tm_text'], false);
		} else {
			pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
		}
	}
	
	if (isset($_GET['key']))
	{
		?>
		<fieldset class="fieldset white">
			<legend><?php __('plugin_locale_import'); ?></legend>
			<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjLocale&amp;action=pjActionImport" method="post" class="form pj-form">
				<input type="hidden" name="import" value="1" />
				<input type="hidden" name="key" value="<?php echo pjSanitize::html($_GET['key']); ?>" />
				<?php
				$STORE = @$_SESSION[$_GET['key']];
				if (isset($tpl['locale_arr']) && !empty($tpl['locale_arr']))
				{
					foreach ($tpl['locale_arr'] as $locale)
					{
						?><p><label><input type="checkbox" name="locale[]" value="<?php echo $locale['id']; ?>" checked="checked"<?php echo !is_array(@$STORE['locales']) || !in_array($locale['id'], $STORE['locales']) ? ' disabled="disabled"' : NULL; ?> /> <?php echo pjSanitize::html($locale['title'] . (!empty($locale['region']) ? sprintf(' (%s)', $locale['region']): NULL)); ?></label></p><?php
					}
				}
				?>
				<p>
					<input type="submit" value="<?php __('plugin_locale_import'); ?>" class="pj-button" />
				</p>
			</form>
		</fieldset>
		<?php
	}
}
?>