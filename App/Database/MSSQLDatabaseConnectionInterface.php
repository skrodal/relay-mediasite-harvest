<?php namespace Uninett\Database; 
interface MSSQLDatabaseConnectionInterface {
	public function query($query);
}