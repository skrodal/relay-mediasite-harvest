<?php namespace Uninett\Collections\Helpers;
use Uninett\Config;

class Arithmetic {
	public function add($num1, $num2)
	{
		return (double)round(($num1 + $num2), Config::get('arithmetic')['numberOfDecimals'], PHP_ROUND_HALF_UP);
	}
	public function subtract($num1, $num2)
	{
		return (double)round(($num1 - $num2), Config::get('arithmetic')['numberOfDecimals'], PHP_ROUND_HALF_UP);
	}

	public function consideredToBeEqual($a, $b)
	{
		return abs($a-$b) < 0.01;
	}
}