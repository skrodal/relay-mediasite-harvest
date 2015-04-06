<?php namespace Uninett\Database;

class EcampusSQLConnection2 extends Database
{
	public function __construct()
	{
		$host = getenv('ESQL_HOST');
		$db = getenv('ESQL_DATABASE');
		$user = getenv('ESQL_USERNAME');
		$password = getenv('ESQL_PASSWORD');

		parent::__construct(new \PDO("dblib:host={$host};dbname={$db}", "{$user}", "{$password}"));
	}

	public function disableForeignkeyChecks()
	{
		$this->connection->getPdo()->exec("sp_msforeachtable 'ALTER TABLE ? NOCHECK CONSTRAINT all' ");
	}

	public function enableForeignkeyChecks()
	{
		$this->connection->getPdo()->exec("sp_msforeachtable 'ALTER TABLE ? CHECK CONSTRAINT all' ");
	}
}