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
 * Multibyte class
 *
 * @package framework.components
 *
 */
class pjMultibyte
{
/**
 * Default encoding
 *
 * @var string
 * @staticvar
 */
	static $hab_enc = 'UTF-8';
/**
 * Multibyte library in use
 *
 * @var int
 * @access private
 * @staticvar
 */
	private static $use_library = 1; //mbstring
/**
 * Private constructor to prevent it being created directly
 *
 * @access private
 */
	private function __construct()
	{
		
	}
	
	private static function useMbstring()
	{
		return extension_loaded('mbstring');
	}
/**
 *
 *
 * @param string $use_enc
 * @static
 * @access public
 * @return string
 */
	public static function hab_encoding($use_enc = null)
	{
		if ( $use_enc === null ) {
			return self::$hab_enc;
		}
		else {
			$old_enc = self::$hab_enc;
			self::$hab_enc = $use_enc;
			return $old_enc;
		}
	}
/**
 *
 *
 * @param mixed $new_library
 * @static
 * @access public
 * @return mixed
 */
	public static function library($new_library = null)
	{
		if ( $new_library === null ) {
			return self::$use_library;
		}
		elseif ( $new_library === self::useMbstring() ) {
			$old_library = self::$use_library;
			self::$use_library = $new_library;
			return $old_library;
		}
		elseif ( $new_library === false ) {
			$old_library = self::$use_library;
			self::$use_library = $new_library;
			return $old_library;
		}
		else {
			return false;
		}
	}
/**
 * Convert character encoding
 *
 * Converts the character encoding of string str to to_encoding from optionally from_encoding.
 *
 * @param string $str The string being encoded.
 * @param string $use_enc The type of encoding that <var>str</var> is being converted to.
 * @param mixed $from_enc Is specified by character code names before conversion. It is either an array, or a comma separated enumerated list. If <var>from_enc</var> is not specified, the internal encoding will be used.
 * @static
 * @access public
 * @return string The encoded string.
 */
	public static function convert_encoding($str, $use_enc = null, $from_enc = null)
	{
		$ret = false;
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			if ( $from_enc == null ) {
				$from_enc = pjMultibyte::detect_encoding($str);
			}
			$ret = mb_convert_encoding($str, $enc, $from_enc);
		}
		return $ret;
	}
/**
 * Detect character encoding
 *
 * Detects character encoding in string <var>str</var>.
 *
 * @param string $str The string being detected.
 * @static
 * @access public
 * @return string|false The detected character encoding or <b>FALSE</b> if the encoding cannot be detected from the given string.
 */
	public static function detect_encoding($str)
	{
		$enc = false;
		if ( self::$use_library == self::useMbstring() ) {
			// get original detection order
			$old_order = mb_detect_order();
			// make sure  ISO-8859-1 is included
			mb_detect_order(array('ASCII', 'JIS', 'UTF-8', 'ISO-8859-1', 'EUC-JP', 'SJIS'));
			//detect the encoding . the detected encoding may be wrong, but it's better than guessing
			$enc = mb_detect_encoding($str);
			// reset detection order
			mb_detect_order($old_order);
		}
		return $enc;
	}
/**
 *
 *
 * @param unknown_type $source_contents
 * @static
 * @access public
 * @return string
 */
	public static function detect_bom_encoding($source_contents)
	{
		$ret = false;
		if ( "\xFE\xFF" == substr(0, 2, $source_contents) ) {
			$ret = 'UTF-16BE';
		}
		else if ( "\xFF\xFE" == substr(0, 2, $source_contents) ) {
			$ret = 'UTF-16LE';
		}
		else if ( "\xEF\xBB\xBF" == substr(0, 3, $source_contents) ) {
			$ret = 'UTF-8';
		}
		return $ret;
	}
