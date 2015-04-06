<?php 
class DatabaseSeeder{

	protected $seeders = array(
		'TblUsersTableSeeder'
	);

	protected $tables = array(
		'tblUser'
	);

	public function seed(){

		foreach($this->tables as $table)
			$table->truncate();

		foreach($this->seeders as $seed)
			$seed->run();
	}

}