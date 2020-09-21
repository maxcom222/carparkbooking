<?php
/**
 * PHP Framework
 *
 * @copyright Copyright 2016, StivaSoft, Ltd. (https://www.stivasoft.com)
 * @link      https://www.phpjabbers.com/
 * @package   framework.components
 * @version   1.5.2
 */
define("MATH_BIGINTEGER_MODE", 1);
include_once dirname(__FILE__) . '/Math_BigInteger.php';
/**
 * Math wrapper
 *
 * @package framework.components
 * @since 1.0.4
 */
class pjMath
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
		$a = new Math_BigInteger($a);
		$b = new Math_BigInteger($b);
		$c = $a->add($b);
		
		return $c->toString();
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
		$a = new Math_BigInteger($a);
		$b = new Math_BigInteger($b);
		$c = $a->multiply($b);
		
		return $c->toString();
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
		//FIXME
		return gmp_strval(gmp_pow($base, $exp));
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
		//FIXME
		$base = new Math_BigInteger($base);
		$exp = new Math_BigInteger($exp);
		$mod = new Math_BigInteger($mod);
		$mod = $base->modPow($exp, $mod);
		
		return $mod->toString();
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
		$a = new Math_BigInteger($a);
		$b = new Math_BigInteger($b);
		list($quotient, ) = $a->divide($b);

		return $quotient->toString();
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
		$n = new Math_BigInteger($n);
		$d = new Math_BigInteger($d);
		
		return $n->_mod2($d);
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
		$a = new Math_BigInteger($a);
		$b = new Math_BigInteger($b);
		$c = $a->compare($b);
		
		return $c;
	}
}
?>