/**
 * Get part of string
 *
 * Performs a multi-byte safe <b>substr</b> operation based on number of
 * characters. Position is counted from the beginning of <var>str</var>. First
 * character's position is 0. Second character position is 1, and so on.
 *
 * @param string $str The string to extract the substring from.
 * @param int $begin Position of first character to use from <var>str</var>.
 * @param int $len Maximum number of characters to use from <var>str</var>.
 * @param string $use_enc The <var>use_enc</var> parameter is the character encoding. If it is omitted, the internal character encoding value will be used.
 * @static
 * @access public
 * @return string Returns the portion of <var>str</var> specified by the <var>begin</var> and <var>len</var> parameters.
 */
	public static function substr($str, $begin, $len = null, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			if ( !isset($len) ) {
				$len = pjMultibyte::strlen($str) - $begin;
			}
			$ret = mb_substr($str, $begin, $len, $enc);
		}
		else {
			$ret = substr($str, $begin, $len);
		}
		return $ret;
	}
/**
 * Get string length
 *
 * Gets the length of a string.
 *
 * @param string $str The string being checked for length.
 * @param string $use_enc The <var>use_enc</var> parameter is the character encoding. If it is omitted, the internal character encoding value will be used.
 * @static
 * @access public
 * @return int Returns the number of characters in string <var>str</var> having character encoding <var>use_enc</var>. A multi-byte character is counted as 1
 */
	public static function strlen($str, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			$len = mb_strlen($str, $enc);
		}
		else {
			$len = strlen($str);
		}
		return $len;
	}
/**
 * Find the position of the first occurrence of a substring in a string
 *
 * Find the numeric position of the first occurrence of <var>needle</var> in the <var>haystack</var> string.
 *
 * @param unknown_type $haysack The string to search in.
 * @param unknown_type $needle If <var>needle</var> is not a string, it is converted to an integer and applied as the ordinal value of a character.
 * @param unknown_type $offset If specified, search will start this number of characters counted from the beginning of the string. The offset cannot be negative.
 * @param unknown_type $use_enc The expected encoding.
 * @static
 * @access public
 * @return int|false Returns the position of where the needle exists relative to the beginning of the <var>haystack</var> string (independent of offset). Also note that string positions start at 0, and not 1. Returns <b>FALSE</b> if the needle was not found.
 */
	public static function strpos($haysack, $needle, $offset = 0, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			$ret = mb_strpos($haysack, $needle, $offset, $enc);
		}
		else {
			$ret = strpos($haysack, $needle, $offset);
		}
		return $ret;
	}
/**
 * Find the position of the first occurrence of a case-insensitive substring in a string
 *
 * @param string $haysack The string to search in.
 * @param string $needle Note that the <var>needle</var> may be a string of one or more characters. If <var>needle</var> is not a string, it is converted to an integer and applied as the ordinal value of a character.
 * @param int $offset If specified, search will start this number of characters counted from the beginning of the string. Unlike strrpos() and strripos(), the offset cannot be negative.
 * @param string $use_enc The expected encoding.
 * @static
 * @access public
 * @return int|false Returns the position of where the needle exists relative to the beginnning of the <var>haystack</var> string (independent of offset). Also note that string positions start at 0, and not 1. Returns <b>FALSE</b> if the needle was not found.
 */
	public static function stripos($haysack, $needle, $offset = 0, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			$ret = mb_stripos($haysack, $needle, $offset, $enc);
		}
		else {
			$ret = stripos($haysack, $needle, $offset);
		}
		return $ret;
	}
/**
 * Find the position of the last occurrence of a substring in a string
 *
 * @param string $haysack The string to search in.
 * @param string $needle If <var>needle</var> is not a string, it is converted to an integer and applied as the ordinal value of a character.
 * @param int $offset If specified, search will start this number of characters counted from the beginning of the string. If the value is negative, search will instead start from that many characters from the end of the string, searching backwards.
 * @param string $use_enc The expected encoding.
 * @static
 * @access public
 * @return int|false Returns the position where the needle exists relative to the beginnning of the <var>haystack</var> string (independent of search direction or offset). Also note that string positions start at 0, and not 1. Returns <b>FALSE</b> if the needle was not found.
 */
	public static function strrpos($haysack, $needle, $offset = 0, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			$ret = mb_strrpos($haysack, $needle, $offset, $enc);
		}
		else {
			$ret = strrpos($haysack, $needle, $offset);
		}
		return $ret;
	}
