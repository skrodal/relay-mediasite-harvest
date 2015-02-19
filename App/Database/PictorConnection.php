<?php namespace Uninett\Database;

class PictorDatabaseMSSQLDatabaseConnection extends MSSQLDatabaseConnection
{
	public function __construct()
	{
		$this->host = getenv('PICTOR_HOST');
		$this->username = getenv('PICTOR_USERNAME');
		$this->password = getenv('PICTOR_PASSWORD');
		$this->database = getenv('PICTOR_DATABASE');
		$this->connect();
	}

}