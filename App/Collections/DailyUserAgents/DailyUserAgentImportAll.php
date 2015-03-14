<?php namespace Uninett\Collections\DailyUserAgents;
use DateInterval;
use DatePeriod;
use DateTime;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\StatisticDateImporter;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\DailyUserAgentsSchema;

class DailyUserAgentImportAll extends StatisticDateImporter implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct(DailyUserAgentsSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$lastImportedDateInDb = $this->findLastInsertedDate();

		$fromDate = $this->getTodaysDateFromUnixTimestamp($lastImportedDateInDb->sec);
		$toDate = new DateTime('today');
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($fromDate, $interval, $toDate);

		$this->run($fromDate, $toDate, $period);

	}

	public function run($startDate, $endDate, $datePeriod) {
		$this->logStart($startDate, $endDate);

		foreach ($datePeriod as $dt)
			$this->import(
				$dt,
				new DailyUserAgentCreate,
				new DailyUserAgentFind(new PictorConnection)
			);

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($endDate);
	}

	public function logStart($startDate, $endDate)
	{
		//Modify endDate by - 1 day because prepareImport does not include endDate.
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}

	public function updateDateInMongoDb($date)
	{
		$last = new LastUpdates();
		$last->updateDailyUserAgentsDate($date->format('Y-m-d'));
	}

	public function findLastInsertedDate()
	{
		$last = new LastUpdates();

		return $last->findLastInsertedDailyUserAgentsDate();
	}
}
