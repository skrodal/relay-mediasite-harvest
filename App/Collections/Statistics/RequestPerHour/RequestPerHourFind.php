<?php namespace Uninett\Collection\Statistics\RequestPerHour;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\RequestsPerHourSchema;

class RequestPerHourFind
{
	protected $database;

	function __construct()
	{
		$this->database = new PictorConnection();
	}

	public function findData($date)
    {
        $queryString =
            "SELECT * FROM " . RequestsPerHourSchema::PICTOR_TABLE_NAME.
            " WHERE Dates = '" . $date->format('Y-m-d H:i:s') . "' ORDER BY Dates DESC" ;

        return $this->database->query($queryString);
    }
}
