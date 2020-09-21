<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjRoleModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'roles';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'role', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
	
	public static function factory($attr=array())
	{
		return new pjRoleModel($attr);
	}
}
?>