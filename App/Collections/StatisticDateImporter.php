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

	public function import($fromDate, $toDate, $interval, $includeStartDate)
	{
		$startDate = new DateTime($fromDate);
		$endDate = new DateTime($toDate);
		$dateInterval = DateInterval::createFromDateString($interval);

		if($includeStartDate)
		{
			$this->LogError("Including start date");
			$this->run(
				$startDate,
				$endDate,
				new DatePeriod($startDate, $dateInterval, $endDate, DatePeriod::EXCLUDE_START_DATE)
			);
		}

		else
		{
			$this->LogError("Excluding start date");
			$this->run(
				$startDate,
				$endDate,
				new DatePeriod($startDate, $dateInterval, $endDate, DatePeriod::EXCLUDE_START_DATE)
			);
		}
	}

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
}