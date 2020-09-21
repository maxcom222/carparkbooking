<div class="container-fluid pjCpbContainer">
	<div class="panel panel-default pjCpbPanel">
		<div class="panel-heading clearfix pjCpbHead">
			<h1 class="panel-title pull-left pjCpbTitle"><?php __('front_extras')?></h1><!-- /.panel-title pull-left pjCpbTitle -->
			<div class="btn-group pull-right pjCpbLanguage">
				<?php
				include_once PJ_VIEWS_PATH . 'pjFront/elements/locale.php';
				?>
			</div><!-- /.btn-group pull-right pjCpbLanguage -->
		</div><!-- /.panel-heading clearfix pjCpbHead -->
		<?php
		if($tpl['status'] == 'OK')
		{ 
			?>
			<div class="panel-body pjCpbBody">
				<?php
				$STORE = @$_SESSION[$controller->defaultStore];
				$from = date($tpl['option_arr']['o_date_format'], strtotime($STORE['from'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($STORE['from']));
				$to = date($tpl['option_arr']['o_date_format'], strtotime($STORE['to'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($STORE['to']));
							
				?>
				<p class="pjCpbFromPrev">
					<?php
					if($tpl['rental_days'] != 1)
					{ 
						echo sprintf(__('front_date_range', true), $tpl['rental_days'], $from, $to);
					}else{
						echo sprintf(__('front_date_range_singluar', true), $tpl['rental_days'], $from, $to);
					}
					?>
					<a href="#" class="btn-link pjCpbChangeDates"><?php __('front_change_dates');?></a>
				</p><!-- /.pull-left pjCpbFromPrev -->
				<p class="pjCpbFromPrev">
					<?php __('front_space');?>: <?php echo pjSanitize::html($tpl['space']['name']);?>
					<a href="#" class="btn-link pjCpbChangeSpace"><?php __('front_change');?></a>
				</p><!-- /.pjCpbFromPrev -->
			</div><!-- /.panel-body pjCpbBody -->
			<ul class="list-group pjCpbExtras">
				<?php
				if(!empty($tpl['extra_arr']))
				{
					$extra_per = __('extra_per', true);
					foreach($tpl['extra_arr'] as $k => $extra)
					{
						$max = $extra['cnt'] - $extra['bookings'];
						if ($extra['type'] == 'unlimited')
						{
							$max = 100;
						}
						?>
						<li class="list-group-item pjCpbExtra">
							<?php
							if($k == 0)
							{ 
								?><p class="pjCpbExtrasTitle"><?php __('front_add_extras_question');?></p><!-- /.pjCpbExtrasTitle --><?php
							} 
							?>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p class="pull-left pjCpbExtraTitle"><?php echo pjSanitize::html($extra['name']);?></p><!-- /.pull-left pjCpbExtraTitle -->
									
									<div class="pull-right">
										<p class="pull-left pjCpbExtraPrice">
											<strong><?php echo pjUtil::formatCurrencySign($extra['price'], $tpl['option_arr']['o_currency']);?></strong>
											<span><?php echo $extra_per[$extra['per']]; ?></span>
										</p><!-- /.pull-left pjCpbExtraPrice -->
										<?php
										switch ($extra['is_single'])
										{
											case 1:
												# Button
												if (isset($STORE['extras']) && array_key_exists($extra['id'], @$STORE['extras']) && (int) @$STORE['extras'][$extra['id']] > 0)
												{
													# Remove extra
													?>
													<button class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBtnRemoveExtra" data-id="<?php echo $extra['id']; ?>"><?php __('front_btn_remove');?></button>
													<?php
												} else {
													# Add extra
													if ($max > 0)
													{
														?><button class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBtnAddExtra" data-id="<?php echo $extra['id']; ?>"><?php __('front_btn_add');?></button><?php
													} else {
														?><div class="pjCpbExtraNA"><?php __('front_na'); ?></div><?php
													}
												}
												break;
											case 0:
												# Selectbox
												if ($max > 0)
												{
													?>
													<select name="count[<?php echo $extra['id']; ?>]" class="pjCpbExtraCount" data-id="<?php echo $extra['id']; ?>">
														<option value="">--</option>
														<?php
														foreach (range(1, $max) as $i)
														{
															?><option value="<?php echo $i; ?>"<?php echo @$STORE['extras'][$extra['id']] == $i ? ' selected="selected"' : NULL; ?>><?php echo $i; ?></option><?php
														}
														?>
													</select>
													<?php
												} else {
													?><div class="pjCpbExtraNA"><?php __('front_na'); ?></div><?php
												}
												break;
										} 
										?>
									</div><!-- /.pull-right -->
								</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
							</div><!-- /.row -->
							<?php
							$list = preg_split('/[\n]+/', $extra['description']);
							$list = array_chunk($list, round(count($list) / 2));
							?>
							<div class="row">
								<?php
								foreach($list as $col)
								{
									?>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<ul class="list-unstyled pjCpbListTicks">
											<?php
											foreach($col as $li)
											{
												?><li><i class="fa fa-check"></i> <?php echo pjSanitize::html($li);?></li><?php
											} 
											?>
										</ul><!-- /.list-unstyled pjCpbListTicks -->
									</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-12 -->
									<?php
								} 
								?>
							</div><!-- /.row --><!-- /.row -->
						</li><!-- /.list-group-item pjCpbExtra -->
						<?php
					}
				} else {
					?>
					<li class="list-group-item pjCpbExtra">					
						<div class="row">
							<span><?php __('front_no_extras_found');?></span>
						</div><!-- /.row -->
					</li><!-- /.list-group-item pjCpbExtra -->
					<?php
				}
				?>
			</ul><!-- /.list-group pjCpbExtras -->
			
			<div class="panel-footer clearfix pjCpbFooter">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left pjCpBackContainer">
						<a href="#" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBackToSpaces"><i class="fa fa-angle-double-left"></i> <?php __('front_btn_back');?></a>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right pjCpButtonContainer">
						<a href="#" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbProceedPayment"><?php __('front_btn_proceed_to_payment');?></a>
					</div>
				</div>
			</div><!-- /.panel-footer clearfix text-right pjCpbFooter -->
			<?php
		}else{
			?>
			<div class="panel-body pjCpbBody">
				<p class="text-warning"><?php __('front_missing_parameters');?></p>
			</div>
			<div class="panel-footer clearfix text-right pjCpbFooter">
				<input type="button" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBtnStartOver" value="<?php __('front_btn_start_over')?>" />
			</div><!-- /.panel-footer clearfix text-right pjCpbFooter -->
			<?php 
		} 
		?>
	</div>
</div>