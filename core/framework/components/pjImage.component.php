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
 * Image processing class
 *
 * @package framework.components
 * @since 1.0.0
 */
class pjImage extends pjUpload
{
/**
 * RGB color
 * 
 * @var array
 * @access private
 */
	private $color = array(255, 255, 255);
/**
 * Font path
 *
 * @var string
 * @access private
 */
	private $font;
/**
 * Font size
 *
 * @var int
 * @access private
 */
	private $fontSize;
/**
 * Image resource identifier
 *
 * @var resource
 * @access private
 */
	private $image;
/**
 * One of the IMAGETYPE_* constants indicating the type of the image.
 *
 * @var int
 * @access private
 */
	private $imageType;
/**
 * Image width (cache only)
 * @var int
 * @access private
 */	
	private $w;
/**
 * Image height (cache only)
 *
 * @var int
 * @access private
 */
	private $h;
/**
 * Image width
 * 
 * @var int
 * @access private
 */
	private $width;
/**
 * Image height
 *
 * @var int
 * @access private
 */
	private $height;
/**
 * Constructor - automatically called when you create a new instance of a class with new
 *
 * @access public
 * @return self
 */
	public function __construct()
	{
		if (!extension_loaded('gd') || !function_exists('gd_info'))
		{
			$this->error = "GD extension is not loaded";
			$this->errorCode = 200;
		}
	}
/**
 * Crop image
 *
 * @param number $src_x x-coordinate of source point.
 * @param number $src_y y-coordinate of source point.
 * @param number $dst_w Destination width.
 * @param number $dst_h Destination height.
 * @param number $src_w Source width.
 * @param number $src_h Source height.
 * @param number $dst_x x-coordinate of destination point.
 * @param number $dst_y y-coordinate of destination point.
 * @access public
 * @return self
 */
	public function crop($src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $dst_x = 0, $dst_y = 0)
	{
		$new_image = imagecreatetruecolor($dst_w, $dst_h);
		$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
		imagealphablending($new_image, false);
		imagesavealpha($new_image, true);
		imagefilledrectangle($new_image, 0, 0, $dst_w, $dst_h, $transparent);
		
		imagecopyresampled($new_image, $this->getImage(), $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		
		$rightX = $dst_w - $dst_x;
		$rightY = $dst_y;
		if ($this->w == $rightX && $dst_x == 0 && $dst_y == 0)
		{
			//do nothing
		} else {
			imagefilledrectangle($new_image, $rightX, 0, $dst_w, $dst_h, $transparent);
		}
		
		$bottomX = $dst_x;
		$bottomY = $dst_h - $dst_y;
		if ($this->h == $bottomY && $dst_x == 0 && $dst_y == 0)
		{
			//do nothing
		} else {
			imagefilledrectangle($new_image, $bottomX, $bottomY, $dst_w, $dst_h, $transparent);
		}

		$this->width = $dst_w;
		$this->height = $dst_h;
		$this->setImage($new_image);
		return $this;
	}
/**
 * Get color
 *
 * @access public
 * @return array
 */
	public function getColor()
	{
		return $this->color;
	}
/**
 * Get font
 * 
 * @access public
 * @return string
 */
	public function getFont()
	{
		return $this->font;
	}
/**
 * Get font size
 * 
 * @access public
 * @return number
 */
	public function getFontSize()
	{
		return $this->fontSize;
	}
/**
 * Get image height
 *
 * @access public
 * @return int|false Return the height of the image or FALSE on errors.
 */
	public function getHeight()
	{
		return imagesy($this->getImage());
	}
/**
 * Get image resource
 *
 * @access public
 * @return resource
 */
	public function getImage()
	{
		return $this->image;
	}
/**
 * Get the size of an image
 *
 * @access public
 * @return Returns an array with 7 elements.
 */
	public function getImageSize()
    {
    	return getimagesize($this->file['tmp_name']);
    }
/**
 * Get the type of image resource
 * 
 * @access public
 * @return number
 */
    public function getImageType()
    {
    	return $this->imageType;
    }
/**
 * Get image width
 *
 * @access public
 * @return int|false Return the width of the image or FALSE on errors.
 */
	public function getWidth()
	{
		return imagesx($this->getImage());
	}
/**
 * Check if system memory is enough for image processing
 *
 * @access public
 * @return array
 */
	public function isConvertPossible()
	{
		$status = true;
		if (function_exists('memory_get_usage') && ini_get('memory_limit'))
		{
			$info = $this->getImageSize();
			$MB = 1024 * 1024;
			$K64 = 64 * 1024;
			$tweak_factor = 1.6;
			$channels = isset($info['channels']) ? $info['channels'] : 3; // 3 for RGB pictures and 4 for CMYK pictures
			$memory_needed = round(($info[0] * $info[1] * $info['bits'] * $channels / 8 + $K64) * $tweak_factor);
			$memory_needed = memory_get_usage() + $memory_needed;
			$memory_limit = ini_get('memory_limit');
			if ($memory_limit != '')
			{
				$memory_limit = substr($memory_limit, 0, -1) * $MB;
			}
			if ($memory_needed > $memory_limit)
			{
				//$memory_needed = round($memory_needed / 1024 / 1024, 2);
				$status = false;
			}
		}
		return compact('status', 'memory_needed', 'memory_limit');
	}
/**
 * Load locale image file for later processing
 *
 * @param string $path The path to image
 * @access public
 * @return self
 */
	public function loadImage($path=NULL)
	{
		if (!is_null($path))
		{
			$this->file = array(
				'tmp_name' => $path,
				'name' => basename($path)
			);
		}
		$info = $this->getImageSize();
		$this->width = $info[0];
		$this->height = $info[1];
		$this->setImageType($info[2]);
		$file = $this->getFile('tmp_name');
		
		switch ($this->imageType)
		{
			case IMAGETYPE_JPEG:
				$this->setImage(@imagecreatefromjpeg($file));
				break;
			case IMAGETYPE_GIF:
				$this->setImage(@imagecreatefromgif($file));
				break;
			case IMAGETYPE_PNG:
				$this->setImage(@imagecreatefrompng($file));
				break;
		}
		return $this;
	}
/**
 * Image resize to fixed size
 *
 * @param int $width
 * @param int $height
 * @access public
 * @return self
 */
	public function resize($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		switch ($this->imageType)
		{
			case IMAGETYPE_PNG:
				imagealphablending($new_image, false);
				imagesavealpha($new_image, true);
				$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
				imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
				break;
			case IMAGETYPE_GIF:
				$transparent_index = imagecolortransparent($this->getImage());
				if ($transparent_index >= 0)
				{
		            $transparent_color = imagecolorsforindex($this->getImage(), $transparent_index);
		            $transparent_index = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
		            imagefill($new_image, 0, 0, $transparent_index);
		            imagecolortransparent($new_image, $transparent_index);
				}
				break;
		}
		imagecopyresampled($new_image, $this->getImage(), 0, 0, 0, 0, $width, $height, $this->width, $this->height);
		$this->width = $width;
		$this->height = $height;
		$this->setImage($new_image);
		
		return $this;
	}
/**
 * Image resize (smart one)
 *
 * @param int $width
 * @param int $height
 * @access public
 * @return self
 */
	public function resizeSmart($width, $height)
	{
		if (defined("PJ_IMAGE_USE_THUMBNAIL") && PJ_IMAGE_USE_THUMBNAIL === TRUE)
		{
			$this->thumbnail($width, $height);
		} else {
			$this->thumb($width, $height);
		}
		
		return $this;
	}
/**
 * Image resize to specific height
 *
 * @param int $height
 * @access public
 * @return self
 */
	public function resizeToHeight($height)
	{
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
		return $this;
	}
/**
 * Image resize to specific width
 *
 * @param int $width
 * @access public
 * @return self
 */
	public function resizeToWidth($width)
	{
		$ratio = $width / $this->getWidth();
		$height = $this->getHeight() * $ratio;
		$this->resize($width, $height);
		return $this;
	}
/**
 * Image rotate
 *
 * @param int $degrees
 * @access public
 * @return self
 */
	public function rotate($degrees=-90)
	{
		$new = imagerotate($this->getImage(), $degrees, 0);
		imagesavealpha($new, true);
		imagealphablending($new, true);
		
		$this->setImage($new);
		$this->width = $this->getWidth();
		$this->height = $this->getHeight();
		return $this;
	}
/**
 * Save image to local disk
 *
 * @param string $dst The path to save the file to.
 * @param int $image_type One of IMAGETYPE_* constants
 * @param int $compression Compression is optional, and ranges from 0 (worst quality, smaller file) to 100 (best quality, biggest file). The default is about 100.
 * @param int $permissions To ensure the expected operation, you need to prefix <var>permissions</var> with a zero (0)
 * @access public
 * @return self
 */
	public function saveImage($dst, $image_type=NULL, $compression=100, $permissions=null)
	{
		$image_type = is_null($image_type) ? $this->imageType : $image_type;
		switch ($image_type)
		{
			case IMAGETYPE_JPEG:
				imageinterlace($this->getImage(), true);
				imagejpeg($this->getImage(), $dst, round($compression));
				break;
			case IMAGETYPE_GIF:
				imagegif($this->getImage(), $dst);
				break;
			case IMAGETYPE_PNG:
				imagealphablending($this->getImage(), false);
				imagesavealpha($this->getImage(), true);
				imagepng($this->getImage(), $dst, round(9 * $compression / 100));
				break;
		}
		if ($permissions != null)
		{
			chmod($dst, $permissions);
		}
		return $this;
	}
/**
 * Write text to the image
 *
 * @param string $text The text string in UTF-8 encoding.
 * @param string $position Accept: 'tl', 'tr', 'tc', 'bl', 'br', 'bc', 'cl', 'cr', 'cc'. <b>t</b> stands for Top, <b>b</b> stands for Bottom, <b>l</b> stands for Left, <b>r</b> stands for Right, <b>c</b> stands for Center.
 * @access public
 * @return self
 */
	public function setWatermark($text, $position)
	{
		$rgb = $this->getColor();
		
		$color = imagecolorallocate($this->getImage(), $rgb[0], $rgb[1], $rgb[2]);
		// Simple text
		//$font = imageloadfont($this->getFont());
		//imagestring($this->getImage(), $font, 0, $this->getHeight() / 2, $text, $color);
		//imagestring($this->getImage(), 5, 0, $this->getHeight() / 2, $text, $color);
		
		$tb = imagettfbbox($this->getFontSize(), 0, $this->getFont(), $text);
		
		switch ($position)
		{
			case 'tl':
				$x = $tb[0];
				$y = $this->getFontSize();
				break;
			case 'tr':
				$x = floor($this->getWidth() - $tb[2]);
				$y = $this->getFontSize();
				break;
			case 'tc':
				$x = ceil(($this->getWidth() - $tb[2]) / 2);
				$y = $this->getFontSize();
				break;
			case 'bl':
				$x = $tb[0];
				$y = floor($this->getHeight() - $this->getFontSize());
				break;
			case 'br':
				$x = floor($this->getWidth() - $tb[2]);
				$y = floor($this->getHeight() - $this->getFontSize());
				break;
			case 'bc':
				$x = ceil(($this->getWidth() - $tb[2]) / 2);
				$y = floor($this->getHeight() - $this->getFontSize());
				break;
			case 'cl':
				$x = $tb[0];
				$y = ceil($this->getHeight() / 2);
				break;
			case 'cr':
				$x = floor($this->getWidth() - $tb[2]);
				$y = ceil($this->getHeight() / 2);
				break;
			case 'cc':
			default:
				$x = ceil(($this->getWidth() - $tb[2]) / 2);
				$y = ceil($this->getHeight() / 2);
				break;
		}
		imagettftext($this->getImage(), $this->getFontSize(), 0, $x, $y, $color, $this->getFont(), $text);
		return $this;
	}
/**
 * Set font path
 *
 * @param string $path The path to font file
 * @access public
 * @return self
 */
	public function setFont($path)
	{
		$this->font = $path;
		return $this;
	}
/**
 * Set font size
 *
 * @param int $size
 * @access public
 * @return self
 */
	public function setFontSize($size)
	{
		$this->fontSize = $size;
		return $this;
	}
/**
 * Set RGB color
 *
 * @param array $color Expect numeric array, eg. array(255, 255, 255)
 * @access public
 * @return self
 */
	public function setColor($color)
	{
		if (is_array($color) && count($color) === 3)
		{
			$this->color = $color;
		}
		return $this;
	}
/**
 * Set image resource
 * 
 * @param resource $resource
 * @access public
 * @return self
 */
	public function setImage($resource)
	{
		if (is_resource($resource))
		{
			$this->image = $resource;
		}
		return $this;
	}
/**
 * Set the type of image resource
 *  
 * @param int $value
 * @return self
 */
	public function setImageType($value)
	{
		if (is_int($value))
		{
			$this->imageType = $value;
		}
		return $this;
	}
/**
 * Outputs image without saving
 * 
 * @param string $image_type
 * @param number $compression
 */	
	public function output($image_type=IMAGETYPE_JPEG, $compression=100)
	{
		switch ($image_type)
		{
			case IMAGETYPE_JPEG:
				header("Content-Type: image/jpeg");
				imageinterlace($this->getImage(), true);
				imagejpeg($this->getImage(), NULL, $compression);
				break;
			case IMAGETYPE_GIF:
				header("Content-Type: image/gif");
				imagegif($this->getImage());
				break;
			case IMAGETYPE_PNG:
				header("Content-Type: image/png");
				imagepng($this->getImage());
				break;
		}
		exit;
	}
	
	public function thumb($width, $height)
	{
		$this->w = $width;
		$this->h = $height;
		
		$h = $this->getHeight();
		$w = $this->getWidth();
		
		$src_ratio = $w / $h;
		$dst_ratio = $width / $height;
		
		$dst_x = abs($width - $w) / 2;
		$dst_y = abs($height - $h) / 2;
		
		$dst_x = $width > $w ? $dst_x : 0;
		$dst_y = $height > $h ? $dst_y : 0;
			
		if ($w == $h)
		{
			if ($width > $height)
			{
				$this->resizeToWidth($width > $w ? $w : $width);
			} else {
				$this->resizeToHeight($height > $h ? $h : $height);
			}
				
			$h = $this->getHeight();
			$w = $this->getWidth();
				
			$dst_x = abs($width - $w) / 2;
			$dst_y = abs($height - $h) / 2;
				
			$dst_x = $width > $w ? $dst_x : 0;
			$dst_y = $height > $h ? $dst_y : 0;
				
			// Crop from center
			$index = 1;
			$x = max(0, round($w / 2) - round(ceil($width * $index) / 2));
			$y = max(0, round($h / 2) - round(ceil($height * $index) / 2));
		
			$this->crop($x, $y, $width, $height, $width, $height, $dst_x, $dst_y);
			return $this;
		}
		
		if ($w < $h)
		{
			# Uploaded (or given one) image is vertical [300 x 400]
			if ($height > $h)
			{
				# Needed image is bigger than uploaded (or given one) [400 x 600], [800 x 600]
				if ($width < $height)
				{
					# We want to get vertical image [400 x 600]
					//$this->crop(0, 0, $width, $height, $width, $height);
					$this->crop(0, 0, $width, $height, $width > $w ? $width : $w, $height > $h ? $height : $h, $dst_x, $dst_y);
				} else {
					# We want to get horizontal image [800 x 600]
					//$this->crop(0, 0, $width, $height, $width, $height);
					$this->crop(0, 0, $width, $height, $width > $w ? $width : $w, $height > $h ? $height : $h, $dst_x, $dst_y);
				}
				return $this;
			} else {
				# Needed image is smaller than uploaded (or given one) [90 x 68], [75 x 110]
				if ($width < $height)
				{
					# We want to get vertical image [75 x 110]
					$index = $h / $height;
				} else {
					# We want to get horizontal image [90 x 68]
					$index = $w / $width;
				}
				// Crop from center
				$x = max(0, round($w / 2) - round(ceil($width * $index) / 2));
				$y = max(0, round($h / 2) - round(ceil($height * $index) / 2));
		
				$this->crop($x, $y, ceil($width * $index), ceil($height * $index), ceil($width * $index), ceil($height * $index));
				$this->resize($width, $height);
				return $this;
			}
		} else {
			# Uploaded (or given one) image is horizontal [600 x 400]
			if ($width > $w)
			{
				# Needed image is bigger than uploaded (or given one) [768 x 1024], [800 x 600]
				if ($width < $height)
				{
					# We want to get vertical image [768 x 1024]
					//$this->crop(0, 0, $width, $height, $width, $height);
					$this->crop(0, 0, $width, $height, $width, $height, $dst_x, $dst_y);
				} else {
					# We want to get horizontal image [800 x 600]
					$this->crop(0, 0, $width, $height, $width, $height, $dst_x, $dst_y);
					//$this->crop(0, 0, $width, $height, $width, $height);
				}
				return $this;
			} else {
				# Needed image is smaller than uploaded (or given one) [90 x 68], [75 x 110]
				if ($width == $height)
				{
					$this->resizeToWidth($width > $w ? $w : $width);
						
					$dst_x = abs($width - $this->getWidth()) / 2;
					$dst_y = abs($height - $this->getHeight()) / 2;
						
					//$dst_x = $width > $w ? $dst_x : 0;
					//$dst_y = $height > $h ? $dst_y : 0;
						
					$this->crop(0, 0, $width, $height, $width, $height, $dst_x, $dst_y);
					return $this;
				}
		
				# -----------------
				if ($src_ratio > $dst_ratio)
				{
					$this->resizeToHeight($height);
				} else {
					$this->resizeToWidth($width);
				}
				$w = $this->getWidth();
				$h = $this->getHeight();
				$index = $dst_ratio < 0 ? $w / $width : $h / $height;
					
				$dst_x = 0;
				$dst_y = 0;
				# ------------------------
		
				/*if ($width < $height)
				{
					# We want to get vertical image [75 x 110]
					$index = $w / $width;
				} else {
					# We want to get horizontal image [90 x 68]
					$index = $h / $height;
				}
				// Crop from center
				$dst_x = (ceil($width * $index) - $w) / 2;
				$dst_y = (ceil($height * $index) - $h) / 2;
		
				if ($dst_x < 0)
				{
					$dst_x = 0;
				}
				if ($dst_y < 0)
				{
					$dst_y = 0;
				}
		
				//$dst_x = $width > $w ? $dst_x : 0;
				//$dst_y = $height > $h ? $dst_y : 0;
				*/
			 	$src_x = max(0, round($w / 2) - round(ceil($width * $index) / 2));
			 	$src_y = max(0, round($h / 2) - round(ceil($height * $index) / 2));
		
			 	$dst_w = ceil($width * $index);
			 	$dst_h = ceil($height * $index);
			 	$src_w = ceil($width * $index);
				$src_h = ceil($height * $index);
		
				//$this->crop($src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $dst_x, $dst_y);
				$this->crop($src_x, $src_y, $width, $height, $width, $height, $dst_x, $dst_y);
				$this->resize($width, $height);
				return $this;
			}
		}
		
		return $this;
	}
/**
 * Thumbnail
 *
 * This function attempts to get the image to as close to the provided dimensions as possible, and then crops the
 * remaining overflow (from the center) to get the image to be the size specified. Useful for generating thumbnails.
 *
 * @param int $width
 * @param int|null $height If omitted - assumed equal to $width
 * @return pjImage
 */
	public function thumbnail($width, $height=null)
	{
		$height = $height ?$height: $width;
	
		$current_aspect_ratio = $this->height / $this->width;
		$new_aspect_ratio = $height / $width;
	
		if ($new_aspect_ratio > $current_aspect_ratio)
		{
			$this->fitToHeight($height);
		} else {
			$this->fitToWidth($width);
		}
		$left = floor(($this->width / 2) - ($width / 2));
		$top = floor(($this->height / 2) - ($height / 2));
	
		$this->cropImage($left, $top, $width + $left, $height + $top);
		return $this;
	}
/**
 * Fit to height (proportionally resize to specified height)
 * 
 * @param int $height
 * @return pjImage
 */
	public function fitToHeight($height)
	{
		$aspect_ratio = $this->height / $this->width;
		$width = $height / $aspect_ratio;
	
		$this->resize($width, $height);
		return $this;
	}
/**
 * Fit to width (proportionally resize to specified width)
 * 
 * @param int $width
 * @return pjImage
 */
	public function fitToWidth($width)
	{
		$aspect_ratio = $this->height / $this->width;
		$height = $width * $aspect_ratio;
	
		$this->resize($width, $height);
		return $this;
	}
/**
 * Crop an image
 * 
 * @param int $x1 Left
 * @param int $y1 Top
 * @param int $x2 Right
 * @param int $y2 Bottom
 * @return pjImage
 */
	public function cropImage($x1, $y1, $x2, $y2)
	{
		if ($x2 < $x1)
		{
			list($x1, $x2) = array($x2, $x1);
		}
		if ($y2 < $y1)
		{
			list($y1, $y2) = array($y2, $y1);
		}
		$crop_width = $x2 - $x1;
		$crop_height = $y2 - $y1;
	
		$new = imagecreatetruecolor($crop_width, $crop_height);
		imagealphablending($new, false);
		imagesavealpha($new, true);
		imagecopyresampled($new, $this->getImage(), 0, 0, $x1, $y1, $crop_width, $crop_height, $crop_width, $crop_height);
	
		$this->width = $crop_width;
		$this->height = $crop_height;
		$this->setImage($new);
	
		return $this;
	}
}
?>