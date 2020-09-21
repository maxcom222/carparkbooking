<div class="container-fluid pjCpbContainer">
	<div class="panel panel-default pjCpbPanel">
		<div class="panel-heading clearfix pjCpbHead">
			<h1 class="panel-title pull-left pjCpbTitle"><?php __('front_booking_completed')?></h1><!-- /.panel-title pull-left pjCpbTitle -->
			<div class="btn-group pull-right pjCpbLanguage">
				<?php
				include_once PJ_VIEWS_PATH . 'pjFront/elements/locale.php';
				?>
			</div><!-- /.btn-group pull-right pjCpbLanguage -->
		</div><!-- /.panel-heading clearfix pjCpbHead -->
		<div class="panel-body pjCpbBody">
			<?php
			if (isset($tpl['get']['payment_method']))
			{
				$status = __('front_booking_statuses', true);
				switch ($tpl['get']['payment_method'])
				{
					case 'paypal':
						?><p class="text-success text-center pjTbMessage pjTbMessageSucces"><?php echo $status[0]; ?></p><?php
						if (pjObject::getPlugin('pjPaypal') !== NULL)
						{
							$controller->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionForm', 'params' => $tpl['params']));
						}
						break;
					case 'authorize':
						?><p class="text-success text-center pjTbMessage pjTbMessageSucces"><?php echo $status[0]; ?></p><?php
						if (pjObject::getPlugin('pjAuthorize') !== NULL)
						{
							$controller->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionForm', 'params' => $tpl['params']));
						}
						break;
					case 'bank':
						?><p class="text-success text-center pjTbMessage pjTbMessageSucces"><?php echo $status[1]; ?></p><?php
						break;
					case 'creditcard':
					case 'cash':
					default:
						?><p class="text-success text-center pjTbMessage pjTbMessageSucces"><?php echo $status[1]; ?></p><?php
				}
			}
			?>
		</div><!-- /.panel-body pjCpbBody -->
		<?php
		if($tpl['get']['payment_method'] == 'bank' || $tpl['get']['payment_method'] == 'creditcard' || $tpl['get']['payment_method'] == 'cash' || $tpl['option_arr']['o_payment_disable'] == 'Yes') 
		{
			?>
			<div class="panel-footer clearfix text-right pjCpbFooter">
				<input type="button" class="btn btn-default pjCpbBtn pjCpbBtnPrimary pjCpbSelectorButton pjCpbBtnStartOver" value="<?php __('front_btn_start_over')?>" />
			</div><!-- /.panel-footer clearfix text-right pjCpbFooter -->
			<?php
		} 
		?>
	</div><!-- /.panel panel-default pjCpbPanel -->
</div>