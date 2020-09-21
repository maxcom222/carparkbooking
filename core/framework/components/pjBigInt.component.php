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
 * Wrapper class for Math BigInteger
 *
 * @package framework.components
 * @since 1.0.4
 */
class pjBigInt
{
/**
 * Add two arbitrary precision numbers
 *
 * @param string $a The left operand, as a string.
 * @param string $b The right operand, as a string.
 * @access public
 * @return string The sum of the two operands, as a string
 */
	public function add($a, $b)
	{
		return bi_add($a, $b);
	}
/**
 * Multiply two arbitrary precision number
 *
 * @param string $a The left operand, as a string.
 * @param string $b The right operand, as a string.
 * @access public
 * @return string Returns the result as a string.
 */
	public function mul($a, $b)
	{
		return bi_mul($a, $b);
	}
/**
 * Raise an arbitrary precision number to another
 *
 * @param string $base The left operand, as a string.
 * @param string $exp The right operand, as a string.
 * @access public
 * @return string Returns the result as a string.
 */
	public function pow($base, $exp)
	{
		return bi_pow($base, $exp);
	}
/**
 * Raise an arbitrary precision number to another, reduced by a specified modulus
 *
 * @param string $base The left operand, as a string.
 * @param string $exp The right operand, as a string.
 * @param string $mod The modulus, as a string.
 * @access public
 * @return string|null Returns the result as a string, or <b>NULL</b> if modulus is 0.
 */
	public function powmod($base, $exp, $mod)
	{
		return bi_powmod($base, $exp, $mod);
	}
/**
 * Divide two arbitrary precision numbers
 *
 * @param string $a The left operand, as a string.
 * @param string $b The right operand, as a string.
 * @access public
 * @return string|null Returns the result of the division as a string, or <b>NULL</b> if right_operand is 0.
 */
	public function div($a, $b)
	{
		return bi_div($a, $b);
	}
/**
 * Get modulus of an arbitrary precision number
 *
 * @param string $n The left operand, as a string.
 * @param string $d The modulus, as a string.
 * @access public
 * @return string Returns the modulus as a string, or <b>NULL</b> if modulus is 0.
 */
	public function mod($n, $d)
	{
		return bi_mod($n, $d);
	}
/**
 * Compare two arbitrary precision numbers
 *
 * @param string $a The left operand, as a string.
 * @param string $b The right operand, as a string.
 * @access public
 * @return int Returns 0 if the two operands are equal, 1 if the left_operand is larger than the right_operand, -1 otherwise.
 */
	public function cmp($a, $b)
	{
		return bi_cmp($a, $b);
	}
}
?>