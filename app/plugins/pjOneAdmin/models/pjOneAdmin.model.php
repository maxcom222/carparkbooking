<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjOneAdminModel extends pjOneAdminAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_one_admin';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'url', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'password', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES')
	);
	
	public static function factory($attr=array())
	{
		return new pjOneAdminModel($attr);
	}
	
	public function pjActionSetup()
	{

	}
}
?>