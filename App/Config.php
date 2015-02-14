<?php namespace Uninett;

class Config {
	protected static $config = array();

	public static function get($name, $default = null)
	{
		return isset(self::$config[$name]) ? self::$config[$name] : $default;
	}

	public static function add($parameters = array())
	{
		self::$config = array_merge(self::$config, $parameters);
	}

}