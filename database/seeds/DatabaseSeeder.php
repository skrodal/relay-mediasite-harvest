<?php
use Uninett\Core\Seeders\Seeder;
use Uninett\Database\DatabaseConnection;

class DatabaseSeeder {
	protected $seeders = array(
		'TblUserTableSeeder',
		'TblFileTableSeeder'
	);

	protected $tables = array(
		'tblUser',
		'tblFile'
	);

	public function truncate()
	{
		$db = new DatabaseConnection('ecampussql');

		$db->disableForeignkeyChecks();

		foreach($this->tables as $table) {
			$db->connection->getPdo()->exec("TRUNCATE TABLE {$table}");
		}

		$db->enableForeignkeyChecks();
	}

	public function seed()
	{
		/* @var $seed Seeder */
		foreach($this->seeders as $seeder)
		{
			$seed = (new ReflectionClass($seeder))
				->newInstance(new DatabaseConnection('ecampussql'));
			$seed->run();
		}
	}
}