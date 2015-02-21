<?php namespace Uninett\Collection\Statistics\RequestPerHour;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\RequestsPerHourSchema;

class RequestPerHourFind
{
	protected $database;

	function __construct(PictorConnection $database)
	{
		$this->database = $database;
	}

	public function findData($date)
    {
        $queryString =
            "SELECT * FROM requestsPerHour" .
            " WHERE Dates = '" . $date->format('Y-m-d H:i:s') . "' ORDER BY Dates DESC" ;

        return $this->database->query($queryString);
    }
}
