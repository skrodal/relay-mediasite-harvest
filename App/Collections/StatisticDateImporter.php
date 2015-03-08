<?php namespace Uninett\Collections;
use DateInterval;
use DatePeriod;
use DateTime;
use Uninett\Database\MongoConnection;

abstract class StatisticDateImporter extends Collection {
	protected $numberFound = 0;
	protected $numberInserted = 0;

	protected $create;
	protected $find;

	protected $mongo;

	use StatisticsDateImporterTrait;

	public function __construct($collection)
	{
		parent::__construct($collection);

		$this->mongo = new MongoConnection($collection);
	}

	public abstract function update();
	public abstract function logStart($startDate, $endDate);

	public abstract function setup();

	public abstract function updateDateInMongoDb($date);
	public abstract function findLastInsertedDate();

	protected function startImport($date)
	{
	/*	$create = new DailyUserAgentCreate;*/
/*		$find = new DailyUserAgentFind(new PictorConnection);*/

		$query = $this->find->findData($date);

		if ($this->queryContainsNewFiles($query)) {

			while ($result = mssql_fetch_assoc($query)) {

				$this->numberFound = $this->numberFound + 1;

				$objectWasCreatedSuccessfully = $this->create->createObjectFromResult($result);

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

	public function prepareForImport($fromDate, $toDate, $interval)
	{
		$startDate = new DateTime($fromDate);
		$endDate = new DateTime($toDate);

		$dateInterval = DateInterval::createFromDateString($interval);

		$datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);

		$this->logStart($startDate, $endDate);

		foreach ($datePeriod as $dt)
			$this->startImport($dt);

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($endDate);
	}

	public function prepareForImportAndExludeStartDate($fromDate, $toDate, $interval)
	{
		$startDate = new DateTime($fromDate);
		$endDate = new DateTime($toDate);

		$dateInterval = DateInterval::createFromDateString($interval);

		$datePeriod = new DatePeriod($startDate, $dateInterval, $endDate, DatePeriod::EXCLUDE_START_DATE);

		$this->logStart($startDate, $endDate);

		foreach ($datePeriod as $dt)
			$this->startImport($dt);

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($endDate);
	}

}