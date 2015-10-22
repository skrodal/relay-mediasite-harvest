<?php namespace Uninett\Collections\RequestPerHour;
use Uninett\Database\ScreencastSQLConnection;
use Uninett\Schemas\RequestsPerHourSchema;

class RequestPerHourFind
{
	protected $database;

	function __construct(ScreencastSQLConnection $database)
	{
		$this->database = $database;
	}

	public function findData($date)
    {
        $queryString =
            "SELECT * FROM " . RequestsPerHourSchema::SCREENCAST_TABLE_NAME.
            " WHERE Dates = '" . $date->format('Y-m-d H:i:s') . "' ORDER BY Dates DESC" ;

        return $this->database->query($queryString);
    }
}
