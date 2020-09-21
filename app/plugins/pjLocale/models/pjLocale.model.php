<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjLocaleModel extends pjLocaleAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_locale';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'language_iso', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'flag', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'dir', 'type' => 'enum', 'default' => 'ltr'),
		array('name' => 'sort', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'is_default', 'type' => 'tinyint', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjLocaleModel($attr);
	}
	
	public function pjActionSetup()
	{

	}
}
?>