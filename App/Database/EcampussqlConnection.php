<?php namespace Uninett\Database;

use Uninett\Config;

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

	public function userAccountExists($username)
	{
		$query = "
        SELECT userName
        FROM tblUser
        WHERE userName LIKE '" . $username . "'";

		$query = mssql_query($query);

		if($query == false)
			return false;

		return true;

	}
}