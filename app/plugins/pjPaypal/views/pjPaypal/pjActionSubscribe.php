<?php
# HTML variables
# https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/#id08A6HI00JQU

$url = PJ_TEST_MODE ? 'https://sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
?>
<form action="<?php echo $url; ?>" method="post"
	style="display: inline"
	class="<?php echo pjSanitize::html(@$tpl['arr']['class']); ?>"
	name="<?php echo pjSanitize::html($tpl['arr']['name']); ?>"
	id="<?php echo pjSanitize::html($tpl['arr']['id']); ?>"
	target="<?php echo pjSanitize::html(@$tpl['arr']['target']); ?>">
	<input type="hidden" name="cmd" value="_xclick-subscriptions" />
	<input type="hidden" name="business" value="<?php echo pjSanitize::html($tpl['arr']['business']); ?>" />
	<input type="hidden" name="item_name" value="<?php echo pjSanitize::html($tpl['arr']['item_name']); ?>" />
	<input type="hidden" name="custom" value="<?php echo pjSanitize::html($tpl['arr']['custom']); ?>" />
	<input type="hidden" name="currency_code" value="<?php echo pjSanitize::html($tpl['arr']['currency_code']); ?>" />
	<input type="hidden" name="lc" value="US" />
	<input type="hidden" name="no_note" value="1" />
	<input type="hidden" name="no_shipping" value="1" />
	<?php
	if (isset($tpl['arr']['a1_price'], $tpl['arr']['p1_duration'], $tpl['arr']['t1_duration_unit']))
	{
		?>
		<input type="hidden" name="a1" value="<?php echo number_format($tpl['arr']['a1_price'], 2, '.', ''); ?>" />
		<input type="hidden" name="p1" value="<?php echo (int) $tpl['arr']['p1_duration']; ?>" />
		<input type="hidden" name="t1" value="<?php echo pjSanitize::html($tpl['arr']['t1_duration_unit']); ?>" />
		<?php
	}
	if (isset($tpl['arr']['a2_price'], $tpl['arr']['p2_duration'], $tpl['arr']['t2_duration_unit']))
	{
		?>
		<input type="hidden" name="a2" value="<?php echo number_format($tpl['arr']['a2_price'], 2, '.', ''); ?>" />
		<input type="hidden" name="p2" value="<?php echo (int) $tpl['arr']['p2_duration']; ?>" />
		<input type="hidden" name="t2" value="<?php echo pjSanitize::html($tpl['arr']['t2_duration_unit']); ?>" />
		<?php
	}
	?>
	<input type="hidden" name="a3" value="<?php echo number_format($tpl['arr']['a3_price'], 2, '.', ''); ?>" />
	<input type="hidden" name="p3" value="<?php echo (int) $tpl['arr']['p3_duration']; ?>" />
	<input type="hidden" name="t3" value="<?php echo pjSanitize::html($tpl['arr']['t3_duration_unit']); ?>" />
	<?php
	if (isset($tpl['arr']['recurring_payments']) && in_array((int) $tpl['arr']['recurring_payments'], array(0,1)))
	{
		// The default is 0
		?><input type="hidden" name="src" value="<?php echo (int) $tpl['arr']['recurring_payments']; ?>" />
		<?php
		if ((int) $tpl['arr']['recurring_payments'] === 1
			&& isset($tpl['arr']['recurring_times'])
			&& in_array((int) $tpl['arr']['recurring_times'], range(2,52)))
		{
			?><input type="hidden" name="srt" value="<?php echo (int) $tpl['arr']['recurring_times']; ?>" />
			<?php
		}
	}
	if (isset($tpl['arr']['reattempt_on_failure']) && in_array((int) $tpl['arr']['reattempt_on_failure'], array(0,1)))
	{
		// The default is 1
		?><input type="hidden" name="sra" value="<?php echo (int) $tpl['arr']['reattempt_on_failure']; ?>" />
		<?php
	}
	if (isset($tpl['arr']['return']) && !empty($tpl['arr']['return']))
	{
		?>
		<input type="hidden" name="rm" value="2" />
		<input type="hidden" name="return" value="<?php echo $tpl['arr']['return']; ?>" />
		<?php
	}
	if (isset($tpl['arr']['cancel_return']) && !empty($tpl['arr']['cancel_return']))
	{
		?><input type="hidden" name="cancel_return" value="<?php echo $tpl['arr']['cancel_return']; ?>" />
		<?php
	}
	if (isset($tpl['arr']['notify_url']) && !empty($tpl['arr']['notify_url']))
	{
		?><input type="hidden" name="notify_url" value="<?php echo $tpl['arr']['notify_url']; ?>" />
		<?php
	}
	if (isset($tpl['arr']['submit']))
	{
		?><input type="submit" value="<?php echo pjSanitize::html($tpl['arr']['submit']); ?>" class="<?php echo pjSanitize::html(@$tpl['arr']['submit_class']); ?>" />
		<?php
	}
	?>
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>