<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjLocaleLanguageModel extends pjLocaleAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_locale_languages';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'iso', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'region', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'native', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'dir', 'type' => 'enum', 'default' => 'ltr'),
		array('name' => 'country_abbr', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'language_abbr', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'file', 'type' => 'varchar', 'default' => ':NULL'),
	);
	
	public static function factory($attr=array())
	{
		return new pjLocaleLanguageModel($attr);
	}
}
?>