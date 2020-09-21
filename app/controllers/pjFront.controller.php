<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFront extends pjAppController
{	
	public $defaultCaptcha = 'pjCarPark_Captcha';
	
	public $defaultLocale = 'pjCarPark_LocaleId';
	
	public $defaultStore = 'pjCarPark_Store';
	
	public $defaultForm = 'pjCarPark_Form';
	
	public $defaultFieldsIndex = 'fields_index';
	
	public $defaultFields = 'front_fields';
	
	public function __construct()
	{
		$this->setLayout('pjActionFront');
		self::allowCORS();
	}

	public function isXHR()
	{
		return parent::isXHR() || isset($_SERVER['HTTP_ORIGIN']);
	}
	
	static protected function allowCORS()
	{
		$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
		header('P3P: CP="ALL DSP COR CUR ADM TAI OUR IND COM NAV INT"');
		header("Access-Control-Allow-Origin: $origin");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With");
	}
	
	private function _get($key)
	{
		if ($this->_is($key))
		{
			return $_SESSION[$this->defaultStore][$key];
		}
		return false;
	}
	
	private function _is($key)
	{
		return isset($_SESSION[$this->defaultStore]) && isset($_SESSION[$this->defaultStore][$key]);
	}
	
	private function _set($key, $value)
	{
		$_SESSION[$this->defaultStore][$key] = $value;
		return $this;
	}
	
	public function afterFilter()
	{		
		if (!isset($_GET['hide']) || (isset($_GET['hide']) && (int) $_GET['hide'] !== 1) &&
				in_array($_GET['action'], array('pjActionSearch', 'pjActionSpaces', 'pjActionExtras', 'pjActionCheckout', 'pjActionPreview', 'pjActionGetPaymentForm')))
		{
			$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file, t2.title')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
				->where('t2.file IS NOT NULL')
				->orderBy('t1.sort ASC')->findAll()->getData();
		
			$this->set('locale_arr', $locale_arr);
		}
	}
	
	public function beforeFilter()
	{
		$OptionModel = pjOptionModel::factory();
		$this->option_arr = $OptionModel->getPairs($this->getForeignId());
		$this->set('option_arr', $this->option_arr);
		$this->setTime();

		if (!in_array($_GET['action'], array('pjActionLoadCss')))
		{
			if(isset($_GET['locale']) && (int) $_GET['locale'] > 0 && $_GET['locale'] != $this->pjActionGetLocale())
			{
				$this->setLocaleId($_GET['locale']);
				$this->loadSetFields(true);
			}else{
				if($_GET['action'] == 'pjActionLoad')
				{
					$this->loadSetFields(true);
				}else{
					$this->loadSetFields();
				}
			}
		}
		if (!isset($_SESSION[$this->defaultLocale]))
		{
			$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
			if (count($locale_arr) === 1)
			{
				$this->setLocaleId($locale_arr[0]['id']);
				$this->loadSetFields(true);
			}
		}
	}
	
	public function beforeRender()
	{
		if (isset($_GET['iframe']))
		{
			$this->setLayout('pjActionIframe');
		}
	}
	
	public function pjActionLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_GET['locale_id']))
			{
				$this->pjActionSetLocale($_GET['locale_id']);
				
				$this->loadSetFields(true);
				
				$day_names = __('day_names', true);
				ksort($day_names, SORT_NUMERIC);
				
				$months = __('months', true);
				ksort($months, SORT_NUMERIC);
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Locale have been changed.', 'opts' => array(
					'day_names' => array_values($day_names),
					'month_names' => array_values($months)
				)));
			}
		}
		exit;
	}
	private function pjActionSetLocale($locale)
	{
		if ((int) $locale > 0)
		{
			$_SESSION[$this->defaultLocale] = (int) $locale;
		}
		return $this;
	}
	
	public function pjActionGetLocale()
	{
		return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : FALSE;
	}
	
	public function pjActionCaptcha()
	{
		$this->setAjax(true);
		$Captcha = new pjCaptcha('app/web/obj/Anorexia.ttf', $this->defaultCaptcha, 6);
		$Captcha->setImage('app/web/img/button.png')->init(isset($_GET['rand']) ? $_GET['rand'] : null);
	}

	public function pjActionCheckCaptcha()
	{
		$this->setAjax(true);
		if (!isset($_GET['captcha']) || empty($_GET['captcha']) || strtoupper($_GET['captcha']) != $_SESSION[$this->defaultCaptcha]){
			echo 'false';
		}else{
			echo 'true';
		}
		exit;
	}
	
	public function pjActionLoadCss()
	{
		$dm = new pjDependencyManager(PJ_THIRD_PARTY_PATH);
		$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
		
		$theme = isset($_GET['theme']) ? $_GET['theme'] : $this->option_arr['o_theme'];
		if((int) $theme > 0)
		{
			$theme = 'theme' . $theme;
		}
		$arr = array(
				array('file' => 'jquery-ui.custom.min.css', 'path' => $dm->getPath('pj_jquery_ui') . 'css/smoothness/'),
				array('file' => 'font-awesome.min.css', 'path' => $dm->getPath('font_awesome') . 'css/'),
				array('file' => 'jquery-ui-timepicker-addon.css', $dm->getPath('pj_datetimepicker')),
				array('file' => 'pjCarParkBooking.css', 'path' => PJ_CSS_PATH),
				array('file' => "$theme.css", 'path' => PJ_CSS_PATH)
		);
		header("Content-Type: text/css; charset=utf-8");
		foreach ($arr as $item)
		{
			ob_start();
			@readfile($item['path'] . $item['file']);
			$string = ob_get_contents();
			ob_end_clean();
				
			if ($string !== FALSE)
			{
				echo str_replace(
						array('../img/', '../fonts/glyphicons', '../fonts/fontawesome', 'images/', "pjWrapper"),
						array(
								PJ_INSTALL_URL . PJ_IMG_PATH,
								PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/fonts/glyphicons',
								PJ_INSTALL_URL . $dm->getPath('font_awesome') . 'fonts/fontawesome',
								PJ_INSTALL_URL . $dm->getPath('pj_jquery_ui') . 'css/smoothness/images/',
								"pjWrapperCarParkBooking_" . $theme
						),
						$string
				) . "\n";
			}
		}
		exit;
	}
	
	public function pjActionLoad()
	{
		ob_start();
		header("Content-Type: text/javascript; charset=utf-8");
	}
	
	
	public function pjActionSetDateTimes()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['from']))
			{
				$from_arr = pjUtil::convertDateTime($_POST['from'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
				$to_arr = pjUtil::convertDateTime($_POST['to'], $this->option_arr['o_date_format'], $this->option_arr['o_time_format']);
				
				$this->_set('from', $from_arr['iso_date_time']);
				$this->_set('to', $to_arr['iso_date_time']);
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200));
			}
		}
		exit;
	}
	
	public function pjActionHandleExtras()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_GET['extra_id']) && (int) $_GET['extra_id'] > 0)
			{
				if (!$this->_is('extras'))
				{
					$this->_set('extras', array());
				}
				if (!isset($_SESSION[$this->defaultStore]['extras'][$_GET['extra_id']]))
				{
					$_SESSION[$this->defaultStore]['extras'][$_GET['extra_id']] = 0;
				}
				if((int) $_GET['cnt'] > 0)
				{
					$_SESSION[$this->defaultStore]['extras'][$_GET['extra_id']] = (int) $_GET['cnt'];
				}else{
					unset($_SESSION[$this->defaultStore]['extras'][$_GET['extra_id']]);
				}
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200));
			}
		}
		exit;
	}
	
	public function pjActionAddPromo()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			if(isset($_GET['code']))
			{
				$from = $this->_get("from");
				$to = $this->_get("to");
				
				$from_ts = strtotime($from);
				$to_ts = strtotime($to);
				
				$iso_from = date('Y-m-d', $from_ts);
				$iso_to = date('Y-m-d', $to_ts);
				
				$code_arr = pjCodeModel::getCode($this->_get('space_id'), $_GET['code'], $iso_from, $iso_to);
				if (count($code_arr) > 0)
				{
					$extras = null;
					if($this->_is('extras'))
					{
						$extras = $this->_get('extras');
					}
					$rental_days = pjAppController::getRentalDays($this->option_arr, $iso_from, $iso_to, date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));
					$price = pjAppController::getPrice($this->option_arr, $this->_get('space_id'), $iso_from, $iso_to, $rental_days, $this->getLocaleId(), $_GET['code'], $extras);
					if (isset($price['discount_formatted']))
					{
						$price['discount_text'] = $price['discount_formatted'] . " " . __('front_discount_text', true);
					}
					$this->_set('code', $_GET['code']);
					$this->set('price',$price);
				}
			}
		}
	}
	
	public function pjActionRemovePromo()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			if ($this->_is('code'))
			{
				unset($_SESSION[$this->defaultStore]['code']);
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200));
		}
		exit;
	}
	
	
	public function pjActionSearch()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (!isset($_SESSION[$this->defaultStore]) || count($_SESSION[$this->defaultStore]) === 0)
			{
				$_SESSION[$this->defaultStore] = array();
			}
		}
	}
	
	public function pjActionSpaces()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
			{
				$sortby = 'price_asc';
				if(isset($_GET['sortby']) && in_array($_GET['sortby'], array('price_asc', 'price_desc', 'name_asc', 'name_desc')))
				{
					$sortby = $_GET['sortby'];
				}
				
				$from = $this->_get("from");
				$to = $this->_get("to");
				
				$from_ts = strtotime($from);
				$to_ts = strtotime($to);
				
				$iso_from = date('Y-m-d', $from_ts);
				$iso_to = date('Y-m-d', $to_ts);
				
				$rental_days = pjAppController::getRentalDays($this->option_arr, $iso_from, $iso_to, date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));
				$space_arr = pjAppController::getSpaces($from_ts, $to_ts, $iso_from, $iso_to, $rental_days, $this->getLocaleId(), $sortby, null);

				$cnt_extras = pjExtraModel::factory()->findCount()->getData();
				
				$this->set('rental_days', $rental_days);
				$this->set('space_arr', $space_arr);
				$this->set('cnt_extras', $cnt_extras);
				$this->set('status', 'OK');
			}else{
				$this->set('status', 'ERR');
			}
		}
	}
	
	public function pjActionExtras()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
			{
				if(isset($_GET['space_id']) && (int) $_GET['space_id'] > 0)
				{
					$this->_set('space_id', $_GET['space_id']);
				}
				if($this->_is('space_id'))
				{
					$space_id = $this->_get('space_id');
					
					$from = $this->_get("from");
					$to = $this->_get("to");
		
					$from_ts = strtotime($from);
					$to_ts = strtotime($to);
		
					$iso_from = date('Y-m-d', $from_ts);
					$iso_to = date('Y-m-d', $to_ts);
		
					$space = pjSpaceModel::factory()
						->join('pjMultiLang', "t2.model='pjSpace' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->select('t1.*, t2.content as name')
						->find($space_id)
						->getData();
					
					$rental_days = pjAppController::getRentalDays($this->option_arr, $iso_from, $iso_to, date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));
					$extra_arr = pjAppController::getExtras($iso_from, $iso_to, $this->getLocaleId(), $this->option_arr, null);
									
					$this->set('space', $space);
					$this->set('rental_days', $rental_days);
					$this->set('extra_arr', $extra_arr);
					$this->set('status', 'OK');
				}else{
					$this->set('status', 'ERR');
				}
			}else{
				$this->set('status', 'ERR');
			}
		}
	}
	
	public function pjActionCheckout()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
			{
				if(isset($_GET['space_id']) && (int) $_GET['space_id'] > 0)
				{
					$this->_set('space_id', $_GET['space_id']);
				}
				if($this->_is('space_id'))
				{
					if(isset($_POST['cp_checkout']))
					{
						$_SESSION[$this->defaultForm] = $_POST;
					
						pjAppController::jsonResponse(array('status' => 'OK', 'code' => 211));
					}else{
						$from = $this->_get("from");
						$to = $this->_get("to");
						
						$from_ts = strtotime($from);
						$to_ts = strtotime($to);
						
						$iso_from = date('Y-m-d', $from_ts);
						$iso_to = date('Y-m-d', $to_ts);
						
						$rental_days = pjAppController::getRentalDays($this->option_arr, $iso_from, $iso_to, date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));
						
						$space = pjSpaceModel::factory()
							->join('pjMultiLang', "t2.model='pjSpace' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->select('t1.*, t2.content as name')
							->find($this->_get('space_id'))
							->getData();
						
						$country_arr = pjCountryModel::factory()
							->select('t1.id, t2.content AS country_title')
							->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->orderBy('`country_title` ASC')
							->findAll()
							->getData();
						
						$extras = null;
						$code = null;
						if($this->_is('extras'))
						{
							$extras = $this->_get('extras');
						}
						if($this->_is('code'))
						{
							$code = $this->_get('code');
						}
						
						$price = pjAppController::getPrice($this->option_arr, $this->_get('space_id'), $iso_from, $iso_to, $rental_days, $this->getLocaleId(), $code, $extras);
						if (isset($price['discount_formatted']))
						{
							$price['discount_text'] = $price['discount_formatted'] . " " . __('front_discount_text', true);
						}
						
						$cnt_extras = pjExtraModel::factory()->findCount()->getData();
						
						$terms_conditions = pjMultiLangModel::factory()->select('t1.*')
							->where('t1.model','pjOption')
							->where('t1.locale', $this->getLocaleId())
							->where('t1.field', 'o_terms')
							->limit(0, 1)
							->findAll()->getData();
						$this->set('terms_conditions', $terms_conditions[0]['content']);
						
						$this->set('extras', $price['extra_compilation']['extra_name']);
						$this->set('extra_arr', $price['extra_compilation']['extra_arr']);
						$this->set('extra_price', $price['extra_compilation']['extra_price']);
						
						$this->set('space', $space);
						$this->set('rental_days', $rental_days);
						$this->set('cnt_extras', $cnt_extras);
						$this->set('price', $price);
						$this->set('country_arr', $country_arr);
					}
					$this->set('status', 'OK');
				}else{
					$this->set('status', 'ERR');
				}
			}else{
				$this->set('status', 'ERR');
			}
		}
	}
	
	public function pjActionPreview()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
			{
				if($this->_is('space_id'))
				{
					$from = $this->_get("from");
					$to = $this->_get("to");
						
					$from_ts = strtotime($from);
					$to_ts = strtotime($to);
						
					$iso_from = date('Y-m-d', $from_ts);
					$iso_to = date('Y-m-d', $to_ts);
						
					$rental_days = pjAppController::getRentalDays($this->option_arr, $iso_from, $iso_to, date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));
						
					$space = pjSpaceModel::factory()
						->join('pjMultiLang', "t2.model='pjSpace' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->select('t1.*, t2.content as name')
						->find($this->_get('space_id'))
						->getData();
					
					if(isset($_SESSION[$this->defaultForm]['c_country']) && (int) $_SESSION[$this->defaultForm]['c_country'] > 0)
					{	
						$country_arr = pjCountryModel::factory()
							->select('t1.id, t2.content AS country_title')
							->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->find($_SESSION[$this->defaultForm]['c_country'])
							->getData();
						$this->set('country_arr', $country_arr);
					}
						
					$extras = null;
					$code = null;
					if($this->_is('extras'))
					{
						$extras = $this->_get('extras');
					}
					if($this->_is('code'))
					{
						$code = $this->_get('code');
					}
						
					$price = pjAppController::getPrice($this->option_arr, $this->_get('space_id'), $iso_from, $iso_to, $rental_days, $this->getLocaleId(), $code, $extras);
					if (isset($price['discount_formatted']))
					{
						$price['discount_text'] = $price['discount_formatted'] . " " . __('front_discount_text', true);
					}
					
					$this->set('extras', $price['extra_compilation']['extra_name']);
					$this->set('extra_arr', $price['extra_compilation']['extra_arr']);
					$this->set('extra_price', $price['extra_compilation']['extra_price']);
					
					$this->set('space', $space);
					$this->set('rental_days', $rental_days);
					$this->set('price', $price);
					$this->set('status', 'OK');
				}else{
					$this->set('status', 'ERR');
				}
			}else{
				$this->set('status', 'ERR');
			}
		}
	}
	
	public function pjActionGetPrices()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			$from = $this->_get("from");
			$to = $this->_get("to");

			$from_ts = strtotime($from);
			$to_ts = strtotime($to);

			$iso_from = date('Y-m-d', $from_ts);
			$iso_to = date('Y-m-d', $to_ts);

			$rental_days = pjAppController::getRentalDays($this->option_arr, $iso_from, $iso_to, date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));

			$extras = null;
			$code = null;
			if($this->_is('extras'))
			{
				$extras = $this->_get('extras');
			}
			if($this->_is('code'))
			{
				$code = $this->_get('code');
			}

			$price = pjAppController::getPrice($this->option_arr, $this->_get('space_id'), $iso_from, $iso_to, $rental_days, $this->getLocaleId(), $code, $extras);
			if (isset($price['discount_formatted']))
			{
				$price['discount_text'] = $price['discount_formatted'] . " " . __('front_discount_text', true);
			}
			
			$this->set('extras', $price['extra_compilation']['extra_name']);
			$this->set('extra_arr', $price['extra_compilation']['extra_arr']);
			$this->set('extra_price', $price['extra_compilation']['extra_price']);
			$this->set('rental_days', $rental_days);
			$this->set('price', $price);
		}
	}
	
	public function pjActionSaveBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!isset($_POST['cp_preview']) || !isset($_SESSION[$this->defaultForm]) || empty($_SESSION[$this->defaultForm]) || !isset($_SESSION[$this->defaultStore]) || empty($_SESSION[$this->defaultStore]))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 109));
			}
	
			if ((int) $this->option_arr['o_bf_include_captcha'] === 3 && (!isset($_SESSION[$this->defaultForm]['captcha']) ||
					!pjCaptcha::validate($_SESSION[$this->defaultForm]['captcha'], $_SESSION[$this->defaultCaptcha]) ))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 110));
			}
			
			$STORE = @$_SESSION[$this->defaultStore];
			$FORM = @$_SESSION[$this->defaultForm];

			$from = $this->_get("from");
			$to = $this->_get("to");
			
			$from_ts = strtotime($from);
			$to_ts = strtotime($to);
			
			$iso_from = date('Y-m-d', $from_ts);
			$iso_to = date('Y-m-d', $to_ts);
			
			$data = array();
				
			$uuid = pjUtil::uuid();
			$data['uuid'] = $uuid;
			$data['space_id'] = $this->_get('space_id');
			$data['discount_code'] = $this->_get('code');
			$data['from'] = $iso_from . ' ' . date("H:i:s", $from_ts);
			$data['to'] = $iso_to . ' ' . date("H:i:s", $to_ts);
			$data['status'] = $this->option_arr['o_booking_status'];
			$data['ip'] = pjUtil::getClientIp();
				
			$payment = 'none';
			if(isset($FORM['payment_method']))
			{
				if (isset($FORM['payment_method'])){
					$payment = $FORM['payment_method'];
				}
			}
				
			$pjBookingModel = pjBookingModel::factory();
				
			$id = $pjBookingModel->setAttributes(array_merge($FORM, $data))->insert()->getInsertId();
			if ($id !== false && (int) $id > 0)
			{
				$rental_days = pjAppController::getRentalDays($this->option_arr, $iso_from, $iso_to, date('H', $from_ts), date('H', $to_ts), date('i', $from_ts), date('i', $to_ts));
				
				$extras = null;
				$code = null;
				if($this->_is('extras'))
				{
					$extras = $this->_get('extras');
				}
				if($this->_is('code'))
				{
					$code = $this->_get('code');
				}
				
				$price = pjAppController::getPrice($this->option_arr, $this->_get('space_id'), $iso_from, $iso_to, $rental_days, $this->getLocaleId(), $code, $extras);
				
				if(isset($price['extra_compilation']['extra_arr']) && !empty($price['extra_compilation']['extra_arr']))
				{
					$pjBookingExtraModel = pjBookingExtraModel::factory();
					$pjBookingExtraModel->begin();
					foreach($price['extra_compilation']['extra_arr'] as $k => $v)
					{
						$pjBookingExtraModel
							->reset()
							->set('booking_id', $id)
							->set('extra_id', $v['id'])
							->set('price', $v['price'])
							->set('cnt', $v['qty'])
							->insert();
					}
					$pjBookingExtraModel->commit();
				}

				$arr = $pjBookingModel
					->reset()
					->find($id)
					->getData();
				
				$pdata = array();
				$pdata['booking_id'] = $id;
				$pdata['payment_method'] = $payment;
				$pdata['payment_type'] = 'online';
				$pdata['amount'] = $arr['deposit'];
				$pdata['status'] = 'notpaid';
				pjBookingPaymentModel::factory()->setAttributes($pdata)->insert();

				pjFront::pjActionConfirmSend($this->option_arr, $arr, PJ_SALT, 'confirm');

				unset($_SESSION[$this->defaultStore]);
				unset($_SESSION[$this->defaultForm]);

				$json = array('code' => 200, 'text' => '', 'booking_id' => $id, 'payment' => $payment);
				pjAppController::jsonResponse($json);
			}else {
				pjAppController::jsonResponse(array('code' => 'ERR', 'code' => 119));
			}
		}
	}
	
	public function pjActionCancel()
	{
		$this->setLayout('pjActionCancel');
	
		$pjBookingModel = pjBookingModel::factory();
	
		if (isset($_POST['booking_cancel']))
		{
			$booking_arr = $pjBookingModel->find($_POST['id'])->getData();
			if (count($booking_arr) > 0)
			{
				$sql = "UPDATE `".$pjBookingModel->getTable()."` SET status = 'cancelled' WHERE SHA1(CONCAT(`id`, `created`, '".PJ_SALT."')) = '" . $_POST['hash'] . "'";
	
				$pjBookingModel->reset()->execute($sql);
	
				$arr = $pjBookingModel
					->reset()
					->find($_POST['id'])
					->getData();
				pjFront::pjActionConfirmSend($this->option_arr, $arr, PJ_SALT, 'cancel');
	
				pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjFront&action=pjActionCancel&err=200');
			}
		}else{
			if (isset($_GET['hash']) && isset($_GET['id']))
			{
				$arr = $pjBookingModel
					->reset()
					->select('t1.*, t2.content as space_name, t3.content as country_title')
					->join('pjMultiLang', "t2.model='pjSpace' AND t2.foreign_id=t1.space_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->join('pjMultiLang', "t3.model='pjCountry' AND t3.foreign_id=t1.c_country AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
					->find($_GET['id'])
					->getData();
				if (count($arr) == 0)
				{
					$this->set('status', 2);
				}else{
					if ($arr['status'] == 'cancelled')
					{
						$this->set('status', 4);
					}else{
						$hash = sha1($arr['id'] . $arr['created'] . PJ_SALT);
						if ($_GET['hash'] != $hash)
						{
							$this->set('status', 3);
						}else{
								
							$this->set('arr', $arr);
						}
					}
				}
			}else if (!isset($_GET['err'])) {
				$this->set('status', 1);
			}
		}
	}
	
	public function pjActionGetPaymentForm()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$arr = pjBookingModel::factory()
				->select('t1.*, t2.content as space_name')
				->join('pjMultiLang', "t2.model='pjSpace' AND t2.foreign_id=t1.space_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->find($_GET['booking_id'])
				->getData();
			
			if (!empty($arr))
			{
				switch ($arr['payment_method'])
				{
					case 'paypal':
						$this->set('params', array(
							'name' => 'cpPaypal',
							'id' => 'cpPaypal',
							'business' => $this->option_arr['o_paypal_address'],
							'item_name' => pjSanitize::html($arr['space_name']),
							'custom' => $arr['id'],
							'amount' => $arr['deposit'],
							'currency_code' => $this->option_arr['o_currency'],
							'return' => $this->option_arr['o_thank_you_page'],
							'notify_url' => PJ_INSTALL_URL . 'index.php?controller=pjFront&action=pjActionConfirmPaypal',
							'target' => '_self',
							'charset' => 'utf-8'
						));
						break;
					case 'authorize':
						$this->set('params', array(
							'name' => 'cpAuthorize',
							'id' => 'cpAuthorize',
							'target' => '_self',
							'timezone' => $this->option_arr['o_authorize_timezone'],
							'transkey' => $this->option_arr['o_authorize_transkey'],
							'x_login' => $this->option_arr['o_authorize_merchant_id'],
							'x_description' => pjSanitize::html($arr['space_name']),
							'x_amount' => $arr['deposit'],
							'x_invoice_num' => $arr['id'],
							'x_receipt_link_url' => $this->option_arr['o_thank_you_page'],
							'x_relay_url' => PJ_INSTALL_URL . 'index.php?controller=pjFront&action=pjActionConfirmAuthorize'
						));
						break;
				}
			}
			$this->set('arr', $arr);
			$this->set('get', $_GET);
		}
	}
	
	public function pjActionConfirmAuthorize()
	{
		$this->setAjax(true);
		
		if (pjObject::getPlugin('pjAuthorize') === NULL)
		{
			$this->log('Authorize.NET plugin not installed');
			exit;
		}
		$pjBookingModel = pjBookingModel::factory();
		
		$booking_arr = $pjBookingModel
			->find($_POST['x_invoice_num'])
			->getData();							
		if (count($booking_arr) == 0)
		{
			$this->log('No such booking');
			pjUtil::redirect($this->option_arr['o_thankyou_page']);
		}					
		
		if (count($booking_arr) > 0)
		{
			$params = array(
				'transkey' => $this->option_arr['o_authorize_transkey'],
				'x_login' => $this->option_arr['o_authorize_merchant_id'],
				'md5_setting' => $this->option_arr['o_authorize_md5_hash'],
				'key' => md5($this->option_arr['private_key'] . PJ_SALT)
			);
			
			$response = $this->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
			if ($response !== FALSE && $response['status'] === 'OK')
			{
				$pjBookingModel->reset()
					->setAttributes(array('id' => $response['transaction_id']))
					->modify(array('status' => $this->option_arr['o_payment_status'], 'processed_on' => ':NOW()'));

				pjBookingPaymentModel::factory()->setAttributes(array('booking_id' => $response['transaction_id'], 'payment_type' => 'online'))
												->modify(array('status' => 'paid'));
					
				pjFront::pjActionConfirmSend($this->option_arr, $booking_arr, PJ_SALT, 'payment');
				
			} elseif (!$response) {
				$this->log('Authorization failed');
			} else {
				$this->log('Booking not confirmed. ' . $response['response_reason_text']);
			}
			pjUtil::redirect($this->option_arr['o_thankyou_page']);
		}
	}

	public function pjActionConfirmPaypal()
	{
		$this->setAjax(true);
		
		if (pjObject::getPlugin('pjPaypal') === NULL)
		{
			$this->log('Paypal plugin not installed');
			exit;
		}
		$pjBookingModel = pjBookingModel::factory();
		
		$booking_arr = $pjBookingModel
			->find($_POST['custom'])
			->getData();
		if (count($booking_arr) == 0)
		{
			$this->log('No such booking');
			pjUtil::redirect($this->option_arr['o_thankyou_page']);
		}		

		$params = array(
			'txn_id' => @$booking_arr['txn_id'],
			'paypal_address' => $this->option_arr['o_paypal_address'],
			'deposit' => @$booking_arr['deposit'],
			'currency' => $this->option_arr['o_currency'],
			'key' => md5($this->option_arr['private_key'] . PJ_SALT)
		);
		$response = $this->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
		
		if ($response !== FALSE && $response['status'] === 'OK')
		{
			$this->log('Booking confirmed');
			$pjBookingModel->reset()->setAttributes(array('id' => $booking_arr['id']))->modify(array(
				'status' => $this->option_arr['o_payment_status'],
				'txn_id' => $response['transaction_id'],
				'processed_on' => ':NOW()'
			));
			pjBookingPaymentModel::factory()->setAttributes(array('booking_id' => $booking_arr['id'], 'payment_type' => 'online'))
											->modify(array('status' => 'paid'));
			
			pjFront::pjActionConfirmSend($this->option_arr, $booking_arr, PJ_SALT, 'payment');
			
		} elseif (!$response) {
			$this->log('Authorization failed');
		} else {
			$this->log('Booking not confirmed');
		}
		pjUtil::redirect($this->option_arr['o_thankyou_page']);
	}	

	public function pjActionConfirmSend($option_arr, $booking_arr, $salt, $opt)
	{
		$Email = new pjEmail();
		if ($option_arr['o_send_email'] == 'smtp')
		{
			$Email
				->setTransport('smtp')
				->setSmtpHost($option_arr['o_smtp_host'])
				->setSmtpPort($option_arr['o_smtp_port'])
				->setSmtpUser($option_arr['o_smtp_user'])
				->setSmtpPass($option_arr['o_smtp_pass'])
			;
		}
		$Email->setContentType('text/html');
		
		$admin_email = $this->getAdminEmail();
		$admin_phone = $this->getAdminPhone();
		$from_email = $admin_email;
		
		$locale_id = $this->getLocaleId();
		
		$tokens = pjAppController::getTokens($booking_arr, $option_arr, PJ_SALT, $locale_id);
		
		$pjMultiLangModel = pjMultiLangModel::factory();
		
		if ($option_arr['o_email_payment'] == 1 && $opt == 'payment')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_payment_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_payment_subject')
				->limit(0, 1)
				->findAll()->getData();
		
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
		
				$Email
					->setTo($booking_arr['c_email'])
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send(pjUtil::textToHtml($message));
			}
		}
		if ($option_arr['o_admin_email_payment'] == 1 && $opt == 'payment')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_payment_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_payment_subject')
				->limit(0, 1)
				->findAll()->getData();
		
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
		
				$Email
				->setTo($admin_email)
				->setFrom($from_email)
				->setSubject($lang_subject[0]['content'])
				->send(pjUtil::textToHtml($message));
			}
		}
		if(!empty($admin_phone) && $opt == 'payment')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_sms_payment_message')
				->limit(0, 1)
				->findAll()->getData();
			if (count($lang_message) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				if($message != '')
				{
					$params = array(
							'text' => $message,
							'type' => 'unicode',
							'key' => md5($option_arr['private_key'] . PJ_SALT)
					);
					$params['number'] = $admin_phone;
					$this->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => $params), array('return'));
				}
			}
		}
		
		if ($option_arr['o_email_confirmation'] == 1 && $opt == 'confirm')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_confirmation_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_confirmation_subject')
				->limit(0, 1)
				->findAll()->getData();
				
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
					
				$Email
					->setTo($booking_arr['c_email'])
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send(pjUtil::textToHtml($message));
			}
		}
		if ($option_arr['o_admin_email_confirmation'] == 1 && $opt == 'confirm')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_confirmation_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_confirmation_subject')
				->limit(0, 1)
				->findAll()->getData();
				
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				$Email
					->setTo($admin_email)
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send(pjUtil::textToHtml($message));
			}
		}
		if(!empty($booking_arr['c_phone']) && $opt == 'confirm')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_sms_confirmation_message')
				->limit(0, 1)
				->findAll()->getData();
			if (count($lang_message) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				if($message != '')
				{
					$params = array(
							'text' => $message,
							'type' => 'unicode',
							'key' => md5($option_arr['private_key'] . PJ_SALT)
					);
					$params['number'] = $booking_arr['c_phone'];
					$this->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => $params), array('return'));
				}
			}
		}
		if(!empty($admin_phone) && $opt == 'confirm')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_sms_confirmation_message')
				->limit(0, 1)
				->findAll()->getData();
			if (count($lang_message) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				if($message != '')
				{
					$params = array(
							'text' => $message,
							'type' => 'unicode',
							'key' => md5($option_arr['private_key'] . PJ_SALT)
					);
					$params['number'] = $admin_phone;
					$this->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => $params), array('return'));
				}
			}
		}
		
		if ($option_arr['o_email_cancel'] == 1 && $opt == 'cancel')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_cancel_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_cancel_subject')
				->limit(0, 1)
				->findAll()->getData();
				
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
		
				$Email
					->setTo($booking_arr['c_email'])
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send(pjUtil::textToHtml($message));
			}
		}
		if ($option_arr['o_admin_email_cancel'] == 1 && $opt == 'cancel')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_cancel_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_cancel_subject')
				->limit(0, 1)
				->findAll()->getData();
				
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
		
				$Email
					->setTo($admin_email)
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send(pjUtil::textToHtml($message));
			}
		}
	}
}
?>