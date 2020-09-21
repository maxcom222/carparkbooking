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
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('menuBookings'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionExport"><?php __('tabExport'); ?></a></li>
		</ul>
	</div>
	<?php
	pjUtil::printNotice(__('infoBookingsListTitle', true, false), __('infoBookingsListDesc', true, false)); 
	?>
	<div class="b10">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="float_left pj-form r10">
			<input type="hidden" name="controller" value="pjAdminBookings" />
			<input type="hidden" name="action" value="pjActionCreate" />
			<input type="submit" class="pj-button" value="<?php __('btnAddBooking'); ?>" />
		</form>
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
			<button type="button" class="pj-button pj-button-detailed"><span class="pj-button-detailed-arrow"></span></button>
		</form>
		<?php
		$bs = __('booking_statuses', true);
		?>
		<div class="float_right t5">
			<a href="#" class="pj-button btn-all pj-button-active"><?php __('lblAll')?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="confirmed"><?php echo $bs['confirmed']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="pending"><?php echo $bs['pending']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="cancelled"><?php echo $bs['cancelled']; ?></a>
		</div>
		<br class="clear_both" />
	</div>
	
	<div class="pj-form-filter-advanced" style="display: none">
		<span class="pj-menu-list-arrow"></span>
		<form action="" method="get" class="form pj-form pj-form-search frm-filter-advanced">
			<div class="overflow float_left w340 r20">
				<p>
					<label class="title120"><?php __('lblBookingID'); ?></label>
					<span class="inline-block">
						<input type="text" name="uuid" id="uuid" class="pj-form-field w150" value="<?php echo isset($_GET['uuid']) ? pjSanitize::html($_GET['uuid']) : NULL; ?>"/>
					</span>
				</p>
				<p>
					<label class="title120"><?php __('lblName'); ?></label>
					<span class="inline-block">
						<input type="text" name="c_name" id="c_name" class="pj-form-field w150" value="<?php echo isset($_GET['c_name']) ? pjSanitize::html($_GET['c_name']) : NULL; ?>"/>
					</span>
				</p>
				<p>
					<label class="title120"><?php __('lblBookingEmail'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
						<input type="text" name="c_email" class="pj-form-field email w130" value="<?php echo isset($_GET['c_email']) ? pjSanitize::html($_GET['c_email']) : NULL; ?>" />
					</span>
				</p>
				<p>
					<label class="title120">&nbsp;</label>
					<input type="submit" value="<?php __('btnSearch'); ?>" class="pj-button" />
					<input type="reset" value="<?php __('btnCancel'); ?>" class="pj-button" />
				</p>
			</div>
			
			<div class="overflow float_left w380">
				<p>
					<label class="title120"><?php __('lblSpace'); ?></label>
					<span class="inline-block">
						<select name="space_id" class="pj-form-field w180">
							<option value="">-- <?php __('lblChoose');?> --</option>
							<?php
							foreach($tpl['space_arr'] as $v)
							{
								?><option value="<?php echo $v['id']?>"><?php echo pjSanitize::html($v['name']);?></option><?php
							} 
							?>
						</select>
					</span>
				</p>
				<p>
					<label class="title120"><?php __('lblStatus'); ?></label>
					<span class="inline-block">
						<select name="status" class="pj-form-field w180">
							<option value="">-- <?php __('lblChoose');?> --</option>
							<?php
							foreach($bs as $k => $v)
							{
								?><option value="<?php echo $k;?>"><?php echo pjSanitize::html($v);?></option><?php
							} 
							?>
						</select>
					</span>
				</p>
				<p>
					<label class="title120"><?php __('lblBookingFilterDates'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-after">
						<input type="text" name="date_from" class="pj-form-field pointer w80 datepick" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['date_from']) ? htmlspecialchars($_GET['date_from']) : NULL; ?>" />
						<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					</span>
					<span class="pj-form-field-custom pj-form-field-custom-after">
						<input type="text" name="date_to" class="pj-form-field pointer w80 datepick" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['date_to']) ? htmlspecialchars($_GET['date_to']) : NULL; ?>" />
						<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					</span>
				</p>
			</div>
		</form>
	</div>
	
	<div id="grid"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.queryString = "";
	<?php
	if (isset($_GET['space_id']) && (int) $_GET['space_id'] > 0)
	{
		?>pjGrid.queryString += "&space_id=<?php echo (int) $_GET['space_id']; ?>";<?php
	}
	?>
	var myLabel = myLabel || {};
	myLabel.date_time = "<?php __('lblDateTimeFromTo'); ?>";
	myLabel.name = "<?php __('lblName'); ?>";
	myLabel.space = "<?php __('lblSpace'); ?>";
	myLabel.exported = "<?php __('lblExport'); ?>";
	myLabel.delete_selected = "<?php __('delete_selected'); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
	myLabel.status = "<?php __('lblStatus'); ?>";
	myLabel.pending = "<?php echo $bs['pending']; ?>";
	myLabel.confirmed = "<?php echo $bs['confirmed']; ?>";
	myLabel.cancelled = "<?php echo $bs['cancelled']; ?>";
	</script>
	<?php
}
?>