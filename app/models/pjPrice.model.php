<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPriceModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'prices';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'space_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date_from', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'date_to', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjPriceModel($attr);
	}
	
	public static function getPrice($space_id, $date_from, $date_to, $days)
	{
		$pjPriceModel = pjPriceModel::factory();
		$pjDiscountModel = pjDiscountModel::factory();
	
		$price = $regular = $save = 0;
		foreach (range(1, $days) as $i)
		{
			$date = date("Y-m-d", strtotime($date_from, strtotime(sprintf("+%u day", $i-1))));
				
			$arr = $pjPriceModel
				->reset()
				->where('t1.space_id', $space_id)
				->where("('$date' BETWEEN t1.date_from AND t1.date_to)")
				->limit(1)
				->findAll()
				->getData();
				
			if (count($arr) === 1)
			{
				$discount_arr = $pjDiscountModel
					->reset()
					->where('t1.price_id', $arr[0]['id'])
					->where('t1.space_id', $space_id)
					->where('t1.min_days <=', $days)
					->where('t1.max_days >=', $days)
					->limit(1)
					->findAll()
					->getData();
				if (count($discount_arr) === 1)
				{
					$price += $discount_arr[0]['price'];
				} else {
					$price += $arr[0]['price'];
				}
				$regular += $arr[0]['price'];
			}
		}
		$save = $regular - $price;
		return compact('price', 'save');
	}
}
?>