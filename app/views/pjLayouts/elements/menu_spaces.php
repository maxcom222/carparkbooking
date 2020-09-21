<?php
$active = ' ui-tabs-active ui-state-active';
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminSpaces' ? $active : null;?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSpaces&amp;action=pjActionIndex"><?php __('menuSpaces'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminPrices' ? $active : null;?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPrices&amp;action=pjActionIndex"><?php __('menuPrices'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminExtras' ? $active : null;?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionIndex"><?php __('menuExtras'); ?></a></li>
	</ul>
</div>