<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAuthorize extends pjAuthorizeAppController
{
	private $currencies = array(
		'AUD',
		'GBP',
		'CAD',
		'DKK',
		'EUR',
		'NZD',
		'NOK',
		'PLN',
		'SEK',
		'CHF',
		'USD',
	);
	
	public function pjActionGetCurrencies()
	{
		return $this->currencies;
	}
	
	public function pjActionCheckCurrency()
	{
		$params = $this->getParams();
		
		if (!isset($params['currency']) || empty($params['currency']))
		{
			return array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty \'currency\' parameter');
		}
		
		$currency = strtoupper($params['currency']);
		
		if (!in_array($currency, $this->currencies))
		{
			return array(
				'status' => 'ERR', 
				'code' => 101, 
				'text' => sprintf(__('plugin_authorize_currency_not_supported', true), $currency),
				'currency' => $currency,
				'currencies' => $this->currencies,
			);
		}
		
		return array(
			'status' => 'OK', 
			'code' => 200, 
			'text' => sprintf(__('plugin_authorize_currency_supported', true), $currency),
			'currency' => $currency,
			'currencies' => $this->currencies,
		);
	}
                                            
	public function pjActionConfirm()
	{
		$params = $this->getParams();
		if (!isset($params['key']) || $params['key'] != md5($this->option_arr['private_key'] . PJ_SALT))
		{
			$this->log('pjAuthorize | Missing or invalid \'key\' parameter.');
			return FALSE;
		}
		
		$resp = array();
		
		if (isset($params['x_login'], $params['transkey'], $params['md5_setting']) &&
			!empty($params['x_login']) && !empty($params['transkey']))
		{
			define("AUTHORIZENET_API_LOGIN_ID", $params['x_login']);
			define("AUTHORIZENET_TRANSACTION_KEY", $params['transkey']);
			define("AUTHORIZENET_SANDBOX", PJ_TEST_MODE);
			define("TEST_REQUEST", PJ_TEST_MODE);
		
			require_once $this->getConst('PLUGIN_DIR') . 'anet_php_sdk/AuthorizeNet.php';
			$response = new AuthorizeNetSIM($params['x_login'], $params['md5_setting']);
			
	    	if ($response->isAuthorizeNet())
	    	{
	        	if ($response->approved)
	        	{
					// Transaction approved!
	        		$this->log('pjAuthorize | Transaction approved!');
					$resp['transaction_id'] = $_POST['x_invoice_num'];
					$resp['status'] = 'OK';
				} else {
					// There was a problem.
					$this->log('pjAuthorize | Error - ' . sprintf('Reason text: %s | Reason code: %s | Code: %s', $response->response_reason_text, $response->response_reason_code, $response->response_code));
					$resp['response_reason_code'] = $response->response_reason_code;
					$resp['response_code'] = $response->response_code;
					$resp['response_reason_text'] = $response->response_reason_text;
					$resp['status'] = 'FAIL';
				}
			} else {
				$this->log('pjAuthorize | MD5 Hash failed.');
				$resp['response_reason_text'] = "MD5 Hash failed";
				$resp['status'] = 'FAIL';
			}
		} else {
			$this->log('pjAuthorize | Missing or empty parameters: x_login, transkey, md5_setting.');
			$resp['response_reason_text'] = "Missing or empty parameters";
			$resp['status'] = 'FAIL';
		}

		return $resp;
	}
	
	public function pjActionForm()
	{
		$this->setAjax(true);
		//KEYS:
		//-------------
		//name
		//id
		//timezone
		//transkey
		//x_login
		//x_description
		//x_amount
		//x_invoice_num
		//x_receipt_link_url
		//x_relay_url
		//submit
		//submit_class
		//target
		$this->set('arr', $this->getParams());
	}
}
?>