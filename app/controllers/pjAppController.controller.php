<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAppController extends pjController
{
	public $models = array();
	
	public $defaultLocale = 'admin_locale_id';
  
	public $defaultFields = 'admin_fields';
	
	public $defaultFieldsIndex = 'admin_index';
  
	protected function loadSetFields($force=FALSE, $locale_id=NULL, $fields=NULL)
	{
		if (is_null($locale_id))
		{
			$locale_id = $this->getLocaleId();
		}
		
		if (is_null($fields))
		{
			$fields = $this->defaultFields;
		}
		
		$registry = pjRegistry::getInstance();
		if ($force
				|| !isset($_SESSION[$this->defaultFieldsIndex])
				|| $_SESSION[$this->defaultFieldsIndex] != $this->option_arr['o_fields_index']
				|| !isset($_SESSION[$fields])
				|| empty($_SESSION[$fields]))
		{
			pjAppController::setFields($locale_id);
	
			# Update session
			if ($registry->is('fields'))
			{
				$_SESSION[$fields] = $registry->get('fields');
			}
			$_SESSION[$this->defaultFieldsIndex] = $this->option_arr['o_fields_index'];
		}
	
		if (isset($_SESSION[$fields]) && !empty($_SESSION[$fields]))
		{
			# Load fields from session
			$registry->set('fields', $_SESSION[$fields]);
		}
		
		return TRUE;
	}
	
	public function isCountryReady()
    {
    	return $this->isAdmin();
    }
    
	public function isOneAdminReady()
    {
    	return $this->isAdmin();
    }
    
    public function isInvoiceReady()
    {
    	return $this->isAdmin() || $this->isEditor();
    }
	
	public static function setTimezone($timezone="UTC")
    {
    	if (in_array(version_compare(phpversion(), '5.1.0'), array(0,1)))
		{
			date_default_timezone_set($timezone);
		} else {
			$safe_mode = ini_get('safe_mode');
			if ($safe_mode)
			{
				putenv("TZ=".$timezone);
			}
		}
    }

	public static function setMySQLServerTime($offset="-0:00")
    {
		pjAppModel::factory()->prepare("SET SESSION time_zone = :offset;")->exec(compact('offset'));
    }
    
	public function setTime()
	{
		if (isset($this->option_arr['o_timezone']))
		{
			$offset = $this->option_arr['o_timezone'] / 3600;
			if ($offset > 0)
			{
				$offset = "-".$offset;
			} elseif ($offset < 0) {
				$offset = "+".abs($offset);
			} elseif ($offset === 0) {
				$offset = "+0";
			}
	
			pjAppController::setTimezone('Etc/GMT' . $offset);
			if (strpos($offset, '-') !== false)
			{
				$offset = str_replace('-', '+', $offset);
			} elseif (strpos($offset, '+') !== false) {
				$offset = str_replace('+', '-', $offset);
			}
			pjAppController::setMySQLServerTime($offset . ":00");
		}
	}
    
    public function beforeFilter()
    {
    	$this->appendJs('jquery.min.js', PJ_THIRD_PARTY_PATH . 'jquery/');
		$dm = new pjDependencyManager(PJ_THIRD_PARTY_PATH);
		$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
		$this->appendJs('jquery-migrate.min.js', $dm->getPath('jquery_migrate'), FALSE, FALSE);
		$this->appendJs('pjAdminCore.js');
		$this->appendCss('reset.css');
		 
		$this->appendJs('js/jquery-ui.custom.min.js', PJ_THIRD_PARTY_PATH . 'jquery_ui/');
		$this->appendCss('css/smoothness/jquery-ui.min.css', PJ_THIRD_PARTY_PATH . 'jquery_ui/');
				
		$this->appendCss('pj-all.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
		$this->appendCss('admin.css');
		
    	if ($_GET['controller'] != 'pjInstaller')
		{
			$this->models['Option'] = pjOptionModel::factory();
			$this->option_arr = $this->models['Option']->getPairs($this->getForeignId());
			$this->set('option_arr', $this->option_arr);
			$this->setTime();
			
			if (!isset($_SESSION[$this->defaultLocale]))
			{
				$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
				if (count($locale_arr) === 1)
				{
					$this->setLocaleId($locale_arr[0]['id']);
				}
			}
			$this->loadSetFields();
		}
    }
    
	public function isEditor()
    {
    	return $this->getRoleId() == 2;
    }
    
    public function getForeignId()
    {
    	return 1;
    }
    
    public static function setFields($locale)
    {
    	if(isset($_SESSION['lang_show_id']) && (int) $_SESSION['lang_show_id'] == 1)
		{
			$fields = pjMultiLangModel::factory()
				->select('CONCAT(t1.content, CONCAT(":", t2.id, ":")) AS content, t2.key')
				->join('pjField', "t2.id=t1.foreign_id", 'inner')
				->where('t1.locale', $locale)
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->findAll()
				->getDataPair('key', 'content');
		}else{
			$fields = pjMultiLangModel::factory()
				->select('t1.content, t2.key')
				->join('pjField', "t2.id=t1.foreign_id", 'inner')
				->where('t1.locale', $locale)
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->findAll()
				->getDataPair('key', 'content');
		}
		$registry = pjRegistry::getInstance();
		$tmp = array();
		if ($registry->is('fields'))
		{
			$tmp = $registry->get('fields');
		}
		$arrays = array();
		foreach ($fields as $key => $value)
		{
			if (strpos($key, '_ARRAY_') !== false)
			{
				list($prefix, $suffix) = explode("_ARRAY_", $key);
				if (!isset($arrays[$prefix]))
				{
					$arrays[$prefix] = array();
				}
				$arrays[$prefix][$suffix] = $value;
			}
		}
		require PJ_CONFIG_PATH . 'settings.inc.php';
		$fields = array_merge($tmp, $fields, $settings, $arrays);
		$registry->set('fields', $fields);
    }

    public static function jsonDecode($str)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->decode($str);
	}
	
	public static function jsonEncode($arr)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->encode($arr);
	}
	
	public static function jsonResponse($arr)
	{
		header("Content-Type: application/json; charset=utf-8");
		echo pjAppController::jsonEncode($arr);
		exit;
	}

	public function getLocaleId()
	{
		return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : false;
	}
	
	public function setLocaleId($locale_id)
	{
		$_SESSION[$this->defaultLocale] = (int) $locale_id;
	}
	
	public function pjActionCheckInstall()
	{
		$this->setLayout('pjActionEmpty');
		
		$result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());
		$folders = array(
							'app/web/upload'
						);
		foreach ($folders as $dir)
		{
			if (!is_writable($dir))
			{
				$result['status'] = 'ERR';
				$result['code'] = 101;
				$result['text'] = 'Permission requirement';
				$result['info'][] = sprintf('Folder \'<span class="bold">%1$s</span>\' is not writable. You need to set write permissions (chmod 777) to directory located at \'<span class="bold">%1$s</span>\'', $dir);
			}
		}
		
		return $result;
	}
	
	public function friendlyURL($str, $divider='-')
	{
		$str = mb_strtolower($str, mb_detect_encoding($str));
		$str = trim($str);
		$str = preg_replace('/[_|\s]+/', $divider, $str);
		$str = preg_replace('/\x{00C5}/u', 'AA', $str);
		$str = preg_replace('/\x{00C6}/u', 'AE', $str);
		$str = preg_replace('/\x{00D8}/u', 'OE', $str);
		$str = preg_replace('/\x{00E5}/u', 'aa', $str);
		$str = preg_replace('/\x{00E6}/u', 'ae', $str);
		$str = preg_replace('/\x{00F8}/u', 'oe', $str);
		$str = preg_replace('/[^a-z\x{0400}-\x{04FF}0-9-]+/u', '', $str);
		$str = preg_replace('/[-]+/', $divider, $str);
		$str = preg_replace('/^-+|-+$/', '', $str);
		return $str;
	}

	static public function getAdminEmail()
	{
		$arr = pjUserModel::factory()
			->findAll()
			->orderBy("t1.id ASC")
			->limit(1)
			->getData();
		return !empty($arr) ? $arr[0]['email'] : null;
	}
	
	static public function getAdminPhone()
	{
		$arr = pjUserModel::factory()
			->findAll()
			->orderBy("t1.id ASC")
			->limit(1)
			->getData();
		return !empty($arr) ? (!empty($arr[0]['phone']) ? $arr[0]['phone'] : null) : null;
	}
	
	static public function  getRentalDays($option_arr, $date_from, $date_to, $hour_from=null, $hour_to=null, $minutes_from=null, $minutes_to=null)
	{
		switch ($option_arr['o_price_based_on'])
		{
			case 'Day':
				/* from 24.12.2012 to 25.12.2012 = 2 Days */
				$rental_days = 1 + ((strtotime($date_to) - strtotime($date_from)) / 86400);
				break;
			case 'Night':
				/* from 24.12.2012 to 25.12.2012 = 1 Day */
				$rental_days = (strtotime($date_to) - strtotime($date_from)) / 86400;
				break;
			case '24Hour':
			default:
				/* from 9 AM at 24.12.2012 to 9 AM at 25.12.2012 = 1 Day
				   from 9 AM at 24.12.2012 to 10 AM at 25.12.2012 = 2 Days */
				$rental_days = ceil((strtotime($date_to . " " . $hour_to . ":" . $minutes_to) - strtotime($date_from. " " . $hour_from . ":" . $minutes_from)) / 86400);
				break;
		}
		return $rental_days > 0 ? $rental_days : 1;
	}
	
	static public function getPrice($option_arr, $space_id, $date_from, $date_to, $rental_days, $locale_id, $code=null, $extras=null)
	{
		$extra_compilation = pjExtraModel::getPrice($rental_days, $locale_id, $extras);
		$extra_price = $extra_compilation['extra_price'];
		
		$code_arr = array();
		if (!is_null($code))
		{
			$code_arr = pjCodeModel::getCode($space_id, $code, $date_from, $date_to);
		}
		$price_arr =  pjPriceModel::getPrice($space_id, $date_from, $date_to, $rental_days);
		$price = $price_arr['price'] + $extra_price;
		
		if (count($code_arr) > 0)
		{
			switch ($code_arr['type'])
			{
				case 'percent':
					$price_after_discount = $price - (($price * $code_arr['discount']) / 100);
					$price_after_discount = $price_after_discount > 0 ? $price_after_discount : 0;
					break;
				case 'amount':
					$price_after_discount = $price - $code_arr['discount'] > 0 ? $price - $code_arr['discount'] : 0;
					break;
			}
			$tax = ($price_after_discount * $option_arr['o_tax_payment']) / 100;
			$total = $price_after_discount + $tax;
			$deposit = ($total * ((float) $option_arr['o_deposit_payment'])) / 100;
				
			$p = array(
					'type' => $code_arr['type'],
					'discount' => $code_arr['discount'],
					'discount_formatted' => $code_arr['type'] == 'amount' ? pjUtil::formatCurrencySign(number_format($code_arr['discount'], 2), $option_arr['o_currency']) : (float) $code_arr['discount'] .'%',
					'rental_price' => round($price_arr['price'], 2),
					'rental_price_formatted' => pjUtil::formatCurrencySign(number_format($price_arr['price'], 2), $option_arr['o_currency']),
					'price_before_discount' => round($price, 2),
					'price_after_discount' => round($price_after_discount, 2),
					'price_before_formatted' => pjUtil::formatCurrencySign(number_format($price, 2), $option_arr['o_currency']),
					'price_after_formatted' => pjUtil::formatCurrencySign(number_format($price_after_discount, 2), $option_arr['o_currency']),
					'total' => round($total, 2),
					'total_formatted' => pjUtil::formatCurrencySign(number_format($total, 2), $option_arr['o_currency']),
					'deposit' => round($deposit, 2),
					'deposit_formatted' => pjUtil::formatCurrencySign(number_format($deposit, 2), $option_arr['o_currency']),
					'tax' => round($tax, 2),
					'tax_formatted' => pjUtil::formatCurrencySign(number_format($tax, 2), $option_arr['o_currency']),
					'extra_compilation' => $extra_compilation
			);
		}else{
			$tax = ($price * $option_arr['o_tax_payment']) / 100;
			$total = $price + $tax;
			$deposit = ($total * ((float) $option_arr['o_deposit_payment'])) / 100;
			
			$p = array(
					'rental_price' => round($price_arr['price'], 2),
					'rental_price_formatted' => pjUtil::formatCurrencySign(number_format($price_arr['price'], 2), $option_arr['o_currency']),
					'price' => round($price, 2),
					'price_formatted' => pjUtil::formatCurrencySign(number_format($price, 2), $option_arr['o_currency']),
					'total' => round($total, 2),
					'total_formatted' => pjUtil::formatCurrencySign(number_format($total, 2), $option_arr['o_currency']),
					'deposit' => round($deposit, 2),
					'deposit_formatted' => pjUtil::formatCurrencySign(number_format($deposit, 2), $option_arr['o_currency']),
					'tax' => round($tax, 2),
					'tax_formatted' => pjUtil::formatCurrencySign(number_format($tax, 2), $option_arr['o_currency']),
					'extra_compilation' => $extra_compilation
			);
		}
		return $p;
	}
	
	static public function getSpaces($from_ts, $to_ts, $date_from, $date_to, $rental_days, $locale_id, $sort = null, $booking_id=null)
	{
		$dates = array();
		
		$dt_from = date('Y-m-d H:i:s', $from_ts);
		$dt_to = date('Y-m-d H:i:s', $to_ts);
		
		for ($i = $from_ts; $i <= $to_ts; $i = $i + 86400)
		{
			$dates[date("Y-m-d", $i)] = array();
		}
		
		$pjSpaceModel = pjSpaceModel::factory();
		$pjSpaceDateModel = pjSpaceDateModel::factory();
		$pjBookingModel = pjBookingModel::factory();
		
		$col_name = 'name';
		$direction = 'asc';
		if (!empty($sort) && strpos($sort, "_") !== false)
		{
			list($col_name, $direction) = explode("_", $sort);
		}
		
		$space_arr = $pjSpaceModel
			->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpace' AND t2.locale = '".$locale_id."' AND t2.field = 'name'", 'left')
			->join('pjMultiLang', "t3.foreign_id = t1.id AND t3.model = 'pjSpace' AND t3.locale = '".$locale_id."' AND t3.field = 'description'", 'left')
			->select('t1.*, t2.content as name, t3.content as description')
			->where('t1.status', 'T')
			->groupBy("t1.id")
			->findAll()
			->getData();
		$space_date_arr = $pjSpaceDateModel
			->findAll()
			->getData();
		foreach ($space_arr as $k => $space)
		{
			$space_arr[$k]['dates'] = $dates;
			foreach ($dates as $date => $val)
			{
				$ts = strtotime($date);
				$pjBookingModel->reset();
				if($booking_id != null)
				{
					$pjBookingModel->where("t1.id <>", $booking_id);
				}
				$bookings = $pjBookingModel
					->where('t1.space_id', $space['id'])
					->where('t1.status <>', 'cancelled')
					->where("((`from` BETWEEN '$dt_from' AND '$dt_to') OR (`to` BETWEEN '$dt_from' AND '$dt_to'))")
					->findCount()
					->getData();
				$space_arr[$k]['dates'][$date]['bookings'] = $bookings;
				foreach ($space_date_arr as $space_date)
				{
					if ($space_date['space_id'] == $space['id'] && $ts >= strtotime($space_date['date_from']) && $ts <= strtotime($space_date['date_to']))
					{
						$space_arr[$k]['dates'][$date]['spaces'] = $space_date['spaces'];
						break;
					}
				}
			}
		}
		foreach ($space_arr as $k => $space)
		{
			$space_arr[$k]['is_available'] = 1;
			foreach ($space['dates'] as $date => $item)
			{
				if (!isset($item['spaces']) || (int) $item['bookings'] >= (int) $item['spaces'])
				{
					$space_arr[$k]['is_available'] = 0;
					break;
				}
			}
		}
		$id = $name = $description = $price = $save = array();
		foreach ($space_arr as $k => $space)
		{
			$id[$k] = $space['id'];
			$name[$k] = $space['name'];
			$description[$k] = $space['description'];
			$p_arr = pjPriceModel::getPrice($space['id'], $date_from, $date_to, $rental_days);
			$space_arr[$k]['price'] = $p_arr['price'];
			$space_arr[$k]['save'] = $p_arr['save'];
			$price[$k] = $space_arr[$k]['price'];
			$save[$k] = $space_arr[$k]['save'];
		}
		
		switch ($col_name)
		{
			case 'name':
				switch ($direction)
				{
					case 'asc':
						array_multisort($name, SORT_ASC, SORT_STRING, $price, SORT_ASC, SORT_NUMERIC, $save, SORT_DESC, SORT_NUMERIC, $id, SORT_ASC, SORT_NUMERIC, $description, $space_arr);
						break;
					case 'desc':
						array_multisort($name, SORT_DESC, SORT_STRING, $price, SORT_ASC, SORT_NUMERIC, $save, SORT_DESC, SORT_NUMERIC, $id, SORT_ASC, SORT_NUMERIC, $description, $space_arr);
						break;
				}
				break;
			case 'price':
				switch ($direction)
				{
					case 'asc':
						array_multisort($price, SORT_ASC, SORT_NUMERIC, $save, SORT_DESC, SORT_NUMERIC, $name, SORT_ASC, SORT_STRING, $id, SORT_ASC, SORT_NUMERIC, $description, $space_arr);
						break;
					case 'desc':
						array_multisort($price, SORT_DESC, SORT_NUMERIC, $save, SORT_DESC, SORT_NUMERIC, $name, SORT_ASC, SORT_STRING, $id, SORT_ASC, SORT_NUMERIC, $description, $space_arr);
						break;
				}
				break;
		}
		
		return $space_arr;
	}
	
	static public function getExtras($iso_from, $iso_to, $locale_id, $option_arr, $booking_id=null)
	{
		$where = "";
		if($booking_id != null)
		{
			$where = " `id` <> '$booking_id' AND";
		}
		$extra_arr = pjExtraModel::factory()
			->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '".$locale_id."' AND t2.field = 'name'", 'left')
			->join('pjMultiLang', "t3.foreign_id = t1.id AND t3.model = 'pjExtra' AND t3.locale = '".$locale_id."' AND t3.field = 'description'", 'left')
			->select(sprintf("t1.*, t2.content AS name, t3.content as description, 
									  (SELECT SUM(`cnt`)
									   FROM `%1\$s`
									   WHERE `extra_id` = `t1`.`id`
									   AND `booking_id` IN ( SELECT `id` FROM `%2\$s` WHERE%5\$s `status` = 'confirmed' 
										   AND ( ('%3\$s' BETWEEN DATE(`from`) AND DATE(`to`)) OR ('%4\$s' BETWEEN DATE(`from`) AND DATE(`to`)) OR (DATE(`from`) BETWEEN '%3\%s' AND '%4\$s') OR (DATE(`to`) BETWEEN '%3\%s' AND '%4\$s') ))
									  ) AS bookings", pjBookingExtraModel::factory()->getTable(), pjBookingModel::factory()->getTable(), $iso_from, $iso_to, $where))
		  	->orderBy("name ASC")
		  	->findAll()
		  	->getData();
		
		$extra_per = __('extra_per', true);
		foreach($extra_arr as $k => $v)
		{
			$v['price_format'] = pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']) . ' ' . $extra_per[$v['per']];
			$extra_arr[$k] = $v;
		}
		return $extra_arr;
	}
	
	static public function getTokens($data, $option_arr, $salt, $locale_id)
	{
		$country = NULL;
		$extras = NULL;
		$space = NULL;
		$extra_arr = array();
		
		$personal_titles = __('personal_titles', true, false);
		$extra_per = __('extra_per', true);
		$payment_methods = __('payment_methods', true, false);
		
		$title = $personal_titles[$data['c_title']];
		$payment_method = $payment_methods[$data['payment_method']];
		
		$space_arr = pjSpaceModel::factory()
			->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjSpace' AND t2.locale = '".$locale_id."' AND t2.field = 'name'", 'left')
			->select('t1.*, t2.content as name')
			->find($data['space_id'])
			->getData();
		$space = $space_arr['name'] . ' (' . pjUtil::formatCurrencySign($data['rental_price'], $option_arr['o_currency']) . ')';
		
		if (isset($data['c_country']) && !empty($data['c_country']))
		{
			if(isset($data['c_country']) && (int) $data['c_country'] > 0)
			{
				$country_arr = pjCountryModel::factory()
					->select('t1.id, t2.content AS country_title')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$locale_id."'", 'left outer')
					->find($data['c_country'])
					->getData();
				if (!empty($country_arr))
				{
					$country = $country_arr['country_title'];
				}
			}
		}
		$be_arr = pjBookingExtraModel::factory()
			->select('t1.*, t3.content as name, t2.per, t2.price as unit_price')
			->join('pjExtra', "t2.id=t1.extra_id", 'left')
			->join('pjMultiLang', "t3.foreign_id = t1.extra_id AND t3.model = 'pjExtra' AND t3.locale = '".$locale_id."' AND t3.field = 'name'", 'left')
			->where('t1.booking_id', $data['id'])
			->findAll()
			->getData();

		foreach($be_arr as $k => $v)
		{
			$extra_arr[] = $v['name'] . ' (' . pjUtil::formatCurrencySign($v['unit_price'], $option_arr['o_currency']) . ' ' . $extra_per[$v['per']] .  ') x ' . $v['cnt'] . ' = ' . pjUtil::formatCurrencySign($v['price'], $option_arr['o_currency']);
		}
		$extras = join("\r\n", $extra_arr);
		
		$tax = pjUtil::formatCurrencySign($data['tax'], $option_arr['o_currency']);
		$deposit = pjUtil::formatCurrencySign($data['deposit'], $option_arr['o_currency']);
		$total = pjUtil::formatCurrencySign($data['total'], $option_arr['o_currency']);
		
		$cancelURL = PJ_INSTALL_URL . 'index.php?controller=pjFront&action=pjActionCancel&id='.@$data['id'].'&hash='.sha1(@$data['id'].@$data['created'].$salt);
		$cancelURL = '<a href="'.$cancelURL.'">' . $cancelURL . '</a>';
		$from = date($option_arr['o_date_format'], strtotime($data['from'])) . ', ' . date($option_arr['o_time_format'], strtotime($data['from']));
		$to = date($option_arr['o_date_format'], strtotime($data['to'])) . ', ' . date($option_arr['o_time_format'], strtotime($data['to']));
		
		$search = array(
				'{Title}',
				'{Name}',
				'{Email}',
				'{Phone}',
				'{Country}',
				'{City}',
				'{State}',
				'{Zip}',
				'{Address}',
				'{Company}',
				'{Notes}',
				'{RegNo}',
				'{Make}',
				'{Model}',
				'{DtFrom}',
				'{DtTo}',
				'{Space}',
				'{Extras}',
				'{BookingID}',
				'{UniqueID}',
				'{Deposit}',
				'{Total}',
				'{Tax}',
				'{PaymentMethod}',
				'{CCType}',
				'{CCNum}',
				'{CCExp}',
				'{CCSec}',
				'{CancelURL}'
			);
		$replace = array(
				$title,
				@$data['c_name'],
				@$data['c_email'],
				@$data['c_phone'],
				$country,
				@$data['c_city'],
				@$data['c_state'],
				@$data['c_zip'],
				@$data['c_address'],
				@$data['c_company'],
				@$data['c_notes'],
				@$data['c_regno'],
				@$data['c_make'],
				@$data['c_model'],
				$from,
				$to,
				$space,
				$extras,
				@$data['id'],
				@$data['uuid'],
				$deposit,
				$total,
				$tax,
				$payment_method,
				@$data['cc_type'],
				@$data['cc_num'],
				@$data['cc_exp_month'] . '-' . @$data['cc_exp_year'],
				@$data['cc_code'],
				$cancelURL
			);
		return compact('search', 'replace');
	}
}
?>