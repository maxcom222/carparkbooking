<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjSmsModel extends pjSmsAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_sms';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'number', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'text', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()')
	);
	
	public static function factory($attr=array())
	{
		return new pjSmsModel($attr);
	}
	
	public function pjActionSetup()
	{
		
	}
}
?>