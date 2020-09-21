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
}else{
	?>
	<div class="dashboard_header">
		<div class="item">
			<div class="stat spaces">
				<div class="info">
					<abbr><?php echo $tpl['spaces_occupied_today'];?></abbr>
					<?php $tpl['spaces_occupied_today'] != 1 ? __('lblDashSpacesOccupiedToday') : __('lblDashSpaceOccupiedToday');?>
				</div>
			</div>
		</div>
		<div class="item">
			<div class="stat cars">
				<div class="info">
					<abbr><?php echo $tpl['car_coming_today'];?></abbr>
					<?php $tpl['car_coming_today'] != 1 ? __('lblDashCarsComingToday') : __('lblDashCarComingToday');?>
				</div>
			</div>
		</div>
		<div class="item">
			<div class="stat cars">
				<div class="info">
					<abbr><?php echo $tpl['car_leaving_today'];?></abbr>
					<?php $tpl['car_leaving_today'] != 1 ? __('lblDashCarsLeavingToday') : __('lblDashCarLeavingToday');?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="dashboard_box">
		<div class="dashboard_top">
			<div class="dashboard_column_top"><?php __('lblDashLatestBookings');?></div>
			<div class="dashboard_column_top"><?php __('lblDashComingToday');?></div>
			<div class="dashboard_column_top"><?php __('lblDashLeavingToday');?></div>
		</div>
		<div class="dashboard_middle">
			<div class="dashboard_column">
				<div class="dashboard_list dashboard_latest_list">
					<?php
					if(count($tpl['latest_bookings']) > 0)
					{
						foreach($tpl['latest_bookings'] as $v)
						{
							?>
							<div class="dashboard_row">							
								<label><?php __('lblDashName')?>: <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id'];?>"><?php echo pjSanitize::html($v['c_name']);?></a></label>
								<label><?php __('lblDashMake')?>: <?php echo pjSanitize::html($v['c_make']);?></label>
								<label><?php __('lblDashModel')?>: <?php echo pjSanitize::html($v['c_model']);?></label>
								<label><?php __('lblDashRegistration')?>: <strong class="bold"><?php echo pjSanitize::html($v['c_regno']);?></strong></label>
								<?php
								if($v['days'] != 1)
								{
									?><label><?php __('lblDashDays')?>: <?php echo $v['days'];?></label><?php
								} else {
									?><label><?php __('lblDashDay')?>: <?php echo $v['days'];?></label><?php
								}
								?>
							</div>
							<?php
						}
					}else{
						?>
						<div class="dashboard_row"><label><?php __('lblDashNoBookingsFound');?></label></div>
						<?php
					} 
					?>
				</div>
			</div>
			
			<div class="dashboard_column">
				<div class="dashboard_list dashboard_latest_list">
					<?php
					if(count($tpl['coming_bookings']) > 0)
					{
						foreach($tpl['coming_bookings'] as $v)
						{
							?>
							<div class="dashboard_row">							
								<label><?php __('lblDashName')?>: <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id'];?>"><?php echo pjSanitize::html($v['c_name']);?></a></label>
								<label><?php __('lblDashMake')?>: <?php echo pjSanitize::html($v['c_make']);?></label>
								<label><?php __('lblDashModel')?>: <?php echo pjSanitize::html($v['c_model']);?></label>
								<label><?php __('lblDashRegistration')?>: <strong class="bold"><?php echo pjSanitize::html($v['c_regno']);?></strong></label>
								<?php
								if($v['days'] != 1)
								{
									?><label><?php __('lblDashDays')?>: <?php echo $v['days'];?></label><?php
								} else {
									?><label><?php __('lblDashDay')?>: <?php echo $v['days'];?></label><?php
								}
								?>
							</div>
							<?php
						}
					}else{
						?>
						<div class="dashboard_row"><label><?php __('lblDashNoCarsFound');?></label></div>
						<?php
					} 
					?>
				</div>
			</div>
			<div class="dashboard_column">
				<div class="dashboard_list dashboard_latest_list">
					<?php
					if(count($tpl['leaving_bookings']) > 0)
					{
						foreach($tpl['leaving_bookings'] as $v)
						{
							?>
							<div class="dashboard_row">							
								<label><?php __('lblDashName')?>: <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id'];?>"><?php echo pjSanitize::html($v['c_name']);?></a></label>
								<label><?php __('lblDashMake')?>: <?php echo pjSanitize::html($v['c_make']);?></label>
								<label><?php __('lblDashModel')?>: <?php echo pjSanitize::html($v['c_model']);?></label>
								<label><?php __('lblDashRegistration')?>: <strong class="bold"><?php echo pjSanitize::html($v['c_regno']);?></strong></label>
								<?php
								if($v['days'] != 1)
								{
									?><label><?php __('lblDashDays')?>: <?php echo $v['days'];?></label><?php
								} else {
									?><label><?php __('lblDashDay')?>: <?php echo $v['days'];?></label><?php
								}
								?>
							</div>
							<?php
						}
					}else{
						?>
						<div class="dashboard_row"><label><?php __('lblDashNoCarsFound');?></label></div>
						<?php
					} 
					?>
				</div>
			</div>
		</div>
		<div class="dashboard_bottom"></div>
	</div>
	
	<div class="clear_left t20 overflow">
		<div class="float_left black t30 t20"><span class="gray"><?php echo ucfirst(__('lblDashLastLogin', true)); ?>:</span> 
		<?php echo date($tpl['option_arr']['o_date_format'], strtotime($_SESSION[$controller->defaultUser]['last_login'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($_SESSION[$controller->defaultUser]['last_login'])); ?></div>
		<div class="float_right overflow">
		<?php
		$days = __('days', true, false);
		?>
			<div class="dashboard_date">
				<abbr><?php echo $days[date('w')]; ?></abbr>
				<?php echo pjUtil::formatDate(date('Y-m-d'), 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>
			</div>
			<div class="dashboard_hour"><?php echo date($tpl['option_arr']['o_time_format']); ?></div>
		</div>
	</div>
	<?php
}
?>