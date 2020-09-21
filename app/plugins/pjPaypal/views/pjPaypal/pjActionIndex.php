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
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjPaypal&amp;action=pjActionIndex"><?php __('plugin_paypal_menu_ipn'); ?></a></li>
		</ul>
	</div>

	<div id="grid"></div>
	
	<div id="dialogPaypalInfo" style="display: none" title="<?php __('plugin_paypal_info_title', false, true); ?>"></div>
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.foreign_id = <?php echo pjAppController::jsonEncode(__('plugin_paypal_foreign_id', true)); ?>;
	myLabel.subscr_id = <?php echo pjAppController::jsonEncode(__('plugin_paypal_subscr_id', true)); ?>;
	myLabel.txn_id = <?php echo pjAppController::jsonEncode(__('plugin_paypal_txn_id', true)); ?>;
	myLabel.txn_type = <?php echo pjAppController::jsonEncode(__('plugin_paypal_txn_type', true)); ?>;
	myLabel.gross = <?php echo pjAppController::jsonEncode(__('plugin_paypal_mc_gross', true)); ?>;
	myLabel.currency = <?php echo pjAppController::jsonEncode(__('plugin_paypal_mc_currency', true)); ?>;
	myLabel.email = <?php echo pjAppController::jsonEncode(__('plugin_paypal_payer_email', true)); ?>;
	myLabel.dt = <?php echo pjAppController::jsonEncode(__('plugin_paypal_dt', true)); ?>;
	myLabel.btn_view = <?php echo pjAppController::jsonEncode(__('plugin_paypal_btn_view', true)); ?>;
	myLabel.btn_close = <?php echo pjAppController::jsonEncode(__('plugin_paypal_btn_close', true)); ?>;
	</script>
	<?php
}
?>