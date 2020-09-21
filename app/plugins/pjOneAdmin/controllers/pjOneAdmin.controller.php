<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjOneAdmin extends pjOneAdminAppController
{
	public function pjActionDelete()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isOneAdminReady())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				if (pjOneAdminModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Item have been deleted.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Item have not been deleted.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionDeleteBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isOneAdminReady())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				pjOneAdminModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Item(s) have been deleted.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionGet()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isOneAdminReady())
		{
			$pjOneAdminModel = pjOneAdminModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjOneAdminModel->escapeStr($_GET['q']);
				$q = str_replace(array('%', '_'), array('\%', '\_'), trim($q));
				$pjOneAdminModel->where('t1.name LIKE', "%$q%");
			}
				
			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjOneAdminModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjOneAdminModel
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
						
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isOneAdminReady())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjOneAdmin.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionMenu()
	{
		$this->checkLogin();
		
		$this->setAjax(true);
		
		$this->set('arr', pjOneAdminModel::factory()->orderBy('t1.name ASC')->findAll()->getData());
	}
	
	public function pjActionSave()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isOneAdminReady())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && isset($_POST['column'], $_POST['value']))
			{
				pjOneAdminModel::factory()->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Item have been updated.'));
			} else {
				$insert_id = pjOneAdminModel::factory(array('name' => 'Script name', 'url' => 'http://www.example.com/'))->insert()->getInsertId();
				if ($insert_id !== false && (int) $insert_id > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Item have been saved.', 'id' => $insert_id));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Item have not been saved'));
			}
		}
		exit;
	}
}
?>