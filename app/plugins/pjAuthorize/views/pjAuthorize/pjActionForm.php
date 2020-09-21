<?php
require_once $controller->getConst('PLUGIN_DIR') . 'anet_php_sdk/AuthorizeNet.php';

$transaction_key = $tpl['arr']['transkey'];
$url = PJ_TEST_MODE ? 'https://test.authorize.net/gateway/transact.dll' : 'https://secure2.authorize.net/gateway/transact.dll';

$x_login        = $tpl['arr']['x_login'];
$x_amount       = number_format($tpl['arr']['x_amount'], 2, '.', '');
$x_description  = $tpl['arr']['x_description'];
$x_fp_sequence	= md5(uniqid(rand(), true));
$x_fp_timestamp	= time() + (int) $tpl['arr']['timezone'];
$fingerprint    = AuthorizeNetSIM_Form::getFingerprint($x_login, $transaction_key, $x_amount, $x_fp_sequence, $x_fp_timestamp);
?>
<form method="post" action="<?php echo $url; ?>" style="display: inline" name="<?php echo $tpl['arr']['name']; ?>" id="<?php echo $tpl['arr']['id']; ?>" target="<?php echo @$tpl['arr']['target']; ?>">
	<input type="hidden" name="x_login" value="<?php echo $x_login; ?>" />
	<input type="hidden" name="x_amount" value="<?php echo $x_amount; ?>" />
	<input type="hidden" name="x_description" value="<?php echo $x_description; ?>" />
	<input type="hidden" name="x_invoice_num" value="<?php echo $tpl['arr']['x_invoice_num']; ?>" />
	<input type="hidden" name="x_fp_sequence" value="<?php echo $x_fp_sequence; ?>" />
	<input type="hidden" name="x_fp_timestamp" value="<?php echo $x_fp_timestamp; ?>" />
	<input type="hidden" name="x_fp_hash" value="<?php echo $fingerprint; ?>" />
	<input type="hidden" name="x_test_request" value="<?php echo PJ_TEST_MODE ? 'true' : 'false'; ?>" />
	<input type="hidden" name="x_version" value="3.1" />
	<input type="hidden" name="x_show_form" value="payment_form" />
	<input type="hidden" name="x_method" value="cc" />
	<input type="hidden" name="x_receipt_link_method" value="LINK" />
    <input type="hidden" name="x_receipt_link_url" value="<?php echo $tpl['arr']['x_receipt_link_url']; ?>" />
	<input type="hidden" name="x_relay_response" value="TRUE" />
	<input type="hidden" name="x_relay_url" value="<?php echo $tpl['arr']['x_relay_url']; ?>" />
	<?php
	if (isset($tpl['arr']['submit']))
	{
		?><input type="submit" value="<?php echo htmlspecialchars(@$tpl['arr']['submit']); ?>" class="<?php echo @$tpl['arr']['submit_class']; ?>" /><?php
	}
	?>
</form>