/**
 * Find the position of the last occurrence of a case-insensitive substring in a string
 *
 * @param string $haysack The string to search in.
 * @param string $needle If <var>needle</var> is not a string, it is converted to an integer and applied as the ordinal value of a character.
 * @param int $offset If specified, search will start this number of characters counted from the beginning of the string. If the value is negative, search will instead start from that many characters from the end of the string, searching backwards.
 * @param string $use_enc The expected encoding.
 * @static
 * @access public
 * @return string
 */
	public static function strripos($haysack, $needle, $offset = 0, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			$ret = mb_strripos($haysack, $needle, $offset, $enc);
		}
		else {
			$ret = strripos($haysack, $needle, $offset);
		}
		return $ret;
	}
/**
 * Make a string lowercase
 *
 * @param string $str The string being lowercased.
 * @param string $use_enc The <var>use_enc</var> parameter is the character encoding. If it is omitted, the internal character encoding value will be used.
 * @static
 * @access public
 * @return string <var>str</var> with all alphabetic characters converted to lowercase.
 */
	public static function strtolower($str, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			$ret = mb_strtolower($str, $enc);
		}
		else {
			$ret = strtolower($str);
		}
		return $ret;
	}
/**
 * Make a string uppercase
 *
 * Returns <var>str</var> with all alphabetic characters converted to uppercase.
 *
 * @param string $str The string being uppercased.
 * @param string $use_enc The <var>use_enc</var> parameter is the character encoding. If it is omitted, the internal character encoding value will be used.
 * @static
 * @access public
 * @return string <var>str</var> with all alphabetic characters converted to uppercase.
 */
	public static function strtoupper($str, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			$ret = mb_strtoupper($str, $enc);
		}
		else {
			$ret = strtoupper($str);
		}
		return $ret;
	}
/**
 * Check if the string is valid for the specified encoding
 *
 * @param string $str The byte stream to check. If it is omitted, this function checks all the input from the beginning of the request.
 * @param string $use_enc The expected encoding.
 * @static
 * @access public
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
	public static function valid_data($str, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			return mb_check_encoding($str, $enc);
		}
		return true;
	}
/**
 * Make a string's first character uppercase
 *
 * Returns a string with the first character of str capitalized, if
 * that character is alphabetic.
 *
 * @param string $str The input string.
 * @param string $use_enc Specifies which encoding to use.
 * @static
 * @access public
 * @return string Returns the resulting string.
 */
	public static function ucfirst($str, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			// get the first character
			$first = self::substr($str, 0, 1, $enc);
			// uppercase it
			$first = self::strtoupper($first, $enc);
			// get the rest of the characters
			$last = self::substr($str, 1, null, $enc);
			// put them back together
			$ret = $first . $last;
		}
		else {
			$ret = ucfirst($str);
		}
		return $ret;
	}
/**
 * Make a string's first character lowercase
 *
 * @param string $str The input string.
 * @param string $use_enc Specifies which encoding to use.
 * @static
 * @access public
 * @return string Returns the resulting string.
 */
	public static function lcfirst($str, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			// get the first character
			$first = self::substr($str, 0, 1, $enc);
			// lowercase it
			$first = self::strtolower($first, $enc);
			// get the rest of the characters
			$last = self::substr($str, 1, null, $enc);
			// put them back together
			$ret = $first . $last;
		}
		else {
			// lcfirst() is php 5.3+ so we'll emulate it
			$first = substr($str, 0, 1);
			$first = strtolower($first);
			$last = substr($str, 1);
			$ret = $first . $last;
		}
		return $ret;
	}
