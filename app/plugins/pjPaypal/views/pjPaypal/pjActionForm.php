<?php
$url = PJ_TEST_MODE ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
?>
<form action="<?php echo $url; ?>" method="post" style="display: inline" name="<?php echo $tpl['arr']['name']; ?>" id="<?php echo $tpl['arr']['id']; ?>" target="<?php echo $tpl['arr']['target']; ?>">
	<input type="hidden" name="cmd" value="_xclick" />
	<input type="hidden" name="business" value="<?php echo $tpl['arr']['business']; ?>" />
	<input type="hidden" name="item_name" value="<?php echo htmlspecialchars($tpl['arr']['item_name']); ?>" />
	<input type="hidden" name="item_number" value="1" />
	<input type="hidden" name="custom" value="<?php echo $tpl['arr']['custom']; ?>" />
	<input type="hidden" name="amount" value="<?php echo number_format($tpl['arr']['amount'], 2, '.', ''); ?>" />
	<input type="hidden" name="no_shipping" value="1" />
	<input type="hidden" name="no_note" value="1" />
	<input type="hidden" name="currency_code" value="<?php echo $tpl['arr']['currency_code']; ?>" />
    <input type="hidden" name="return" value="<?php echo $tpl['arr']['return']; ?>" />
	<input type="hidden" name="notify_url" value="<?php echo $tpl['arr']['notify_url']; ?>" />
	<input type="hidden" name="lc" value="US" />
	<input type="hidden" name="rm" value="2" />
	<input type="hidden" name="bn" value="PP-BuyNowBF" />
	<?php
	if(isset($tpl['arr']['charset']))
	{
		?><input type="hidden" name="charset" value="<?php echo $tpl['arr']['charset'];?>" /><?php
	}
	if (isset($tpl['arr']['submit']))
	{
		?><input type="submit" value="<?php echo htmlspecialchars($tpl['arr']['submit']); ?>" class="<?php echo @$tpl['arr']['submit_class']; ?>" /><?php
	}
	?>
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>