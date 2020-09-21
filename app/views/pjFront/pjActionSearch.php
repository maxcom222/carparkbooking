<div class="container-fluid pjCpbContainer">
	<div class="panel panel-default pjCpbPanel">
		<div class="panel-heading clearfix pjCpbHead">
			<h1 class="panel-title pull-left pjCpbTitle"><?php __('front_select_your_dates')?></h1><!-- /.panel-title pull-left pjCpbTitle -->
			<div class="btn-group pull-right pjCpbLanguage">
				<?php
				include_once PJ_VIEWS_PATH . 'pjFront/elements/locale.php';
				?>
			</div><!-- /.btn-group pull-right pjCpbLanguage -->
		</div><!-- /.panel-heading clearfix pjCpbHead -->
		<div class="panel-body pjCpbBody">
			<div class="row pjCpbForm pjCpbFormChoose">
				<?php
				$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
				$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
				$jqTimeFormat = pjUtil::jqTimeFormat($tpl['option_arr']['o_time_format']);
				
				$months = __('months', true);
				$short_months = __('short_months', true);
				$days = __('days', true);
				$short_days = __('short_days', true);
				ksort($months);
				ksort($short_months);
				ksort($days);
				ksort($short_days);
								
				$STORE = @$_SESSION[$controller->defaultStore];
				
				$from = $to = null;
				if(isset($STORE['from']))
				{
					$from = date($tpl['option_arr']['o_date_format'], strtotime($STORE['from'])) . ' ' . date($tpl['option_arr']['o_time_format'], strtotime($STORE['from']));
					$to = date($tpl['option_arr']['o_date_format'], strtotime($STORE['to'])) . ' ' . date($tpl['option_arr']['o_time_format'], strtotime($STORE['to']));
				}
				?>
				<form id="pjCpSearchForm_<?php echo $_GET['index']?>" action="#" method="post">
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<div class="row">
										<label for="" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 control-label"><?php __('front_entry_date_time');?></label>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="input-group date pjCpbDatePicker">
												<input type="text" name="from" value="<?php echo $from;?>" readonly="readonly" class="form-control pjCpbDateTimePicker required" data-msg-required="<?php __('front_field_required');?>" data-fday="<?php echo $week_start; ?>" data-dformat="<?php echo $jqDateFormat; ?>" data-tformat="<?php echo $jqTimeFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>" data-timeText="<?php __('front_time');?>" data-hourText="<?php __('front_hour');?>" data-minuteText="<?php __('front_minute');?>" data-currentText="<?php __('front_now');?>" data-closeText="<?php __('front_done');?>"/>
												
												<span class="input-group-addon pjCpbDateTimeIcon">
													<i class="fa fa-calendar"></i>
												</span>
											</div><!-- /.input-group date pjCpbDatePicker -->
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
									</div>
								</div><!-- /.form-group -->
							</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-12 -->
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<div class="row">
										<label for="" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 control-label"><?php __('front_exit_date_time');?></label>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="input-group date pjCpbDatePicker">
												<input type="text" name="to" value="<?php echo $to;?>" readonly="readonly" class="form-control pjCpbDateTimePicker required" data-msg-required="<?php __('front_field_required');?>" data-fday="<?php echo $week_start; ?>" data-dformat="<?php echo $jqDateFormat; ?>" data-tformat="<?php echo $jqTimeFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>" data-timeText="<?php __('front_time');?>" data-hourText="<?php __('front_hour');?>" data-minuteText="<?php __('front_minute');?>" data-currentText="<?php __('front_now');?>" data-closeText="<?php __('front_done');?>"/>
												
												<span class="input-group-addon pjCpbDateTimeIcon">
													<i class="fa fa-calendar"></i>
												</span>
											</div><!-- /.input-group date pjCpbDatePicker -->
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
									</div>
								</div><!-- /.form-group -->
							</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-12 -->
						</div><!-- /.row -->
					</div><!-- /.col-lg-9 col-md-9 col-sm-12 col-xs-12 -->
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pjCpbFormActions">
						<input type="submit" class="btn btn-default btn-block pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBtnSearch" value="<?php __('front_btn_choose_your_space');?>" />
					</div><!-- /.col-lg-3 col-md-3 col-sm-12 col-xs-12 pjCpbFormActions -->
				</form>
			</div><!-- /.row pjCpbForm pjCpbFormChoose -->
		</div><!-- /.panel-body pjCpbBody -->
	</div>
</div>