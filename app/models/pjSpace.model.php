<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjSpaceModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'spaces';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
	
	public $i18n = array('name', 'description');
	
	public static function factory($attr=array())
	{
		return new pjSpaceModel($attr);
	}
}
?>