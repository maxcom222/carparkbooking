<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjDiscountModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'discounts';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'space_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'min_days', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'max_days', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjDiscountModel($attr);
	}
}
?>