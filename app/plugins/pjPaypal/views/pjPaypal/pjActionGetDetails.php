<?php
if (isset($tpl['arr']) && !empty($tpl['arr']))
{
	?>
	<div class="form pj-form">
		<p>
			<label class="title bold"><?php __('plugin_paypal_foreign_id'); ?></label>
			<span class="left"><?php echo $tpl['arr']['foreign_id']; ?></span>
		</p>
		<p>
			<label class="title bold"><?php __('plugin_paypal_subscr_id'); ?></label>
			<span class="left"><?php echo $tpl['arr']['subscr_id']; ?></span>
		</p>
		<p>
			<label class="title bold"><?php __('plugin_paypal_txn_id'); ?></label>
			<span class="left"><?php echo $tpl['arr']['txn_id']; ?></span>
		</p>
		<p>
			<label class="title bold"><?php __('plugin_paypal_txn_type'); ?></label>
			<span class="left"><?php echo $tpl['arr']['txn_type']; ?></span>
		</p>
		<p>
			<label class="title bold"><?php __('plugin_paypal_mc_gross'); ?></label>
			<span class="left"><?php echo $tpl['arr']['mc_gross']; ?></span>
		</p>
		<p>
			<label class="title bold"><?php __('plugin_paypal_mc_currency'); ?></label>
			<span class="left"><?php echo $tpl['arr']['mc_currency']; ?></span>
		</p>
		<p>
			<label class="title bold"><?php __('plugin_paypal_payer_email'); ?></label>
			<span class="left"><?php echo $tpl['arr']['payer_email']; ?></span>
		</p>
		<p>
			<label class="title bold"><?php __('plugin_paypal_dt'); ?></label>
			<span class="left"><?php echo $tpl['arr']['dt']; ?></span>
		</p>
	</div>
	<?php
}
?>