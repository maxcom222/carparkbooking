<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
/**
 * PHP Framework
 *
 * @copyright Copyright 2016, StivaSoft, Ltd. (https://www.stivasoft.com)
 * @link      https://www.phpjabbers.com/
 * @package   framework
 * @version   1.5.2
 */
/**
 * The Singleton ensures that there can be only one instance of a Class and provides a global access point to that instance.
 *
 * Brief example of use:
 *
 * <code>
 * $pjMysqlDriver = pjSingleton::getInstance('pjMysqlDriver');
 * </code>
 *
 * @package framework
 * @since 1.0.0
 */
class pjSingleton
{
/**
 * The instances of the singleton
 *
 * @var array
 * @staticvar
 * @access private
 */
	private static $instances = array();
/**
 * Private constructor to prevent creating a new instance of the
 * Singleton via the new operator from outside of this class.
 *
 * @access private
 */
	private function __construct()
	{
		//Locked down the constructor, therefore the class cannot be externally instantiated
	}
/**
 * Private clone method to prevent cloning of the instance of the
 * Singleton instance.
 *
 * @access public
 * @return void
 */
	public function __clone()
	{
		trigger_error("Cannot clone instance of Singleton pattern", E_USER_ERROR);
	}
/**
 * Private unserialize method to prevent unserializing of the Singleton instance.
 *
 * @access public
 * @return void
 */
	public function __wakeup()
	{
		trigger_error("Cannot deserialize instance of Singleton pattern", E_USER_ERROR);
	}
/**
 * Returns the Singleton instance of this class.
 *
 * @param string Name of the class who will be created as singleton.
 * @param array Array with parameters which to pass to class constructor.
 * @access public
 * @static
 * @staticvar Singleton $instance The Singleton instances of this class.
 * @return Singleton The Singleton instance.
 */
	public static function getInstance($className, $params=array())
	{
		if (!is_array(self::$instances))
		{
			self::$instances = array();
		}
		
		if (!isset(self::$instances[$className]))
		{
			if (count($params) === 0)
			{
				self::$instances[$className] = new $className;
			} else {
				$reflector = new ReflectionClass($className);
				self::$instances[$className] = $reflector->newInstance($params);
			}
		}
		
		return self::$instances[$className];
	}
}
?>