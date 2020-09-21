<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjLogConfigModel extends pjLogAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_log_config';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'filename', 'type' => 'varchar', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjLogConfigModel($attr);
	}
}
?>