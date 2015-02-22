<?php namespace Uninett\Collections\DailyUniqueTraffic;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\DailyUniqueTrafficSchema;

class DailyUniqueTrafficFind {

	protected $database;

	function __construct(PictorConnection $database)
	{
		$this->database = $database;
	}

	public function findData($date)
	{
		$queryString =
			"SELECT * FROM " . DailyUniqueTrafficSchema::PICTOR_TABLE_NAME.
			" WHERE Dates = '" . $date->format('Y-m-d H:i:s') . "' ORDER BY Dates DESC" ;

		return $this->database->query($queryString);
	}

}