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
 * XML data mapper
 *
 * @package framework.components
 */
class pjXML
{
/**
 * Version
 *
 * @var string
 * @access private
 */
	private $version = "1.0";
/**
 * End of line
 *
 * @var string
 * @access private
 */
	private $eol = "\n";
/**
 * Encoding
 *
 * @var string
 * @access private
 */
	private $encoding = "UTF-8";
/**
 * Data
 *
 * @var array
 * @access private
 */
	private $data = NULL;
/**
 * File name
 *
 * @var string
 * @access private
 */
	private $name = NULL;
/**
 * Item node name
 *
 * @var string
 * @access private
 */
	private $record = 'item';
/**
 * Root node name
 *
 * @var string
 * @access private
 */
	private $root = 'items';
/**
 * Fields
 *
 * @var array
 * @access private
 */
	private $fields = array();
/**
 * Content type
 *
 * @var string
 * @access private
 */
	private $mimeType = "text/xml";
/**
 * A stream resource (e.g. returned from fopen())
 *
 * @var resource
 * @access private
 */
	private $fp;
/**
 * I/O stream
 *
 * @var string
 * @access private
 * @example php://output, php://memory, php://temp
 */
	private $io_stream = 'php://temp/maxmemory:0';
/**
 * Constructor - automatically called when you create a new instance of a class with new
 *
 * @access public
 * @return self
 */
	public function __construct()
	{
		$this->name = time() . ".xml";
	}
/**
 * Destructor - The destructor will be called even if script execution is stopped using exit().
 */
	public function __destruct()
	{
		if (is_resource($this->fp))
		{
			fclose($this->fp);
		}
	}
/**
 * Force browser to download the data as file
 *
 * @access public
 * @return void
 * @throws Exception
 */
	public function download()
	{
		if (!is_resource($this->fp))
		{
			throw new Exception('pjXML::$fp is not valid stream resource.', 101);
		}
		
		if (ob_get_level())
		{
			ob_end_clean();
		}
		
		pjToolkit::sendDownloadHeaders($this->name, 0, $this->mimeType);
		
		fpassthru($this->fp);
		fclose($this->fp);
	}
/**
 * Make data XML-ready
 *
 * @param array $data
 * @access public
 * @return self
 */
	public function process($data=array())
	{
		if (!$this->fp = @fopen($this->io_stream, 'wb'))
		{
			throw new Exception("Can't open $this->io_stream", 100);
		}
		$str = '<?xml version="'.$this->version.'" encoding="'.$this->encoding.'"?>';
		$str .= $this->eol . '<' . $this->root . '>';
		fwrite($this->fp, $str);
		foreach ($data as $item)
		{
			$str = $this->eol . "\t<" . $this->record . ">";
			foreach ($item as $key => $value)
			{
				$str .= $this->eol . "\t\t<" . $key . ">" . pjSanitize::html($value) . "</" . $key . ">";
			}
			$str .= $this->eol . "\t</" . $this->record . ">";
			fwrite($this->fp, $str);
		}
		$str = $this->eol . "</" . $this->root . ">";
		fwrite($this->fp, $str);
		rewind($this->fp);
		
		return $this;
	}
/**
 * Write data to a file
 *
 * @access public
 * @return self
 */
	public function write()
	{
		file_put_contents($this->name, $this->getData());
		return $this;
	}
/**
 * Upload and parse XML file
 *
 * @param array $file
 * @access public
 * @return boolean
 */
	public function load($file)
	{
		$pjUpload = new pjUpload();
		$pjUpload->setAllowedExt(array('xml'));

		$data = array();
		if ($pjUpload->load($file))
		{
			$filename = $pjUpload->getFile('tmp_name');
			if (function_exists('simplexml_load_file'))
			{
				$xml = simplexml_load_file($filename);
				
				$xml = (array) $xml;
				$xml = array_values($xml);
				foreach ($xml[0] as $item)
				{
					$item = (array) $item;
					foreach ($item as $k => $v)
					{
						$item[$k] = strval($v);
					}
					$data[] = $item;
				}
				
				$this->setData($data);
				return true;
			}
		}
		return false;
	}
/**
 * Import data to given model. Runs SQL INSERT queries
 *
 * @param string $modelName
 * @access public
 * @return self
 */
	public function import($modelName)
	{
		if (is_array($this->data) && !empty($this->data))
		{
			$modelName .= 'Model';
			$model = new $modelName;
			if (is_object($model))
			{
				$model->begin();
				foreach ($this->data as $data)
				{
					if (count($this->fields) > 0)
					{
						foreach ($data as $k => $v)
						{
							if (!array_key_exists($k, $this->fields))
							{
								unset($data[$k]);
							}
						}
					}
					$model->reset()->setAttributes($data)->insert();
				}
				$model->commit();
			}
		}
		
		return $this;
	}
/**
 * Get data
 *
 * @access public
 * @return array
 */
	public function getData()
	{
		if (is_resource($this->fp))
		{
			return stream_get_contents($this->fp);
		}
		
		return $this->data;
	}
/**
 * Set data
 *
 * @param array $value
 * @access public
 * @return self
 */
	public function setData($value)
	{
		$this->data = $value;
		return $this;
	}
/**
 * Set version
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setVersion($value)
	{
		$this->version = $value;
		return $this;
	}
/**
 * Set end of line
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setEol($value)
	{
		$this->eol = $value;
		return $this;
	}
/**
 * Set encoding
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setEncoding($value)
	{
		$this->encoding = $value;
		return $this;
	}
/**
 * Set file name
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setName($value)
	{
		$this->name = $value;
		return $this;
	}
/**
 * Set root node
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setRoot($value)
	{
		$this->root = $value;
		return $this;
	}
/**
 * Set item node
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setRecord($value)
	{
		$this->record = $value;
		return $this;
	}
/**
 * Set conten type
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setMimeType($value)
	{
		$this->mimeType = $value;
		return $this;
	}
/**
 * Set fields
 *
 * @param array $value
 * @access public
 * @return self
 */
	public function setFields($value)
	{
		if (is_array($value))
		{
			$this->fields = $value;
		}
		return $this;
	}
}
?>