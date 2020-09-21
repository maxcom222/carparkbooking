<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjInstallerAppController extends pjPlugin
{
	public function __construct()
	{
		$this->setLayout('pjActionInstall');
	}
	
	public static function getConst($const)
	{
		$registry = pjRegistry::getInstance();
		$store = $registry->get('pjInstaller');
		return isset($store[$const]) ? $store[$const] : NULL;
	}
}
?>