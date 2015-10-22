<?php namespace Uninett\Database;

class ScreencastSQLConnection extends MSSQLDatabaseConnection {
	public function __construct() {
		$this->host     = getenv('SCREENCAST_SQL_HOST');
		$this->username = getenv('SCREENCAST_SQL_USERNAME');
		$this->password = getenv('SCREENCAST_SQL_PASSWORD');
		$this->database = getenv('SCREENCAST_SQL_DATABASE');
		$this->connect();
	}
}