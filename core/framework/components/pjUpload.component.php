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
 * @package   framework.components
 * @version   1.5.2
 */
/**
 * Upload class
 *
 * @package framework.components
 * @since 1.0.0
 */
class pjUpload
{
/**
 * Uploaded file, eg. $_FILE['image']
 *
 * @var array
 * @access protected
 */
	protected $file;
/**
 * Error text
 *
 * @var string
 * @access protected
 */
	protected $error;
/**
 * Error number
 *
 * @var int
 * @access protected
 */
	protected $errorCode;
/**
 * Allowed mime types. Use '*' to allow all.
 *
 * @var array
 * @access private
 */
	private $allowedTypes = array('*'); # image/gif, image/png, image/jpeg, image/jpg, image/pjpeg
/**
 * Allowed file extensions. Use '*' to allow all.
 *
 * @var array
 * @access private
 */
	private $allowedExt = array('*'); # pdf, doc, png, txt, gif
/**
 * Constructor - automatically called when you create a new instance of a class with new
 *
 * @access public
 * @return self
 */
	public function __construct()
	{
		
	}
/**
 * Load file
 *
 * @param array $file The uploaded file via HTTP POST
 * @access public
 * @return boolean
 */
	public function load($file)
	{
		$this->error = NULL;
		$this->errorCode = NULL;

		if (is_array($file) && array_key_exists('tmp_name', $file) && !empty($file['tmp_name']) &&
			is_uploaded_file($file['tmp_name']) && $file['error'] == UPLOAD_ERR_OK)
		{
			$this->file = $file;
			
			$ext = $this->getExtension();
			if (in_array($ext, $this->allowedExt) || in_array('*', $this->allowedExt))
			{
				//ok
			} else {
				$this->error = "File extension not supported. Supported file formats: " . join(", ", $this->allowedExt);
				$this->errorCode = "101";
			}
			if (!in_array('*', $this->allowedTypes))
			{
				if (function_exists('finfo_open') && function_exists('finfo_file') && function_exists('finfo_close'))
				{
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					if ($finfo !== FALSE && is_resource($finfo))
					{
						$mime_type = finfo_file($finfo, $file['tmp_name']);
						finfo_close($finfo);
					}
				} elseif (function_exists('mime_content_type')) {
					$mime_type = mime_content_type($file['tmp_name']);
				}
				if (!isset($mime_type) || $mime_type === FALSE || empty($mime_type))
				{
					$mime_type = array_key_exists('type', $file) ? $file['type'] : NULL;
				}
				
				if (!in_array($mime_type, $this->allowedTypes))
				{
					$this->error = "Mime type not supported. Supported mime types: " . join(", ", $this->allowedTypes);
					$this->errorCode = "102";
				}
			}
		} else {
			switch ($file['error'])
			{
				case UPLOAD_ERR_INI_SIZE:
					$this->error = sprintf("The uploaded file exceeds %s", ini_get('upload_max_filesize'));
					$this->errorCode = "121";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$this->error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
					$this->errorCode = "122";
					break;
				case UPLOAD_ERR_PARTIAL:
					$this->error = "The uploaded file was only partially uploaded.";
					$this->errorCode = "123";
					break;
				case UPLOAD_ERR_NO_FILE:
					$this->error = "No file was uploaded.";
					$this->errorCode = "124";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$this->error = "Missing a temporary folder.";
					$this->errorCode = "126";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$this->error = "Failed to write file to disk.";
					$this->errorCode = "127";
					break;
				case UPLOAD_ERR_EXTENSION:
					$this->error = "A PHP extension stopped the file upload.";
					$this->errorCode = "128";
					break;
				default:
					$this->error = "The file is empty or wasn't uploaded via HTTP POST";
					$this->errorCode = "100";
			}
		}
		
		if (empty($this->error))
		{
			return true;
		}
		return false;
	}
/**
 * Store file to filesystem
 *
 * @param string $destination
 * @access public
 * @return boolean
 */
	public function save($destination)
	{
		if (!move_uploaded_file($this->file['tmp_name'], $destination))
		{
			$this->error = $this->file['name'] . " is not a valid upload file or cannot be moved for some reason.";
			return false;
		}
		return true;
	}
/**
 * Get error text, if any
 *
 * @access public
 * @return string
 */
	public function getError()
	{
		return $this->error;
	}
/**
 * Get error code, if any
 *
 * @access public
 * @return int
 */
	public function getErrorCode()
	{
		return $this->errorCode;
	}
/**
 * Get file extension
 *
 * @access public
 * @return string
 */
	public function getExtension()
    {
    	$arr = explode('.', $this->file['name']);
        $ext = strtolower($arr[count($arr) - 1]);
        return $ext;
    }
/**
 * Gets the size of the file in bytes
 *
 * @access public
 * @return int|false
 */
    public function getSize()
    {
    	return filesize($this->file['tmp_name']);
    }
/**
 * Get value of the file array by given key
 *
 * @param string $key
 * @access public
 * @return string
 */
    public function getFile($key)
    {
    	return $this->file[$key];
    }
/**
 * Set allowed mime types
 *
 * @param array $value Array with mime types, eg. 'image/png', 'image/gif', 'application/pdf'
 * @access public
 * @return self
 */
	public function setAllowedTypes($value)
	{
		if (is_array($value))
		{
			$this->allowedTypes = $value;
		}
		return $this;
	}
/**
 * Set allowed file extensions
 *
 * @param array $value Array with file extensions, eg. 'pdf', 'doc', 'zip'
 * @access public
 * @return self
 */
	public function setAllowedExt($value)
	{
		if (is_array($value))
		{
			$this->allowedExt = $value;
		}
		return $this;
	}
}
?>