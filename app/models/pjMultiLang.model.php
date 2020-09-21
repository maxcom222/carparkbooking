<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjMultiLangModel extends pjAppModel
{
	protected $primaryKey = 'id';

	protected $table = 'multi_lang';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'model', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'locale', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'field', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'content', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'source', 'type' => 'enum', 'default' => 'script')
	);
	
	public function saveMultiLang($data, $foreign_id, $model, $source='data')
	{
		foreach ($data as $locale => $locale_arr)
		{
			foreach ($locale_arr as $field => $content)
			{
				if($field == 'terms_body')
				{
					$content = pjSanitize::html($content);
				}
				$insert_id = $this->reset()->setAttributes(array(
					'foreign_id' => $foreign_id,
					'model' => $model,
					'locale' => $locale,
					'field' => $field,
					'content' => $content,
					'source' => $source
				))->insert()->getInsertId();
				
				if ($insert_id === FALSE || (int) $insert_id <= 0)
				{
					$this->reset()
						->where('foreign_id', $foreign_id)
						->where('model', $model)
						->where('locale', $locale)
						->where('field', $field)
						->limit(1)
						->modifyAll(array('content' => $content));
				}
			}
		}
	}
	
	public function updateMultiLang($data, $foreign_id, $model, $source=NULL)
	{
		$fids = array();
		foreach ($data as $locale => $locale_arr)
		{
			foreach ($locale_arr as $field => $content)
			{
				if(!is_array($content))
				{
					if (!is_null($source))
					{
						$sql = sprintf("INSERT INTO `%1\$s` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
							VALUES (NULL, :foreign_id, :model, :locale, :field, :content, :source)
							ON DUPLICATE KEY UPDATE `content` = :content, `source` = :source;",
							$this->getTable()
						);
					} else {
						$sql = sprintf("INSERT INTO `%1\$s` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`)
							VALUES (NULL, :foreign_id, :model, :locale, :field, :content)
							ON DUPLICATE KEY UPDATE `content` = :content;",
							$this->getTable()
						);
					}
					$modelObj = $this->prepare($sql)->exec(compact('foreign_id', 'model', 'locale', 'field', 'content', 'source'));
	
					if ($modelObj->getAffectedRows() > 0 || $modelObj->getInsertId() > 0)
					{
						$fids[] = $foreign_id;
					}
				}
			}
		}
		
		return $fids;
	}
	
	public function getMultiLang($foreign_id, $model)
	{
		$arr = array();
		$_arr = $this->where('foreign_id', $foreign_id)->where('model', $model)->findAll()->getData();
		foreach ($_arr as $_k => $_v)
		{
			$arr[$_v['locale']][$_v['field']] = $_v['content'];
		}
		return $arr;
	}
	
	public static function factory($attr=array())
	{
		return new pjMultiLangModel($attr);
	}
}
?>