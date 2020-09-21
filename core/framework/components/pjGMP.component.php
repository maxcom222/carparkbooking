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
 * Wrapper class for GNU Multiple Precision
 *
 * @package framework.components
 * @link http://us3.php.net/manual/en/ref.gmp.php
 * @since 1.0.4
 */
class pjGMP
{
/**
 * Add numbers
 *
 * @param resource|string $a A number that will be added. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @param resource|string $b A number that will be added. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @access public
 * @return string A GMP number representing the sum of the arguments, converted to string.
 * @link http://us3.php.net/manual/en/function.gmp-add.php
 */
	public function add($a, $b)
	{
		return gmp_strval(gmp_add($a, $b));
	}
/**
 * Multiply numbers
 *
 * @param resource|string $a A number that will be added. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @param resource|string $b A number that will be added. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @access public
 * @return string A GMP number, converted to string.
 * @link http://us3.php.net/manual/en/function.gmp-mul.php
 */
	public function mul($a, $b)
	{
		return gmp_strval(gmp_mul($a, $b));
	}
/**
 * Raise number into power
 *
 * @param resource|string $base The base number. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @param int $exp The positive power to raise the base.
 * @access public
 * @return string The new (raised) number, GMP number, converted to string.
 * @link http://us3.php.net/manual/en/function.gmp-pow.php
 */
	public function pow($base, $exp)
	{
		return gmp_strval(gmp_pow($base, $exp));
	}
/**
 * Raise number into power with modulo
 *
 * @param resource|string $base The base number. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @param resource|string $exp The positive power to raise the base. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @param resource|string $mod The modulo. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @access public
 * @return string The new (raised) number, as a GMP number, converted to string.
 * @link http://us3.php.net/manual/en/function.gmp-powm.php
 */
	public function powmod($base, $exp, $mod)
	{
		return gmp_strval(gmp_powm($base, $exp, $mod));
	}
/**
 * Divide numbers
 *
 * @param resource|string $a The number being divided. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @param resource|string $b The number that $a is being divided by. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @access public
 * @return string A GMP number resource, converted to string.
 * @link http://us3.php.net/manual/en/function.gmp-div-q.php
 */
	public function div($a, $b)
	{
		return gmp_strval(gmp_div_q($a, $b, GMP_ROUND_ZERO));
	}
/**
 * Modulo operation
 *
 * @param resource|string $n It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @param resource|string $d The modulo that is being evaluated. It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @access public
 * @return string A GMP number resource, converted to string.
 * @link http://us3.php.net/manual/en/function.gmp-mod.php
 */
	public function mod($n, $d)
	{
		return gmp_strval(gmp_mod($n, $d));
	}
/**
 * Compare numbers
 *
 * @param resource $a It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @param resource $b It can be either a GMP number resource, or a numeric string given that it is possible to convert the latter to a number.
 * @access public
 * @return string Returns a positive value if a > b, zero if a = b and a negative value if a < b.
 * @link http://us3.php.net/manual/en/function.gmp-cmp.php
 */
	public function cmp($a, $b)
	{
		return gmp_strval(gmp_cmp($a, $b));
	}
}
?>