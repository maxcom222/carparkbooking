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

	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$jqTimeFormat = pjUtil::jqTimeFormat($tpl['option_arr']['o_time_format']);
	
	pjUtil::printNotice(__('infoReportsTitle', true), __('infoReportsDesc', true));
	
	$booking_statuses = __('booking_statuses', true);
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="form pj-form">
		<input type="hidden" name="controller" value="pjAdminReports" />
		<input type="hidden" name="action" value="pjActionIndex" />
		<p>
			<span class="block float_left r20">
				<label class="block float_left r5 t6"><?php __('lblFrom'); ?></label>
				<span class="pj-form-field-custom pj-form-field-custom-after">
					<input type="text" name="date_from" class="pj-form-field pointer w100 datepick" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : null;?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>"/>
					<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
				</span>
			</span>
			<span class="block float_left r20">
				<label class="block float_left r5 t6"><?php __('lblTo'); ?></label>
				<span class="pj-form-field-custom pj-form-field-custom-after">
					<input type="text" name="date_to" class="pj-form-field pointer w100 datepick" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : null;?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>"/>
					<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
				</span>
			</span>
			<span class="block float_left r20">
				<input type="submit" value="<?php __('btnReport', false, true); ?>" class="pj-button" />
			</span>
		</p>
		<div class="clear_both"></div>
	</form>
	<table class="pj-table b15" cellpadding="0" cellspacing="0" style="width: 100%;">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th><?php echo $booking_statuses['confirmed'];?></th>
				<th><?php echo $booking_statuses['pending'];?></th>
				<th><?php echo $booking_statuses['cancelled'];?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><b><?php __('lblTotalBookings');?></b></td>
				<td><?php echo $tpl['cnt_confirmed'];?> / <?php echo pjUtil::formatCurrencySign(number_format($tpl['total_confirmed'], 2), $tpl['option_arr']['o_currency']);?></td>
				<td><?php echo $tpl['cnt_pending'];?> / <?php echo pjUtil::formatCurrencySign(number_format($tpl['total_pending'], 2), $tpl['option_arr']['o_currency']);?></td>
				<td><?php echo $tpl['cnt_cancelled'];?> / <?php echo pjUtil::formatCurrencySign(number_format($tpl['total_cancelled'], 2), $tpl['option_arr']['o_currency']);?></td>
			</tr>
			<?php
			foreach($tpl['space_arr'] as $k => $v)
			{
				?>
				<tr>
					<td><?php echo pjSanitize::html($v['name']);?></td>
					<td><?php echo $v['cnt_confirmed'];?> / <?php echo pjUtil::formatCurrencySign(number_format($v['total_confirmed'], 2), $tpl['option_arr']['o_currency']);?></td>
					<td><?php echo $v['cnt_pending'];?> / <?php echo pjUtil::formatCurrencySign(number_format($v['total_pending'], 2), $tpl['option_arr']['o_currency']);?></td>
					<td><?php echo $v['cnt_cancelled'];?> / <?php echo pjUtil::formatCurrencySign(number_format($v['total_cancelled'], 2), $tpl['option_arr']['o_currency']);?></td>
				</tr>
				<?php
			}
			if(!empty($tpl['extra_arr']))
			{ 
				$cnt_confirmed = 0;
				$cnt_pending = 0;
				$cnt_cancelled = 0;
				$total_confirmed = 0;
				$total_pending = 0;
				$total_cancelled = 0;
				foreach($tpl['extra_arr'] as $k => $v)
				{
					$cnt_confirmed += intval($v['cnt_confirmed']);
					$cnt_pending += intval($v['cnt_pending']);
					$cnt_cancelled += intval($v['cnt_cancelled']);
					
					$total_confirmed += $v['total_confirmed'];
					$total_pending += $v['total_pending'];
					$total_cancelled += $v['total_cancelled'];
				}
				?>
				<tr>
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td><b><?php __('lblTotalExtras');?></b></td>
					<td><?php echo $cnt_confirmed; ?> / <?php echo pjUtil::formatCurrencySign(number_format($total_confirmed, 2), $tpl['option_arr']['o_currency']);?></td>
					<td><?php echo $cnt_pending; ?> / <?php echo pjUtil::formatCurrencySign(number_format($total_pending, 2), $tpl['option_arr']['o_currency']);?></td>
					<td><?php echo $cnt_cancelled; ?> / <?php echo pjUtil::formatCurrencySign(number_format($total_cancelled, 2), $tpl['option_arr']['o_currency']);?></td>
				</tr>
				<?php
				foreach($tpl['extra_arr'] as $k => $v)
				{
					?>
					<tr>
						<td><?php echo pjSanitize::html($v['name']);?></td>
						<td><?php echo intval($v['cnt_confirmed']);?> / <?php echo pjUtil::formatCurrencySign(number_format($v['total_confirmed'], 2), $tpl['option_arr']['o_currency']);?></td>
						<td><?php echo intval($v['cnt_pending']);?> / <?php echo pjUtil::formatCurrencySign(number_format($v['total_pending'], 2), $tpl['option_arr']['o_currency']);?></td>
						<td><?php echo intval($v['cnt_cancelled']);?> / <?php echo pjUtil::formatCurrencySign(number_format($v['total_cancelled'], 2), $tpl['option_arr']['o_currency']);?></td>
					</tr>
					<?php
				}
			} 
			?>
		</tbody>
	</table>
	<?php
}
?>