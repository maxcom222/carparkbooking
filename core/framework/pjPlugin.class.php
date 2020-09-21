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
 * Plugin's ancestor class
 *
 * @package framework
 * @since 1.0.0
 */
class pjPlugin extends pjAppController
{
/**
 * Invoked after script install
 *
 * Returns array with indexes:
 * - `code` Any code that differ than 200 are treated as an error and will cause stop the process
 * - `info` Array with text describing the errors
 *
 * Example:
 * <code>
 * return array('code' => 200, 'info' => array());
 * //or
 * return array('code' => 101, 'info' => array('The folder need write permissions', 'File not found', '...'));
 * </code>
 *
 * @todo add 'status' index with values 'OK' and 'ERR' and switch to using them instead of 'code' parameter
 * @access public
 * @return array
 */
	public function pjActionAfterInstall()
	{
		
	}
/**
 * Invoked before script install
 *
 * Returns array with indexes:
 * - `code` Any code that differ than 200 are treated as an error and will cause stop the process
 * - `info` Array with text describing the errors
 *
 * Example:
 * <code>
 * return array('code' => 200, 'info' => array());
 * //or
 * return array('code' => 101, 'info' => array('The folder need write permissions', 'File not found', '...'));
 * </code>
 *
 * @todo add 'status' index with values 'OK' and 'ERR' and switch to using them instead of 'code' parameter
 * @access public
 * @return array
 */
	public function pjActionBeforeInstall()
	{
		
	}
/**
 * Invoked on the begining of the install process. Used to perform check for file/folder permissions
 *
 * Returns array with indexes:
 * - `status` String 'OK' or 'ERR'
 * - `code` Integer Used to differentiate the types of status
 * - `text` String Textual representation of the code
 * - `info` Array with items
 *
 * Example:
 * <code>
 * return array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());
 * //or
 * return array('status' => 'ERR', 'code' => 101, 'text' => 'Operation fails', 'info' => array('Something is wrong', 'Folder <span class="bold">app/web/upload/</span> need write permissions.'));
 * </code>
 *
 * @access public
 * @return array
 */
	public function pjActionCheckInstall()
	{
		
	}
}
?>