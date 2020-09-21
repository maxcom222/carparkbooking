<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCodeModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'codes';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'space_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'discount', 'type' => 'decimal', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjCodeModel($attr);
	}
	
	public static function getCode($space_id, $code, $date_from, $date_to)
	{
		$arr = pjCodeModel::factory()
			->join('pjPrice', "t1.price_id=t2.id AND ('$date_from' BETWEEN `t2`.`date_from` AND `t2`.`date_to`) AND ('$date_to' BETWEEN `t2`.`date_from` AND `t2`.`date_to`)")
			->where("t1.space_id", $space_id)
			->where('t1.code', $code)
			->limit(1)
			->findAll()
			->getData();
		
		return count($arr) === 1 ? $arr[0] : array();
	}
}
?>