<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminSpaces extends pjAdmin
{
	public function pjActionCheckSpace()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && isset($_POST['locale']))
		{
			$locale = $_POST['locale'];
	
			$value = $_POST['i18n'][$locale]['name'];
	
			$pjSpaceModel = pjSpaceModel::factory();
	
			if (isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$pjSpaceModel->where('t1.id !=', $_POST['id']);
			}
			$pjSpaceModel->where("t1.id IN(SELECT TL.foreign_id FROM `".pjMultiLangModel::factory()->getTable()."` AS TL WHERE TL.model='pjSpace' AND TL.field='name' AND TL.content = '".$value."' AND TL.locale='$locale')");
			echo $pjSpaceModel->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			if (isset($_POST['space_create']))
			{
				$id = pjSpaceModel::factory($_POST)->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'AS03';
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $id, 'pjSpace');
					}
					if (isset($_POST['date_from']) && count($_POST['date_from']) > 0)
					{
						$pjSpaceDateModel = pjSpaceDateModel::factory();
						$pjSpaceDateModel->begin();
						foreach ($_POST['date_from'] as $k => $v)
						{
							if (!empty($v) && !empty($_POST['date_to'][$k]) && !empty($_POST['spaces'][$k]))
							{
								$pjSpaceDateModel
									->reset()
									->set('space_id', $id)
									->set('date_from', pjUtil::formatDate($_POST['date_from'][$k], $this->option_arr['o_date_format']))
									->set('date_to', pjUtil::formatDate($_POST['date_to'][$k], $this->option_arr['o_date_format']))
									->set('spaces', $_POST['spaces'][$k])
									->insert();
							}
						}
						$pjSpaceDateModel->commit();
					}
					
				} else {
					$err = 'AS04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminSpaces&action=pjActionIndex&err=$err");
			} else {
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file'];
				}
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
		
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminSpaces.js');
			}
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionDeleteSpace()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			if (pjSpaceModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('model', 'pjSpace')->where('foreign_id', $_GET['id'])->eraseAll();
				pjCodeModel::factory()->where('space_id', $_GET['id'])->eraseAll();
				pjDiscountModel::factory()->where('space_id', $_GET['id'])->eraseAll();
				pjPriceModel::factory()->where('space_id', $_GET['id'])->eraseAll();
				pjSpaceDateModel::factory()->where('space_id', $_GET['id'])->eraseAll();
				
				$response['code'] = 200;
			} else {
				$response['code'] = 100;
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteSpaceBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjSpaceModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjSpace')->whereIn('foreign_id', $_POST['record'])->eraseAll();
				pjCodeModel::factory()->whereIn('space_id', $_POST['record'])->eraseAll();
				pjDiscountModel::factory()->whereIn('space_id', $_POST['record'])->eraseAll();
				pjPriceModel::factory()->whereIn('space_id', $_POST['record'])->eraseAll();
				pjSpaceDateModel::factory()->whereIn('space_id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
	public function pjActionStatusSpace()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0 && isset($_GET['status']) && in_array($_GET['status'], array('T', 'F')))
			{
				pjSpaceModel::factory()->whereIn('id', $_POST['record'])->modifyAll(array('status' => $_GET['status']));
			}
		}
		exit;
	}
	
	public function pjActionGetSpace()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjSpaceModel = pjSpaceModel::factory()
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpace' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
				->join('pjMultiLang', "t3.foreign_id = t1.id AND t3.model = 'pjSpace' AND t3.locale = '".$this->getLocaleId()."' AND t3.field = 'description'", 'left');
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjSpaceModel->where('t2.content LIKE', "%$q%");
				$pjSpaceModel->orWhere('t3.content LIKE', "%$q%");
			}

			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjSpaceModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjSpaceModel
				->select("t1.id, t1.status, t2.content AS name,
						  (SELECT `price` FROM `".pjPriceModel::factory()->getTable()."` WHERE space_id = t1.id AND (CURDATE() BETWEEN `date_from` AND `date_to`) LIMIT 1) AS `price`,
						  (SELECT `spaces` FROM `".pjSpaceDateModel::factory()->getTable()."` WHERE space_id = t1.id AND (CURDATE() BETWEEN `date_from` AND `date_to`) LIMIT 1) AS `total`,
						  (SELECT COUNT(*) FROM `".pjBookingModel::factory()->getTable()."` WHERE space_id = t1.id AND `status` = 'confirmed' AND (CURDATE() BETWEEN DATE(`from`) AND DATE(`to`)) LIMIT 1) AS `booked`")
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();
			
			foreach($data as $k => $v)
			{
				$v['avail'] = $v['total'] - $v['booked'];
				$v['price'] = $v['price'] != null ? pjUtil::formatCurrencySign($v['price'], $this->option_arr['o_currency']) : '';
				$data[$k] = $v;
			}
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminSpaces.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSaveSpace()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjSpaceModel = pjSpaceModel::factory();
			if (!in_array($_POST['column'], $pjSpaceModel->i18n))
			{
				$pjSpaceModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjSpace');
			}
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();

		if ($this->isAdmin() || $this->isEditor())
		{
			if (isset($_POST['space_update']))
			{
				pjSpaceModel::factory()->where('id', $_POST['id'])->limit(1)->modifyAll($_POST);
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjSpace');
				}
				
				$pjSpaceDateModel = pjSpaceDateModel::factory();
				$pjSpaceDateModel->where('space_id', $_POST['id'])->eraseAll();
				if (isset($_POST['date_from']) && count($_POST['date_from']) > 0)
				{
					$pjSpaceDateModel->begin();
					foreach ($_POST['date_from'] as $k => $v)
					{
						if (!empty($v) && !empty($_POST['date_to'][$k]) && !empty($_POST['spaces'][$k]))
						{
							$pjSpaceDateModel
								->reset()
								->set('space_id', $_POST['id'])
								->set('date_from', pjUtil::formatDate($_POST['date_from'][$k], $this->option_arr['o_date_format']))
								->set('date_to', pjUtil::formatDate($_POST['date_to'][$k], $this->option_arr['o_date_format']))
								->set('spaces', $_POST['spaces'][$k])
								->insert();
						}
					}
					$pjSpaceDateModel->commit();
				}
				
				pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminSpaces&action=pjActionIndex&err=AS01");
				
			} else {
				$arr = pjSpaceModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminSpaces&action=pjActionIndex&err=AS08");
				}
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjSpace');
				$this->set('arr', $arr);
				
				$sd_arr = pjSpaceDateModel::factory()
					->where('t1.space_id', $_GET['id'])
					->findAll()
					->getData();
				$this->set('sd_arr', $sd_arr);
				
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
				
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; 
				}
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminSpaces.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
}
?>