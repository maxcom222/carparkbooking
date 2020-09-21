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
 * Captcha class
 *
 * Brief example of use:
 *
 * <code>
 * //How to initialize and display captcha image
 * $pjCaptcha = new pjCaptcha(PJ_WEB_PATH . 'obj/Lato-Bol.ttf', $this->defaultCaptcha, 6);
 * $pjCaptcha
 *     ->setImage(PJ_IMG_PATH . 'button.png')
 *     ->init(@$_GET['rand']);
 *
 * //How to change default mode
 * $pjCaptcha = new pjCaptcha(PJ_WEB_PATH . 'obj/Lato-Bol.ttf', $this->defaultCaptcha);
 * $pjCaptcha
 *     ->setImage(PJ_IMG_PATH . 'button.png')
 *     ->setMode('addition')
 *     ->init(@$_GET['rand']);
 *
 * //How to check if user input value is correct
 * if (pjCaptcha::validate($_GET['captcha'], $_SESSION[$this->defaultCaptcha]))
 * {
 *     //It's OK
 * }
 * </code>
 *
 * @package   framework.components
 *
 */
class pjCaptcha
{
/**
 * Font path
 *
 * @var string
 * @access private
 */
	private $font = null;
/**
 * Font size
 *
 * @var int
 * @access private
 */
	private $fontSize = 12;
/**
 * Captcha image height
 *
 * @var int
 * @access private
 */
	private $height = 35;
/**
 * Captcha background image
 *
 * @var string
 * @access private
 */
	private $image = null;
/**
 * Text length (Applicable only if mode is set to 'string')
 *
 * @var int
 * @access private
 */
	private $length = null;
/**
 * Captcha mode specify 'algorithm' or what kind of string to display on image
 *
 * @var string Possible values are: 'string', 'addition', 'subtraction', 'random_math' (random between 'addition' and 'subtraction')
 * @access private
 */
	private $mode = 'string';
/**
 * Name of session variable
 *
 * @var string
 * @access private
 */
	private $sessionVariable = null;
/**
 * Captcha image height
 *
 * @var int
 * @access private
 */
	private $width = 79;
/**
 * Constructor - automatically called when you create a new instance of a class with new
 *
 * @param string $fontPath
 * @param string $sessionVariable
 * @param int $length Applicable only if mode is set to 'string'
 * @access public
 * @return self
 */
	public function __construct($fontPath, $sessionVariable, $length = 4)
	{
		$this->font = $fontPath;
		$this->sessionVariable = $sessionVariable;
		$this->length = intval($length);
	}
/**
 * Output captcha image
 *
 * @param mixed $renew
 * @access public
 * @return void
 */
	public function init($renew=null)
	{
    	if (!is_null($renew))
    	{
    		$_SESSION[$this->sessionVariable] = NULL;
    	}

		if (empty($_SESSION[$this->sessionVariable]))
		{
			switch ($this->mode)
			{
				case 'addition':
					$first = rand(1, 99);
					$second = $first > 9 ? rand(1, 9) : rand(10, 99);
					$operator = '+';
					$stack = array($first, $operator, $second);
					$_SESSION[$this->sessionVariable] = join(",", $stack);
					$rand_code = join(" ", $stack) . " = ?";
					break;
				case 'subtraction':
					$first = rand(10, 99);
					$second = rand(1, 9);
					$operator = '-';
					$stack = array($first, $operator, $second);
					$_SESSION[$this->sessionVariable] = join(",", $stack);
					$rand_code = join(" ", $stack) . " = ?";
					break;
				case 'random_math':
					$operators = array('+', '-');
					$operator = $operators[rand(0, 1)];
					if ($operator == '+')
					{
						$first = rand(1, 99);
						$second = $first > 9 ? rand(1, 9) : rand(10, 99);
					} else {
						$first = rand(10, 99);
						$second = rand(1, 9);
					}
					$stack = array($first, $operator, $second);
					$_SESSION[$this->sessionVariable] = join(",", $stack);
					$rand_code = join(" ", $stack) . " = ?";
					break;
				case 'string':
				default:
					$str = "";
					$length = 0;
					for ($i = 0; $i < $this->length; $i++)
					{
						//this numbers refer to numbers of the ascii table (small-caps)
						// 97 - 122 (small-caps)
						// 65 - 90 (all-caps)
						// 48 - 57 (digits 0-9)
						$str .= chr(rand(65, 90));
					}
					$_SESSION[$this->sessionVariable] = $str;
					$rand_code = $_SESSION[$this->sessionVariable];
					break;
			}
		} else {
			$rand_code = $_SESSION[$this->sessionVariable];
		}

		if (!is_null($this->image))
		{
			$image = imagecreatefrompng($this->image);
		} else {
			$image = imagecreatetruecolor($this->width, $this->height);
			
			$backgr_col = imagecolorallocate($image, 204, 204, 204);
			$border_col = imagecolorallocate($image, 153, 153, 153);
			
			imagefilledrectangle($image, 0, 0, $this->width, $this->height, $backgr_col);
			imagerectangle($image, 0, 0, $this->width - 1, $this->height - 1, $border_col);
		}
		
		$text_col = imagecolorallocate($image, 68, 68, 68);

		$angle = rand(-10, 10);
		$box = imagettfbbox($this->fontSize, $angle, $this->font, $rand_code);
		$x = (int)($this->width - $box[4]) / 2;
		$y = (int)($this->height - $box[5]) / 2;
		imagettftext($image, $this->fontSize, $angle, $x, $y, $text_col, $this->font, $rand_code);
		
		header("Content-type: image/png");
		imagepng($image);
		imagedestroy ($image);
	}
/**
 * Check user input against session value
 *
 * @param string $userValue
 * @param string $sessionValue
 * @static
 * @access public
 * @return boolean Returns <b>TRUE</b> on success, and <b>FALSE</b> on failure.
 */
	static public function validate($userValue, $sessionValue)
	{
		$expr = explode(",", $sessionValue);
		if (count($expr) !== 3)
		{
			return strtoupper($userValue) == $sessionValue;
		}
		
		switch ($expr[1])
		{
			case "-":
				return (int) $userValue === (int) $expr[0] - (int) $expr[2];
				break;
			case "+":
				return (int) $userValue === (int) $expr[0] + (int) $expr[2];
				break;
			default:
				return false;
		}
	}
/**
 * Set font path
 *
 * @param string $fontPath
 * @access public
 * @return self
 */
	public function setFont($fontPath)
	{
		$this->font = $fontPath;
		return $this;
	}
/**
 * Set text length
 *
 * @param int $length
 * @access public
 * @return self
 */
	public function setLength($length)
	{
		if ((int) $length > 0)
		{
			$this->length = intval($length);
		}
		return $this;
	}
/**
 * Set name of session variable
 *
 * @param string $sessionVariable
 * @access public
 * @return self
 */
	public function setSessionVariable($sessionVariable)
	{
		$this->sessionVariable = $sessionVariable;
		return $this;
	}
/**
 * Set captcha height
 *
 * @param int $height
 * @access public
 * @return self
 */
	public function setHeight($height)
	{
		if ((int) $height > 0)
		{
			$this->height = intval($height);
		}
		return $this;
	}
/**
 * Set captcha width
 *
 * @param int $width
 * @access public
 * @return self
 */
	public function setWidth($width)
	{
		if ((int) $width > 0)
		{
			$this->width = intval($width);
		}
		return $this;
	}
/**
 * Set font size
 *
 * @param int $fontSize
 * @access public
 * @return self
 */
	public function setFontSize($fontSize)
	{
		if ((int) $fontSize > 0)
		{
			$this->fontSize = intval($fontSize);
		}
		return $this;
	}
/**
 * Set captcha image background
 *
 * @param string $image Path to background image
 * @access public
 * @return self
 */
	public function setImage($image)
	{
		$this->image = $image;
		return $this;
	}
/**
 * Set captcha mode
 *
 * @param string $value Possible values are: 'string', 'addition', 'subtraction', 'random_math' (random between 'addition' and 'subtraction')
 * @access public
 * @return self
 */
	public function setMode($value)
	{
		if (in_array($value, array('string', 'addition', 'subtraction', 'random_math')))
		{
			$this->mode = $value;
		}
		return $this;
	}
}
?>