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
 * iCal data mapper
 *
 * @package framework.components
 */
class pjICal
{
/**
 * Version
 *
 * @var string
 * @access private
 */
	private $version = "2.0";
/**
 * End of line
 *
 * @var string
 * @access private
 */
	private $eol = "\n";
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
 * proid
 *
 * @var string
 * @access private
 */
	private $prodid = 'Calendar';
/**
 * uiid
 *
 * @var string
 * @access private
 */
	private $uuid = 'uuid';
/**
 * created
 *
 * @var string
 * @access private
 */
	private $created = 'created';
/**
 * modified
 *
 * @var string
 * @access private
 */
	private $modified = 'modified';
/**
 * date_from
 *
 * @var string
 * @access private
 */
	private $date_from = 'date_from';
/**
 * date_to
 *
 * @var string
 * @access private
 */
	private $date_to = 'date_to';
/**
 * summary
 *
 * @var string
 * @access private
 */
	private $summary = 'c_name';
/**
 * c_name
 *
 * @var string
 * @access private
 */
	private $c_name = 'c_name';
/**
 * calendar
 *
 * @var string
 * @access private
 */
	private $location = 'location';
/**
 * timezone
 *
 * @var string
 * @access private
 */
	private $timezone = 'UTC/GMT';
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
	private $mimeType = "text/calendar";
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
		$this->name = time() . ".ics";
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
			throw new Exception('pjICal::$fp is not valid stream resource.', 101);
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
 * Make data iCal-ready
 *
 * @param array $data
 * @access public
 * @return self
 * @throws Exception
 */
	public function process($data=array())
	{
		if (!$this->fp = @fopen($this->io_stream, 'wb'))
		{
			throw new Exception("Can't open $this->io_stream", 100);
		}
	
		$str = "BEGIN:VCALENDAR".$this->eol;
		$str .= $this->version.$this->eol;
		$str .= "PRODID:-//".$this->prodid."//NONSGML Foobar//EN".$this->eol;
		$str .= "METHOD:UPDATE".$this->eol;
		fwrite($this->fp, $str);
	
		$time = time();
	
		foreach ($data as $item)
		{
			$modified = NULL;
			$created = NULL;
			$str = "BEGIN:VEVENT".$this->eol;
			$str .= "UID:".$item[$this->uuid].$this->eol;
			foreach ($item as $key => $value)
			{
				if ($key == $this->modified)
				{
					$modified = "SEQUENCE:" . (!empty($value)
						? strtotime($value)
						: (isset($item[$this->created]) && !empty($item[$this->created]) ? strtotime($item[$this->created]) : $time));
				}
				if ($key == $this->created)
				{
					$created = "SEQUENCE:" . (!empty($value) ? strtotime($value) : $time);
				}
				if ($key == $this->date_from)
				{
					$str .= "DTSTAMP:".date('Ymd',strtotime($value))."T".date('His',strtotime($value)).$this->eol;
					if (strpos($value,':') !== false)
					{
						$str .= "DTSTART;TZID=".$this->timezone.":".date('Ymd',strtotime($value))."T".date('His',strtotime($value)).$this->eol;
					} else {
						$str .= "DTSTART;TZID=".$this->timezone.":".date('Ymd',strtotime($value))."T000000".$this->eol;
					}
				}
				if ($key == $this->date_to)
				{
					if (strpos($value,':') !== false)
					{
						$str .= "DTEND;TZID=".$this->timezone.":".date('Ymd',strtotime($value))."T".date('His',strtotime($value)).$this->eol;
					} else {
						$str .= "DTEND;TZID=".$this->timezone.":".date('Ymd',strtotime($value))."T235959".$this->eol;
					}
				}
			}
			if (!empty($modified))
			{
				$str .= $modified.$this->eol;
			} elseif (!empty($created)) {
				$str .= $created.$this->eol;
			}
			$str .= "SUMMARY:" . stripslashes($item[$this->summary]).$this->eol;
			$str .= "DESCRIPTION: Name: ".stripslashes($item[$this->c_name]).$this->eol;
			$str .= "LOCATION:" . stripslashes($item[$this->location]).$this->eol;
			$str .= "END:VEVENT".$this->eol;
				
			fwrite($this->fp, $str);
		}
		$str = "END:VCALENDAR";
		fwrite($this->fp, $str);
		rewind($this->fp);
	
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
		$this->fp = @fopen($this->io_stream, 'wb');
		if ($this->fp === FALSE)
		{
			throw new Exception("Can't open $this->io_stream", 100);
		}

		fwrite($this->fp, $value);
		rewind($this->fp);
		
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
 * Set prodid
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setProdID($value)
	{
		$this->prodid = $value;
		return $this;
	}
/**
 * Set uuid
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setUUID($value)
	{
		$this->uuid = $value;
		return $this;
	}
/**
 * Set created
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setCreated($value)
	{
		$this->created = $value;
		return $this;
	}
/**
 * Set modified
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setModified($value)
	{
		$this->modified = $value;
		return $this;
	}
/**
 * Set date_from
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setDateFrom($value)
	{
		$this->date_from = $value;
		return $this;
	}
/**
 * Set date_to
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setDateTo($value)
	{
		$this->date_to = $value;
		return $this;
	}
/**
 * Set summary
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setSummary($value)
	{
		$this->summary = $value;
		return $this;
	}
/**
 * Set c_name
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setCName($value)
	{
		$this->c_name = $value;
		return $this;
	}
/**
 * Set location
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setLocation($value)
	{
		$this->location = $value;
		return $this;
	}
/**
 * Set timezone
 *
 * @param string $value
 * @access public
 * @return self
 */
	public function setTimezone($value)
	{
		$this->timezone = $value;
		return $this;
	}
}
?>