/**
 * Replace all occurrences of the search string with the replacement string
 *
 * @param mixed $search The value being searched for, otherwise known as the <b>needle</b>. An array may be used to designate multiple needles.
 * @param mixed $replace The replacement value that replaces found <var>search</var> values. An array may be used to designate multiple replacements.
 * @param mixed $subject The string or array being searched and replaced on, otherwise known as the <b>haystack</b>. If <var>subject</var> is an array, then the search and replace is performed with every entry of <var>subject</var>, and the return value is an array as well.
 * @param int $count If passed, this will be set to the number of replacements performed.
 * @param string $use_enc Specifies which encoding to use.
 * @static
 * @access public
 * @return string This function returns a string or an array with the replaced values.
 */
	public static function str_replace($search, $replace, $subject, &$count = 0, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			// if search is an array and replace is not, we need to make replace an array and pad it to the same number of values as search
			if ( is_array($search) && !is_array($replace) ) {
				$replace = array_fill(0, count($search), $replace);
			}
			// if search is an array and replace is as well, we need to make sure replace has the same number of values - pad it with empty strings
			if ( is_array($search) && is_array($replace) ) {
				$replace = array_pad($replace, count($search), '');
			}
			// if search is not an array, make it one
			if ( !is_array($search) ) {
				$search = array($search);
			}
			// if replace is not an array, make it one
			if ( !is_array($replace) ) {
				$replace = array($replace);
			}
			// if subject is an array, recursively call ourselves on each element of it
			if ( is_array($subject) ) {
				foreach ($subject as $k => $v) {
					$subject[$k] = self::str_replace($search, $replace, $v, $count, $use_enc);
				}
				return $subject;
			}
			// now we've got an array of characters and arrays of search / replace characters with the same values - loop and replace them!
			$search_count = count($search); // we modify $search, so we can't include it in the condition next
			for ($i = 0; $i < $search_count; $i++) {
				// the values we'll match
				$s = array_shift($search);
				$r = array_shift($replace);
				// to avoid an infinite loop if you're replacing with a value that contains the subject we get the position of each instance first
				$positions = array();
				$offset = 0;
				while (self::strpos($subject, $s, $offset, $enc) !== false) {
					// get the position
					$pos = self::strpos($subject, $s, $offset, $enc);
					// add it to the list
					$positions[] = $pos;
					// and set the offset to skip over this value
					$offset = $pos + self::strlen($s, $enc);
				}
				// if we pick through from the beginning, our positions will change if the replacement string is longer
				// instead, we pick through from the last place
				$positions = array_reverse($positions);
				// now that we've got the position of each one, just loop through that and replace them
				foreach ($positions as $pos) {
					// pull out the part before the string
					$before = self::substr($subject, 0, $pos, $enc);
					// pull out the part after
					$after = self::substr($subject, $pos + self::strlen($s, $enc), null, $enc);
					// now we have the string in two parts without the string we're searching for
					// put it back together with the replacement
					$subject = $before . $r . $after;
					// increment our count, a replacement was made
					$count++;
				}
			}
		}
		else {
			$subject = str_replace($search, $replace, $subject, $count);
		}
		return $subject;
	}
