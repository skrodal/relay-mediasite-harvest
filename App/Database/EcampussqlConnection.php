<?php  namespace Uninett\Database;

use Uninett\Config;

class EcampusSQLConnection extends MSSQLDatabaseConnection
{
	public function __construct()
	{
		$this->host = Config::get('ecampussql')['host'];
		$this->username = Config::get('ecampussql')['username'];
		$this->password = Config::get('ecampussql')['password'];
		$this->database = Config::get('ecampussql')['database'];

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