<?php namespace Uninett\Database;
class RelaySQLConnection extends MSSQLDatabaseConnection {
	public function __construct() {
		$this->host     = getenv('RELAY_SQL_HOST');
		$this->username = getenv('RELAY_SQL_USERNAME');
		$this->password = getenv('RELAY_SQL_PASSWORD');
		$this->database = getenv('RELAY_SQL_DATABASE');

		$this->connect();
	}
}