1. How to install a plugin
-----------------------------------------------
	1.1 Before script installation
		- Copy the plugin folder and paste it into 'app/plugins/'
		- Do the same for all the plugins you need, then install the script
	
	1.2 After script installation
		- Copy the plugin folder and paste it into 'app/plugins/'
		- Manually run the plugin *.sql file(s), located in 'app/plugins/PLUGIN_NAME/config/'


2. How to enable a plugin
-----------------------------------------------
	Add plugin name to $CONFIG['plugins'] array into 'app/config/config.inc.php' and 'app/config/config.sample.php'
	For example: 
	<?php
	$CONFIG['plugins'] = array('pjAuthorize', 'pjAdminBackup');
	//-- OR -- 
	$CONFIG['plugins'] = 'pjAuthorize';
	?>


3. How to access a plugin.
-----------------------------------------------
	For example:
	index.php?controller=PLUGIN_NAME&action=SOME_ACTION
	
	Add above url as hyperlink to the menu if you need to.
	

4. How to use a plugin accross the script
-----------------------------------------------
	4.1 Into controllers
		
		- Using the plugin model
		
			(not applicable)

		- Using the plugin resources
		
			(not applicable)
			
		- Other
			//Build data for Authorize.NET form
			$this->set('params', array(
				'name' => 'vrAuthorize',
				'id' => 'vrAuthorize',
				'timezone' => $booking_arr['o_authorize_tz'],
				'transkey' => $booking_arr['o_authorize_transkey'],
				'x_login' => $booking_arr['o_authorize_merchant_id'],
				'x_description' => 'Vacation Rental',
				'x_amount' => $booking_arr['deposit'],
				'x_invoice_num' => $booking_arr['id'],
				'x_relay_url' => PJ_INSTALL_URL . 'index.php?controller=pjListings&action=confirmAuthorize'
			));
					
	4.2 Into presentation layer (views)
		
		// Next code display Authorize.NET form
		
		$controller->requestAction(array(
			'controller' => 'pjAuthorize', 
			'action' => 'form', 
			'params' => $tpl['params']
		));
		
	4.3 How to check if currency is supported:
	
		$params = array('currency' => 'EUR');
		$result = $this->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionCheckCurrency', 'params' => $params), array('return'));
		print_r($result);
		
	4.4 How to get supported currencies:
	
		$result = $this->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionGetCurrencies'), array('return'));
		print_r($result);
		