<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCountryAppController extends pjPlugin
{
	public function __construct()
	{
		$this->setLayout('pjActionAdmin');
	}
	
	public static function getConst($const)
	{
		$registry = pjRegistry::getInstance();
		$store = $registry->get('pjCountry');
		return isset($store[$const]) ? $store[$const] : NULL;
	}
	
	public function isCountryReady()
	{
		$reflector = new ReflectionClass('pjPlugin');
		try {
			//Try to find out 'isCountryReady' into parent class
			$ReflectionMethod = $reflector->getMethod('isCountryReady');
			return $ReflectionMethod->invoke(new pjPlugin(), 'isCountryReady');
		} catch (ReflectionException $e) {
			//echo $e->getMessage();
			//If failed to find it out, denied access, or not :)
			return false;
		}
	}
}
?>