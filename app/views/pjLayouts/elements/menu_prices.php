<?php
$active = ' ui-tabs-active ui-state-active';
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminPrices' && $_GET['action'] == 'pjActionIndex' ? $active : null;?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPrices&amp;action=pjActionIndex"><?php __('tabRegular'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminPrices' && $_GET['action'] == 'pjActionDiscount' ? $active : null;?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPrices&amp;action=pjActionDiscount"><?php __('tabDiscounts'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminPrices'  && $_GET['action'] == 'pjActionCode' ? $active : null;?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPrices&amp;action=pjActionCode"><?php __('tabPromoCodes'); ?></a></li>
	</ul>
</div>