<?php namespace Uninett\Database;
interface MSSQLDatabaseConnectionInterface
{
	public function connect();
	public function query($query);
}