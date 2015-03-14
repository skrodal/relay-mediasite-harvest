<?php namespace Uninett\Collections;
use DateInterval;
use DatePeriod;
use DateTime;
use Uninett\Database\MongoConnection;

abstract class StatisticDateImporter extends Collection {
	protected $numberFound = 0;
	protected $numberInserted = 0;

	protected $mongo;

	public function __construct($collection)
	{
		parent::__construct($collection);

		$this->mongo = new MongoConnection($collection);
	}

	public abstract function logStart($startDate, $endDate);
	public abstract function updateDateInMongoDb($date);
	public abstract function run($startDate, $endDate, $datePeriod);


	protected function startImport($date, $create, $find)
	{
		$query = $find->findData($date);

		if ($this->queryContainsNewFiles($query)) {

			while ($result = mssql_fetch_assoc($query)) {

				$this->numberFound = $this->numberFound + 1;

				$objectWasCreatedSuccessfully = $create->createObjectFromResult($result);

				if (!is_null($objectWasCreatedSuccessfully)) {

					$objectWasInsertedSuccessfully = $this->mongo->insert($objectWasCreatedSuccessfully->jsonSerialize());

					if($objectWasInsertedSuccessfully == true)
						$this->numberInserted = $this->numberInserted + 1;

				} else
					$this->LogError("Could not create object from result at {$date}");
			}
		}
	}

	protected function queryContainsNewFiles($query)
	{
		//return $query ? true : false;
		if($query == false)
			return false;

		return true;
	}

	protected function getNextDay($timestamp) {
		$start_date = (new DateTime())->setTimestamp($timestamp);

		return $start_date->modify('+ 1 day');
	}
}