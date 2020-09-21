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
	?>
	<div class="b10">
		<a href="#" class="pj-button btn-empty"><?php __('plugin_log_btn_empty'); ?></a>
	</div>

	<div id="grid"></div>
	<?php
}
?>