/**
 * Case-insensitive version of <var>str_replace</var>
 *
 * @param mixed $search The value being searched for, otherwise known as the <b>needle</b>. An array may be used to designate multiple needles.
 * @param mixed $replace The replacement value that replaces found <var>search</var> values. An array may be used to designate multiple replacements.
 * @param mixed $subject The string or array being searched and replaced on, otherwise known as the <b>haystack</b>. If <var>subject</var> is an array, then the search and replace is performed with every entry of <var>subject</var>, and the return value is an array as well.
 * @param int $count If passed, this will be set to the number of replacements performed.
 * @param string $use_enc Specifies which encoding to use.
 * @static
 * @access public
 * @return string Returns a string or an array of replacements.
 */
	public static function str_ireplace($search, $replace, $subject, &$count = 0, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			// if search is an array and replace is not, we need to make replace an array and pad it to the same number of values as search
			if ( is_array($search) && !is_array($replace) ) {
				$replace = array_fill(0, count($search), $replace);
			}
			// if search is an array and replace is as well, we need to make sure replace has the same number of values - pad it with empty strings
			if ( is_array($search) && is_array($replace) ) {
				$replace = array_pad($replace, count($search), '');
			}
			// if search is not an array, make it one
			if ( !is_array($search) ) {
				$search = array($search);
			}
			// if replace is not an array, make it one
			if ( !is_array($replace) ) {
				$replace = array($replace);
			}
			// if subject is an array, recursively call ourselves on each element of it
			if ( is_array($subject) ) {
				foreach ($subject as $k => $v) {
					$subject[$k] = self::str_ireplace($search, $replace, $v, $count, $use_enc);
				}
				return $subject;
			}
			$search_count = count($search); // we modify $search, so we can't include it in the condition next
			for ($i = 0; $i < $search_count; $i++) {
				// the values we'll match
				$s = array_shift($search);
				$r = array_shift($replace);
				// to avoid an infinite loop if you're replacing with a value that contains the subject we get the position of each instance first
				$positions = array();
				$offset = 0;
				while (self::stripos($subject, $s, $offset, $enc) !== false) {
					// get the position
					$pos = self::stripos($subject, $s, $offset, $enc);
					// add it to the list
					$positions[] = $pos;
					// and set the offset to skip over this value
					$offset = $pos + self::strlen($s, $enc);
				}
				// if we pick through from the beginning, our positions will change if the replacement string is longer
				// instead, we pick through from the last place
				$positions = array_reverse($positions);
				// now that we've got the position of each one, just loop through that and replace them
				foreach ($positions as $pos) {
					// pull out the part before the string
					$before = self::substr($subject, 0, $pos, $enc);
					// pull out the part after
					$after = self::substr($subject, $pos + self::strlen($s, $enc), null, $enc);
					// now we have the string in two parts without the string we're searching for
					// put it back together with the replacement
					$subject = $before . $r . $after;
					// increment our count, a replacement was made
					$count++;
				}
			}
		}
		else {
			$subject = str_ireplace($search, $replace, $subject, $count);
		}
		return $subject;
	}
/**
 * Uppercase the first character of each word in a string
 *
 * @param string $str The input string.
 * @param string $use_enc Specifies which encoding to use.
 * @static
 * @access public
 * @return string Returns the modified string.
 */
	public static function ucwords($str, $use_enc = null)
	{
		$enc = self::$hab_enc;
		if ( $use_enc !== null ) {
			$enc = $use_enc;
		}
		if ( self::$use_library == self::useMbstring() ) {
			$delimiters = array(
			chr(32), // space
			chr(12), // form-feed
			chr(10), // newline
			chr(13), // carriage return
			chr(9), // horizontal tab
			chr(11), // vertical tab
			);
			// loop through the delimiters and explode the string by each one
			foreach ($delimiters as $d) {
				$pieces = explode($d, $str);
				for ($i = 0; $i < count($pieces); $i++) {
					// capitalize each word
					$pieces[$i] = self::ucfirst($pieces[$i], $enc);
				}
				// put the string back together
				$str = implode($d, $pieces);
			}
		}
		else {
			$str = ucwords($str);
		}
		return $str;
	}
/**
 * Encode string for MIME header
 *
 * @param string $string The string being encoded.
 * @param string $charset Specifies the name of the character set in which <var>string</var> is represented in.
 * @param string $newline Specifies the EOL (end-of-line). Falls back to "\r\n" (CRLF) if not given.
 * @static
 * @access public
 * @return string A converted version of the string represented in ASCII.
 */
	public static function mimeEncode($string, $charset = null, $newline = "\r\n")
	{
		if (!pjMultibyte::checkMultibyte($string) && strlen($string) < 75) {
			return $string;
		}

		if (empty($charset)) {
			$charset = 'UTF-8';
		}
		$charset = strtoupper($charset);

		$start = '=?' . $charset . '?B?';
		$end = '?=';
		$spacer = $end . $newline . ' ' . $start;

		$length = 75 - strlen($start) - strlen($end);
		$length = $length - ($length % 4);
		if ($charset == 'UTF-8') {
			$parts = array();
			$maxchars = floor(($length * 3) / 4);
			$stringLength = strlen($string);
			while ($stringLength > $maxchars) {
				$i = (int)$maxchars;
				$test = ord($string[$i]);
				while ($test >= 128 && $test <= 191) {
					$i--;
					$test = ord($string[$i]);
				}
				$parts[] = base64_encode(substr($string, 0, $i));
				$string = substr($string, $i);
				$stringLength = strlen($string);
			}
			$parts[] = base64_encode($string);
			$string = implode($spacer, $parts);
		} else {
			$string = chunk_split(base64_encode($string), $length, $spacer);
			$string = preg_replace('/' . preg_quote($spacer) . '$/', '', $string);
		}
		return $start . $string . $end;
	}
