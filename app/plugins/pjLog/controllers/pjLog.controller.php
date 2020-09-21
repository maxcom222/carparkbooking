<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjLog extends pjLogAppController
{
	public function pjActionConfig()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$pjLogConfigModel = pjLogConfigModel::factory();

			if (isset($_POST['update_config']))
			{
				$pjLogConfigModel->eraseAll();
				
				if (isset($_POST['filename']) && count($_POST['filename']) > 0)
				{
					$pjLogConfigModel->begin();
					foreach ($_POST['filename'] as $filename)
					{
						$pjLogConfigModel->reset()->set('filename', $filename)->insert();
					}
					$pjLogConfigModel->commit();
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjLog&action=pjActionConfig&err=PLG01");
			}

			$data = array();
			pjUtil::readDir($data, 'app/controllers/');
			pjUtil::readDir($data, 'app/plugins/');
			$this->set('data', $data);

			$this->set('config_arr', $pjLogConfigModel->findAll()->getDataPair('id', 'filename'));
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteLogBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjLogModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
	public function pjActionEmptyLog()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			pjLogModel::factory()->truncate();
		}
		exit;
	}
	
	public function pjActionGetLog()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			$pjLogModel = pjLogModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjLogModel->escapeStr($_GET['q']);
				$q = str_replace(array('%', '_'), array('\%', '\_'), $q);
				$pjLogModel->where('t1.filename LIKE', "%$q%");
			}
				
			$column = 'created';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjLogModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjLogModel->select('t1.*')
				->orderBy("`$column` $direction")->limit($rowCount, $offset)->findAll()->getData();
						
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();

		if ($this->isAdmin())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjLog.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionLogger()
	{
		$params = $this->getParams();
		if (!isset($params['key']) || $params['key'] != md5($this->option_arr['private_key'] . PJ_SALT))
		{
			return FALSE;
		}
		
		$debug_backtrace = debug_backtrace(false);

		$controller = NULL;
    	foreach ($debug_backtrace as $item)
    	{
    		if (strpos($item['file'], 'pjObserver.class.php') !== false)
    		{
    			$params['function'] = $item['args'][0]['action'];
    			$controller = $item['args'][0]['controller'];
    			break;
    		}
    	}
    	
    	foreach ($debug_backtrace as $item)
    	{
    		if (strpos($item['file'], $controller) !== false)
    		{
    			$params['filename'] = str_replace(PJ_INSTALL_PATH, "", str_replace("\\", "/", $item['file']));
    		}
    	}

    	if (!is_null($controller))
    	{
			if (pjLogConfigModel::factory()->where('t1.filename', $controller)->findCount()->getData() != 0)
			{
				pjLogModel::factory($params)->insert();
			}
    	}
	}
}
?>