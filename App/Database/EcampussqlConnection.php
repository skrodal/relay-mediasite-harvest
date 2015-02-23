<?php namespace Uninett\Database;

class EcampusSQLConnection extends MSSQLDatabaseConnection
{
	public function __construct()
	{
		$this->host = getenv('ESQL_HOST');
		$this->username = getenv('ESQL_USERNAME');
		$this->password = getenv('ESQL_PASSWORD');
		$this->database = getenv('ESQL_DATABASE');

		$this->connect();
	}
}