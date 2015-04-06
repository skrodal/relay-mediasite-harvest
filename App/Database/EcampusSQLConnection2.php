<?php namespace Uninett\Database;
use FluentPDO;
use PDO;
use Uninett\Models\Ecampussql\Model;

class EcampusSQLConnection2
{
	public $connection;

	public function __construct()
	{
		$host = getenv('ESQL_HOST');
		$db = getenv('ESQL_DATABASE');
		$user = getenv('ESQL_USERNAME');
		$password = getenv('ESQL_PASSWORD');

		$fpdo = new FluentPDO(new PDO("dblib:host={$host};dbname={$db}", "{$user}", "{$password}"));

		$fpdo->getPdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$this->connection = $fpdo;
	}

	public function insert(Model $model){
		$validation = \SimpleValidator\Validator::validate($model->attributes, $model::$rules);

		if ($validation->isSuccess() === true) {
			$this->connection->insertInto($model::$table, $model->attributes)->execute();
		} else {
			//TODO: Throw exception?
			var_dump($validation->getErrors());
		}
	}

	public function disableForeignkeyChecks()
	{
	//	$this->connection->getPdo()->exec("ALTER TABLE {$table} NOCHECK CONSTRAINT ALL");
		$this->connection->getPdo()->exec("sp_msforeachtable 'ALTER TABLE ? NOCHECK CONSTRAINT all' ");
	}

	public function enableForeignkeyChecks()
	{
		/*$this->connection->getPdo()->exec("ALTER TABLE {$table} CHECK CONSTRAINT ALL");*/
		$this->connection->getPdo()->exec("sp_msforeachtable 'ALTER TABLE ? CHECK CONSTRAINT all' ");
	}
}