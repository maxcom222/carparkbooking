<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPaypalModel extends pjPaypalAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_paypal';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'subscr_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'txn_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'txn_type', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'mc_gross', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'mc_currency', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'payer_email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'dt', 'type' => 'datetime', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjPaypalModel($attr);
	}
}
?>