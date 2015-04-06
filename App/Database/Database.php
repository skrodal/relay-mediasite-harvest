<?php namespace Uninett\Database; 
use Exception;
use FluentPDO;
use PDO;
use Uninett\Models\Ecampussql\Model;

class Database {

	public $connection;

	function __construct(PDO $pdo)
	{
		$fpdo = new FluentPDO($pdo);

		$fpdo->getPdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$this->connection = $fpdo;
	}

	public function insert(Model $model){
		$validation = \SimpleValidator\Validator::validate($model->attributes, $model::$rules);

		if ($validation->isSuccess() === true) {
			$result = $this->connection->insertInto($model::$table, $model->attributes)->execute();

			if($result === false)
			{
				//TODO: Should throw exception
				print_r($this->connection->getPdo()->errorInfo());
			}
		} else {
			//TODO: Throw exception?
			var_dump($validation->getErrors());
		}
	}

	public function disableForeignkeyChecks()
	{
		throw new Exception("Implement this");
	}

	public function enableForeignkeyChecks()
	{
		throw new Exception("Implement this");
	}
}