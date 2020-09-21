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
 * CSV data mapper
 *
 * @package framework.components
 *
 */
class pjCSV
{
/**
 * Delimiter
 *
 * @var string
 * @access private
 */
	private $delimiter = ",";
/**
 * End of line
 *
 * @var string
 * @access private
 */
	private $eol = "\n";
/**
 * Enclose character
 *
 * @var string
 * @access private
 */
	private $enclose = '"';
/**
 * Escape character
 *
 * @var string
 * @access private
 */
	private $escape = '"';
/**
 * Flag indicates whether to include column names in the first row
 *
 * @var boolean
 * @access private
 */
	private $header = false;
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
 * Column names
 *
 * @var array
 * @access private
 */
	private $fields = array();
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
		$this->name = time() . ".csv";
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
			throw new Exception('pjCSV::$fp is not valid stream resource.', 101);
		}
		
		if (ob_get_level())
		{
			ob_end_clean();
		}
		
		pjToolkit::sendDownloadHeaders($this->name, 0, 'text/csv');
		
		fpassthru($this->fp);
		fclose($this->fp);
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
 * Make data CSV-ready
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
		
		$i = 0;
		foreach ($data as $item)
		{
			$str = "";
			if ($i === 0 && $this->header)
			{
				$str .= join($this->delimiter, array_keys($item));
			}
			if ($i > 0 || $i === 0 && $this->header)
			{
				$str .= $this->eol;
			}
			$ii = 0;
			foreach ($item as $value)
			{
				if ($ii > 0)
				{
					$str .= $this->delimiter;
				}
				$str .= $this->enclose . preg_replace('/'.$this->enclose.'/', $this->escape . $this->enclose, $value) . $this->enclose;
				$ii++;
			}
			$i++;
			
			fwrite($this->fp, $str);
		}
		
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
 * Upload and parse CSV file
 *
 * @param array $file
 * @access public
 * @return boolean
 */
	public function load($file)
	{
		$pjUpload = new pjUpload();
		$pjUpload->setAllowedExt(array('csv'));

		$data = array();
		if ($pjUpload->load($file))
		{
			$filename = $pjUpload->getFile('tmp_name');
			$i = 1;
			if (($handle = fopen($filename, "r")) !== FALSE)
			{
				while (($values = fgetcsv($handle, 1000, ",")) !== FALSE)
				{
					if ($i == 1)
					{
						$keys = $values;
					} else {
						$data[] = array_combine($keys, $values);
					}
					$i++;
				}
				fclose($handle);
				$this->data = $data;
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
		if (is_array($this->data) && count($this->data) > 0)
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
 * Set delimiter
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setDelimiter($value)
	{
		$this->delimiter = $value;
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
 * Set enclose character
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setEnclose($value)
	{
		$this->enclose = $value;
		return $this;
	}
/**
 * Set escape character
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setEscape($value)
	{
		$this->escape = $value;
		return $this;
	}
/**
 * Set flag which indicated whether to include column names in the first row
 *
 * @param boolean $value
 * @access public
 * @return self
 */
	public function setHeader($value)
	{
		$this->header = (bool) $value;
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
 * Set column names
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