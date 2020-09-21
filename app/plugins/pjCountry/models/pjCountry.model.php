<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCountryModel extends pjCountryAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_country';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'alpha_2', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'alpha_3', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
	
	protected $i18n = array('name');
	
	public static function factory($attr=array())
	{
		return new pjCountryModel($attr);
	}
	
	public function pjActionSetup()
	{
		
	}
}
?>