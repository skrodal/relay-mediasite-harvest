<?php namespace Uninett\Collections\DailyUniqueTraffic;
use Uninett\Database\ScreencastSQLConnection;
use Uninett\Schemas\DailyUniqueTrafficSchema;

class DailyUniqueTrafficFind {

	protected $database;

	function __construct(ScreencastSQLConnection $database)
	{
		$this->database = $database;
	}

	public function findData($date)
	{
		$queryString =
			"SELECT * FROM " . DailyUniqueTrafficSchema::SCREENCAST_TABLE_NAME.
			" WHERE Dates = '" . $date->format('Y-m-d H:i:s') . "' ORDER BY Dates DESC" ;

		return $this->database->query($queryString);
	}

}