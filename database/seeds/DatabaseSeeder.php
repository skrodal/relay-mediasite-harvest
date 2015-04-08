<?php
use Uninett\Core\Seeders\Seeder;

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
		$db = new \Uninett\Database\DatabaseConnection('ecampussql');

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
			$r = (new ReflectionClass($seeder))->newInstance(new \Uninett\Database\DatabaseConnection('ecampussql'));
			$r->run();


		/*	$seed = new $seeder();
			$seed->run();*/
		}
	}
}