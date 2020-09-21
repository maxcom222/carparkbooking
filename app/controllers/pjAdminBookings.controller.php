<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once PJ_CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminBookings extends pjAdmin
{                  
	public function pjActionCheckUniqueId()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && isset($_GET['uuid']))
		{
			$pjBookingModel = pjBookingModel::factory();
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjBookingModel->where('t1.id !=', $_GET['id']);
			}
			echo $pjBookingModel->where('t1.uuid', $_GET['uuid'])->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionGetBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjBookingModel = pjBookingModel::factory()
				->join('pjMultiLang', "t2.model='pjSpace' AND t2.foreign_id=t1.space_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjBookingModel->where('t1.uuid LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_name LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_email LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_phone LIKE', "%$q%");
			}
						
			if (isset($_GET['space_id']) && (int) $_GET['space_id'] > 0)
			{
				$pjBookingModel->where('t1.space_id', $_GET['space_id']);
			}
			if (isset($_GET['uuid']) && $_GET['uuid'] != '')
			{
				$uuid = pjObject::escapeString($_GET['uuid']);
				$pjBookingModel->where('t1.uuid LIKE', "%$uuid%");
			}
			if (isset($_GET['c_name']) && $_GET['c_name'] != '')
			{
				$customer_name = pjObject::escapeString($_GET['c_name']);
				$pjBookingModel->where('t1.c_name LIKE', "%$customer_name%");
			}
			if (isset($_GET['c_email']) && $_GET['c_email'] != '')
			{
				$customer_email = pjObject::escapeString($_GET['c_email']);
				$pjBookingModel->where('t1.c_email LIKE', "%$customer_email%");
			}
			if (isset($_GET['status']) && !empty($_GET['status']))
			{
				$pjBookingModel->where('t1.status', $_GET['status']);
			}
			if (isset($_GET['date_from']) && !empty($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_to']))
			{
				$df = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
				$dt = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
				$pjBookingModel->where("( (t1.from BETWEEN '$df' AND '$dt') OR (t1.to BETWEEN '$df' AND '$dt') OR ('$df' BETWEEN t1.from AND t1.to) OR ('$dt' BETWEEN t1.from AND t1.to) )");
			
			} else {
				if (isset($_GET['date_from']) && !empty($_GET['date_from']))
				{
					$df = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
					$pjBookingModel->where("(t1.from >= '$df')");
				} elseif (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
					$dt = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
					$pjBookingModel->where("(t1.to <= '$df')");
				}
			}
			
			$column = 'created';
			$direction = 'DESC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjBookingModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			
			$data = $pjBookingModel
				->select("t1.*, t2.content as space")
				->orderBy("`$column` $direction")->limit($rowCount, $offset)->findAll()->getData();
			
			foreach($data as $k => $v)
			{
				$v['from'] = date($this->option_arr['o_date_format'], strtotime($v['from'])) . ', ' . date($this->option_arr['o_time_format'], strtotime($v['from'])) . ' - ' . date($this->option_arr['o_date_format'], strtotime($v['to'])) . ', ' . date($this->option_arr['o_time_format'], strtotime($v['to']));
				unset($v['cc_exp_month']);
				unset($v['cc_exp_year']);
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
			
			$space_arr = pjSpaceModel::factory()
				->select('t1.*, t2.content as name')
				->join('pjMultiLang', "t2.model='pjSpace' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where("t1.status", 'T')
				->orderBy("name ASC")
				->findAll()
				->getData();
			
			$this->set('space_arr', $space_arr);
			
			$this->appendJs('chosen.jquery.min.js', PJ_THIRD_PARTY_PATH . 'chosen/');
			$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminBookings.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionExportBooking()
	{
		$this->checkLogin();
		
		if (isset($_POST['record']) && is_array($_POST['record']))
		{
			$arr = pjBookingModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
			$csv = new pjCSV();
			$csv
				->setHeader(true)
				->setName("Bookings-".time().".csv")
				->process($arr)
				->download();
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			if (isset($_POST['booking_create']))
			{
				$data = array();
				
				$pjBookingModel = pjBookingModel::factory();
				$pjBookingExtraModel = pjBookingExtraModel::factory();
				
				$data['uuid'] = pjUtil::uuid();
				$data['ip']= pjUtil::getClientIp();
				
				$from_arr = pjUtil::convertDateTime($_POST['from'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
				$to_arr = pjUtil::convertDateTime($_POST['to'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
				
				unset($_POST['from']);
				unset($_POST['to']);
				
				$data['from'] = $from_arr['iso_date_time'];
				$data['to'] = $to_arr['iso_date_time'];
				
				$post = array_merge($_POST, $data);
				
				$id = $pjBookingModel->setAttributes($post)->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					if (isset($_POST['extra_id']) && is_array($_POST['extra_id']) && count($_POST['extra_id']) > 0)
					{
						$pjBookingExtraModel->begin();
						foreach ($_POST['extra_id'] as $k => $extra_id)
						{
							$cnt = 1;
							if($_POST['qty'][$k] != 'on')
							{
								$cnt = $_POST['qty'][$k];
							}
							$pjBookingExtraModel
								->reset()
								->set('booking_id', $id)
								->set('extra_id', $extra_id)
								->set('price', $_POST['price'][$k])
								->set('cnt', $cnt)
								->insert();
						}
						$pjBookingExtraModel->commit();
					}
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=AR03");
				} else {
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=AR04");
				}
			}else{
				
				$country_arr = pjCountryModel::factory()
					->select('t1.id, t2.content AS country_title')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->orderBy('`country_title` ASC')
					->findAll()
					->getData();
				
				$this->set('country_arr', $country_arr);
				
				$this->appendJs('jquery-ui-timepicker-addon.js', PJ_THIRD_PARTY_PATH . 'datetimepicker/');
				$this->appendCss('jquery-ui-timepicker-addon.css', PJ_THIRD_PARTY_PATH . 'datetimepicker/');
				$this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminBookings.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
	
		if ($this->isAdmin() || $this->isEditor())
		{
			if (isset($_POST['booking_update']))
			{
				$data = array();
				
				$pjBookingModel = pjBookingModel::factory();
				$pjBookingExtraModel = pjBookingExtraModel::factory();
				
				$from_arr = pjUtil::convertDateTime($_POST['from'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
				$to_arr = pjUtil::convertDateTime($_POST['to'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
				
				unset($_POST['from']);
				unset($_POST['to']);
				
				$data['from'] = $from_arr['iso_date_time'];
				$data['to'] = $to_arr['iso_date_time'];
				$data['modified'] = date('Y-m-d H:i:s');
				
				$post = array_merge($_POST, $data);
				$pjBookingModel->reset()->set('id', $_POST['id'])->modify($post);
				
				$pjBookingExtraModel->where('booking_id', $_POST['id'])->eraseAll();
				if (isset($_POST['extra_id']) && is_array($_POST['extra_id']) && count($_POST['extra_id']) > 0)
				{
					$pjBookingExtraModel->begin();
					foreach ($_POST['extra_id'] as $k => $extra_id)
					{
						$cnt = 1;
						if($_POST['qty'][$k] != 'on')
						{
							$cnt = $_POST['qty'][$k];
						}
						$pjBookingExtraModel
							->reset()
							->set('booking_id', $_POST['id'])
							->set('extra_id', $extra_id)
							->set('price', $_POST['price'][$k])
							->set('cnt', $cnt)
							->insert();
					}
					$pjBookingExtraModel->commit();
				}
				pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminBookings&action=pjActionUpdate&id=".$_POST['id']."&err=AR01");
			}else{
	
				$arr = pjBookingModel::factory()
					->find($_GET['id'])
					->getData();
				
				if(count($arr) <= 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminBookings&action=pjActionIndex&err=AR08");
				}
				
				$be_arr = pjBookingExtraModel::factory()
					->where('t1.booking_id', $_GET['id'])
					->findAll()
					->getData();
				
				$country_arr = pjCountryModel::factory()
					->select('t1.id, t2.content AS country_title')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->orderBy('`country_title` ASC')
					->findAll()
					->getData();
	
				$from_ts = strtotime($arr['from']);
				$to_ts = strtotime($arr['to']);
	
				$iso_from = date('Y-m-d', $from_ts);
				$iso_to = date('Y-m-d', $to_ts);
	
				$from = date($this->option_arr['o_date_format'], $from_ts) . ' ' . date($this->option_arr['o_time_format'], $from_ts);
				$to = date($this->option_arr['o_date_format'], $to_ts) . ' ' . date($this->option_arr['o_time_format'], $to_ts);
	
				$rental_days = pjAppController::getRentalDays($this->option_arr, $iso_from, $iso_to, date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));
				$space_arr = pjAppController::getSpaces($from_ts, $to_ts, $iso_from, $iso_to, $rental_days, $this->getLocaleId(), null, $arr['id']);
				$extra_arr = pjAppController::getExtras($iso_from, $iso_to, $this->getLocaleId(), $this->option_arr,  $arr['id']);
	
				$tokens = pjAppController::getTokens($arr, $this->option_arr, PJ_SALT, $this->getLocaleId());
				
				$pjMultiLangModel = pjMultiLangModel::factory();
				$lang_message = $pjMultiLangModel
					->reset()
					->select('t1.*')
					->where('t1.model','pjOption')
					->where('t1.locale', $this->getLocaleId())
					->where('t1.field', 'o_email_confirmation_message')
					->limit(0, 1)
					->findAll()
					->getData();
				$lang_subject = $pjMultiLangModel
					->reset()
					->select('t1.*')
					->where('t1.model','pjOption')
					->where('t1.locale', $this->getLocaleId())
					->where('t1.field', 'o_email_confirmation_subject')
					->limit(0, 1)
					->findAll()
					->getData();
				$subject = NULL;
				$message = NULL;
				if (count($lang_message) === 1 && count($lang_subject) === 1)
				{
					$subject = str_replace($tokens['search'], $tokens['replace'], $lang_subject[0]['content']);
					$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
					
				}
				$code_arr = pjCodeModel::getCode($arr['space_id'], $arr['discount_code'], $iso_from, $iso_to);
				
				$this->set('arr', $arr);
				$this->set('be_arr', $be_arr);
				$this->set('country_arr', $country_arr);
				$this->set('from', $from);
				$this->set('to', $to);
				$this->set('rental_days', $rental_days);
				$this->set('space_arr', $space_arr);
				$this->set('extra_arr', $extra_arr);
				$this->set('subject', $subject);
				$this->set('message', $message);
				$this->set('code_arr', $code_arr);
	
				$this->appendJs('jquery-ui-timepicker-addon.js', PJ_THIRD_PARTY_PATH . 'datetimepicker/');
				$this->appendCss('jquery-ui-timepicker-addon.css', PJ_THIRD_PARTY_PATH . 'datetimepicker/');
				$this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.noty.packaged.min.js', PJ_THIRD_PARTY_PATH . 'noty/packaged/');
				$this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
				$this->appendJs('pjAdminBookings.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionPrint()
	{
		$this->checkLogin();
	
		$this->setLayout('pjActionPrint');
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$arr = pjBookingModel::factory()
				->find($_GET['id'])
				->getData();
			
			if(count($arr) <= 0)
			{
				pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminBookings&action=pjActionIndex&err=AR08");
			}
			$space_arr = pjSpaceModel::factory()
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpace' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
				->select('t1.*, t2.content as name')
				->find($arr['space_id'])
				->getData();
			$space = $space_arr['name'];
			
			$be_arr = pjBookingExtraModel::factory()
				->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
				->join('pjExtra', "t3.id=t1.extra_id", 'left')
				->select('t1.*, t2.content as name, t3.per, t3.price as unit_price')
				->where('t1.booking_id', $arr['id'])
				->findAll()
				->getData();
			$extra_arr = array();
			$extra_per = __('extra_per', true);
			foreach($be_arr as $k => $v)
			{
				$extra_arr[] = $v['name'] . ' (' . pjUtil::formatCurrencySign($v['unit_price'], $this->option_arr['o_currency']) . ' ' . $extra_per[$v['per']] .  ') x ' . $v['cnt'] . ' = ' . pjUtil::formatCurrencySign($v['price'], $this->option_arr['o_currency']);
			}
			$extras = join("<br/>", $extra_arr);
			
			$country = NULL;
			if (isset($arr['c_country']) && !empty($arr['c_country']))
			{
				if(isset($arr['c_country']) && (int) $arr['c_country'] > 0)
				{
					$country_arr = pjCountryModel::factory()
						->select('t1.id, t2.content AS country_title')
						->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->find($arr['c_country'])
						->getData();
					if (!empty($country_arr))
					{
						$country = $country_arr['country_title'];
					}
				}
			}
			
			$this->set('arr', $arr);
			$this->set('space', $space);
			$this->set('extras', $extras);
			$this->set('country', $country);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionGetSpaces()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$booking_id = null;
			if(isset($_POST['id']))
			{
				$booking_id = $_POST['id'];
			}
			
			$from_arr = pjUtil::convertDateTime($_POST['from'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
			$to_arr = pjUtil::convertDateTime($_POST['to'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
			
			$from_ts = $from_arr['ts'];
			$to_ts = $to_arr['ts'];
			
			$rental_days = pjAppController::getRentalDays($this->option_arr, $from_arr['iso_date'], $to_arr['iso_date'], date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));
			$space_arr = pjAppController::getSpaces($from_ts, $to_ts, $from_arr['iso_date'], $to_arr['iso_date'], $rental_days, $this->getLocaleId(), null, $booking_id);
			$extra_arr = pjAppController::getExtras($from_arr['iso_date'], $to_arr['iso_date'], $this->getLocaleId(), $this->option_arr, null);
			
			$this->set('rental_days', $rental_days);
			$this->set('space_arr', $space_arr);
			$this->set('extra_arr', $extra_arr);
		}
	}
	
	public function pjActionApplyCode()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$response = array();
			$code_arr = array();
			if((int) $_POST['space_id'] > 0 && !empty($_POST['from']) && !empty($_POST['to']))
			{
				$from_arr = pjUtil::convertDateTime($_POST['from'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
				$to_arr = pjUtil::convertDateTime($_POST['to'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
				
				$code_arr = pjCodeModel::getCode($_POST['space_id'], $_POST['discount_code'], $from_arr['iso_date'], $to_arr['iso_date']);
			}
			$response['code'] = 200;
			if(empty($code_arr))
			{
				$response['code'] = 100;
			}
			$response['arr'] = $code_arr;
			pjAppController::jsonResponse($response);
		}
	}
	
	public function pjActionDeleteBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			$pjBookingModel = pjBookingModel::factory();
			$arr = $pjBookingModel->find($_GET['id'])->getData();
			if ($pjBookingModel->reset()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
			{
				pjBookingPaymentModel::factory()->where('booking_id', $_GET['id'])->eraseAll();
				pjBookingExtraModel::factory()->where('booking_id', $_GET['id'])->eraseAll();
				$response['code'] = 200;
			} else {
				$response['code'] = 100;
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteBookingBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				$pjBookingModel = pjBookingModel::factory();
				
				$pjBookingModel->reset()->whereIn('id', $_POST['record'])->eraseAll();
				pjBookingPaymentModel::factory()->whereIn('booking_id', $_POST['record'])->eraseAll();
				pjBookingExtraModel::factory()->whereIn('booking_id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
	public function pjActionSaveBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjBookingModel::factory()->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
		}
		exit;
	}
	
	public function pjActionSend()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['form_send']))
			{
				$send_text = __('send_text', true);
				
				if (!isset($_POST['to']) || !isset($_POST['from']) || !isset($_POST['subject']) || !isset($_POST['message']) ||
						!pjValidation::pjActionEmail($_POST['to']) || !pjValidation::pjActionEmail($_POST['from']) ||
						!pjValidation::pjActionNotEmpty($_POST['subject']) ||
						!pjValidation::pjActionNotEmpty($_POST['message']))
				{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $send_text['101']));
				}
	
				$Email = new pjEmail();
				$Email->setContentType('text/html');
				if ($this->option_arr['o_send_email'] == 'smtp')
				{
					$Email
						->setTransport('smtp')
						->setSmtpHost($this->option_arr['o_smtp_host'])
						->setSmtpPort($this->option_arr['o_smtp_port'])
						->setSmtpUser($this->option_arr['o_smtp_user'])
						->setSmtpPass($this->option_arr['o_smtp_pass']);
				}
				$r = $Email
					->setTo($_POST['to'])
					->setFrom($_POST['from'])
					->setSubject($_POST['subject'])
					->send(pjUtil::textToHtml($_POST['message']));
	
				if ($r)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $send_text['200']));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => $send_text['100']));
			}
			exit;
		}
	}
	
	public function pjActionExport()
	{
		$this->checkLogin();
	
		if ($this->isAdmin()|| $this->isEditor())
		{
			if(isset($_POST['booking_export']))
			{
				$pjBookingModel = pjBookingModel::factory();
				
				if($_POST['period'] == 'next')
				{
					$column = '`from`';
					$direction = 'ASC';

					$where_str = pjUtil::getComingWhere($_POST['coming_period'], $this->option_arr['o_week_start']);
					if($where_str != '')
					{
						$pjBookingModel->where($where_str);
					}
				}else{
					$column = 'created';
					$direction = 'ASC';
					$where_str = pjUtil::getMadeWhere($_POST['made_period'], $this->option_arr['o_week_start']);
					if($where_str != '')
					{
						$pjBookingModel->where($where_str);
					}
				}

				$_arr= $pjBookingModel
					->select('t1.id, t1.space_id, t2.content AS space, t1.uuid, t1.from, t1.to, t1.status, t1.total,
							  t1.deposit, t1.c_name, t1.c_email, t1.c_phone, t1.c_notes, t1.c_address, t1.c_city, t1.c_country, t1.c_state, t1.c_zip, t1.ip, 
							  t1.payment_method, t1.created, t1.modified')
					->join('pjMultiLang', "t2.model='pjSpace' AND t2.foreign_id=t1.space_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->orderBy("$column $direction")
					->findAll()
					->getData();
				foreach($_arr as $v)
				{
					$arr[] = $v;
				}
	
				if($_POST['type'] == 'file')
				{
					$this->setLayout('pjActionEmpty');
						
					if($_POST['format'] == 'csv')
					{
						$csv = new pjCSV();
						$csv
							->setHeader(true)
							->setName("Export-".time().".csv")
							->process($arr)
							->download();
					}
					if($_POST['format'] == 'xml')
					{
						$xml = new pjXML();
						$xml
							->setEncoding('UTF-8')
							->setName("Export-".time().".xml")
							->process($arr)
							->download();
					}
					if($_POST['format'] == 'ical')
					{
						$ical = new pjICal();
						$ical
							->setName("Export-".time().".ics")
							->setProdID('Car Park Booking')
							->setSummary('c_name')
							->setDateFrom('from')
							->setDateTo('to')
							->setLocation('space')
							->setTimezone(pjUtil::getTimezoneName($this->option_arr['o_timezone']))
							->process($arr)
							->download();
					}
					exit;
				}else{
					$pjPasswordModel = pjPasswordModel::factory();
					$password = md5($_POST['password'].PJ_SALT);
					$arr = $pjPasswordModel
						->where("t1.password", $password)
						->limit(1)
						->findAll()
						->getData();
					if (count($arr) != 1)
					{
						$pjPasswordModel->setAttributes(array('password' => $password))->insert();
					}
					$this->set('password', $password);
				}
			}
				
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminBookings.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionExportFeed()
	{
		$this->setLayout('pjActionEmpty');
		$access = true;
		if(isset($_GET['p']))
		{
			$pjPasswordModel = pjPasswordModel::factory();
			$arr = $pjPasswordModel
			->where('t1.password', $_GET['p'])
			->limit(1)
			->findAll()
			->getData();
			if (count($arr) != 1)
			{
				$access = false;
			}
		}else{
			$access = false;
		}
		if($access == true)
		{
			$arr = $this->pjGetFeedData($_GET);
			if(!empty($arr))
			{
				if($_GET['format'] == 'xml')
				{
					$xml = new pjXML();
					echo $xml
						->setEncoding('UTF-8')
						->process($arr)
						->getData();
						
				}
				if($_GET['format'] == 'csv')
				{
					$csv = new pjCSV();
					echo $csv
						->setHeader(true)
						->process($arr)
						->getData();
						
				}
				if($_GET['format'] == 'ical')
				{
					$ical = new pjICal();
					echo $ical
						->setProdID('Car Park Booking')
						->setSummary('c_name')
						->setDateFrom('from')
						->setDateTo('to')
						->setLocation('space')
						->setTimezone(pjUtil::getTimezoneName($this->option_arr['o_timezone']))
						->process($arr)
						->getData();
						
				}
			}
		}else{
			__('lblNoAccessToFeed');
		}
		exit;
	}
	public function pjGetFeedData($get)
	{
		$arr = array();
		$status = true;
		$type = '';
		$period = '';
		if(isset($get['period']))
		{
			if(!ctype_digit($get['period']))
			{
				$status = false;
			}else{
				$period = $get['period'];
			}
		}else{
			$status = false;
		}
		if(isset($get['type']))
		{
			if(!ctype_digit($get['type']))
			{
				$status = false;
			}else{
				$type = $get['type'];
			}
		}else{
			$status = false;
		}
		if($status == true && $type != '' && $period != '')
		{
			$pjBookingModel = pjBookingModel::factory();
			
			if($type == '1')
			{
				$column = '`from`';
				$direction = 'ASC';
					
				$where_str = pjUtil::getComingWhere($period, $this->option_arr['o_week_start']);
				if($where_str != '')
				{
					$pjBookingModel->where($where_str);
				}
			}else{
				$column = 'created';
				$direction = 'DESC';
				$where_str = pjUtil::getMadeWhere($period, $this->option_arr['o_week_start']);
				if($where_str != '')
				{
					$pjBookingModel->where($where_str);
				}
			}
			$_arr = $pjBookingModel
				->select('t1.id, t1.space_id, t2.content AS space, t1.uuid, t1.from, t1.to, t1.status, t1.total,
							  t1.deposit, t1.c_name, t1.c_email, t1.c_phone, t1.c_notes, t1.c_address, t1.c_city, t1.c_country, t1.c_state, t1.c_zip, t1.ip, 
							  t1.payment_method, t1.created, t1.modified')
			  	->join('pjMultiLang', "t2.model='pjSapce' AND t2.foreign_id=t1.space_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			  	->orderBy("$column $direction")
			  	->findAll()
			  	->getData();
			foreach($_arr as $v)
			{
				$arr[] = $v;
			}
		}
		return $arr;
	}
}
?>