/**
 * Finds the last occurrence of a character in a string within another, case insensitive
 *
 * @param string $haystack The string from which to get the last occurrence of <var>needle</var>
 * @param string $needle The string to find in <var>haystack</var>
 * @param boolean $part Determines which portion of <var>haystack</var> this function returns. If set to <b>TRUE</b>, it returns all of <var>haystack</var> from the beginning to the last occurrence of <var>needle</var>. If set to <b>FALSE</b>, it returns all of <var>haystack</var> from the last occurrence of <var>needle</var> to the end
 * @static
 * @access public
 * @return string|false Returns the portion of <var>haystack</var>. or <b>FALSE</b> if <var>needle</var> is not found.
 */
	public static function strrichr($haystack, $needle, $part = false)
	{
		$check = pjMultibyte::strtoupper($haystack);
		$check = pjMultibyte::utf8($check);
		$found = false;

		$haystack = pjMultibyte::utf8($haystack);
		$haystackCount = count($haystack);

		$matches = array_count_values($check);

		$needle = pjMultibyte::strtoupper($needle);
		$needle = pjMultibyte::utf8($needle);
		$needleCount = count($needle);

		$parts = array();
		$position = 0;

		while (($found === false) && ($position < $haystackCount)) {
			if (isset($needle[0]) && $needle[0] === $check[$position]) {
				for ($i = 1; $i < $needleCount; $i++) {
					if ($needle[$i] !== $check[$position + $i]) {
						if ($needle[$i] === $check[($position + $i) - 1]) {
							$found = true;
						}
						unset($parts[$position - 1]);
						$haystack = array_merge(array($haystack[$position]), $haystack);
						break;
					}
				}
				if (isset($matches[$needle[0]]) && $matches[$needle[0]] > 1) {
					$matches[$needle[0]] = $matches[$needle[0]] - 1;
				} elseif ($i === $needleCount) {
					$found = true;
				}
			}

			if (!$found && isset($haystack[$position])) {
				$parts[] = $haystack[$position];
				unset($haystack[$position]);
			}
			$position++;
		}

		if ($found && $part && !empty($parts)) {
			return pjMultibyte::ascii($parts);
		} elseif ($found && !empty($haystack)) {
			return pjMultibyte::ascii($haystack);
		}
		return false;
	}
/**
 * Finds the last occurrence of a character in a string within another
 *
 * @param string $haystack The string from which to get the last occurrence of <var>needle</var>
 * @param string $needle The string to find in <var>haystack</var>
 * @param boolean $part Determines which portion of <var>haystack</var> this function returns. If set to <b>TRUE</b>, it returns all of <var>haystack</var> from the beginning to the last occurrence of <var>needle</var>. If set to <b>FALSE</b>, it returns all of <var>haystack</var> from the last occurrence of <var>needle</var> to the end
 * @static
 * @access public
 * @return string
 */
	public static function strrchr($haystack, $needle, $part = false)
	{
		$check = pjMultibyte::utf8($haystack);
		$found = false;

		$haystack = pjMultibyte::utf8($haystack);
		$haystackCount = count($haystack);

		$matches = array_count_values($check);

		$needle = pjMultibyte::utf8($needle);
		$needleCount = count($needle);

		$parts = array();
		$position = 0;

		while (($found === false) && ($position < $haystackCount)) {
			if (isset($needle[0]) && $needle[0] === $check[$position]) {
				for ($i = 1; $i < $needleCount; $i++) {
					if ($needle[$i] !== $check[$position + $i]) {
						if ($needle[$i] === $check[($position + $i) - 1]) {
							$found = true;
						}
						unset($parts[$position - 1]);
						$haystack = array_merge(array($haystack[$position]), $haystack);
						break;
					}
				}
				if (isset($matches[$needle[0]]) && $matches[$needle[0]] > 1) {
					$matches[$needle[0]] = $matches[$needle[0]] - 1;
				} elseif ($i === $needleCount) {
					$found = true;
				}
			}

			if (!$found && isset($haystack[$position])) {
				$parts[] = $haystack[$position];
				unset($haystack[$position]);
			}
			$position++;
		}

		if ($found && $part && !empty($parts)) {
			return pjMultibyte::ascii($parts);
		} elseif ($found && !empty($haystack)) {
			return pjMultibyte::ascii($haystack);
		}
		return false;
	}
