<?php namespace Uninett\Collections\DailyUserAgents;

use DateInterval;
use DatePeriod;
use DateTime;
use Uninett\Collections\Collection;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\MongoConnection;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\DailyUserAgentsSchema;

abstract class DailyUserAgentImport extends Collection
{
	protected $mongo;

	protected $numberFound = 0;
	protected $numberInserted = 0;

    public function __construct()
    {
	    parent::__construct(DailyUserAgentsSchema::COLLECTION_NAME);

	    $this->mongo = new MongoConnection(DailyUserAgentsSchema::COLLECTION_NAME);
    }

	public abstract function update();
	public abstract function logStart($startDate, $endDate);

	protected function prepareForImport($fromDate, $toDate, $interval)
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

	protected function startImport($date)
	{
		$create = new DailyUserAgentCreate;
		$find = new DailyUserAgentFind(new PictorConnection);

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

	protected function updateDateInMongoDb($date)
	{
		$last = new LastUpdates();
		$last->updateDailyUserAgentsDate($date->format('Y-m-d'));
	}

	protected function findLastInsertedDate()
	{
		$last = new LastUpdates();
		return $last->findLastInsertedDailyUserAgentsDate();
	}
}
