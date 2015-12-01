<?php namespace Uninett\Collections\DailyUserAgents;
use DateInterval;
use DatePeriod;
use DateTime;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\StatisticDateImporter;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\ScreencastSQLConnection;
use Uninett\Helpers\StatisticDate;
use Uninett\Schemas\DailyUserAgentsSchema;

class DailyUserAgentImportAll extends StatisticDateImporter implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct(DailyUserAgentsSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$this->LogInfo("Now running " . get_class() . '...');

		$lastImportedDateInDb = $this->findLastInsertedDate();

		$date = (new StatisticDate)
			->setStartDateTodayByTimestamp($lastImportedDateInDb->sec)
			->setEndDateBystring('today')
			->setDateIntervalFromString('1 day')
			->createDatePeriod();

		$this->log($date->getStartDate(), $date->getEndDate());

		foreach ($date->getDatePeriod() as $dt)
			$this->import(
				$dt,
				new DailyUserAgentCreate,
				new DailyUserAgentFind(new ScreencastSQLConnection)
			);

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($date->getEndDate());

	}

	public function log($startDate, $endDate)
	{
		//Modify endDate by - 1 day because prepareImport does not include endDate.
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to (including) {$endDate->modify('- 1 day')->format('Y-m-d')}");
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
