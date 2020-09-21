<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjExtraModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'extras';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'per', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'cnt', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'is_single', 'type' => 'int', 'default' => '0'),
	);
	
	public $i18n = array('name', 'description');
	
	public static function factory($attr=array())
	{
		return new pjExtraModel($attr);
	}
	
	public static function getPrice($days, $locale_id, $extras=null)
	{
		$extra_price = 0;
		$extra_name = array();
		$extra_arr = array();
		if ($extras !== false && !is_null($extras) && is_array($extras) && count($extras) > 0)
		{
			$extra_arr = pjExtraModel::factory()
				->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$locale_id."'", 'left outer')
				->select('t1.*, t2.content as name')
				->whereIn('t1.id', array_keys($extras))
				->findAll()
				->getData();
			$extra_per = __('extra_per', true);
			
			foreach ($extra_arr as $k => $v)
			{
				if (isset($extras[$v['id']]))
				{
					switch ($v['per'])
					{
						case 'booking':
							$extra_price += $extras[$v['id']] * $v['price'];
							break;
						case 'day':
							$extra_price += $extras[$v['id']] * $v['price'] * $days;
							break;
					}
				}
				$v['qty'] = $extras[$v['id']];
				$v['per_text'] = $extra_per[$v['per']];
				$extra_name[] = $v['name'];
				$extra_arr[$k] = $v;
			}
		}
		return compact('extra_price', 'extra_name', 'extra_arr');
	}
}
?>