<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjUserModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'users';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'role_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'password', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'phone', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'last_login', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T'),
		array('name' => 'is_active', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'ip', 'type' => 'varchar', 'default' => ':NULL')
	);
	
	protected $validate = array(
		'rules' => array(
			'role_id' => array(
				'pjActionNumeric' => true,
				'pjActionRequired' => true
			),
			'email' => array(
				'pjActionEmail' => true,
				'pjActionRequired' => true,
				'pjActionNotEmpty' => true
			),
			'password' => array(
				'pjActionRequired' => true,
				'pjActionNotEmpty' => true
			),
			'name' => array(
				'pjActionRequired' => true,
				'pjActionNotEmpty' => true
			),
			'status' => 'pjActionRequired'
		)
	);

	public static function factory($attr=array())
	{
		return new pjUserModel($attr);
	}
}
?>