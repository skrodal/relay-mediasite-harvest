<?php namespace Uninett\Database;
use Exception;
use Uninett\Config;

class PictorDatabaseMSSQLDatabaseConnection extends MSSQLMSSQLDatabaseConnection {
	public function __construct()
	{
		$this->host = Config::get('pictor')['host'];
		$this->username = Config::get('pictor')['username'];
		$this->password = Config::get('pictor')['password'];
		$this->database = Config::get('pictor')['database'];

		$this->connect();
	}

}