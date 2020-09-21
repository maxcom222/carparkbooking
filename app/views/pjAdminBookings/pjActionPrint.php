<?php
$personal_titles = __('personal_titles', true, false);
$payment_methods = __('payment_methods', true, false);
$cc_types = __('cc_types', true, false); 
$booking_statuses = __('booking_statuses', true, false);
?>
<table class="table" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2"><b><?php __('tabBookingDetails');?></b></td>
	</tr>
	<tr>
		<td><?php __('lblID')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['id']);?></td>
	</tr>
	<tr>
		<td><?php __('lblUniqueID')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['uuid']);?></td>
	</tr>
	<tr>
		<td><?php __('lblSpace')?></td>
		<td><?php echo pjSanitize::html($tpl['space']);?></td>
	</tr>
	<tr>
		<td><?php __('lblExtra')?></td>
		<td><?php echo stripslashes($tpl['extras']);?></td>
	</tr>
	<tr>
		<td><?php __('lblFrom')?></td>
		<td><?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['from'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['from']));;?></td>
	</tr>
	<tr>
		<td><?php __('lblTo')?></td>
		<td><?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['to'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['to']));;?></td>
	</tr>
	<tr>
		<td><?php __('lblSubTotal')?></td>
		<td><?php echo pjUtil::formatCurrencySign($tpl['arr']['sub_total'], $tpl['option_arr']['o_currency']);;?></td>
	</tr>
	<tr>
		<td><?php __('lblTax')?></td>
		<td><?php echo pjUtil::formatCurrencySign($tpl['arr']['tax'], $tpl['option_arr']['o_currency']);;?></td>
	</tr>
	<tr>
		<td><?php __('lblTotal')?></td>
		<td><?php echo pjUtil::formatCurrencySign($tpl['arr']['total'], $tpl['option_arr']['o_currency']);;?></td>
	</tr>
	<tr>
		<td><?php __('lblDeposit')?></td>
		<td><?php echo pjUtil::formatCurrencySign($tpl['arr']['deposit'], $tpl['option_arr']['o_currency']);;?></td>
	</tr>
	<tr>
		<td><?php __('lblPaymentMethod')?></td>
		<td><?php echo $payment_methods[$tpl['arr']['payment_method']];?></td>
	</tr>
	<?php
	if($tpl['arr']['payment_method'] == 'creditcard')
	{
		?>
		<tr>
			<td><?php __('lblCCType')?></td>
			<td><?php echo $cc_types[$tpl['arr']['cc_type']];?></td>
		</tr>
		<tr>
			<td><?php __('lblCCNum')?></td>
			<td><?php echo pjSanitize::html($tpl['cc_num']);?></td>
		</tr>
		<tr>
			<td><?php __('lblCCExp')?></td>
			<td><?php echo pjSanitize::html($tpl['cc_exp_month']);?> - <?php echo pjSanitize::html($tpl['cc_exp_year']);?></td>
		</tr>
		<tr>
			<td><?php __('lblCCCode')?></td>
			<td><?php echo pjSanitize::html($tpl['cc_code']);?></td>
		</tr>
		<?php
	} 
	?>
	<tr>
		<td><?php __('lblStatus')?></td>
		<td><?php echo $booking_statuses[$tpl['arr']['status']];?></td>
	</tr>
	<tr>
		<td><?php __('lblProcessedOn')?></td>
		<td><?php echo !empty($tpl['arr']['processed_on']) ? date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['processed_on'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['processed_on'])) : null;?></td>
	</tr>
	<tr>
		<td colspan="2"><b><?php __('tabClientDetails');?></b></td>
	</tr>
	<tr>
		<td><?php __('lblBookingRegNo')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_regno']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingMake')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_make']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingModel')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_model']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingTitle')?></td>
		<td><?php echo $personal_titles[$tpl['arr']['c_title']];?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingName')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_name']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingEmail')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_email']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingPhone')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_phone']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingNotes')?></td>
		<td><?php echo stripslashes(nl2br($tpl['arr']['c_notes']));?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingCompany')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_company']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingAddress')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_address']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingCity')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_city']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingState')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_state']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingZip')?></td>
		<td><?php echo pjSanitize::html($tpl['arr']['c_zip']);?></td>
	</tr>
	<tr>
		<td><?php __('lblBookingCountry')?></td>
		<td><?php echo pjSanitize::html($tpl['country']);?></td>
	</tr>
</table>