<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjInstaller extends pjInstallerAppController
{
	public $defaultInstaller = 'Installer';
	
	public $defaultErrors = 'Errors';

	public $defaultCaptcha = 'Captcha';
	
	public function beforeFilter()
	{
		$this->appendJs('jquery.min.js', PJ_THIRD_PARTY_PATH . 'jquery/');
		$this->appendCss('admin.css');
		$this->appendCss('install.css', $this->getConst('PLUGIN_CSS_PATH'));
		$this->appendCss('pj-button.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
		$this->appendCss('pj-form.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
	}

	private static function pjActionImportSQL($dbo, $file, $prefix, $scriptPrefix=NULL)
	{
		if (!is_object($dbo))
		{
			return FALSE;
		}
		ob_start();
		readfile($file);
		$string = ob_get_contents();
		ob_end_clean();
		if ($string !== false)
		{
			$string = preg_replace(
				array('/(INSERT\s+INTO|INSERT\s+IGNORE\s+INTO|DROP\s+TABLE|DROP\s+TABLE\s+IF\s+EXISTS|DROP\s+VIEW|DROP\s+VIEW\s+IF\s+EXISTS|CREATE\s+TABLE|CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS|UPDATE|UPDATE\s+IGNORE|FROM|ALTER\s+TABLE|ALTER\s+IGNORE\s+TABLE|DELETE\s+(?:(?:LOW_PRIORITY\s+)?(?:QUICK\s+)?(?:IGNORE\s+)?){2}?FROM)\s+`\b(.*)\b`/'),
				array("\${1} `".$prefix.$scriptPrefix."\${2}`"),
				$string);

			# Get locales
			$statement = sprintf("SELECT `id` FROM `%s%splugin_locale` WHERE 1 ORDER BY `id`", $prefix, $scriptPrefix);
			if (FALSE !== $dbo->query($statement))
			{
				$dbo->fetchAssoc();
				$locales = $dbo->getData();
			}
				
			if (!isset($locales) || empty($locales))
			{
				# Define default locales
				$locales = array(
					array('id' => 1),
				);
			}
			
			$arr = preg_split('/;(\s+)?\n/', $string);
			
			$tmp = array();
			$needle = '::LOCALE::';
			# Search/replace language token
			foreach ($arr as $statement)
			{
				if (strpos($statement, $needle) !== FALSE)
				{
					foreach ($locales as $locale)
					{
						$tmp[] = str_replace($needle, $locale['id'], $statement);
					}
				} else {
					$tmp[] = $statement;
				}
			}
				
			$arr = $tmp;
			
			$dbo->query("START TRANSACTION;");
			foreach ($arr as $statement)
			{
				$statement = trim($statement);
				if (!empty($statement))
				{
					if (!$dbo->query($statement))
					{
						$error = $dbo->error();
						$dbo->query("ROLLBACK");
						return $error . $file . $statement;
					}
				}
			}
			$dbo->query("COMMIT;");
			
			return TRUE;
		}
		return FALSE;
	}

	private static function isSecure()
	{
		$isSecure = false;
		if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		{
			$isSecure = true;
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
			$isSecure = true;
		}
		
		return $isSecure;
	}

	private static function pjActionGetPaths()
	{
		$absolutepath = str_replace("\\", "/", dirname(realpath(basename(getenv("SCRIPT_NAME")))));
		$localpath = str_replace("\\", "/", dirname(getenv("SCRIPT_NAME")));
		
		$localpath = str_replace("\\", "/", $localpath);
		$localpath = preg_replace('/^\//', '', $localpath, 1) . '/';
		$localpath = !in_array($localpath, array('/', '\\')) ? $localpath : NULL;

		$protocol = self::isSecure() ? 'https' : 'http';
		
		return array(
			'install_folder' => '/' . $localpath,
			'install_path' => $absolutepath . '/',
			'install_url' => $protocol . '://' . $_SERVER['SERVER_NAME'] . '/' . $localpath
		);
	}

	public function pjActionIndex()
	{
		pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep0&install=1");
	}
	
	private static function pjActionCheckConfig($redirect=true)
	{
		$filename = 'app/config/config.inc.php';
		$content = @file_get_contents($filename);
		if (strpos($content, 'PJ_HOST') === false && strpos($content, 'PJ_INSTALL_URL') === false)
		{
			//Continue with installation
			return true;
		} else {
			if ($redirect)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep0&install=1");
			}
			return false;
		}
	}
	
	private function pjActionCheckSession()
	{
		if (!isset($_SESSION[$this->defaultInstaller]))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep1&install=1");
		}
	}
	
	private function pjActionCheckTables(&$dbo)
	{
		if (!is_object($dbo))
		{
			return FALSE;
		}
		ob_start();
		readfile('app/config/database.sql');
		$string = ob_get_contents();
		ob_end_clean();

		preg_match_all('/DROP\s+TABLE(\s+IF\s+EXISTS)?\s+`(\w+)`/i', $string, $match);
		if (count($match[0]) > 0)
		{
			$arr = array();
			foreach ($match[2] as $k => $table)
			{
				$result = $dbo->query(sprintf("SHOW TABLES FROM `%s` LIKE '%s'",
					$_SESSION[$this->defaultInstaller]['database'],
					$_SESSION[$this->defaultInstaller]['prefix'] . $table
				));
				if ($result !== FALSE && $dbo->numRows() > 0)
				{
					$row = $dbo->fetchAssoc()->getData();
					$row = array_values($row);
					$arr[] = $row[0];
				}
			}
			return count($arr) === 0;
		}
		return TRUE;
	}
	
	private function pjActionCheckVars()
	{
		if (!isset($_GET['install']))
		{
			return array('status' => 'ERR', 'code' => 100, 'text' => 'Missing \'install\' parameter in the URL address.');
		}
		
		if (!isset($_SESSION[$this->defaultInstaller]))
		{
			return array('status' => 'ERR', 'code' => 101, 'text' => 'Session not found, please start over.');
		}
		
		$indexes = array(
			'hostname' => 'MySQL Hostname', 
			'username' => 'MySQL Username', 
			'password' => 'MySQL Password', 
			'database' => 'MySQL Database', 
			'prefix' => 'MySQL Table prefix', 
			'admin_email' => 'Administrator Login: E-Mail', 
			'admin_password' => 'Administrator Login: Password', 
			'install_folder' => 'Folder Name', 
			'install_path' => 'Server Path', 
			'install_url' => 'Full URL', 
			'license_key' => 'License Key',
		);
		
		foreach ($indexes as $index => $label)
		{
			if (!isset($_SESSION[$this->defaultInstaller][$index]))
			{
				return array('status' => 'ERR', 'code' => 102, 'text' => sprintf("'%s' is not set, please go back to fix it.", $label));
			}
		}
		
		if (!isset($_SESSION[$this->defaultInstaller]['private_key']))
		{
			return array('status' => 'ERR', 'code' => 103, 'text' => "'License Key' is not valid, please go back to fix it.");
		}
		
		return array('status' => 'OK', 'code' => 200, 'text' => 'Success');
	}
	
	private function pjActionCheckTableLength()
	{
		ob_start();
		readfile('app/config/database.sql');
		$string = ob_get_contents();
		ob_end_clean();
		
		preg_match_all('/DROP\s+TABLE(\s+IF\s+EXISTS)?\s+`(\w+)`/i', $string, $match);
		if (count($match[0]) > 0)
		{
			$arr = array();
			foreach ($match[2] as $k => $table)
			{
				$table_name = $_SESSION[$this->defaultInstaller]['prefix'] . $table;
				
				if(strlen($table_name) > 64)
				{
					return $table_name;
				}
			}
		}
		require 'app/config/options.inc.php';
		
		if (isset($CONFIG['plugins']))
		{
			if (!is_array($CONFIG['plugins']))
			{
				$CONFIG['plugins'] = array($CONFIG['plugins']);
			}
			foreach ($CONFIG['plugins'] as $plugin)
			{
				$file = PJ_PLUGINS_PATH . $plugin . '/config/database.sql';
				if (is_file($file))
				{
					ob_start();
					readfile($file);
					$string = ob_get_contents();
					ob_end_clean();
					
					preg_match_all('/DROP\s+TABLE(\s+IF\s+EXISTS)?\s+`(\w+)`/i', $string, $match);
					if (count($match[0]) > 0)
					{
						$arr = array();
						foreach ($match[2] as $k => $table)
						{
							$table_name = $_SESSION[$this->defaultInstaller]['prefix'] . PJ_SCRIPT_PREFIX . $table;
							if(strlen($table_name) > 64)
							{
								return $table_name;
							}
						}
					}
					
					$update_folder = PJ_PLUGINS_PATH . $plugin . '/config/updates';
					if (is_dir($update_folder))
					{
						$files = array();
						pjToolkit::readDir($files, $update_folder);
						foreach ($files as $path)
						{
							if (preg_match('/\.sql$/', basename($path)) && is_file($path))
							{
								ob_start();
								readfile($path);
								$string = ob_get_contents();
								ob_end_clean();
								
								preg_match_all('/DROP\s+TABLE(\s+IF\s+EXISTS)?\s+`(\w+)`/i', $string, $match);
								if (count($match[0]) > 0)
								{
									$arr = array();
									foreach ($match[2] as $k => $table)
									{
										$table_name = $_SESSION[$this->defaultInstaller]['prefix'] . PJ_SCRIPT_PREFIX . $table;
										if(strlen($table_name) > 64)
										{
											return $table_name;
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return '';
	}
	
	public function pjActionStep0()
	{
		if (self::pjActionCheckConfig(false))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep1&install=1");
		}
	}
	
	public function pjActionStep1()
	{
		self::pjActionCheckConfig();
		
		if (!isset($_SESSION[$this->defaultInstaller]))
		{
			$_SESSION[$this->defaultInstaller] = array();
		}
		if (!isset($_SESSION[$this->defaultErrors]))
		{
			$_SESSION[$this->defaultErrors] = array();
		}
		
		# PHP Session check -------------------
		if (!headers_sent())
		{
			@session_start();
			$_SESSION['PJ_SESSION_CHECK'] = 1;
			@session_write_close();
			
			$_SESSION = array();
			@session_start();
			
			$session_check = isset($_SESSION['PJ_SESSION_CHECK']);
			$this->set('session_check', $session_check);
			if ($session_check)
			{
				$_SESSION['PJ_SESSION_CHECK'] = NULL;
				unset($_SESSION['PJ_SESSION_CHECK']);
			}
		}
		
		ob_start();
		phpinfo(INFO_MODULES);
		$content = ob_get_contents();
		ob_end_clean();
		
		# MySQL version -------------------
		if (!PJ_DISABLE_MYSQL_CHECK)
		{
			$drivers = array('mysql', 'mysqli');
			$mysql_version = NULL;
			foreach ($drivers as $driver)
			{
				$mysql_content = explode('name="module_'.$driver.'"', $content);
				if (count($mysql_content) > 1)
				{
					$mysql_content = explode("Client API", $mysql_content[1]);
					if (count($mysql_content) > 1)
					{
						preg_match('/<td class="v">(.*)<\/td>/', $mysql_content[1], $m);
						if (count($m) > 0)
						{
							$mysql_version = trim($m[1]);
							
							if (preg_match('/(\d+\.\d+\.\d+)/', $mysql_version, $m))
							{
								$mysql_version = $m[1];
							}
						}
					}
				}
			
				$mysql_check = true;
				if (is_null($mysql_version) || version_compare($mysql_version, '5.0.0', '<'))
				{
					$mysql_check = false;
				}
			}
			$this->set('mysql_check', $mysql_check);
		}
		
		# PHP version -------------------
		$php_check = true;
		if (version_compare(phpversion(), '5.1.0', '<'))
		{
			$php_check = false;
		}
		$this->set('php_check', $php_check);

		# File permissions
		$filename = 'app/config/config.inc.php';
		$err_arr = array();
		if (!is_writable($filename))
		{
		    $err_arr[] = sprintf('%1$s \'<span class="bold">%2$s</span>\' is not writable. %3$s \'<span class="bold">%2$s</span>\'', 'File', $filename, 'You need to set write permissions (chmod 777) to options file located at');
		}

		# Folder permissions
		$folders = array();
		foreach ($folders as $dir)
		{
			if (!is_writable($dir))
			{
				$err_arr[] = sprintf('%1$s \'<span class="bold">%2$s</span>\' is not writable. %3$s \'<span class="bold">%2$s</span>\'', 'Folder', $dir, 'You need to set write permissions (chmod 777) to directory located at');
			}
		}
		
		# Script (file/folder) permissions
		$result = $this->requestAction(array(
			'controller' => 'pjAppController',
			'action' => 'pjActionCheckInstall'
		), array('return'));
		
		if ($result !== NULL && isset($result['status'], $result['info']) && $result['status'] == 'ERR')
		{
			$err_arr = array_merge($err_arr, $result['info']);
		}
		
		$dependencies_check = TRUE;
		$dependencies_arr = array();
		$pjDependencyManager = new pjDependencyManager(PJ_THIRD_PARTY_PATH);
		
		$result_map = $pjDependencyManager
			->load(PJ_CONFIG_PATH . 'dependencies.php')
			->resolve()
			->getResult();
		
		if (in_array(FALSE, $result_map))
		{
			$dependencies_check = FALSE;
			$dependencies = $pjDependencyManager->getDependencies();
			foreach ($result_map as $key => $value)
			{
				if (!$value)
				{
					$dependencies_arr[] = sprintf('Unresolved dependency check. <span class="bold">Script</span> require <span class="bold">%s %s</span>', $key, $dependencies[$key]);
				}
			}
		}
		
		# Plugin (file/folder) permissions
		$filename = 'app/config/options.inc.php';
		$options = @file_get_contents($filename);
		if ($options !== FALSE)
		{
			preg_match('/\$CONFIG\s*\[\s*[\'\"]plugins[\'\"]\s*\](.*);/sxU', $options, $match);
			if (!empty($match))
			{
				eval($match[0]);
			
				if (isset($CONFIG['plugins']))
				{
					if (!is_array($CONFIG['plugins']))
					{
						$CONFIG['plugins'] = array($CONFIG['plugins']);
					}
					foreach ($CONFIG['plugins'] as $plugin)
					{
						$result = $this->requestAction(array(
							'controller' => $plugin,
							'action' => 'pjActionCheckInstall'
						), array('return'));

						if ($result !== NULL && isset($result['status'], $result['info']) && $result['status'] == 'ERR')
						{
							$err_arr = array_merge($err_arr, $result['info']);
						}
						
						$result_map = $pjDependencyManager
							->reset()
							->load(PJ_PLUGINS_PATH . $plugin . '/config/dependencies.php')
							->resolve()
							->getResult();

						if (in_array(FALSE, $result_map))
						{
							$dependencies_check = FALSE;
							$dependencies = $pjDependencyManager->getDependencies();
							foreach ($result_map as $key => $value)
							{
								if (!$value)
								{
									$dependencies_arr[] = sprintf('Unresolved dependency check. <span class="bold">%s</span> require <span class="bold">%s %s</span>', $plugin, $key, $dependencies[$key]);
								}
							}
						}
					}
				}
			}
		}

		$this->set('folder_check', count($err_arr) === 0);
		$this->set('folder_arr', $err_arr);
		
		$this->set('dependencies_check', $dependencies_check);
		$this->set('dependencies_arr', $dependencies_arr);
			
		$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
		$this->appendJs('pjInstaller.js', $this->getConst('PLUGIN_JS_PATH'));
	}
	
	public function pjActionStep2()
	{
		self::pjActionCheckConfig();
	
		$this->pjActionCheckSession();
	
		if (isset($_POST['step1']))
		{
			$_SESSION[$this->defaultInstaller] = array_merge($_SESSION[$this->defaultInstaller], $_POST);
		}
		
		if (!isset($_SESSION[$this->defaultInstaller]['step1']))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep1&install=1");
		}
	
		$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
		$this->appendJs('pjInstaller.js', $this->getConst('PLUGIN_JS_PATH'));
	}

	public function pjActionStep3()
	{
		self::pjActionCheckConfig();
		
		$this->pjActionCheckSession();
		
		if (isset($_POST['step2']))
		{
			$_POST = array_map('trim', $_POST);
				
			if (!isset($_POST['license_key']) || !pjValidation::pjActionNotEmpty($_POST['license_key']))
			{
				$time = time();
				$_SESSION[$this->defaultErrors][$time] = "License Key is required and can't be empty.";
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep2&install=1&err=" . $time);
			} else {
				$_POST = pjSanitize::clean($_POST, array('encode' => false));
				$_SESSION[$this->defaultInstaller] = array_merge($_SESSION[$this->defaultInstaller], $_POST);
		
				$time = time();
		
				$install = self::pjActionOneInstall($_POST['license_key']);
				switch ($install['status'])
				{
					case 'OK':
						$_SESSION[$this->defaultInstaller]['private_key'] = $install['private_key'];
						$_SESSION[$this->defaultInstaller]['pj_installation'] = $install['hash'];
						break;
					case 'ERR':
						$_SESSION[$this->defaultErrors][$time] = $install['text'];
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep2&install=1&err=" . $time);
						break;
				}
			}
		}
		
		if (!isset($_SESSION[$this->defaultInstaller]['step2']))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep2&install=1");
		}
		
		$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
		$this->appendJs('pjInstaller.js', $this->getConst('PLUGIN_JS_PATH'));
	}
	
	public function pjActionStep4()
	{
		self::pjActionCheckConfig();
		
		$this->pjActionCheckSession();
		
		if (isset($_POST['step3']))
		{
			$_POST = array_map('trim', $_POST);
			$_POST = pjSanitize::clean($_POST, array('encode' => false));
			$_SESSION[$this->defaultInstaller] = array_merge($_SESSION[$this->defaultInstaller], $_POST);
			
			$err = NULL;
			
			if (!isset($_POST['hostname']) || !isset($_POST['username']) || !isset($_POST['database']) ||
				!pjValidation::pjActionNotEmpty($_POST['hostname']) ||
				!pjValidation::pjActionNotEmpty($_POST['username']) ||
				!pjValidation::pjActionNotEmpty($_POST['database']))
			{
				$err = "Hostname, Username and Database are required and can't be empty.";
			} else {
				
				$driver = function_exists('mysqli_connect') ? 'pjMysqliDriver' : 'pjMysqlDriver';
				$params = array(
					'hostname' => $_POST['hostname'],
					'username' => $_POST['username'],
					'password' => $_POST['password'],
					'database' => $_POST['database']
				);
				if (strpos($params['hostname'], ":") !== FALSE)
				{
					list($hostname, $value) = explode(":", $params['hostname'], 2);
					if (preg_match('/\D/', $value))
					{
						$params['socket'] = $value;
					} else {
						$params['port'] = $value;
					}
					$params['hostname'] = $hostname;
				}
				$dbo = pjSingleton::getInstance($driver, $params);
				if (!$dbo->init())
				{
					$err = $dbo->connectError();
					if (empty($err))
					{
						$err = $dbo->error();
					}
				} else {
					$table_name = $this->pjActionCheckTableLength();
					if ($table_name != '')
					{
						$err = "Invalid table name! '".$table_name . "' cannot be longer than 64 characters.";
					}else{
						if (!$this->pjActionCheckTables($dbo))
						{
							$this->set('warning', 1);
						}
						
						$tempTable = 'stivasoft_temp_install';
						
						$dbo->query("DROP TABLE IF EXISTS `$tempTable`;");
						
						if (!$dbo->query("CREATE TABLE IF NOT EXISTS `$tempTable` (`created` datetime DEFAULT NULL);"))
						{
							$err .= "CREATE command denied to current user<br />";
						} else {
							if (!$dbo->query("INSERT INTO `$tempTable` (`created`) VALUES (NOW());"))
							{
								$err .= "INSERT command denied to current user<br />";
							}
							if (!$dbo->query("SELECT * FROM `$tempTable` WHERE 1=1;"))
							{
								$err .= "SELECT command denied to current user<br />";
							}
							if (!$dbo->query("UPDATE `$tempTable` SET `created` = NOW();"))
							{
								$err .= "UPDATE command denied to current user<br />";
							}
							if (!$dbo->query("DELETE FROM `$tempTable` WHERE 1=1;"))
							{
								$err .= "DELETE command denied to current user<br />";
							}
						}
						if (!$dbo->query("DROP TABLE IF EXISTS `$tempTable`;"))
						{
							$err .= "DROP command denied to current user<br />";
						}
					}
				}
			}
			if (!is_null($err))
			{
				$time = time();
				$_SESSION[$this->defaultErrors][$time] = $err;
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep3&install=1&err=" . $time);
			}
			
			$this->set('paths', self::pjActionGetPaths());
			
			$this->set('status', 'ok');
		}
		
		if (!isset($_SESSION[$this->defaultInstaller]['step3']))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep3&install=1");
		}
		
		$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
		$this->appendJs('pjInstaller.js', $this->getConst('PLUGIN_JS_PATH'));
	}

	public function pjActionStep5()
	{
		self::pjActionCheckConfig();
		
		$this->pjActionCheckSession();
		
		if (isset($_POST['step4']))
		{
			$_POST = array_map('trim', $_POST);
			
			if (!isset($_POST['install_folder']) || !isset($_POST['install_url']) || !isset($_POST['install_path']) ||
				!pjValidation::pjActionNotEmpty($_POST['install_folder']) ||
				!pjValidation::pjActionNotEmpty($_POST['install_url']) ||
				!pjValidation::pjActionNotEmpty($_POST['install_path']))
			{
				$time = time();
				$_SESSION[$this->defaultErrors][$time] = "Folder Name, Full URL and Server Path are required and can't be empty.";
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep4&install=1&err=" . $time);
			} else {
				$_POST = pjSanitize::clean($_POST, array('encode' => false));
				$_SESSION[$this->defaultInstaller] = array_merge($_SESSION[$this->defaultInstaller], $_POST);
			}
		}
		
		if (!isset($_SESSION[$this->defaultInstaller]['step4']))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep4&install=1");
		}
		
		$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
		$this->appendJs('pjInstaller.js', $this->getConst('PLUGIN_JS_PATH'));
	}
	
	public function pjActionStep6()
	{
		self::pjActionCheckConfig();
		
		$this->pjActionCheckSession();
		
		if (isset($_POST['step5']))
		{
			$_POST = array_map('trim', $_POST);
		
			if (!isset($_POST['admin_email']) || !isset($_POST['admin_password']) ||
				!pjValidation::pjActionNotEmpty($_POST['admin_email']) ||
				!pjValidation::pjActionEmail($_POST['admin_email']) ||
				!pjValidation::pjActionNotEmpty($_POST['admin_password']))
			{
				$time = time();
				$_SESSION[$this->defaultErrors][$time] = "E-Mail and Password are required and can't be empty.";
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep5&install=1&err=" . $time);
			} else {
				$_POST = pjSanitize::clean($_POST, array('encode' => false));
				$_SESSION[$this->defaultInstaller] = array_merge($_SESSION[$this->defaultInstaller], $_POST);
			}
		}
		
		if (!isset($_SESSION[$this->defaultInstaller]['step5']))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep5&install=1");
		}
		
		$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
		$this->appendJs('pjInstaller.js', $this->getConst('PLUGIN_JS_PATH'));
	}
	
	public function pjActionStep7()
	{
		$this->pjActionCheckSession();
		
		if (isset($_POST['step6']))
		{
			$_POST = pjSanitize::clean($_POST, array('encode' => false));
			$_SESSION[$this->defaultInstaller] = array_merge($_SESSION[$this->defaultInstaller], $_POST);
		}
		
		if (!isset($_SESSION[$this->defaultInstaller]['step6']))
		{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionStep6&install=1");
		}
		
		unset($_SESSION[$this->defaultInstaller]);
		unset($_SESSION[$this->defaultErrors]);
	}
	
	public function pjActionSetDb()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			$vars = self::pjActionCheckVars();
			if ($vars['status'] === 'ERR')
			{
				$vars['url'] = $this->getTrackingUrl('ERR', $vars['text']);
				pjAppController::jsonResponse($vars);
			}
			@set_time_limit(300); //5 minutes
			
			$resp = array();
			
			$driver = function_exists('mysqli_connect') ? 'pjMysqliDriver' : 'pjMysqlDriver';
			$params = array(
				'hostname' => $_SESSION[$this->defaultInstaller]['hostname'],
				'username' => $_SESSION[$this->defaultInstaller]['username'],
				'password' => $_SESSION[$this->defaultInstaller]['password'],
				'database' => $_SESSION[$this->defaultInstaller]['database']
			);
			if (strpos($params['hostname'], ":") !== FALSE)
			{
				list($hostname, $value) = explode(":", $params['hostname'], 2);
				if (preg_match('/\D/', $value))
				{
					$params['socket'] = $value;
				} else {
					$params['port'] = $value;
				}
				$params['hostname'] = $hostname;
			}
			$dbo = pjSingleton::getInstance($driver, $params);
			if (!$dbo->init())
			{
				$err = $dbo->connectError();
				if (!empty($err))
				{
					$resp['code'] = 100;
				    $resp['text'] = 'Could not connect: ' . $err;
				    $resp['url'] = $this->getTrackingUrl('ERR', $resp['text']);
				    self::pjActionDbError($resp);
				} else {
					$resp['code'] = 101;
				    $resp['text'] = $dbo->error();
				    $resp['url'] = $this->getTrackingUrl('ERR', $resp['text']);
				    self::pjActionDbError($resp);
				}
			} else {
				$idb = self::pjActionImportSQL($dbo, 'app/config/database.sql', $_SESSION[$this->defaultInstaller]['prefix']);
				if ($idb === true)
				{
					$_GET['install'] = 2;
					require 'app/config/options.inc.php';
					
					$result = $this->requestAction(array(
						'controller' => 'pjAppController',
						'action' => 'pjActionBeforeInstall'
					), array('return'));
					
					if ($result !== NULL && isset($result['code']) && $result['code'] != 200 && isset($result['info']))
					{
						$resp['text'] = join("<br>", $result['info']);
						$resp['code'] = 104;
						$resp['url'] = $this->getTrackingUrl('ERR', $resp['text']);
						self::pjActionDbError($resp);
					}
					
					$pjOptionModel = pjOptionModel::factory()->setPrefix($_SESSION[$this->defaultInstaller]['prefix']);
					$statement = sprintf("INSERT IGNORE INTO `%s`(`foreign_id`,`key`,`tab_id`,`value`,`type`) VALUES (:foreign_id, :key, :tab_id, NOW(), :type);", $pjOptionModel->getTable());
					$data = array(
						'foreign_id' => $this->getForeignId(),
						'tab_id' => 99,
						'type' => 'string'
					);
					
					if (isset($CONFIG['plugins']))
					{
						if (!is_array($CONFIG['plugins']))
						{
							$CONFIG['plugins'] = array($CONFIG['plugins']);
						}
						foreach ($CONFIG['plugins'] as $plugin)
						{
							$result = $this->pjActionInstallPlugin($pjOptionModel, $dbo, $plugin, $_SESSION[$this->defaultInstaller]['prefix'], FALSE);
							if ($result['status'] === 'ERR')
							{
								$result['url'] = $this->getTrackingUrl('ERR', $result['text']);
								self::pjActionDbError($result);
							}
						}
					}
					
					$updates = self::pjActionGetUpdates();
					foreach ($updates as $record)
					{
						$file_path = $record['path'];
						$response = self::pjActionExecuteSQL($dbo, $file_path, $_SESSION[$this->defaultInstaller]['prefix'], PJ_SCRIPT_PREFIX);
						if ($response['status'] == "ERR")
						{
							$response['url'] = $this->getTrackingUrl('ERR', $response['text']);
							self::pjActionDbError($response);
						} else if ($response['status'] == "OK") {
							$data['key'] = sprintf('o_%s_%s', basename($file_path), md5($file_path));
							$pjOptionModel->prepare($statement)->exec($data);
						}
					}
					
					if (defined("PJ_TEMPLATE_PATH"))
					{
						$updates = self::pjActionGetUpdates(PJ_TEMPLATE_PATH);
						foreach ($updates as $record)
						{
							$file_path = $record['path'];
							$response = self::pjActionExecuteSQL($dbo, $file_path, $_SESSION[$this->defaultInstaller]['prefix'], PJ_SCRIPT_PREFIX);
							if ($response['status'] == "ERR")
							{
								$response['url'] = $this->getTrackingUrl('ERR', $response['text']);
								self::pjActionDbError($response);
							} else if ($response['status'] == "OK") {
								$data['key'] = sprintf('o_%s_%s', basename($file_path), md5($file_path));
								$pjOptionModel->prepare($statement)->exec($data);
							}
						}
					}
					
					if (isset($CONFIG['locales']) && !empty($CONFIG['locales']) && pjObject::getPlugin('pjLocale') !== NULL)
					{
						if (!is_array($CONFIG['locales']))
						{
							$CONFIG['locales'] = array($CONFIG['locales']);
						}

						$languages = pjLocaleLanguageModel::factory()
							->setPrefix($_SESSION[$this->defaultInstaller]['prefix'])
							->whereIn('t1.iso', array_map('strtolower', $CONFIG['locales']))
							->findAll()
							->getDataPair('iso');
						
						foreach ($CONFIG['locales'] as $locale)
						{
							if (!isset($languages[$locale]) || $locale == 'gb')
							{
								continue;
							}
							
							$this->requestAction(array(
								'controller' => 'pjLocale', 
								'action' => 'pjActionAddLocale',
								'params' => array('iso' => $locale)
							), array('return'));
						}
					}
					
					$result = $this->requestAction(array(
						'controller' => 'pjAppController',
						'action' => 'pjActionAfterInstall'
					), array('return'));
					
					if ($result !== NULL && isset($result['code']) && $result['code'] != 200 && isset($result['info']))
					{
						$resp['text'] = join("<br>", $result['info']);
						$resp['code'] = 105;
						$resp['url'] = $this->getTrackingUrl('ERR', $resp['text']);
						self::pjActionDbError($resp);
					}

					pjUserModel::factory()
						->setPrefix($_SESSION[$this->defaultInstaller]['prefix'])
						->setAttributes(array(
							'email' => $_SESSION[$this->defaultInstaller]['admin_email'],
							'password' => $_SESSION[$this->defaultInstaller]['admin_password'],
							'role_id' => 1,
							'name' => "Administrator",
							'ip' => $_SERVER['REMOTE_ADDR']
						))
						->insert();
					
					pjOptionModel::factory()
						->setPrefix($_SESSION[$this->defaultInstaller]['prefix'])
						->setAttributes(array(
							'foreign_id' => $this->getForeignId(),
							'key' => 'private_key',
							'tab_id' => 99,
							'value' => $_SESSION[$this->defaultInstaller]['private_key'],
							'type' => 'string'
						))
						->insert();
					
					if (!isset($resp['code']))
					{
						$resp['code'] = 200;
						$resp['text'] = 'Success';
						$resp['url'] = $this->getTrackingUrl('OK', $resp['text']);
					}
				} elseif ($idb === false) {
					$resp['code'] = 102; //File not found (can't be open/read)
					$resp['text'] = "File not found (or can't be read)";
					$resp['url'] = $this->getTrackingUrl('ERR', $resp['text']);
					self::pjActionDbError($resp);
				} else {
					$resp['code'] = 103; //MySQL error
					$resp['text'] = $idb;
					$resp['url'] = $this->getTrackingUrl('ERR', $resp['text']);
					self::pjActionDbError($resp);
				}
			}
			
			if (isset($resp['code']) && $resp['code'] != 200)
			{
				$resp['url'] = $this->getTrackingUrl('ERR', @$resp['text']);
				self::pjActionDbError($resp);
			}
			pjAppController::jsonResponse($resp);
		}
		exit;
	}
	
	private static function pjActionDbError($resp)
	{
		@file_put_contents('app/config/config.inc.php', '');
		pjAppController::jsonResponse($resp);
	}
	
	private function getTrackingUrl($status='ERR', $text=NULL)
	{
		return sprintf("https://www.stivasoft.com/trackInstall.php?version=%s&build=%s&script=%s&license_key=%s&alert=%s",
			PJ_SCRIPT_VERSION, 
			PJ_SCRIPT_BUILD, 
			PJ_SCRIPT_ID, 
			@$_SESSION[$this->defaultInstaller]['license_key'], 
			urlencode(base64_encode(serialize(array('status' => $status, 'text' => $text))))
		);
	}
	
	public function pjActionSetConfig()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!self::pjActionCheckConfig(false))
			{
				$result = array('code' => 107, 'text' => 'Product is already installed. If you need to re-install it empty app/config/config.inc.php file.');
				$result['url'] = $this->getTrackingUrl('ERR', $result['text']);
				pjAppController::jsonResponse($result);
			}
			$sample = 'app/config/config.sample.php';
			$filename = 'app/config/config.inc.php';
			ob_start();
			readfile($sample);
			$string = ob_get_contents();
			ob_end_clean();
			if ($string === FALSE)
			{
				$result = array('status' => 'ERR', 'code' => 100, 'text' => "An error occurs while reading 'app/config/config.sample.php'");
				$result['url'] = $this->getTrackingUrl('ERR', $result['text']);
				pjAppController::jsonResponse($result);
			}
			if (!self::pjActionCheckVars())
			{
				$result = array('status' => 'ERR', 'code' => 108, 'text' => 'Missing, empty or invalid parameters.');
				$result['url'] = $this->getTrackingUrl('ERR', $result['text']);
				pjAppController::jsonResponse($result);
			}
			if (!is_writable($filename))
			{
				$result = array('status' => 'ERR', 'code' => 101, 'text' => "'app/config/config.inc.php' do not exists or not writable");
				$result['url'] = $this->getTrackingUrl('ERR', $result['text']);
				pjAppController::jsonResponse($result);
			}
			if (!$handle = @fopen($filename, 'wb'))
			{
				$result = array('status' => 'ERR', 'code' => 103, 'text' => "'app/config/config.inc.php' open fails");
				$result['url'] = $this->getTrackingUrl('ERR', $result['text']);
				pjAppController::jsonResponse($result);
			}
			
			$string = self::pjActionReplaceConfigTokens($string, $_SESSION[$this->defaultInstaller]);
			
			if (fwrite($handle, $string) === FALSE)
			{
				$result = array('status' => 'ERR', 'code' => 102, 'text' => "An error occurs while writing to 'app/config/config.inc.php'");
				$result['url'] = $this->getTrackingUrl('ERR', $result['text']);
				pjAppController::jsonResponse($result);
			}
			
			fclose($handle);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Success'));
		}
		exit;
	}
	
	public function pjActionLicense()
	{
		$arr = pjOptionModel::factory()
			->where('t1.foreign_id', $this->getForeignId())
			->where('t1.key', 'private_key')
			->limit(1)
			->findAll()
			->getData();

		$hash = NULL;
		if (count($arr) === 1)
		{
			$hash = $arr[0]['value'];
		}
		pjUtil::redirect(base64_decode("aHR0cDovL3N1cHBvcnQuc3RpdmFzb2Z0LmNvbS9jaGVja2xpY2Vuc2Uv") . $hash);
	}

	public function pjActionVersion()
	{
		if ($this->isLoged() && $this->isAdmin())
		{
			printf('PJ_SCRIPT_ID: %s<br>', PJ_SCRIPT_ID);
			printf('PJ_SCRIPT_BUILD: %s<br><br>', PJ_SCRIPT_BUILD);
			
			$plugins = pjRegistry::getInstance()->get('plugins');
			foreach ($plugins as $plugin => $whtvr)
			{
				printf("%s: %s<br>", $plugin, pjObject::getConstant($plugin, 'PLUGIN_BUILD'));
			}
			if (method_exists('pjObject', 'getFrameworkBuild'))
			{
				printf("<br>Framework: %s<br>", pjObject::getFrameworkBuild());
			}
		}
		exit;
	}
	
	public function pjActionHash()
	{
		@set_time_limit(0);
		
		if (!function_exists('md5_file'))
		{
			die("Function <b>md5_file</b> doesn't exists");
		}
		
		require 'app/config/config.inc.php';
		
		# Origin hash -------------
		if (!is_file(PJ_CONFIG_PATH . 'files.check'))
		{
			die("File <b>files.check</b> is missing");
		}
		$json = @file_get_contents(PJ_CONFIG_PATH . 'files.check');
		$Services_JSON = new pjServices_JSON();
		$data = $Services_JSON->decode($json);
		if (is_null($data))
		{
			die("File <b>files.check</b> is empty or broken");
		}
		$origin = get_object_vars($data);
				
		# Current hash ------------
		$data = array();
		pjUtil::readDir($data, PJ_INSTALL_PATH);
		$current = array();
		foreach ($data as $file)
		{
			$current[str_replace(PJ_INSTALL_PATH, '', $file)] = md5_file($file);
		}
		
		$html = '<style type="text/css">
		table{border: solid 1px #000; border-collapse: collapse; font-family: Verdana, Arial, sans-serif; font-size: 14px}
		td{border: solid 1px #000; padding: 3px 5px; background-color: #fff; color: #000}
		.diff{background-color: #0066FF; color: #fff}
		.miss{background-color: #CC0000; color: #fff}
		</style>
		<table cellpadding="0" cellspacing="0">
		<tr><td><strong>Filename</strong></td><td><strong>Status</strong></td></tr>
		';
		foreach ($origin as $file => $hash)
		{
			if (isset($current[$file]))
			{
				if ($current[$file] == $hash)
				{
					
				} else {
					$html .= '<tr><td>'. $file . '</td><td class="diff">changed</td></tr>';
				}
			} else {
				$html .= '<tr><td>'. $file . '</td><td class="miss">missing</td></tr>';
			}
		}
		$html .= '<table>';
		echo $html;
		exit;
	}
	
	private static function pjActionSortUpdates($haystack)
	{
		$_time = array();
		$_name = array();
		# Set some timezone just to prevent E_NOTICE/E_WARNING message
		date_default_timezone_set('America/Chicago');
		foreach ($haystack as $key => $item)
		{
			if (preg_match('/(20\d\d)_(0[1-9]|1[012])_(0[1-9]|[12][0-9]|3[01])_([01][0-9]|[2][0-3])_([0-5][0-9])_([0-5][0-9]).sql$/', $item['name'], $m))
			{
				$_time[$key] = mktime($m[4], $m[5], $m[6], $m[2], $m[3], $m[1]);
				$_name[$key] = $item['name'];
			}
		}

		if (!empty($haystack))
		{
			array_multisort($_time, SORT_ASC, SORT_NUMERIC, $_name, SORT_ASC, SORT_STRING, $haystack);
		}
		
		return $haystack;
	}
	
	private static function pjActionGetUpdates($update_folder='app/config/updates', $override_data=array())
	{
		if (!is_dir($update_folder))
		{
			return array();
		}

		$files = array();
		pjToolkit::readDir($files, $update_folder);
		
		$data = array();
		foreach ($files as $path)
		{
			$name = basename($path);
			if (preg_match('/(20\d\d)_(0[1-9]|1[012])_(0[1-9]|[12][0-9]|3[01])_([01][0-9]|[2][0-3])_([0-5][0-9])_([0-5][0-9]).sql$/', $name))
			{
				$data[] = array_merge(array(
					'name' => $name,
					'path' => $path
				), $override_data);
			}
		}

		return self::pjActionSortUpdates($data);
	}
	
	private static function pjActionExecuteSQL($dbo, $file_path, $prefix=PJ_PREFIX, $scriptPrefix=PJ_SCRIPT_PREFIX)
	{
		$name = basename($file_path);
				
		$pdb = self::pjActionImportSQL($dbo, $file_path, $prefix, $scriptPrefix);
		
		if ($pdb === false)
		{
			$text = sprintf("File '%s' not found (or can't be read).", $name);
			return array('status' => 'ERR', 'code' => 102, 'text' => $text);
		} elseif ($pdb === true) {
			$text = sprintf("File '%s' have been executed.", $name);
			return array('status' => 'OK', 'code' => 200, 'text' => $text);
		} else {
			$text = sprintf("File '%s': %s", $name, $pdb);
			return array('status' => 'ERR', 'code' => 103, 'text' => $text);
		}
	}
	
	public function pjActionSecureSetUpdate()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			# Next will init dbo
			pjAppModel::factory();
			
			$dbo = NULL;
			$registry = pjRegistry::getInstance();
			if ($registry->is('dbo'))
			{
				$dbo = $registry->get('dbo');
			}
			
			if (!isset($_REQUEST['module']))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Module parameter is missing.'));
			}
			
			if (isset($_POST['path']) && !empty($_POST['path']))
			{
			switch ($_REQUEST['module'])
			{
				case 'template':
						$pattern = defined('PJ_TEMPLATE_PATH') ? sprintf('|^%s(.*)/updates|', PJ_TEMPLATE_PATH) : '|^templates/(.*)/updates|';
					break;
				case 'plugin':
					$pattern = '|^'.str_replace('\\', '/', PJ_PLUGINS_PATH).'|';
					break;
				case 'script':
				default:
					$pattern = '|^app/config/updates|';
					break;
			}
			
				if (preg_match($pattern, str_replace('\\', '/', $_POST['path'])))
				{
					$response = self::pjActionExecuteSQL($dbo, $_POST['path']);
					if ($response['status'] == "OK")
					{
						$key = sprintf('o_%s_%s', basename($_POST['path']), md5($_POST['path']));
						$pjOptionModel = pjOptionModel::factory()
							->where('t1.foreign_id', $this->getForeignId())
							->where('t1.key', $key);
						if (0 != $pjOptionModel->findCount()->getData())
						{
							$pjOptionModel
								->reset()
								->where('foreign_id', $this->getForeignId())
								->where('`key`', $key)
								->modifyAll(array('value' => ':NOW()'));
						} else {
							$pjOptionModel
								->reset()
								->setAttributes(array(
									'foreign_id' => $this->getForeignId(),
									'key' => $key,
									'tab_id' => 99,
									'value' => ':NOW()',
									'type' => 'string'
								))
								->insert();
						}
					}
					pjAppController::jsonResponse($response);
				} else {
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Filename pattern doesn\'t match.'));
				}
			}
			
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				$pjOptionModel = pjOptionModel::factory();
				foreach ($_POST['record'] as $k => $record)
				{
					switch ($_REQUEST['module'][$k])
					{
						case 'template':
							$pattern = defined('PJ_TEMPLATE_PATH') ? sprintf('|^%s(.*)/updates|', PJ_TEMPLATE_PATH) : '|^templates/(.*)/updates|';
							break;
						case 'plugin':
							$pattern = '|^'.str_replace('\\', '/', PJ_PLUGINS_PATH).'|';
							break;
						case 'script':
						default:
							$pattern = '|^app/config/updates|';
							break;
					}
					
					if (!preg_match($pattern, str_replace('\\', '/', $record)))
					{
						continue;
					}
					$response = self::pjActionExecuteSQL($dbo, $record);
					if ($response['status'] == 'ERR')
					{
						pjAppController::jsonResponse($response);
					} elseif ($response['status'] == 'OK') {
						$key = sprintf('o_%s_%s', basename($record), md5($record));
						$pjOptionModel
							->reset()
							->where('t1.foreign_id', $this->getForeignId())
							->where('t1.key', $key);
						if (0 != $pjOptionModel->findCount()->getData())
						{
							$pjOptionModel
								->reset()
								->where('foreign_id', $this->getForeignId())
								->where('`key`', $key)
								->modifyAll(array('value' => ':NOW()'));
						} else {
							$pjOptionModel
								->reset()
								->setAttributes(array(
									'foreign_id' => $this->getForeignId(),
									'key' => $key,
									'tab_id' => 99,
									'value' => ':NOW()',
									'type' => 'string'
								))
								->insert();
						}
					}
				}
				
				pjAppController::jsonResponse($response);
			}
		}
		exit;
	}
	
	public function pjActionSecureGetUpdate()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			# Build data
			$data = self::pjActionBuildUpdates();
			
			# Sort data
			$data = self::pjActionSortUpdates($data);
			
			$keys = array();
			
			foreach ($data as &$item)
			{
				$item['base'] = base64_encode($item['path']);
				$keys[] = sprintf('o_%s_%s', $item['name'], md5($item['path']));
			}
			
			if (!empty($keys))
			{
				$options = pjOptionModel::factory()
					->select('t1.key, t1.value')
					->where('t1.foreign_id', $this->getForeignId())
					->whereIn('t1.key', $keys)
					->groupBy('t1.key')
					->findAll()
					->getDataPair('key', 'value');
				
				# Set some timezone just to prevent E_NOTICE/E_WARNING message
				date_default_timezone_set('America/Chicago');
				foreach ($data as &$item)
				{
					$index = sprintf('o_%s_%s', $item['name'], md5($item['path']));
					if (isset($options[$index]) && !empty($options[$index]))
					{
						$item['date'] = date("d/m/Y, H:i a", strtotime($options[$index]));
						$item['is_new'] = 0;
					} else {
						$item['date'] = "new DB update";
						$item['is_new'] = 1;
					}
				}
			}
			
			$total = count($data);
			$rowCount = $total;
			$pages = 1;
			$page = 1;
			$offset = 0;
						
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionSecureUpdate()
	{
		if ($this->isLoged() && $this->isAdmin())
		{
	    	$this->appendJs('jquery-ui.custom.min.js', PJ_THIRD_PARTY_PATH . 'jquery_ui/js/');
			$this->appendCss('jquery-ui.min.css', PJ_THIRD_PARTY_PATH . 'jquery_ui/css/smoothness/');
			$this->appendCss('pj-table.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
			
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjInstallerUpdate.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', "");
		} else {
			$this->set('status', 2);
		}
		
		$this->appendCss('secure.css', $this->getConst('PLUGIN_CSS_PATH'));
	}
	
	public function pjActionSecureView()
	{
		if (!($this->isLoged() && $this->isAdmin()))
		{
			exit('Not logged or hasn\'t permissions to view.');
		}
		if (!(isset($_GET['p']) && !empty($_GET['p'])))
		{
			exit('Missing, empty or invalid URL parameters.');
		}
		$path = base64_decode($_GET['p']);
		if ($path === FALSE)
		{
			exit('Data could not be decoded.');
		}
		if (!preg_match('/\.sql$/', $path))
		{
			exit('An .sql extension could not be found.');
		}
		$data = self::pjActionBuildUpdates();
		$in_array = FALSE;
		foreach ($data as $item)
		{
			if ($item['path'] == $path)
			{
				$in_array = TRUE;
				break;
			}
		}
		if (!$in_array)
		{
			exit('File not found in allowed list.');
		}
		if (!is_file($path))
		{
			exit('File not exists.');
		}

		$handle = fopen($path, 'rb');
		header("Content-Type: text/plain; charset=utf-8");
		while (!feof($handle))
		{
			$buffer = fread($handle, 4096);
			echo $buffer;
			ob_flush();
			flush();
		}
		fclose($handle);
		exit;			
	}
	
	private static function pjActionBuildUpdates()
	{
		# Script
		$data1 = self::pjActionGetUpdates('app/config/updates', array('module' => 'script', 'label' => 'script'));
			
		# Plugins
		$data2 = array();
		if (isset($GLOBALS['CONFIG']['plugins']))
		{
			if (!is_array($GLOBALS['CONFIG']['plugins']))
			{
				$GLOBALS['CONFIG']['plugins'] = array($GLOBALS['CONFIG']['plugins']);
			}
			foreach ($GLOBALS['CONFIG']['plugins'] as $plugin)
			{
				$data2 = array_merge($data2, self::pjActionGetUpdates(PJ_PLUGINS_PATH . $plugin . '/config/updates', array('module' => 'plugin', 'label' => 'plugin '.$plugin)));
			}
		}
								
		# Templates
		$data3 = array();
		if (defined('PJ_TEMPLATE_PATH'))
		{
			$data3 = self::pjActionGetUpdates(PJ_TEMPLATE_PATH, array('module' => 'template'));
			foreach ($data3 as &$item)
			{
				$item['label'] = basename(dirname(dirname($item['path'])));
			}
		}

		return array_merge($data1, $data2, $data3);
	}
	
	public function pjActionCheckCaptcha()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			echo isset($_SESSION[$this->defaultCaptcha], $_GET['captcha']) 
				&& pjCaptcha::validate($_GET['captcha'], $_SESSION[$this->defaultCaptcha]) ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionCaptcha()
	{
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
		
		header("Cache-Control: max-age=3600, private");
		
		$pjCaptcha = new pjCaptcha($this->getConst('PLUGIN_FONTS_PATH') . 'Anorexia.ttf', $this->defaultCaptcha, 6);
		$pjCaptcha
			->setImage($this->getConst('PLUGIN_IMG_PATH') . 'button.png')
			->init(@$_GET['rand']);
		exit;
	}
		
	public function pjActionChange()
	{
		if (self::pjActionCheckConfig(FALSE))
		{
			$this->set('status', 1);
			return;
		}
		
		require 'app/config/config.inc.php';
		
		$sessionVar = 'ChangeLogin';
		
		# Login processing
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_login']))
		{
			$time = time();
			
			# Form validation
			if (!(isset($_POST['email'], $_POST['license_key'], $_POST['captcha'], $_SESSION[$this->defaultCaptcha]) 
				&& !empty($_POST['email'])
				&& !empty($_POST['license_key'])
				&& !empty($_POST['captcha'])
				&& pjValidation::pjActionEmail($_POST['email'])
				&& pjCaptcha::validate($_POST['captcha'], $_SESSION[$this->defaultCaptcha])
			))
			{
				$_SESSION[$this->defaultErrors][$time] = array('status' => 'ERR', 'text' => 'Missing, empty or invalid form data.');
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
			}
			
			$endPoint = base64_decode("aHR0cDovL3N1cHBvcnQuc3RpdmFzb2Z0LmNvbS8=");
			
			$queryString = http_build_query(array(
				'controller' => 'Api',
				'action' => 'getInstallData',
				'key' => $_POST['license_key'],
				'email' => $_POST['email'],
				'script_id' => PJ_SCRIPT_ID,
				'version' => PJ_SCRIPT_VERSION,
				'server_name' => parse_url(PJ_INSTALL_URL, PHP_URL_HOST),
			));
			
			$http = new pjHttp();
			$response = $http
				->setMethod('GET')
				->request($endPoint . 'index.php?' . $queryString)
				->getResponse();
			
			if (!empty($response))
			{
				$result = self::pjActionJsonDecode($response);
			}
						
			if (empty($response) || !isset($result, $result['status'], $result['data']['hash']) || $result['status'] !== 'OK')
			{
				$_SESSION[$this->defaultErrors][$time] = array('status' => 'ERR', 'text' => 'Authorization failed (1).');
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
			}
				
			if (PJ_INSTALLATION != $result['data']['hash'])
			{
				$_SESSION[$this->defaultErrors][$time] = array('status' => 'ERR', 'text' => 'Authorization failed (2).');
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
			}
			
			$_SESSION[$sessionVar] = array(
				'email' => $_POST['email'],
				'license_key' => $_POST['license_key'],
				'login_string' => sha1($_POST['email'] . PJ_SALT . $_POST['license_key'])
			);
			
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange");
		}
		
		$isLoged = isset($_SESSION[$sessionVar], $_SESSION[$sessionVar]['email'], $_SESSION[$sessionVar]['license_key'], $_SESSION[$sessionVar]['login_string']) 
			&& sha1($_SESSION[$sessionVar]['email'] . PJ_SALT . $_SESSION[$sessionVar]['license_key']) == $_SESSION[$sessionVar]['login_string'];
		
		if (!$isLoged)
		{
			$this->set('status', 3);
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjInstaller.js', $this->getConst('PLUGIN_JS_PATH'));
			return;
		}
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$time = time();
			
			# Form validation
			$required = array('do_change', 'change_domain', 'change_db', 'change_paths', 'new_domain', 'license_key', 'hostname', 'username', 'password', 'database');
			foreach ($required as $index)
			{
				if (!isset($_POST[$index]))
				{
					$_SESSION[$this->defaultErrors][$time] = array('status' => 'ERR', 'text' => 'Missing form parameters.');
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
					break;
				}
			}

			$string = @file_get_contents('app/config/config.sample.php');
			$STORE = array();
			$isThereSmthngToChange = FALSE;
			$isNewInstall = FALSE;
			
			if ($_POST['change_domain'] == 1 && !empty($_POST['license_key']) && !empty($_POST['new_domain']))
			{
				$install_data = self::pjActionGetInstall($_POST['license_key'], $_POST['new_domain']);
				if ($install_data['status'] === 'OK')
				{
					$isThereSmthngToChange = TRUE;
					$isNewInstall = TRUE;
					$STORE['pj_installation'] = $install_data['result']['hash'];
				}
			} else {
				$STORE['pj_installation'] = PJ_INSTALLATION;
			}

			if ($_POST['change_db'] == 1 && !empty($_POST['hostname']) && !empty($_POST['username']) && !empty($_POST['database']))
			{
				$isThereSmthngToChange = TRUE;
				
				$STORE['hostname'] = $_POST['hostname'];
				$STORE['username'] = $_POST['username'];
				$STORE['password'] = $_POST['password'];
				$STORE['database'] = $_POST['database'];
			} else {
				$STORE['hostname'] = PJ_HOST;
				$STORE['username'] = PJ_USER;
				$STORE['password'] = PJ_PASS;
				$STORE['database'] = PJ_DB;
			}
			
			if ($_POST['change_paths'] == 1)
			{
				$isThereSmthngToChange = TRUE;
				
				# Get paths
				$paths = self::pjActionGetPaths();
				
				$STORE['install_folder'] = $paths['install_folder'];
				$STORE['install_path'] = $paths['install_path'];
				$STORE['install_url'] = $paths['install_url'];
			} else {
				$STORE['install_folder'] = PJ_INSTALL_FOLDER;
				$STORE['install_path'] = PJ_INSTALL_PATH;
				$STORE['install_url'] = PJ_INSTALL_URL;
			}
			
			if ($isThereSmthngToChange && $string !== FALSE)
			{
				$STORE['salt'] = PJ_SALT;
				$STORE['prefix'] = PJ_PREFIX;
				
				$string = self::pjActionReplaceConfigTokens($string, $STORE, FALSE);
				
				$filename = 'app/config/config.inc.php';
				if (is_writable($filename))
				{
					if (!$handle = @fopen($filename, 'wb'))
					{
						$_SESSION[$this->defaultErrors][$time] = array('status' => 'ERR', 'text' => "'app/config/config.inc.php' open fails");
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
					} else {
						if (fwrite($handle, $string) === FALSE)
						{
							$_SESSION[$this->defaultErrors][$time] = array('status' => 'ERR', 'text' => "An error occurs while writing to 'app/config/config.inc.php'");
							pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
						} else {
							fclose($handle);
							
							if ($isNewInstall)
							{
								self::pjActionNewInstall($_POST['license_key']);
							}
							
							$_SESSION[$this->defaultErrors][$time] = array('status' => 'OK', 'text' => "Installation has been changed successfully.");
							pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
						}
					}
				} else {
					$_SESSION[$this->defaultErrors][$time] = array('status' => 'ERR', 'text' => "'app/config/config.inc.php' do not exists or not writable");
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
				}
			}
			
			$_SESSION[$this->defaultErrors][$time] = array('status' => 'ERR', 'text' => 'There is nothing to change.');
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjInstaller&action=pjActionChange&err=" . $time);
		}

		if ($_SERVER['REQUEST_METHOD'] == 'GET')
		{
			$paths = self::pjActionGetPaths();

			$areTheSamePaths = ($paths['install_folder'] == PJ_INSTALL_FOLDER 
				&& $paths['install_url'] == PJ_INSTALL_URL 
				&& $paths['install_path'] == PJ_INSTALL_PATH);
			
			$this->set('areTheSamePaths', $areTheSamePaths);
			
			$this->set('domain', pjUtil::getDomain(PJ_INSTALL_URL));
			
			if (!$areTheSamePaths)
			{
				$this->set('paths', $paths);
			} else {
				$this->set('status', 2);
			}
			
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjInstaller.js', $this->getConst('PLUGIN_JS_PATH'));
		}
	}
	
	private static function pjActionNewInstall($license_key, $server_name=NULL)
	{
		if (empty($server_name))
		{
			$server_name = $_SERVER['SERVER_NAME'];
		}
	
		$http = new pjHttp();
		$http->request(base64_decode("aHR0cDovL3N1cHBvcnQuc3RpdmFzb2Z0LmNvbS8=") . 'index.php?controller=Api&action=newInstall'.
			'&key=' . urlencode($license_key) .
			"&version=". urlencode(PJ_SCRIPT_VERSION) .
			"&script_id=" . urlencode(PJ_SCRIPT_ID) .
			"&server_name=" . urlencode($server_name) .
			"&ip=" . urlencode($_SERVER['REMOTE_ADDR']) .
			"&referer=" . urlencode($_SERVER['HTTP_REFERER']));
		
		$response = $http->getResponse();
		$error = $http->getError();

		if ($response === FALSE || (!empty($error) && $error['code'] == 109))
		{
			return array('status' => 'ERR', 'code' => 109, 'text' => 'Installation key cannot be verified. Please, make sure you install on a server which is connected to the internet.');
		}
		
		$output = unserialize($response);
		
		if (isset($output['hash']) && isset($output['code']) && $output['code'] == 200)
		{
			return array('status' => 'OK', 'code' => 200, 'text' => 'Success.', 'hash' => $output['hash']);
		}
		
		if (isset($output['code']) && $output['code'] == 101)
		{
			return array('status' => 'ERR', 'code' => 101, 'text' => 'License key is not valid.');
		}
		
		if (isset($output['code']) && $output['code'] == 106)
		{
			return array('status' => 'ERR', 'code' => 106, 'text' => 'Number of installations allowed has been reached.');
		}
	
		return array('status' => 'ERR', 'code' => 100, 'text' => 'Key is wrong or not valid. Please check you data again.');
	}
	
	private static function pjActionGetInstall($license_key, $server_name=NULL)
	{
		if (empty($server_name))
		{
			$server_name = $_SERVER['SERVER_NAME'];
		}
		
		$http = new pjHttp();
		$http->request(base64_decode("aHR0cDovL3N1cHBvcnQuc3RpdmFzb2Z0LmNvbS8=") . 'index.php?controller=Api&action=getInstall'.
			"&key=" . urlencode($license_key) .
			"&server_name=" . urlencode($server_name));
		$response = $http->getResponse();
		if (empty($response))
		{
			return array('status' => 'ERR', 'code' => 100, 'text' => 'The returned response is empty.');
		}
	
		$output = unserialize($response);
		
		if (isset($output['hash']) && isset($output['code']) && $output['code'] == 200)
		{
			return array('status' => 'OK', 'code' => 200, 'text' => 'Success.', 'result' => array('hash' => $output['hash']));
		}
		
		return array('status' => 'ERR', 'code' => 104, 'text' => 'Security vulnerability detected.');
	}
	
	private static function pjActionOneInstall($license_key, $server_name=NULL)
	{
		if (empty($server_name) && isset($_SERVER['SERVER_NAME']))
		{
			$server_name = $_SERVER['SERVER_NAME'];
		}
		
		$endPoint = base64_decode("aHR0cDovL3N1cHBvcnQuc3RpdmFzb2Z0LmNvbS8=");
		
		$queryString = http_build_query(array(
			'controller' => 'Api',
			'action' => 'oneInstall',
			'key' => $license_key,
			'version' => PJ_SCRIPT_VERSION,
			'script_id' => PJ_SCRIPT_ID,
			'server_name' => $server_name,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'referer' => @$_SERVER['HTTP_REFERER'],
		));
		
		$http = new pjHttp();
		$response = $http
			->setMethod('GET')
			->request($endPoint . 'index.php?' . $queryString)
			->getResponse();
		
		$error = $http->getError();
		
		if (empty($response) || (!empty($error) && $error['code'] == 109))
		{
			return array('status' => 'ERR', 'code' => 109, 'text' => 'Installation key cannot be verified. Please, make sure you install on a server which is connected to the internet.');
		}
		
		$result = self::pjActionJsonDecode($response);
		
		if (isset($result['hash'], $result['private_key'], $result['status']) && $result['status'] === 'OK')
		{
			return array('status' => 'OK', 'code' => 200, 'text' => 'Success', 'hash' => $result['hash'], 'private_key' => $result['private_key']);
		}
		
		if (isset($result['status'], $result['code']) && $result['status'] === 'ERR')
		{
			switch ($result['code'])
			{
				case 101:
					return array('status' => 'ERR', 'code' => 101, 'text' => 'License key is not valid.');
					break;
				case 106:
					return array('status' => 'ERR', 'code' => 106, 'text' => 'Number of installations allowed has been reached.');
					break;
			}
		}
		
		return array('status' => 'ERR', 'code' => 100, 'text' => 'Key is wrong or not valid. Please check you data again.');
	}
	
	private static function pjActionReplaceConfigTokens($string, $data, $random_salt=TRUE)
	{
		if (isset($data['hostname']))
		{
			$string = str_replace('[hostname]', $data['hostname'], $string);
		}
		if (isset($data['username']))
		{
			$string = str_replace('[username]', $data['username'], $string);
		}
		if (isset($data['password']))
		{
			$string = str_replace('[password]', str_replace(
					array('\\', '$', '"'),
					array('\\\\', '\$', '\"'),
					$data['password']
			), $string);
		}
		if (isset($data['database']))
		{
			$string = str_replace('[database]', $data['database'], $string);
		}
		if (isset($data['prefix']))
		{
			$string = str_replace('[prefix]', $data['prefix'], $string);
		}
		if (isset($data['install_folder']))
		{
			$string = str_replace('[install_folder]', $data['install_folder'], $string);
		}
		if (isset($data['install_path']))
		{
			$string = str_replace('[install_path]', $data['install_path'], $string);
		}
		if (isset($data['install_url']))
		{
			$string = str_replace('[install_url]', $data['install_url'], $string);
		}
		if ($random_salt)
		{
			$string = str_replace('[salt]', pjUtil::getRandomPassword(8), $string);
		} else {
			if (isset($data['salt']))
			{
				$string = str_replace('[salt]', $data['salt'], $string);
			}
		}
		
		if (isset($data['pj_installation']))
		{
			$string = str_replace('[pj_installation]', $data['pj_installation'], $string);
		}
		
		return $string;
	}
	
	public function pjActionSecurePlugins()
	{
		if ($this->isLoged() && $this->isAdmin())
		{
			$this->appendJs('jquery-ui.custom.min.js', PJ_THIRD_PARTY_PATH . 'jquery_ui/js/');
			$this->appendCss('jquery-ui.min.css', PJ_THIRD_PARTY_PATH . 'jquery_ui/css/smoothness/');
			$this->appendCss('pj-table.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
				
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjInstallerPlugins.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', "");
		} else {
			$this->set('status', 2);
		}
	
		$this->appendCss('secure.css', $this->getConst('PLUGIN_CSS_PATH'));
	}
	
	public function pjActionSecureGetPlugins()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			$data = array();
			
			$plugins = $GLOBALS['CONFIG']['plugins'];
			if (!is_array($plugins))
			{
				$plugins = array($plugins);
			}
			
			$name = array();
			foreach ($plugins as $plugin)
			{
				$data[] = array(
					'name' => $plugin
				);
				
				$name[] = $plugin;
			}
			
			array_multisort($name, SORT_STRING, $data);
				
			$keys = array();
			
			foreach ($data as &$item)
			{
				$keys[] = sprintf('o_plugin_%s', $item['name']);
			}
			
			if (!empty($keys))
			{
				$options = pjOptionModel::factory()
					->select('t1.key, t1.value')
					->where('t1.foreign_id', $this->getForeignId())
					->whereIn('t1.key', $keys)
					->groupBy('t1.key')
					->findAll()
					->getDataPair('key', 'value');
	
				# Set some timezone just to prevent E_NOTICE/E_WARNING message
				date_default_timezone_set('America/Chicago');
				foreach ($data as &$item)
				{
					$index = sprintf('o_plugin_%s', $item['name']);
					if (isset($options[$index]) && !empty($options[$index]))
					{
						$item['date'] = date("d/m/Y, H:i a", strtotime($options[$index]));
						$item['is_new'] = 0;
					} else {
						$item['date'] = "new plugin";
						$item['is_new'] = 1;
					}
				}
			}
					
			$total = count($data);
			$rowCount = $total;
			$pages = 1;
			$page = 1;
			$offset = 0;
	
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionSecureInstallPlugin()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			# Next will init dbo
			pjAppModel::factory();
			
			$dbo = NULL;
			$registry = pjRegistry::getInstance();
			if ($registry->is('dbo'))
			{
				$dbo = $registry->get('dbo');
			}
			
			$pjOptionModel = pjOptionModel::factory();
			
			$result = $this->pjActionInstallPlugin($pjOptionModel, $dbo, basename($_POST['name']), PJ_PREFIX);
			
			pjAppController::jsonResponse($result);
		}
		exit;
	}
	
	private function pjActionInstallPlugin($pjOptionModel, $dbo, $plugin, $prefix, $dependencyCheck=TRUE)
	{
		# Dependency check
		if ($dependencyCheck)
		{
			$pjDependencyManager = new pjDependencyManager(PJ_THIRD_PARTY_PATH);
			
			$result = $pjDependencyManager
				->load(PJ_PLUGINS_PATH . $plugin . '/config/dependencies.php')
				->resolve()
				->getResult();
			
			if (in_array(FALSE, $result))
			{
				$text = 'Unresolved dependency check.';
				$dependencies = $pjDependencyManager->getDependencies();
				foreach ($result as $library => $value)
				{
					if (!$value)
					{
						$text .= sprintf('<br><span class="bold">%s</span> require <span class="bold">%s %s</span>', $plugin, $library, $dependencies[$library]);
					}
				}
				return array('status' => 'ERR', 'code' => 100, 'text' => $text);
			}
		}
		
		# Proceed to install
		$pjOptionModel->setPrefix($prefix);
		$statement = sprintf("INSERT IGNORE INTO `%s`(`foreign_id`,`key`,`tab_id`,`value`,`type`) VALUES (:foreign_id, :key, :tab_id, NOW(), :type);", $pjOptionModel->getTable());
		$data = array(
			'foreign_id' => $this->getForeignId(),
			'tab_id' => 99,
			'type' => 'string'
		);
		
		$file = PJ_PLUGINS_PATH . $plugin . '/config/database.sql';
		if (is_file($file))
		{
			$response = self::pjActionExecuteSQL($dbo, $file, $prefix, PJ_SCRIPT_PREFIX);
			if ($response['status'] == "ERR")
			{
				return $response;
			}
		
			$updates = self::pjActionGetUpdates(PJ_PLUGINS_PATH . $plugin . '/config/updates');
			foreach ($updates as $record)
			{
				$path = $record['path'];
				$response = self::pjActionExecuteSQL($dbo, $path, $prefix, PJ_SCRIPT_PREFIX);
				if ($response['status'] == "ERR")
				{
					return $response;
				} else if ($response['status'] == "OK") {
					$data['key'] = sprintf('o_%s_%s', basename($path), md5($path));
					$pjOptionModel->prepare($statement)->exec($data);
				}
			}
		}
		$modelName = pjObject::getConstant($plugin, 'PLUGIN_MODEL');
		if (class_exists($modelName) && method_exists($modelName, 'pjActionSetup'))
		{
			$pluginModel = new $modelName;
			$pluginModel->begin();
			$pluginModel->pjActionSetup();
			$pluginModel->commit();
		}
		
		$result = $this->requestAction(array(
			'controller' => $plugin,
			'action' => 'pjActionBeforeInstall'
		), array('return'));
			
		if ($result !== NULL && isset($result['code']) && $result['code'] != 200 && isset($result['info']))
		{
			return array('status' => 'ERR', 'code' => 104, 'text' => join("<br>", $result['info']));
		}
		
		$data['key'] = sprintf('o_plugin_%s', $plugin);
		$pjOptionModel->prepare($statement)->exec($data);
		
		return array('status' => 'OK', 'code' => 200, 'text' => 'Plugin has been installed');
	}
	
	private static function pjActionJsonDecode($value)
	{
		if (function_exists('json_decode'))
		{
			return json_decode($value, TRUE);
		}
		
		if (method_exists('pjAppController', 'jsonDecode'))
		{
			return pjAppController::jsonDecode($value);
		}
		
		return NULL;
	}
}
?>