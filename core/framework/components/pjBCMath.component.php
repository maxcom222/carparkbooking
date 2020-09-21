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
 * Wrapper class for BCMath Arbitrary Precision Mathematics
 *
 * @package framework.components
 * @link http://us3.php.net/manual/en/ref.bc.php
 * @since 1.0.4
 */
class pjBCMath
{
/**
 * Add two arbitrary precision numbers
 *
 * @param string $a The left operand, as a string.
 * @param string $b The right operand, as a string.
 * @access public
 * @return string The sum of the two operands, as a string
 * @link http://us3.php.net/manual/en/function.bcadd.php
 */
	public function add($a, $b)
	{
		return bcadd($a, $b);
	}
/**
 * Multiply two arbitrary precision number
 *
 * @param string $a The left operand, as a string.
 * @param string $b The right operand, as a string.
 * @access public
 * @return string Returns the result as a string.
 * @link http://us3.php.net/manual/en/function.bcmul.php
 */
	public function mul($a, $b)
	{
		return bcmul($a, $b);
	}
/**
 * Raise an arbitrary precision number to another
 *
 * @param string $base The left operand, as a string.
 * @param string $exp The right operand, as a string.
 * @access public
 * @return string Returns the result as a string.
 * @link http://us3.php.net/manual/en/function.bcpow.php
 */
	public function pow($base, $exp)
	{
		return bcpow($base, $exp);
	}
/**
 * Raise an arbitrary precision number to another, reduced by a specified modulus
 *
 * @param string $base The left operand, as a string.
 * @param string $exp The right operand, as a string.
 * @param string $mod The modulus, as a string.
 * @access public
 * @return string|null Returns the result as a string, or <b>NULL</b> if modulus is 0.
 * @link http://us3.php.net/manual/en/function.bcpowmod.php
 */
	public function powmod($base, $exp, $mod)
	{
		return bcpowmod($base, $exp, $mod);
	}
/**
 * Divide two arbitrary precision numbers
 *
 * @param string $a The left operand, as a string.
 * @param string $b The right operand, as a string.
 * @access public
 * @return string|null Returns the result of the division as a string, or <b>NULL</b> if right_operand is 0.
 * @link http://us3.php.net/manual/en/function.bcdiv.php
 */
	public function div($a, $b)
	{
		return bcdiv($a, $b);
	}
/**
 * Get modulus of an arbitrary precision number
 *
 * @param string $n The left operand, as a string.
 * @param string $d The modulus, as a string.
 * @access public
 * @return string Returns the modulus as a string, or <b>NULL</b> if modulus is 0.
 * @link http://us3.php.net/manual/en/function.bcmod.php
 */
	public function mod($n, $d)
	{
		return bcmod($n, $d);
	}
/**
 * Compare two arbitrary precision numbers
 *
 * @param string $a The left operand, as a string.
 * @param string $b The right operand, as a string.
 * @access public
 * @return int Returns 0 if the two operands are equal, 1 if the left_operand is larger than the right_operand, -1 otherwise.
 * @link http://us3.php.net/manual/en/function.bccomp.php
 */
	public function cmp($a, $b)
	{
		return bccomp($a, $b);
	}
}
?>