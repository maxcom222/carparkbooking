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
	include dirname(__FILE__) . '/elements/menu.php';
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['POA01'], @$bodies['POA01']);
	?>
	<div class="b10">
		<a href="#" class="pj-button btn-add"><?php __('plugin_one_admin_btn_add'); ?></a>
	</div>

	<div id="grid"></div>
	<?php
}
?>