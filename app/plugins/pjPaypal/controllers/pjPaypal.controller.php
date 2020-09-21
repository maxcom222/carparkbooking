<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPaypal extends pjPaypalAppController
{
	public function pjActionConfirm()
	{
		$this->setLayout('pjActionEmpty');
		
		$params = $this->getParams();
		if (!isset($params['key']) || $params['key'] != md5($this->option_arr['private_key'] . PJ_SALT))
		{
			return FALSE;
		}
		
		$response = array(
			'status' => 'FAIL'
		);

		$url = PJ_TEST_MODE ? 'ssl://sandbox.paypal.com' : 'ssl://www.paypal.com';
		$host = PJ_TEST_MODE ? 'www.sandbox.paypal.com' : 'www.paypal.com';
		$port = 443;
		$timeout = 30;
		
		$this->log('PayPal confirm start ---');

		// STEP 1: Read POST data
		$req = 'cmd=_notify-validate';
		if (function_exists('get_magic_quotes_gpc'))
		{
			$get_magic_quotes_exists = true;
		}
		foreach ($_POST as $key => $value)
		{
			if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1)
			{
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}

		// STEP 2: Post IPN data back to paypal to validate
		$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Host: $host\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen($url, $port, $errno, $errstr, $timeout);
		
		if (!empty($errstr))
		{
			$this->log($errstr);
		}
		
		// assign posted variables to local variables
		$txn_id           = $_POST['txn_id'];
		$payment_status   = $_POST['payment_status'];
		$payment_amount   = @$_POST[isset($params['amount_index']) ? $params['amount_index'] : 'mc_gross'];
		$receiver_email   = $_POST['receiver_email'];
		$payment_currency = $_POST['mc_currency'];
		
		$response['transaction_id'] = $_POST['txn_id'];

		if (isset($params['foreign_id']))
		{
			$data = $_POST;
			$data['mc_gross'] = $payment_amount;
			$data['payment_date'] = @$_POST[isset($params['date_index']) ? $params['date_index'] : 'payment_date'];
			$this->pjActionSaveIpn($params['foreign_id'], $data);
		}
		
		if (!is_array($params['txn_id']))
		{
			$params['txn_id'] = array($params['txn_id']);
		}
		
		if (!$fp)
		{
			$this->log($txn_id.'<br>HTTP error: ' . $errstr);
		} else {
			fwrite($fp, $header . $req);
			while (!feof($fp))
			{
				$buffer = fgets($fp, 1024);
				// STEP 3: Inspect IPN validation result and act accordingly
				if (strcasecmp(trim($buffer), "VERIFIED") == 0)
				{
					$this->log($txn_id.'<br>VERIFIED');
					//if ($payment_status == "Completed")
					//{
						//$this->log('Completed');
						if (!in_array($txn_id, $params['txn_id']))
						{
							$this->log($txn_id.'<br>TXN_ID is OK');
							if ($receiver_email == $params['paypal_address'])
							{
								$this->log($txn_id.'<br>EMAIL address is OK');
								if ($payment_amount == $params['deposit'])
								{
									$this->log($txn_id.'<br>AMOUNT is OK');
									if ($payment_currency == $params['currency'])
									{
										$this->log($txn_id.'<br>EVERYTHING is OK, proceed with booking');
										$response['status'] = 'OK';
										return $response;
									} else {
										$this->log($txn_id.'<br>CURRENCY didn\'t match');
									}
								} else {
									$this->log($txn_id.'<br>AMOUNT didn\'t match: ' . $payment_amount . ' != ' . $params['deposit']);
								}
							} else {
								$this->log($txn_id.'<br>EMAIL address didn\'t match');
							}
						} else {
							$this->log($txn_id.'<br>TXN_ID is the same.');
						}
					//} else {
						//$this->log('Not Completed');
					//}
			    } elseif (strcasecmp($buffer, "INVALID") == 0) {
			    	$this->log($txn_id.'<br>INVALID');
			  	}
			}
			fclose($fp);
		}
		return $response;
	}
	
	public function pjActionSave($foreign_id, $data=array())
	{
		$this->setLayout('pjActionEmpty');
		
		$params = $this->getParams();
		if (!isset($params['key']) || $params['key'] != md5($this->option_arr['private_key'] . PJ_SALT))
		{
			return FALSE;
		}
		
		return $this->pjActionSaveIpn($params['foreign_id'], $params['data']);
	}
	
	private function pjActionSaveIpn($foreign_id, $data)
	{
		return pjPaypalModel::factory()
			->setAttributes(array(
				'foreign_id' => $foreign_id,
				'subscr_id' => @$data['subscr_id'],
				'txn_id' => @$data['txn_id'],
				'txn_type' => @$data['txn_type'],
				'mc_gross' => @$data['mc_gross'],
				'mc_currency' => @$data['mc_currency'],
				'payer_email' => @$data['payer_email'],
				'dt' => date("Y-m-d H:i:s", strtotime(@$data['payment_date']))
			))
			->insert()
			->getInsertId();
	}
	
	public function pjActionForm()
	{
		$this->setLayout('pjActionEmpty');
		
		$this->setAjax(true);
		//KEYS:
		//-------------
		//name
		//id
		//business
		//item_name
		//custom
		//amount
		//currency_code
		//return
		//notify_url
		//submit
		//submit_class
		//target
		$this->set('arr', $this->getParams());
	}
/**
 * @link https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/#id08A6HI00JQU
 */
	public function pjActionSubscribe()
	{
		$this->setAjax(true);
		// KEYS:
		//-------------
		//name
		//id
		//class
		//target
		//business
		//item_name => 127 chars
		//currency_code => 3 chars
		//custom => 255 chars
		//a1_price
		//p1_duration => 1-90 or 1-52 or 1-24 or 1-5 (depend of duration_unit)
		//t1_duration_unit => D,W,M,Y
		//a2_price
		//p2_duration => 1-90 or 1-52 or 1-24 or 1-5 (depend of duration_unit)
		//t2_duration_unit => D,W,M,Y
		//a3_price
		//p3_duration => 1-90 or 1-52 or 1-24 or 1-5 (depend of duration_unit)
		//t3_duration_unit => D,W,M,Y
		//recurring_payments => 0,1
		//recurring_times => 2-52
		//reattempt_on_failure => 0,1
		//return
		//cancel_return
		//notify_url
		//submit
		//submit_class
		$this->set('arr', $this->getParams());
	}

	public function pjActionGetDetails()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$this->set('arr', pjPaypalModel::factory()->find($_GET['id'])->getData());
			}
		}
	}
	
	public function pjActionGetPaypal()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			$pjPaypalModel = pjPaypalModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjPaypalModel->escapeStr($_GET['q']);
				$q = str_replace(array('%', '_'), array('\%', '\_'), $q);
				$pjPaypalModel->where('t1.filename LIKE', "%$q%");
			}
				
			$column = 'dt';
			$direction = 'DESC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjPaypalModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjPaypalModel->select('t1.*')
				->orderBy("`$column` $direction")->limit($rowCount, $offset)->findAll()->getData();
						
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();

		if ($this->isAdmin())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjPaypal.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
}
?>