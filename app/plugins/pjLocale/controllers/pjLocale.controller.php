<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjLocale extends pjLocaleAppController
{
	public $pjLocaleKey = 'pjLocaleKey';
	
	private $pjLocaleError = 'pjLocaleError';
	
	private function pjActionUpdateFieldsIndex()
	{
		return pjOptionModel::factory()
			->where('`key`', 'o_fields_index')
			->limit(1)
			->modifyAll(array('value' => md5(uniqid(rand(), true))))
			->getAffectedRows();
	}
	
	public function pjActionIsFlagReady()
	{
		if (!isset($this->option_arr['o_multi_lang']) || (int) $this->option_arr['o_multi_lang'] === 0)
		{
			return FALSE;
		}
		
		$cnt = pjLocaleModel::factory()
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
			->where('t2.file IS NOT NULL')
			->orderBy('t1.sort ASC')
			->findCount()
			->getData();
		
		return ($cnt > 1);
	} 
	
	public function pjActionLocales()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
		
		if (isset($this->option_arr['o_multi_lang']) && (int) $this->option_arr['o_multi_lang'] === 1)
		{
			$arr = pjLocaleLanguageModel::factory()->where('t1.file IS NOT NULL')->orderBy('t1.title ASC')->findAll()->getData();
			
			foreach ($arr as &$item)
			{
				if (!empty($item['region']))
				{
					$item['title'] = sprintf('%s (%s)', $item['title'], $item['region']);
				}
			}
			
			$this->set('language_arr', $arr);
			
			$this->appendJs('zino.upload.min.js', PJ_THIRD_PARTY_PATH . 'zino_ui/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjLocale.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			$this->appendCss('plugin_locale.css', $this->getConst('PLUGIN_CSS_PATH'));
		} else {
			$this->set('status', 3);
			return;
		}
	}
	
	public function pjActionSaveFields()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
		
		if (isset($_POST['i18n']) && count($_POST['i18n']) > 0)
		{
			$pjFieldModel = pjFieldModel::factory();
			$MultiLangModel = pjMultiLangModel::factory();
			$MultiLangModel->begin();
			foreach ($_POST['i18n'] as $locale_id => $arr)
			{
				foreach ($arr as $foreign_id => $locale_arr)
				{
					$data = array();
					$data[$locale_id] = array();
					foreach ($locale_arr as $name => $content)
					{
						$data[$locale_id][$name] = $content;
					}
					$fids = $MultiLangModel->updateMultiLang($data, $foreign_id, 'pjField');
					if (!empty($fids))
					{
						$pjFieldModel->reset()->whereIn('id', $fids)->limit(count($fids))->modifyAll(array('modified' => ':NOW()'));
					}
				}
			}
			$MultiLangModel->commit();
			$this->pjActionUpdateFieldsIndex();
		}
		pjUtil::redirect(sprintf("%sindex.php?controller=pjLocale&action=%s&err=PAL01&tab=1&q=%s&locale=%u&page=%u", PJ_INSTALL_URL, $_POST['next_action'], urlencode($_POST['q']), $_POST['locale'], $_POST['page']));
		exit;
	}
	
	private function pjActionCheckDefault()
	{
		if (0 == pjLocaleModel::factory()->where('is_default', 1)->findCount()->getData())
		{
			pjLocaleModel::factory()->limit(1)->modifyAll(array('is_default' => 1));
		}
	}
	
	public function pjActionDeleteLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!(isset($_GET['id']) && !empty($_GET['id'])))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
			}
			
			$pjLocaleModel = pjLocaleModel::factory();
			$arr = $pjLocaleModel->find($_GET['id'])->getData();
			if (empty($arr))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Locale not found.'));
			}
			
			if ($pjLocaleModel->reset()->set('id', $_GET['id'])->erase()->getAffectedRows() != 1)
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Locale has not been deleted.'));
			}
			
			if (!empty($arr['flag']) && is_file($arr['flag']))
			{
				@unlink($arr['flag']);
			}
			
			pjMultiLangModel::factory()->where('locale', $_GET['id'])->eraseAll();
			$this->pjActionUpdateFieldsIndex();
			
			$this->pjActionCheckDefault();
			
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Locale has been deleted.'));
		}
		exit;
	}
	
	public function pjActionGetLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjLocaleModel = pjLocaleModel::factory();
			
			$column = 't1.sort';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjLocaleModel->findCount()->getData();
			$rowCount = 100;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjLocaleModel
				->select(sprintf("t1.*, t2.title, CONCAT('%spj/img/flags/', t2.file) AS `file`", PJ_FRAMEWORK_LIBS_PATH))
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
				->orderBy("$column $direction")->findAll()->getData();
						
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionImportExport()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
    
    	$pjLocaleModel = pjLocaleModel::factory()
			->select('t1.*, t2.title')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso');
      
    	if (!isset($this->option_arr['o_multi_lang']) || (int) $this->option_arr['o_multi_lang'] === 0)
		{
			$pjLocaleModel->where('t1.is_default', 1);
		}
    
		$this->set('locale_arr',$pjLocaleModel->orderBy('t1.sort ASC')
			->findAll()
			->getData()
		);
	}
	
	public function pjActionImportConfirm()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
    	$pjLocaleModel = pjLocaleModel::factory()
			->select('t1.*, t2.title, t2.region')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso');
		if (!isset($this->option_arr['o_multi_lang']) || (int) $this->option_arr['o_multi_lang'] === 0)
		{
			$pjLocaleModel->where('t1.is_default', 1);
		}
		$locale_arr = $pjLocaleModel->orderBy('t1.sort ASC')
			->findAll()
			->getDataPair('id');
			
		$columns = count($locale_arr) + 2;
		
		if (isset($_POST['import']))
		{
			if (isset($_FILES['file'], $_POST['separator']))
			{
				$pjUpload = new pjUpload();
				$pjUpload->setAllowedExt(array('csv', 'txt'));
				$pjUpload->setAllowedTypes(array(
					'text/plain', 
					'text/x-comma-separated-values',
					'text/comma-separated-values',
					'application/x-csv',
					'application/csv',
					'text/x-csv',
					'text/csv', 
					'application/vnd.ms-excel', 
					'application/octet-stream',
				));
				
				if ($pjUpload->load($_FILES['file']))
				{
					if (($handle = fopen($pjUpload->getFile('tmp_name'), "rb")) !== FALSE)
					{
						$separators = array(
							'comma' => ",",
							'semicolon' => ";",
							'tab' => "\t"
						);
						$separator = $separators[$_POST['separator']];
						
						$field_arr = pjFieldModel::factory()->findAll()->getDataPair('id', 'key');
						
						$time = time();
						if (!isset($_SESSION[$this->pjLocaleError]))
						{
							$_SESSION[$this->pjLocaleError] = array();
						}
						
						$i = 1;
						$prev_cnt = 0;
						$header = array();
						while (($data = fgetcsv($handle, 0, $separator)) !== FALSE)
						{
							if (!empty($data))
							{
								$nl = preg_grep('/\r\n|\n/', $data);
								if (!empty($nl))
								{
									$_SESSION[$this->pjLocaleError][$time] = sprintf(__('plugin_locale_error_line', true), $i);
									$err = 'PAL14&tm=' . $time;
									break;
								}
								
								$cnt = count($data);
								if ($cnt <= 2)
								{
									$_SESSION[$this->pjLocaleError][$time] = sprintf(__('plugin_locale_error_line', true), $i);
									$err = 'PAL15&tm=' . $time;
									break;
								}
								if ($prev_cnt > 0 && $cnt != $prev_cnt)
								{
									$_SESSION[$this->pjLocaleError][$time] = sprintf(__('plugin_locale_error_line', true), $i);
									$err = 'PAL16&tm=' . $time;
									break;
								}
								
								if ($i > 1 && isset($id, $key) && $id !== FALSE && $key !== FALSE)
								{
									if (!preg_match('/^\d+$/', $data[$id]) || !preg_match('/^[\w\-]+$/', $data[$key]))
									{
										$_SESSION[$this->pjLocaleError][$time] = sprintf(__('plugin_locale_error_line', true), $i);
										$err = 'PAL19&tm=' . $time;
										break;
									}
									if (!isset($field_arr[$data[$id]]))
									{
										continue;
									}
									if (isset($field_arr[$data[$id]]) && $data[$key] != $field_arr[$data[$id]])
									{
										continue;
									}
								} elseif ($i == 1) {
									$header = $data;
									$id = array_search('id', $data);
									$key = array_search('key', $data);
									if ($id === FALSE || $key === FALSE)
									{
										$_SESSION[$this->pjLocaleError][$time] = sprintf(__('plugin_locale_error_line', true), $i);
										$err = 'PAL18&tm=' . $time;
										break;
									}
								}
								
								$prev_cnt = $cnt;
								$i += 1;
							} else {
								$_SESSION[$this->pjLocaleError][$time] = sprintf(__('plugin_locale_error_line', true), $i);
								$err = 'PAL17&tm=' . $time;
								break;
							}
						}
						fclose($handle);
					} else {
						$err = 'PAL13';
					}
				} else {
					$err = 'PAL12';
				}
			} else {
				$err = 'PAL11';
			}
			
			if (!isset($err))
			{
				$locales = array();
				foreach ($header as $k => $col)
				{
					if (in_array($k, array($id, $key)))
					{
						continue;
					}
					list($locales[],) = explode('::', $col);
				}
				
				$key = md5(uniqid(rand(), true));
				$dest = PJ_UPLOAD_PATH . $key . ".csv";
				if ($pjUpload->save($dest))
				{
					$_SESSION[$key] = array(
						'name' => $dest,
						'separator' => $_POST['separator'],
						'locales' => $locales
					);
					$err = 'PAL20&key=' . $key;
				} else {
					$err = 'PAL20';
				}
			}
			pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjLocale&action=pjActionImportConfirm&tab=1&err=".$err);
		}
		
		$this->set('locale_arr', $locale_arr);
		
		if (isset($_GET['tm'], $_SESSION[$this->pjLocaleError][$_GET['tm']]))
		{
			$this->set('tm_text', $_SESSION[$this->pjLocaleError][$_GET['tm']]);
		}
	}
	
	public function pjActionImport()
	{
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
		
		$err = 'PAL02';
		if (isset($_POST['import']) && $this->isLoged() && $this->isAdmin())
		{
			@set_time_limit(600); //10 min
			
			if (isset($_POST['key'], $_POST['locale'], $_SESSION[$_POST['key']], $_SESSION[$_POST['key']]['name'], $_SESSION[$_POST['key']]['separator'])
				&& !empty($_POST['locale'])
				&& !empty($_POST['key'])
				&& !empty($_SESSION[$_POST['key']]['name'])
				&& !empty($_SESSION[$_POST['key']]['separator']))
			{
				if (($handle = fopen($_SESSION[$_POST['key']]['name'], "rb")) !== FALSE)
				{
					$pjMultiLangModel = pjMultiLangModel::factory();
					
					$multi_lang_arr = $pjMultiLangModel
						->select('t1.locale, t1.id AS `mid`, t2.id, t2.key')
						->join('pjField', 't2.id=t1.foreign_id', 'inner')
						->where('t1.model', 'pjField')
						->where('t1.field', 'title')
						->whereIn('t1.locale', $_POST['locale'])
						->where('t1.source !=', 'data')
						->findAll()
						->getData();
		
					if (empty($multi_lang_arr))
					{
						exit;
					}
		
					$import_arr = array();
					foreach ($multi_lang_arr as $k => $item)
					{
						if (!isset($import_arr[$item['key']]))
						{
							$import_arr[$item['key']] = array(
								'id' => $item['id'],
								'key' => $item['key'],
								'locales' => array()
							);
						}
						$import_arr[$item['key']]['locales'][$item['locale']] = $item['mid'];
					}
					
					if (empty($import_arr))
					{
						exit;
					}
			
					$separators = array(
						'comma' => ",",
						'semicolon' => ";",
						'tab' => "\t"
					);
					$separator = $separators[$_SESSION[$_POST['key']]['separator']];
					
					$pjMultiLangModel->reset()->begin();
					
					$i = 1;
					while (($data = fgetcsv($handle, 0, $separator)) !== FALSE)
					{
						if (!empty($data))
						{
							if ($i > 1 && isset($id, $key, $locales)
								&& !empty($locales)
								&& $id !== FALSE
								&& $key !== FALSE
								&& isset($import_arr[$data[$key]]))
							{
								foreach ($import_arr[$data[$key]]['locales'] as $locale_id => $mid)
								{
									if (($index = array_search($locale_id, $locales)) !== FALSE
										&& $data[$key] == $import_arr[$data[$key]]['key'])
									{
										$pjMultiLangModel
											->set('id', $mid)
											->modify(array(
												'content' => str_replace(array('\n', '\t'), array("\r\n", "\t"), $data[$index])
											));
									}
								}
							} elseif ($i == 1) {
								$id = array_search('id', $data);
								$key = array_search('key', $data);
								if ($id !== FALSE && $key !== FALSE)
								{
									$locales = array();
									foreach ($data as $k => $col)
									{
										if (in_array($k, array($id, $key)))
										{
											continue;
										}
										list($loc,) = explode('::', $col);
										$locales[$k] = $loc;
									}
								}
							}
							$i += 1;
						}
					}
				    fclose($handle);
					@unlink($_SESSION[$_POST['key']]['name']);
					
				    if ($i > 1)
					{
						$pjMultiLangModel->commit();
						$this->pjActionUpdateFieldsIndex();
						$err = 'PAL03';
					} else {
						$err = 'PAL04';
					}
				} else {
					$err = 'PAL05';
				}
			}
		}
		pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjLocale&action=pjActionImportExport&tab=1&err=".$err);
	}
	
	public function pjActionExport()
	{
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
		
		if (isset($_POST['export']) && isset($_POST['separator']) && $this->isLoged() && $this->isAdmin())
		{
			@set_time_limit(600); //10 min
			
			$name = 'pjLocale-'.time();

			$AppModel = pjAppModel::factory();
			$pjFieldModel = pjFieldModel::factory();
			$pjMultiLangModel = pjMultiLangModel::factory();
      
       		$pjLocaleModel = pjLocaleModel::factory()
       			->select('t1.*, t2.title, t2.region')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso');
      		if (!isset($this->option_arr['o_multi_lang']) || (int) $this->option_arr['o_multi_lang'] === 0)
			{
				$pjLocaleModel->where('t1.is_default', 1);
			}
				
			$locale_arr = $pjLocaleModel->orderBy('t1.sort ASC')
				->findAll()
				->getDataPair('id');

			if (empty($locale_arr))
			{
				exit;
			}

			$multi_lang_arr = $pjMultiLangModel
				->select('t1.locale, t1.content, t2.id, t2.key')
				->join('pjField', 't2.id=t1.foreign_id', 'left outer')
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->whereIn('t1.locale', array_keys($locale_arr))
				->where('t1.source !=', 'data')
				->findAll()
				->getData();

			if (empty($multi_lang_arr))
			{
				exit;
			}

			$export_arr = array();
			foreach ($multi_lang_arr as $k => $item)
			{
				if (!isset($export_arr[$item['id']]))
				{
					$export_arr[$item['id']] = array(
						'key' => $item['key'],
						'locales' => array()
					);
				}
				$export_arr[$item['id']]['locales'][$item['locale']] = $item['content'];
			}
			
			$csv = array();
			
			$separators = array(
				'comma' => ",",
				'semicolon' => ";",
				'tab' => "\t"
			);
			$separator = $separators[$_POST['separator']];

			$header = array('id', 'key');
			foreach ($locale_arr as $id => $data)
			{
				$title = $data['title'] . (!empty($data['region']) ? sprintf(' (%s)', $data['region']) : NULL);
				$title = str_replace(array(',', ';'), ' ', $title);
				$title = preg_replace('/\t/', ' ', $title);
				$header[] = $id . '::' . $title;
			}
			$csv[] = join($separator, $header);
			
			foreach ($export_arr as $id => $data)
			{
				if(!empty($id))
				{
					$cells = array();
					$cells[] = '"' . (int) $id . '"';
					$cells[] = '"' . str_replace(array("\r\n", "\n", "\t", '"'), array('\n', '\n', '\t', '""'), $data['key']) . '"';
					foreach ($locale_arr as $locale_id => $item)
					{
						if (isset($data['locales'][$locale_id]))
						{
							$cells[] = '"' . str_replace(array("\r\n", "\n", "\t", '"'), array('\n', '\n', '\t', '""'), $data['locales'][$locale_id]) . '"';
						} else {
							$cells[] = '""';
						}
					}
					
					$csv[] = "\n";
					$csv[] = join($separator, $cells);
				}
			}

   			$content = join("", $csv);
   			pjToolkit::download($content, $name.'.csv');
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();

		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
		if (isset($_POST['lang_show_id']))
		{
			if (isset($_POST['show_id']))
			{
				$_SESSION['lang_show_id'] = 1;
			} else {
				$_SESSION['lang_show_id'] = 0;
			}
			$this->pjActionUpdateFieldsIndex();
		}

		$this->set('field_arr', pjFieldModel::factory()->findAll()->getDataPair('id', 'label'));
		$pjLocaleModel = pjLocaleModel::factory()
			->select('t1.*, t2.title, t2.region, t2.file')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
			->where('t2.file IS NOT NULL')
			->orderBy('t1.sort ASC');
		if (!isset($this->option_arr['o_multi_lang']) || (int) $this->option_arr['o_multi_lang'] === 0)
		{
			$pjLocaleModel->where('t1.is_default', 1);
		}
		$locale_arr = $pjLocaleModel->orderBy("is_default DESC, t1.id ASC")->findAll()->getData();

		$lp_arr = array();
		foreach ($locale_arr as $item)
		{
			$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
		}
		$this->set('lp_arr', $locale_arr);
		$this->set('locale_str', pjAppController::jsonEncode($lp_arr));

		$pjFieldModel = pjFieldModel::factory()
			->join('pjMultiLang', "t2.model='pjField' AND t2.foreign_id=t1.id AND t2.locale='".$this->getLocaleId()."'", 'left');
		if (isset($_GET['q']) && !empty($_GET['q']))
		{
			$q = $pjFieldModel->escapeStr(trim($_GET['q']));
			$q = str_replace(array('%', '_'), array('\%', '\_'), $q);

			$id_arr = array();
			$_arr = explode(":", $q);
			if (count($_arr) >= 3)
			{
				$last_index = count($_arr)-1;
				unset($_arr[0]);
				unset($_arr[$last_index]);
				foreach($_arr as $id)
				{
					if((int)$id > 0)
					{
						$id_arr[] = $id;
					}
				}
			}
			if (!empty($id_arr))
			{
				$pjFieldModel->whereIn("t1.id", $id_arr);
			} else {
				$pjFieldModel->where("(t1.label LIKE '%$q%' OR t2.content LIKE '%$q%')");
			}
			
			if (get_magic_quotes_gpc())
	    	{
	    		$_GET['q'] = stripslashes($_GET['q']);
	    	}
		}
		$pjMultiLangModel = pjMultiLangModel::factory()->where('model', 'pjField')->where('field', 'title');

		$column = 'id';
		$direction = 'ASC';
		if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
		{
			$column = $_GET['column'];
			$direction = strtoupper($_GET['direction']);
		}

		$total = $pjFieldModel->findCount()->getData();
		$row_count = isset($_GET['row_count']) && (int) $_GET['row_count'] > 0 ? (int) $_GET['row_count'] : 15;
		$pages = ceil($total / $row_count);
		$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
		if ($page > $pages)
		{
			$page = $pages > 0 ? $pages : 1;
			$_GET['page'] = $page;
		}
		$offset = ((int) $page - 1) * $row_count;

		$_arr = $pjFieldModel->select("t1.*")->orderBy("$column $direction")->limit($row_count, $offset)->findAll()->getData();

		foreach ($_arr as $_k => $_v)
		{
			$pjMultiLangModel->reset()
				->select('t1.*, t2.is_default')
				->join('pjLocale', 't2.id=t1.locale', 'left')
				->where('model', 'pjField')
				->where('field', 'title')
				->where('foreign_id', $_v['id']);
        	if (!isset($this->option_arr['o_multi_lang']) || (int) $this->option_arr['o_multi_lang'] === 0)
		  	{
				$pjMultiLangModel->where('t1.locale', $locale_arr[0]['id']);
        	}
			$tmp = $pjMultiLangModel->orderBy("t2.is_default DESC, t2.id ASC")
				->findAll()
				->getData();
			$_arr[$_k]['i18n'] = array();
			foreach ($tmp as $item)
			{
				$_arr[$_k]['i18n'][$item['locale']] = $item;
			}
		}

		$this->set('arr', $_arr);
		$this->set('paginator', compact('pages'));

		$this->appendJs('pjLocale.js', $this->getConst('PLUGIN_JS_PATH'));
		$this->appendCss('plugin_locale.css', $this->getConst('PLUGIN_CSS_PATH'));
	}
		
	public function pjActionSaveLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!($_SERVER['REQUEST_METHOD'] === 'POST'))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'HTTP method not allowed.'));
			}
			
			if (isset($_GET['id'], $_POST['column'], $_POST['value'])
				&& !empty($_POST['column']) 
				&& (int) $_GET['id'] > 0)
			{
				$data = array($_POST['column'] => $_POST['value']);
				if ($_POST['column'] == 'language_iso')
				{
					$tmp = pjLocaleLanguageModel::factory()->where('t1.iso', $_POST['value'])->limit(1)->findAll()->getDataIndex(0);
					
					$data['dir'] = $tmp['dir'];
					$data['name'] = $tmp['native'];
					$data['flag'] = ':NULL';
				}
					
				pjLocaleModel::factory()->set('id', $_GET['id'])->modify($data);
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Locale has been updated.'));
			}
			
			$lang = pjLocaleLanguageModel::factory()
				->where(sprintf("t1.iso NOT IN (SELECT `language_iso` FROM `%s`)", pjLocaleModel::factory()->getTable()))
				->where('t1.file IS NOT NULL')
				->orderBy('t1.title ASC')
				->limit(1)
				->findAll()
				->getDataPair(null, 'iso');
			
			$result = $this->addLocale(@$lang[0]);
			if ($result['status'] === 'OK')
			{
				$this->pjActionUpdateFieldsIndex();
			}
			pjAppController::jsonResponse($result);
		}
		exit;
	}
	
	private function addLocale($iso, $locale_id=NULL)
	{
		$language = pjLocaleLanguageModel::factory()->where('t1.iso', $iso)->limit(1)->findAll()->getDataIndex(0);
		
		$pjLocaleModel = pjLocaleModel::factory();
		
		$pjLocaleModel->begin();
		
		$statement = sprintf("SET @sort := (SELECT MAX(`sort`) + 1 FROM `%s` LIMIT 1);", $pjLocaleModel->getTable());
		$pjLocaleModel->prepare($statement)->exec();
		
		$statement = sprintf("INSERT IGNORE INTO `%s` (`id`, `language_iso`, `name`, `dir`, `sort`, `is_default`) VALUES (NULL, :language_iso, :name, :dir, @sort, 0);", $pjLocaleModel->getTable());
		$insert_id = $pjLocaleModel
			->prepare($statement)
			->exec(array(
				'language_iso' => $iso,
				'name' => isset($language['native']) ? $language['native'] : 'NULL',
				'dir' => isset($language['dir']) ? $language['dir'] : 'NULL',
			))
			->getInsertId();
		
		if (!($insert_id !== FALSE && (int) $insert_id > 0))
		{
			return array('status' => 'ERR', 'code' => 100, 'text' => 'Locale has not been added.');
		}
		
		if (empty($locale_id))
		{
			$arr = $pjLocaleModel->reset()->findAll()->getData();
			foreach ($arr as $locale)
			{
				if ($locale['language_iso'] == 'gb')
				{
					$locale_id = $locale['id'];
					break;
				}
			}
			if (is_null($locale_id) && !empty($arr))
			{
				$locale_id = $arr[0]['id'];
			}
		}
		
		if (empty($locale_id))
		{
			$pjLocaleModel->rollback();
			return array('status' => 'ERR', 'code' => 101, 'text' => 'Locale ID is empty.');
		}
		
		$pjLocaleModel->commit();
		
		$pjMultiLangModel = pjMultiLangModel::factory();
		
		$sql = sprintf("INSERT IGNORE INTO `%1\$s` (`foreign_id`, `model`, `locale`, `field`, `content`)
			SELECT t1.foreign_id, t1.model, :insert_id, t1.field, t1.content
			FROM `%1\$s` AS t1
			WHERE t1.locale = :locale", $pjMultiLangModel->getTable());
		
		$pjMultiLangModel->prepare($sql)->exec(array(
			'insert_id' => $insert_id,
			'locale' => (int) $locale_id
		));
		
		return array('status' => 'OK', 'code' => 200, 'text' => 'Locale has been added.', 'id' => $insert_id);
	}
	
	public function pjActionAddLocale()
	{
		$params = $this->getParams();
		
		return $this->addLocale($params['iso'], @$params['locale']);
	}
	
	public function pjActionSaveDefault()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjLocaleModel::factory()
				->where(1,1)
				->modifyAll(array('is_default' => '0'))
				->reset()
				->set('id', $_POST['id'])
				->modify(array('is_default' => 1));
				
			$this->setLocaleId($_POST['id']);
		}
		exit;
	}
	
	public function pjActionSortLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$LocaleModel = new pjLocaleModel();
			$arr = $LocaleModel->whereIn('id', $_POST['sort'])->orderBy("t1.sort ASC")->findAll()->getDataPair('id', 'sort');
			$fliped = array_flip($_POST['sort']);
			$combined = array_combine(array_keys($fliped), $arr);
			$LocaleModel->begin();
			foreach ($combined as $id => $sort)
			{
				$LocaleModel->setAttributes(compact('id'))->modify(compact('sort'));
			}
			$LocaleModel->commit();
		}
		exit;
	}

	public function pjActionClean()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
		
		if (isset($_POST['clean_step']))
		{
			if ($_POST['clean_step'] == 1)
			{
				$pjMultiLangModel = pjMultiLangModel::factory();
				$arr = pjMultiLangModel::factory()
					->select('t1.id')
					->join('pjField', 't2.id=t1.foreign_id', 'left')
					->where('t1.model', 'pjField')
					->where('t2.id IS NULL')
					->findAll()
					->getDataPair(null, 'id');
	
				if (!empty($arr))
				{
					$pjMultiLangModel->reset()->whereIn('id', $arr)->eraseAll();
					$this->pjActionUpdateFieldsIndex();
				}
			}
			
			if ($_POST['clean_step'] == 2)
			{
				if (isset($_POST['field_id']) && !empty($_POST['field_id']))
				{
					pjFieldModel::factory()->whereIn('id', $_POST['field_id'])->eraseAll();
					pjMultiLangModel::factory()->where('model', 'pjField')->whereIn('foreign_id', $_POST['field_id'])->eraseAll();
					$this->pjActionUpdateFieldsIndex();
				}
			}
			
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjLocale&action=pjActionClean");
		}
		
		# Step 1
		$step1_arr = pjMultiLangModel::factory()
			->select('t1.id')
			->join('pjField', 't2.id=t1.foreign_id', 'left')
			->where('t1.model', 'pjField')
			->where('t2.id IS NULL')
			->findAll()
			->getDataPair(null, 'id');
		
		$this->set('step1_arr', $step1_arr);
		
		# Step 2
		$keys = $start = $data = array();
		pjToolkit::readDir($data, PJ_APP_PATH);
		
		foreach ($data as $file)
		{
			$ext = pjToolkit::getFileExtension($file);
			if ($ext !== 'php')
			{
				continue;
			}
			
			$string = file_get_contents($file);
			if ($string !== FALSE)
			{
				preg_match_all('/__\(\s*\'(\w+)\'\s*(?:,\s*(true|false))?\)/i', $string, $matches);
				if (!empty($matches[1]))
				{
					foreach ($matches[1] as $k => $m)
					{
						if (!empty($matches[2][$k]) && strtolower($matches[2][$k]) == 'true')
						{
							$start[] = $m;
						} else {
							$keys[] = $m;
						}
					}
				}
			}
		}
		$keys = array_unique($keys);
		$keys = array_values($keys);
		
		$start = array_unique($start);
		$start = array_values($start);
		
		if (!empty($keys) || !empty($start))
		{
			$field_arr = pjFieldModel::factory()
				->whereNotIn('t1.key', $keys)
				->whereNotIn("SUBSTRING_INDEX(t1.key, '_ARRAY_', 1)", $start)
				->orderBy("FIELD(t1.type, 'backend', 'frontend', 'arrays'), t1.key ASC", false)
				->findAll()
				->getData();
			
			$this->set('field_arr', $field_arr);
		}
		
		$this->appendJs('pjLocale.js', $this->getConst('PLUGIN_JS_PATH'));
	}
	
	public function pjActionFlagReset()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
			}
				
			if (!(isset($_POST['id']) && !empty($_POST['id'])))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
			}
			
			$pjLocaleModel = pjLocaleModel::factory();
			
			$arr = $pjLocaleModel->find($_POST['id'])->getData();
			if (!empty($arr) && !empty($arr['flag']) && is_file($arr['flag']))
			{
				@unlink($arr['flag']);
			}
			
			$affected_rows = $pjLocaleModel
				->reset()
				->set('id', $_POST['id'])
				->modify(array(
					'flag' => ':NULL'
				))
				->getAffectedRows();
				
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Flag has been reset.'));
		}
		exit;
	}
	
	public function pjActionUpload()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
			}
			
			$post_max_size = ini_get('post_max_size');
			switch (substr($post_max_size, -1))
			{
				case 'G':
					$post_max_size = (int) $post_max_size * 1024 * 1024 * 1024;
					break;
				case 'M':
					$post_max_size = (int) $post_max_size * 1024 * 1024;
					break;
				case 'K':
					$post_max_size = (int) $post_max_size * 1024;
					break;
			}
			if (isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size)
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Image filesize too large. '. $_SERVER['CONTENT_LENGTH'].' bytes exceeds the maximum size of '. $post_max_size.' bytes.'));
			}
			
			if (!(isset($_FILES['flag'], $_POST['id']) && !empty($_FILES['flag']) && !empty($_POST['id'])))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
			}
			
			$image = new pjImage();
			$image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpeg', 'image/pjpeg'));
			$image->setAllowedExt(array('png', 'gif', 'jpg'));
			if (!$image->load($_FILES['flag']))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Flag is not valid.'));
			}
			
			$dst = sprintf('%slocale/%u.png', PJ_UPLOAD_PATH, (int) $_POST['id']);
			
			$image->loadImage();
			$image->resize(16, 11);
			$image->saveImage($dst, IMAGETYPE_PNG);
			
			pjLocaleModel::factory()
				->set('id', $_POST['id'])
				->modify(array(
					'flag' => $dst
				));

			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Flag has been uploaded.'));
		}
		exit;
	}
}
?>