<?php
if (!headers_sent())
{
	if (isset($_GET['session_id']))
	{
		$session_id = trim($_GET['session_id']);
		$pattern = '/[a-zA-Z0-9]+/';
		if (version_compare(PHP_VERSION, '5.0.0', '>='))
		{
			$pattern = '/[a-zA-Z0-9,-]+/';
		}
		if (preg_match($pattern, $session_id))
		{
			session_id($session_id);
		}
	}
	session_name('CarParkBooking');
	@session_start();
}

if (isset($_GET["reporting"]) && $_GET["reporting"] == '0') 
{
	$_SESSION["error_reporting"] = '0';
} else if (isset($_GET["reporting"]) && $_GET["reporting"]== '1') {
	$_SESSION["error_reporting"] = '1';
}
if (isset($_SESSION["error_reporting"]) && $_SESSION["error_reporting"]=='1')
{
	ini_set("display_errors", "On");
	error_reporting(E_ALL|E_STRICT);
} else {
	error_reporting(0);
}

header("Content-type: text/html; charset=utf-8");
if (!defined("ROOT_PATH"))
{
	define("ROOT_PATH", dirname(__FILE__) . '/');
}

require ROOT_PATH . 'app/config/options.inc.php';

require_once PJ_FRAMEWORK_PATH . 'pjAutoloader.class.php';
pjAutoloader::register();

if (!isset($_GET['controller']) || empty($_GET['controller']))
{
	header("HTTP/1.1 301 Moved Permanently");
	pjUtil::redirect(PJ_INSTALL_URL . basename($_SERVER['PHP_SELF'])."?controller=pjAdmins&action=pjActionIndex");
}

if (isset($_GET['controller']))
{
	$pjObserver = pjObserver::factory();
	$pjObserver->init();
}
?>