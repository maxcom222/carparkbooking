<div class="container-fluid pjCpbContainer">
	<div class="panel panel-default pjCpbPanel">
		<div class="panel-heading clearfix pjCpbHead">
			<h1 class="panel-title pull-left pjCpbTitle"><?php __('front_choose_your_space')?></h1><!-- /.panel-title pull-left pjCpbTitle -->
			<div class="btn-group pull-right pjCpbLanguage">
				<?php
				include_once PJ_VIEWS_PATH . 'pjFront/elements/locale.php';
				?>
			</div><!-- /.btn-group pull-right pjCpbLanguage -->
		</div><!-- /.panel-heading clearfix pjCpbHead -->
		<div class="panel-body pjCpbBody">
			<?php
			$STORE = @$_SESSION[$controller->defaultStore];
			$from = date($tpl['option_arr']['o_date_format'], strtotime($STORE['from'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($STORE['from']));
			$to = date($tpl['option_arr']['o_date_format'], strtotime($STORE['to'])) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($STORE['to']));
						
			?>
			<p class="pull-left pjCpbFromPrev">
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
			<?php
			if(!empty($tpl['space_arr']))
			{ 
				?>
				<div class="form-inline pull-right pjCpbFormSort">
					<div class="form-group">
						<label for=""><?php __('front_sort_by');?>: </label>
	
						<select name="sort_by" class="form-control pjCpbSortBy">
							<?php
							$sort_by_arr = pjUtil::sortArrayByArray(__('front_sort', true), array('price_asc', 'price_desc', 'name_asc', 'name_desc'));
							foreach($sort_by_arr as $k => $v)
							{
								?><option value="<?php echo $k;?>"<?php echo isset($_GET['sortby']) ? ($_GET['sortby'] == $k ? ' selected="selected"' : null) : null;?>><?php echo $v;?></option><?php
							} 
							?>
						</select>
					</div><!-- /.form-group -->
				</div><!-- /.form-inline pull-right pjCpbFormSort -->
				<?php
			} 
			?>
		</div><!-- /.panel-body pjCpbBody -->
		
		<ul class="list-group pjCpbSpaces">
			<?php
			if(!empty($tpl['space_arr']))
			{
				foreach($tpl['space_arr'] as $space)
				{
					?>
					<li class="list-group-item pjCpbSpace">
						<div class="well well-sm clearfix">
							<div class="pull-left pjCpbSpaceTitle">
								<?php echo pjSanitize::html($space['name']);?>
							</div><!-- /.pull-left -->
		
							<div class="pull-right">
								<p class="pull-left pjCpbSpacePrice">
									<?php
									if(($space['is_available'] == 1 && (float) $space['price'] > 0))
									{ 
										__('front_price');
										?> 
										<strong><?php echo pjUtil::formatCurrencySign(number_format($space['price'], 2), $tpl['option_arr']['o_currency']);?></strong>
										<?php
										if((float) $space['save'] > 0)
										{
											?>
											<br/>
											<span class="pjCpbSavePrice"><?php echo sprintf(__('front_save_price', true), pjUtil::formatCurrencySign(number_format($space['save'], 2), $tpl['option_arr']['o_currency']));?></span>
											<?php
										}
									}else{
										?><span><b><?php __('front_not_available');?></b></span><?php
									} 
									?>
								</p><!-- /.pull-left pjCpbSpacePrice -->
								<?php
								if(($space['is_available'] == 1 && (float) $space['price'] > 0))
								{ 
									?>
									<a href="#" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbBtnBookNow" data-id="<?php echo $space['id'];?>" data-extras="<?php echo $tpl['cnt_extras'];?>"><?php __('front_btn_book_now');?></a>
									<?php
								} 
								?>
							</div><!-- /.pull-right -->
						</div><!-- /.well well-sm clearfix -->
		
						<p><?php echo nl2br(stripslashes($space['description']));?></p>
					</li><!-- /.list-group-item pjCpbSpace -->
					<?php
				}
			}else{
				?>
				<li class="list-group-item pjCpbSpace">
					<?php __('front_no_spaces_available');?>
				</li>
				<?php 
			} 
			?>
		</ul><!-- /.list-group pjCpbSpaces -->
	</div>
</div>