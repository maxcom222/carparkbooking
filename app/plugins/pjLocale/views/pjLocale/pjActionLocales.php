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
	pjUtil::printNotice(__('plugin_locale_index_title', true), __('plugin_locale_index_body', true));
	?>
	<div class="b10">
		<a href="#" class="pj-button btn-add"><?php __('plugin_locale_add_lang'); ?></a>
	</div>
	
	<div id="grid"></div>
	<div id="upload" style="position: absolute; left: -9999px; top: 0;"></div>
	<div id="dialogFlagReset" title="<?php __('plugin_locale_flag_reset_title'); ?>" style="display: none"><?php __('plugin_locale_flag_reset_content'); ?></div>
	<div id="dialogFlagInfo" title="<?php __('plugin_locale_flag_info_title'); ?>" style="display: none"></div>
	<?php
	$languages = array();
	foreach ($tpl['language_arr'] as $item)
	{
		$languages[] = '{value: "'.$item['iso'].'", label: "'.$item['title'].'"}';
	}
	?>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.languages = [];
	<?php
	if (count($languages) > 0)
	{
		printf('pjGrid.languages.push('.join(",", $languages).');');
	}
	?>
	pjGrid.maxFileSize = <?php echo (int) ini_get('upload_max_filesize') * 1024 * 1024; ?>;
	var myLabel = myLabel || {};
	myLabel.language = <?php __encode('plugin_locale_lbl_language'); ?>;
	myLabel.name = <?php __encode('plugin_locale_lbl_fend'); ?>;
	myLabel.flag = <?php __encode('plugin_locale_lbl_flag'); ?>;
	myLabel.dir = <?php __encode('plugin_locale_lbl_dir'); ?>;
	myLabel.directions = <?php __encode('plugin_locale_dir'); ?>;
	myLabel.is_default = <?php __encode('plugin_locale_lbl_is_default'); ?>;
	myLabel.order = <?php __encode('plugin_locale_lbl_order'); ?>;
	myLabel.btn_reset = <?php __encode('plugin_locale_btn_reset'); ?>;
	myLabel.btn_cancel = <?php __encode('plugin_locale_button_cancel'); ?>;
	myLabel.btn_close = <?php __encode('plugin_locale_btn_close'); ?>;
	myLabel.tooltip_reset = <?php __encode('plugin_locale_tooltip_reset'); ?>;
	myLabel.tooltip_upload = <?php __encode('plugin_locale_tooltip_upload'); ?>;
	</script>
	<?php
}
?>