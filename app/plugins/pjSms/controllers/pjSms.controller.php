<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjSms extends pjSmsAppController
{
	private $statuses = array(
		0 => 'Account limit reached',
		1 => 'Message sent',
		2 => 'Message not sent',
		3 => 'Account not confirmed',
		4 => 'Incorrect API key',
		5 => 'Account is disabled',
	);
	
	public function pjActionGetSms()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			$pjSmsModel = pjSmsModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjSmsModel->escapeStr($_GET['q']);
				$q = str_replace(array('%', '_'), array('\%', '\_'), $q);
				$pjSmsModel->where("(t1.number LIKE '%$q%' OR t1.text LIKE '%$q%')");
			}
			
			$column = 'created';
			$direction = 'DESC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjSmsModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjSmsModel->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
					
			foreach ($data as &$item)
			{
				if (!empty($item['created']))
				{
					$ts = strtotime($item['created']);
					$date = date('Y-m-d', $ts);
					$time = date('H:i:s', $ts);
					if (isset($this->option_arr['o_date_format']) && !empty($this->option_arr['o_date_format']))
					{
						$date = date($this->option_arr['o_date_format'], $ts);
					}
					if (isset($this->option_arr['o_time_format']) && !empty($this->option_arr['o_time_format']))
					{
						$time = date($this->option_arr['o_time_format'], $ts);
					} 
					$item['created'] = $date . ', ' . $time;
				} else {
					$item['created'] = NULL;
				}
				$item['status'] = isset($this->statuses[$item['status']]) ? $this->statuses[$item['status']] : $item['status'];
			}
					
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_POST['sms_post']))
			{
				$pjOptionModel = pjOptionModel::factory();
				
				if (0 != $pjOptionModel
					->where('foreign_id', $this->getForeignId())
					->where('`key`', 'plugin_sms_api_key')
					->findCount()->getData()
				)
				{
					$pjOptionModel
						->limit(1)
						->modifyAll(array(
							'value' => $_POST['plugin_sms_api_key']
						));
				} else {
					$pjOptionModel->setAttributes(array(
						'foreign_id' => $this->getForeignId(),
						'key' => 'plugin_sms_api_key',
						'tab_id' => '99',
						'value' => $_POST['plugin_sms_api_key'],
						'type' => 'string',
						'is_visible' => 0
					))->insert();
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjSms&action=pjActionIndex&err=PSS02");
			}
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjSms.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionSend()
	{
		$this->setAjax(true);
		
		$params = $this->getParams();
		
		if (!isset($params['key']) || $params['key'] != md5($this->option_arr['private_key'] . PJ_SALT) ||
			!isset($params['number']) || !isset($params['text']) || !isset($this->option_arr['plugin_sms_api_key']))
		{
			return FALSE;
		}
		
		$pjSmsApi = new pjSmsApi();
		
		if (isset($params['type']))
		{
			$pjSmsApi->setType($params['type']);
		}

		$sender = null;
		if(isset($params['sender']) && !empty($params['sender']))
		{
			$sender = $params['sender'];
		}
		
		$response = $pjSmsApi
			->setApiKey($this->option_arr['plugin_sms_api_key'])
			->setNumber($params['number'])
			->setText($params['text'])
			->setSender($sender)
			->send();
			
		pjSmsModel::factory()->setAttributes(array(
			'number' => $pjSmsApi->getNumber(),
			'text' => $pjSmsApi->getText(),
			'status' => $response
		))->insert();
		
		return $response;
	}
}
?>