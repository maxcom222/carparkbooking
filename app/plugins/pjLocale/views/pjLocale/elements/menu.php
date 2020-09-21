<?php
$active = ' ui-tabs-active ui-state-active';
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<?php if (isset($tpl['option_arr']['o_multi_lang']) && (int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['action'] == 'pjActionLocales' ? $active : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjLocale&amp;action=pjActionLocales&amp;tab=1"><?php __('plugin_locale_languages'); ?></a></li>
		<?php endif; ?>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['action'] == 'pjActionIndex' ? $active : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjLocale&amp;action=pjActionIndex&amp;tab=1"><?php __('plugin_locale_titles'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo in_array($_GET['action'], array('pjActionImportExport', 'pjActionImportConfirm')) ? $active : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjLocale&amp;action=pjActionImportExport&amp;tab=1"><?php __('plugin_locale_import_export'); ?></a></li>
	</ul>
</div>