<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice(NULL, $status[1]);
			break;
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	include dirname(__FILE__) . '/elements/menu.php';
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjLog&amp;action=pjActionConfig" method="post" class="pj-form">
		<input type="hidden" name="update_config" value="1" />
		<fieldset class="fieldset white">
			<legend><?php __('plugin_log_menu_config'); ?></legend>
			<?php
			foreach ($tpl['data'] as $file)
			{
				preg_match('/(\w+)\.controller\.php/', $file, $match);
				if (isset($match[1]))
				{
					?><div class="b5"><label><input type="checkbox" name="filename[]" value="<?php echo $match[1]; ?>"<?php echo in_array($match[1], $tpl['config_arr']) ? ' checked="checked"' : NULL; ?> /> <?php echo $match[1]; ?></label></div><?php
				}
			}
			?>
			<p><input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" /></p>
		</fieldset>
	</form>
	<?php
}
?>