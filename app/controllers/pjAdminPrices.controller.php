<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminPrices extends pjAdmin
{
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$pjPriceModel = pjPriceModel::factory();
			
			$price_arr = $pjPriceModel
				->orderBy("t1.space_id ASC, t1.date_from ASC, t1.date_to ASC, t1.price ASC")
				->findAll()
				->getData();
			
			if(isset($_POST['price_update']))
			{
				$remove_id_arr = array();
				foreach($price_arr as $price)
				{
					if(!isset($_POST['space_id'][$price['id']]))
					{
						$remove_id_arr[] = $price['id'];
					}
				}
				foreach ($_POST['space_id'] as $index => $space_id)
				{
					if ((int) $space_id > 0 && !empty($_POST['date_from'][$index]) && !empty($_POST['date_to'][$index]) && !empty($_POST['price'][$index]))
					{
						$data = array();
						$data['space_id'] = $space_id;
						$data['date_from'] = pjUtil::formatDate($_POST['date_from'][$index], $this->option_arr['o_date_format']);
						$data['date_to'] = pjUtil::formatDate($_POST['date_to'][$index], $this->option_arr['o_date_format']);
						$data['price'] = $_POST['price'][$index];
						
						if (isset($_POST['id'][$index]))
						{
							$pjPriceModel->reset()->where('id', $_POST['id'][$index])->limit(1)->modifyAll($data);
						}else{
							$pjPriceModel->reset()->setAttributes($data)->insert();
						}
					}
				}
				if(!empty($remove_id_arr))
				{
					$pjPriceModel->reset()->whereIn('id', $remove_id_arr)->eraseAll();
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminPrices&action=pjActionIndex&err=AP01");
			}else{
				$space_arr = pjSpaceModel::factory()
					->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpace' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
					->select("t1.*, t2.content AS name")
					->where('t1.status', 'T')
					->orderBy("name ASC")
					->findAll()
					->getData();
				
				$this->set('space_arr', $space_arr);
				$this->set('price_arr', $price_arr);
			}
			
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminPrices.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDiscount()
	{
		$this->checkLogin();
	
		if ($this->isAdmin() || $this->isEditor())
		{
			$pjDiscountModel = pjDiscountModel::factory();
				
			$discount_arr = $pjDiscountModel
				->orderBy("t1.space_id ASC, t1.min_days ASC, t1.max_days ASC, t1.price ASc")
				->findAll()
				->getData();
				
			if(isset($_POST['discount_update']))
			{
				$remove_id_arr = array();
				foreach($discount_arr as $discount)
				{
					if(!isset($_POST['space_id'][$discount['id']]))
					{
						$remove_id_arr[] = $discount['id'];
					}
				}
				foreach ($_POST['space_id'] as $index => $space_id)
				{
					if ((int) $space_id > 0 && (int) $_POST['price_id'][$index] > 0 && (int) $_POST['min_days'][$index] > 0 && (int) $_POST['max_days'][$index] > 0 && !empty($_POST['price'][$index]))
					{
						$data = array();
						$data['space_id'] = $space_id;
						$data['price_id'] = $_POST['price_id'][$index];
						$data['min_days'] = $_POST['min_days'][$index];
						$data['max_days'] = $_POST['max_days'][$index];
						$data['price'] = $_POST['price'][$index];
	
						if (isset($_POST['id'][$index]))
						{
							$pjDiscountModel->reset()->where('id', $_POST['id'][$index])->limit(1)->modifyAll($data);
						}else{
							$pjDiscountModel->reset()->setAttributes($data)->insert();
						}
					}
				}
				if(!empty($remove_id_arr))
				{
					$pjDiscountModel->reset()->whereIn('id', $remove_id_arr)->eraseAll();
				}
	
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminPrices&action=pjActionDiscount&err=AP01");
			}else{
				$space_arr = pjSpaceModel::factory()
					->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpace' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
					->select("t1.*, t2.content AS name")
					->where('t1.status', 'T')
					->orderBy("name ASC")
					->findAll()
					->getData();
				
				$price_arr = pjPriceModel::factory()
					->orderBy("t1.date_from ASC, t1.date_to ASC")
					->findAll()
					->getData();
	
				$this->set('space_arr', $space_arr);
				$this->set('price_arr', $price_arr);
				$this->set('discount_arr', $discount_arr);
			}
				
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminPrices.js');
		} else {
			$this->set('status', 2);
		}
	}
	

	public function pjActionCode()
	{
		$this->checkLogin();
	
		if ($this->isAdmin() || $this->isEditor())
		{
			$pjCodeModel = pjCodeModel::factory();
	
			$code_arr = $pjCodeModel
				->orderBy("t1.space_id ASC")
				->findAll()
				->getData();
	
			if(isset($_POST['code_update']))
			{
				$remove_id_arr = array();
				foreach($code_arr as $code)
				{
					if(!isset($_POST['space_id'][$code['id']]))
					{
						$remove_id_arr[] = $code['id'];
					}
				}
				foreach ($_POST['space_id'] as $index => $space_id)
				{
					if ((int) $space_id > 0 && (int) $_POST['price_id'][$index] > 0 && !empty($_POST['code'][$index]) && !empty($_POST['discount'][$index]))
					{
						$data = array();
						$data['space_id'] = $space_id;
						$data['price_id'] = $_POST['price_id'][$index];
						$data['code'] = $_POST['code'][$index];
						$data['type'] = $_POST['type'][$index];
						$data['discount'] = $_POST['discount'][$index];
	
						if (isset($_POST['id'][$index]))
						{
							$pjCodeModel->reset()->where('id', $_POST['id'][$index])->limit(1)->modifyAll($data);
						}else{
							$pjCodeModel->reset()->setAttributes($data)->insert();
						}
					}
				}
				if(!empty($remove_id_arr))
				{
					$pjCodeModel->reset()->whereIn('id', $remove_id_arr)->eraseAll();
				}
	
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminPrices&action=pjActionCode&err=AC01");
			}else{
				$space_arr = pjSpaceModel::factory()
					->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpace' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
					->select("t1.*, t2.content AS name")
					->where('t1.status', 'T')
					->orderBy("name ASC")
					->findAll()
					->getData();
	
				$price_arr = pjPriceModel::factory()
					->orderBy("t1.date_from ASC, t1.date_to ASC")
					->findAll()
					->getData();
	
				$this->set('space_arr', $space_arr);
				$this->set('price_arr', $price_arr);
				$this->set('code_arr', $code_arr);
			}
	
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminPrices.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionGetDateRange()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$arr = array();
			if((int) $_GET['space_id'] > 0)
			{
				$arr = pjPriceModel::factory()
					->where('t1.space_id', $_GET['space_id'])
					->orderBy("t1.date_from ASC, t1.date_to ASC")
					->findAll()
					->getData();
			}
			$this->set('arr', $arr);
		}
	}
}
?>