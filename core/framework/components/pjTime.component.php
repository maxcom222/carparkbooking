<?php
/**
 * PHP Framework
 *
 * @copyright Copyright 2016, StivaSoft, Ltd. (https://www.stivasoft.com)
 * @link      https://www.phpjabbers.com/
 * @package   framework.components
 * @version   1.5.2
 */
/**
 * Time class
 *
 * @package framework.components
 * @since 1.0.1
 */
class pjTime
{
/**
 * Attributes
 *
 * @var array
 * @access private
 */
	private $attr = array(); //name, id, class, etc.
/**
 * Properties
 *
 * @var array
 * @access private
 */
	private $prop = array(); //selected, format, emptyValue, emptyTitle, left, right, start, end, skip, step
/**
 * The Factory pattern allows for the instantiation of objects at runtime.
 *
 * @param array $attr
 * @static
 * @access public
 * @return self
 */
	public static function factory($attr=array())
	{
		return new pjTime($attr);
	}
/**
 * Generate HTML SELECT element filled with days. Properties and attributes are applied first.
 *
 * @access public
 * @return string
 */
	public function day()
	{
		$html = sprintf('<select name="%s" id="%s" class="%s">', $this->attr('name'), $this->attr('id'), $this->attr('class'));
		if (isset($this->prop['emptyTitle']) && isset($this->prop['emptyValue']))
		{
			$html .= sprintf('<option value="%s">%s</option>', $this->prop('emptyValue'), $this->prop('emptyTitle'));
		}
		foreach (range(1, 31) as $v)
		{
			if (strlen($v) == 1)
			{
				$v = '0' . $v;
			}
			$html .= sprintf('<option value="%s"%s>%s</option>', $v, (!is_null($this->prop('selected')) && $v == $this->prop('selected') ? ' selected="selected"' : NULL), $v);
		}
		$html .= '</select>';
		
		return $html;
	}
/**
 * Generate HTML SELECT element filled with months. Properties and attributes are applied first.
 *
 * @access public
 * @return string
 */
	public function month()
	{
		$html = sprintf('<select name="%s" id="%s" class="%s">', $this->attr('name'), $this->attr('id'), $this->attr('class'));
		if (isset($this->prop['emptyTitle']) && isset($this->prop['emptyValue']))
		{
			$html .= sprintf('<option value="%s">%s</option>', $this->prop('emptyValue'), $this->prop('emptyTitle'));
		}
		$format = !is_null($this->prop('format')) && in_array($this->prop('format'), array('F', 'm', 'M', 'n')) ? $this->prop('format') : "m";
		
		foreach (range(1, 12) as $v)
		{
			if (strlen($v) == 1)
			{
				$v = '0' . $v;
			}
			$html .= sprintf('<option value="%s"%s>%s</option>', $v, (!is_null($this->prop('selected')) && $v == $this->prop('selected') ? ' selected="selected"' : NULL), date($format, mktime(0, 0, 0, $v, 1, 2000)));
		}
		$html .= '</select>';
		
		return $html;
	}
/**
 * Generate HTML SELECT element filled with years. Properties and attributes are applied first.
 *
 * @access public
 * @return string
 */
	public function year()
	{
		$html = sprintf('<select name="%s" id="%s" class="%s">', $this->attr('name'), $this->attr('id'), $this->attr('class'));
		if (isset($this->prop['emptyTitle']) && isset($this->prop['emptyValue']))
		{
			$html .= sprintf('<option value="%s">%s</option>', $this->prop('emptyValue'), $this->prop('emptyTitle'));
		}
		$curr_year = date("Y");
			
		foreach (range($curr_year - (int) $this->prop('left'), $curr_year + 1 + (int) $this->prop('right')) as $v)
		{
			$html .= sprintf('<option value="%s"%s>%s</option>', $v, (!is_null($this->prop('selected')) && $v == $this->prop('selected') ? ' selected="selected"' : NULL), $v);
		}
		$html .= '</select>';
		
		return $html;
	}
/**
 * Generate HTML SELECT element filled with hours. Properties and attributes are applied first.
 *
 * @access public
 * @return string
 */
	public function hour()
	{
		$opts = array(
			'start' => !is_null($this->prop('start')) ? $this->prop('start') : 0,
			'end' => !is_null($this->prop('end')) ? $this->prop('end') : 23,
			'skip' => !is_null($this->prop('skip')) ? $this->prop('skip') : array()
		);
		
		$attributes = NULL;
		foreach ($this->attr as $k => $v)
		{
			if (!in_array($k, array('name', 'id', 'class')))
			{
				$attributes .= sprintf(' %s="%s"', $k, $v);
			}
		}
		$html = sprintf('<select name="%s" id="%s" class="%s"%s>', $this->attr('name'), $this->attr('id'), $this->attr('class'), $attributes);
		foreach (range($opts['start'], $opts['end']) as $v)
		{
			if (in_array($v, $opts['skip'])) continue;
			
			if (strlen($v) == 1)
			{
				$v = '0' . $v;
			}
			$html .= sprintf('<option value="%s"%s>%s</option>', $v, (!is_null($this->prop('selected')) && $v == $this->prop('selected') ? ' selected="selected"' : NULL), $v);
		}
		$html .= '</select>';
		
		return $html;
	}
/**
 * Generate HTML SELECT element filled with minutes. Properties and attributes are applied first.
 *
 * @access public
 * @return string
 */
	public function minute()
	{
		$opts = array(
			'start' => !is_null($this->prop('start')) ? $this->prop('start') : 0,
			'end' => !is_null($this->prop('end')) ? $this->prop('end') : 59,
			'skip' => !is_null($this->prop('skip')) ? $this->prop('skip') : array(),
			'step' => !is_null($this->prop('step')) ? $this->prop('step') : 1
		);
		
		$attributes = NULL;
		foreach ($this->attr as $k => $v)
		{
			if (!in_array($k, array('name', 'id', 'class')))
			{
				$attributes .= sprintf(' %s="%s"', $k, $v);
			}
		}
		$html = sprintf('<select name="%s" id="%s" class="%s"%s>', $this->attr('name'), $this->attr('id'), $this->attr('class'), $attributes);
		foreach (range($opts['start'], $opts['end']) as $v)
		{
			if (is_array($opts['skip']) && in_array($v, $opts['skip'])) continue;
			
			if (!is_null($this->prop('step')) && $this->prop('step') > 0 && $v % $this->prop('step') !== 0)
			{
				continue;
			}
			if (strlen($v) == 1)
			{
				$v = '0' . $v;
			}
			$html .= sprintf('<option value="%s"%s>%s</option>', $v, (!is_null($this->prop('selected')) && $v == $this->prop('selected') ? ' selected="selected"' : NULL), $v);
		}
		$html .= '</select>';
		
		return $html;
	}
/**
 * Get or set attribute
 *
 * @param string $name
 * @param string $value
 * @access public
 * @return self
 */
	public function attr($name, $value=NULL)
	{
		if (func_num_args() === 1)
		{
			//Get
			return isset($this->attr[$name]) ? $this->attr[$name] : NULL;
		}
		//Set
		$this->attr[$name] = $value;
		return $this;
	}
/**
 * Get or set property
 *
 * @param string $name
 * @param string $value
 * @access public
 * @return self
 */
	public function prop($name, $value=NULL)
	{
		if (func_num_args() === 1)
		{
			//Get
			return isset($this->prop[$name]) ? $this->prop[$name] : NULL;
		}
		//Set
		$this->prop[$name] = $value;
		return $this;
	}
/**
 * Reset properties and attributes
 *
 * @access public
 * @return self
 */
	public function reset()
	{
		$this->attr = array();
		$this->prop = array();
		
		return $this;
	}
}
?>