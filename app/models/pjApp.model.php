<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAppModel extends pjModel
{
	public static function factory($attr=array())
	{
		return new pjAppModel($attr);
	}
}
?>