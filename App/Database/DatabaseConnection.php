<?php namespace Uninett\Database;

class DatabaseConnection extends Database
{
	public function __construct($source)
	{
		switch($source) {
			case "ecampussql":
				$host = getenv('ESQL_HOST');
				$db = getenv('ESQL_DATABASE');
				$user = getenv('ESQL_USERNAME');
				$password = getenv('ESQL_PASSWORD');
				parent::__construct(new \PDO("dblib:host={$host};dbname={$db}", "{$user}", "{$password}"));
				return $this;
				break;
			case 'pictor':
				echo "Noe annet ble valgt som source";
				return $this;
				break;
			default:
				echo "Du må velge source";
				return $this;
				break;
		}
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