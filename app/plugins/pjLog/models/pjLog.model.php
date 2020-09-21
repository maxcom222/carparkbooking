<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjLogModel extends pjLogAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_log';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'filename', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'function', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'value', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()')
	);
	
	public static function factory($attr=array())
	{
		return new pjLogModel($attr);
	}
	
	public function pjActionSetup()
	{

	}
}
?>