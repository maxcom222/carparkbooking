<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminExtras extends pjAdmin
{
	public function pjActionCheckExtra()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && isset($_POST['locale']))
		{
			$locale = $_POST['locale'];
	
			$value = $_POST['i18n'][$locale]['name'];
	
			$pjExtraModel = pjExtraModel::factory();
	
			if (isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$pjExtraModel->where('t1.id !=', $_POST['id']);
			}
			$pjExtraModel->where("t1.id IN(SELECT TL.foreign_id FROM `".pjMultiLangModel::factory()->getTable()."` AS TL WHERE TL.model='pjExtra' AND TL.field='name' AND TL.content = '".$value."' AND TL.locale='$locale')");
			echo $pjExtraModel->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			if (isset($_POST['extra_create']))
			{
				$data = array();
				if($_POST['type'] == 'unlimited')
				{
					$data['cnt'] = ':NULL';
					unset($_POST['cnt']);
				}
				$id = pjExtraModel::factory($_POST)->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'AE03';
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $id, 'pjExtra');
					}
					
				} else {
					$err = 'AE04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminExtras&action=pjActionIndex&err=$err");
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
				$this->appendJs('pjAdminExtras.js');
			}
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionDeleteExtra()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			if (pjExtraModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('model', 'pjExtra')->where('foreign_id', $_GET['id'])->eraseAll();
								
				$response['code'] = 200;
			} else {
				$response['code'] = 100;
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteExtraBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjExtraModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjExtra')->whereIn('foreign_id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
	public function pjActionGetExtra()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjExtraModel = pjExtraModel::factory()
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
				->join('pjMultiLang', "t3.foreign_id = t1.id AND t3.model = 'pjExtra' AND t3.locale = '".$this->getLocaleId()."' AND t3.field = 'description'", 'left');
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjExtraModel->where('t2.content LIKE', "%$q%");
				$pjExtraModel->orWhere('t3.content LIKE', "%$q%");
			}

			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjExtraModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjExtraModel
				->select("t1.*, t2.content AS name")
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();

			$extra_per = __('extra_per', true);
			$extra_types = __('extra_types', true);
			foreach($data as $k => $v)
			{
				$v['price'] = pjUtil::formatCurrencySign($v['price'], $this->option_arr['o_currency']) . ' ' . $extra_per[$v['per']];
				if($v['type'] == 'unlimited')
				{
					$v['cnt'] = $extra_types['unlimited'];
				}
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
			$this->appendJs('pjAdminExtras.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSaveExtra()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjExtraModel = pjExtraModel::factory();
			if (!in_array($_POST['column'], $pjExtraModel->i18n))
			{
				$pjExtraModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjExtra');
			}
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();

		if ($this->isAdmin() || $this->isEditor())
		{
			if (isset($_POST['extra_update']))
			{
				$data = array();
				if(isset($_POST['is_single']))
				{
					$data['is_single'] = 1;
					unset($_POST['is_single']);
				}else{
					$data['is_single'] = 0;
				}
				if($_POST['type'] == 'unlimited')
				{
					$data['cnt'] = ':NULL';
					unset($_POST['cnt']);
				}
				pjExtraModel::factory()->where('id', $_POST['id'])->limit(1)->modifyAll(array_merge($_POST, $data));
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjExtra');
				}
				
				pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminExtras&action=pjActionIndex&err=AE01");
				
			} else {
				$arr = pjExtraModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminExtras&action=pjActionIndex&err=AE08");
				}
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjExtra');
				$this->set('arr', $arr);
				
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
				$this->appendJs('pjAdminExtras.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
}
?>