/**
 * Finds first occurrence of a string within another, case insensitive
 *
 * @param string $haystack The string from which to get the first occurrence of <var>needle</var>
 * @param string $needle The string to find in <var>haystack</var>
 * @param boolean $part Determines which portion of <var>haystack</var> this function returns. If set to <b>TRUE</b>, it returns all of <var>haystack</var> from the beginning to the first occurrence of <var>needle</var>. If set to <b>FALSE</b>, it returns all of <var>haystack</var> from the first occurrence of <var>needle</var> to the end,
 * @static
 * @access public
 * @return string
 */
	public static function stristr($haystack, $needle, $part = false)
	{
		$php = (PHP_VERSION < 5.3);

		if (($php && $part) || pjMultibyte::checkMultibyte($haystack)) {
			$check = pjMultibyte::strtoupper($haystack);
			$check = pjMultibyte::utf8($check);
			$found = false;

			$haystack = pjMultibyte::utf8($haystack);
			$haystackCount = count($haystack);

			$needle = pjMultibyte::strtoupper($needle);
			$needle = pjMultibyte::utf8($needle);
			$needleCount = count($needle);

			$parts = array();
			$position = 0;

			while (($found === false) && ($position < $haystackCount)) {
				if (isset($needle[0]) && $needle[0] === $check[$position]) {
					for ($i = 1; $i < $needleCount; $i++) {
						if ($needle[$i] !== $check[$position + $i]) {
							break;
						}
					}
					if ($i === $needleCount) {
						$found = true;
					}
				}
				if (!$found) {
					$parts[] = $haystack[$position];
					unset($haystack[$position]);
				}
				$position++;
			}

			if ($found && $part && !empty($parts)) {
				return pjMultibyte::ascii($parts);
			} elseif ($found && !empty($haystack)) {
				return pjMultibyte::ascii($haystack);
			}
			return false;
		}

		if (!$php) {
			return stristr($haystack, $needle, $part);
		}
		return stristr($haystack, $needle);
	}
/**
 * Finds first occurrence of a string within another
 *
 * @param string $haystack The string from which to get the first occurrence of <var>needle</var>
 * @param string $needle The string to find in <var>haystack</var>
 * @param boolean $part Determines which portion of <var>haystack</var> this function returns. If set to <b>TRUE</b>, it returns all of <var>haystack</var> from the beginning to the first occurrence of needle. If set to <b>FALSE</b>, it returns all of <var>haystack</var> from the first occurrence of <var>needle</var> to the end
 * @static
 * @access public
 * @return string|false Returns the portion of <var>haystack</var>, or <b>FALSE</b> if <var>needle</var> is not found.
 */
    public static function strstr($haystack, $needle, $part = false)
    {
		$php = (PHP_VERSION < 5.3);

		if (($php && $part) || pjMultibyte::checkMultibyte($haystack)) {
			$check = pjMultibyte::utf8($haystack);
			$found = false;

			$haystack = pjMultibyte::utf8($haystack);
			$haystackCount = count($haystack);

			$needle = pjMultibyte::utf8($needle);
			$needleCount = count($needle);

			$parts = array();
			$position = 0;

			while (($found === false) && ($position < $haystackCount)) {
				if (isset($needle[0]) && $needle[0] === $check[$position]) {
					for ($i = 1; $i < $needleCount; $i++) {
						if ($needle[$i] !== $check[$position + $i]) {
							break;
						}
					}
					if ($i === $needleCount) {
						$found = true;
					}
				}
				if (!$found) {
					$parts[] = $haystack[$position];
					unset($haystack[$position]);
				}
				$position++;
			}

			if ($found && $part && !empty($parts)) {
				return pjMultibyte::ascii($parts);
			} elseif ($found && !empty($haystack)) {
				return pjMultibyte::ascii($haystack);
			}
			return false;
		}

		if (!$php) {
			return strstr($haystack, $needle, $part);
		}
		return strstr($haystack, $needle);
	}
