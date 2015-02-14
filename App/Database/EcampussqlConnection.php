<?php  namespace Uninett\Database;

use Exception;
use Uninett\Config;

class EcampussqlMSSQLDatabaseConnection extends MSSQLMSSQLDatabaseConnection {
	public function __construct()
	{
		$this->host = Config::get('ecampussql')['host'];
		$this->username = Config::get('ecampussql')['username'];
		$this->password = Config::get('ecampussql')['password'];
		$this->database = Config::get('ecampussql')['database'];

		$this->connect();
	}


}