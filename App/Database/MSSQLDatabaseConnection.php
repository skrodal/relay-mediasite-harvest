<?php  namespace Uninett\Database; 
use Exception;

class MSSQLDatabaseConnection implements MSSQLDatabaseConnectionInterface
{
	protected $username;
	protected $password;
	protected $database;
	protected $host;

	protected $connection;

	public function connect()
	{
		try {
			$this->connection =  mssql_connect(
				$this->host, $this->username, $this->password
			);
			mssql_select_db($this->database, $this->connection);
		} catch (Exception $e) {
			throw new Exception($e->getMessage() . PHP_EOL .  mssql_get_last_message());
		}

		return $this->connection;
	}

	public function query($query)
	{
		if(is_bool($query)) {
			echo __FILE__ . ": Query cannot be executed. It came in as a boolean: " .  $query . PHP_EOL . mssql_get_last_message() . PHP_EOL;
			return false;
		}

		$result = mssql_query($query);


       if(is_bool($result)) {
            echo __FILE__ . ": Result cannot be returned. It is as a boolean. This means the query either failed or no rows were returned: " .  $query . PHP_EOL . mssql_get_last_message() . PHP_EOL;
            return false;
        }

		if(mssql_num_rows($result) == 0) {

			if($result == false)
				echo __FILE__ . ": Query failed. Result was false " .  $query . PHP_EOL . mssql_get_last_message() . PHP_EOL;

			//! Query could also be true if no rows was returned, but query succeeded

			return false;
		}

		return $result;
	}

/*	public function query($query)
	{

		if(is_bool($query)) {
			echo __FILE__ . ": Query cannot be executed. It came in as a boolean: " . PHP_EOL;
			return false;
		}

		$result = mssql_query($query);

		//If no rows was returned
		if($result === true) {
			echo __FILE__ . ": No rows was returned: " .  $query . PHP_EOL;
			return false;
		}

		//If error
		if($result === false)
			echo __FILE__ . ": Query failed. Result was false " .  $query . PHP_EOL . mssql_get_last_message() . PHP_EOL;

		return $result;
	}*/
}