/**
 *
 *
 * @param array|string $array
 * @static
 * @access public
 * @return string
 */
	public static function ascii($array)
	{
		$ascii = '';

		foreach ($array as $utf8)
		{
			if ($utf8 < 128)
			{
				$ascii .= chr($utf8);
			} elseif ($utf8 < 2048) {
				$ascii .= chr(192 + (($utf8 - ($utf8 % 64)) / 64));
				$ascii .= chr(128 + ($utf8 % 64));
			} else {
				$ascii .= chr(224 + (($utf8 - ($utf8 % 4096)) / 4096));
				$ascii .= chr(128 + ((($utf8 % 4096) - ($utf8 % 64)) / 64));
				$ascii .= chr(128 + ($utf8 % 64));
			}
		}
		return $ascii;
	}
/**
 * Count the number of substring occurrences
 *
 * @param string $haystack The string being checked.
 * @param string $needle The string being found.
 * @static
 * @access public
 * @return int The number of times the <var>needle</var> substring occurs in the <var>haystack</var> string.
 */
    public static function substrCount($haystack, $needle)
    {
		$count = 0;
		$haystack = pjMultibyte::utf8($haystack);
		$haystackCount = count($haystack);
		$matches = array_count_values($haystack);
		$needle = pjMultibyte::utf8($needle);
		$needleCount = count($needle);

		if ($needleCount === 1 && isset($matches[$needle[0]]))
		{
			return $matches[$needle[0]];
		}

		for ($i = 0; $i < $haystackCount; $i++)
		{
			if (isset($needle[0]) && $needle[0] === $haystack[$i])
			{
				for ($ii = 1; $ii < $needleCount; $ii++)
				{
					if ($needle[$ii] === $haystack[$i + 1])
					{
						if ((isset($needle[$ii + 1]) && $haystack[$i + 2]) && $needle[$ii + 1] !== $haystack[$i + 2])
						{
							$count--;
						} else {
							$count++;
						}
					}
				}
			}
		}
		return $count;
	}
/**
 * Checks if whether the string contain multibyte characters
 *
 * @param string $string
 * @static
 * @access public
 * @return boolean
 */
    public static function checkMultibyte($string)
    {
		$length = strlen($string);

		for ($i = 0; $i < $length; $i++) {
			$value = ord(($string[$i]));
			if ($value > 128) {
				return true;
			}
		}
		return false;
	}
/**
 *
 *
 * @param string $string
 * @access public
 * @return array
 */
	public static function utf8($string)
	{
		$map = array();

		$values = array();
		$find = 1;
		$length = strlen($string);

		for ($i = 0; $i < $length; $i++) {
			$value = ord($string[$i]);

			if ($value < 128) {
				$map[] = $value;
			} else {
				if (empty($values)) {
					$find = ($value < 224) ? 2 : 3;
				}
				$values[] = $value;

				if (count($values) === $find) {
					if ($find == 3) {
						$map[] = (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64);
					} else {
						$map[] = (($values[0] % 32) * 64) + ($values[1] % 64);
					}
					$values = array();
					$find = 1;
				}
			}
		}
		return $map;
	}
}
?>