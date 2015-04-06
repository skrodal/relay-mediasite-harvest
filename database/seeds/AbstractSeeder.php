<?php 
class AbstractSeeder {

	public $connection;

	function __construct($connection)
	{
		$this->connection = $connection;
	}


}