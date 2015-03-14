<?php namespace Uninett\Collections\DailyUniqueTraffic;
use DateInterval;
use DatePeriod;
use DateTime;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\StatisticDateImporter;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\PictorConnection;
use Uninett\Helpers\StatisticDate;
use Uninett\Schemas\DailyUniqueTrafficSchema;

class DailyUniqueTrafficImportDaily extends StatisticDateImporter implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct(DailyUniqueTrafficSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$lastImportedDateInDb = $this->findLastInsertedDate();

		$date = (new StatisticDate)
			->setStartDateNextDayByTimestamp($lastImportedDateInDb->sec)
			->setEndDateBystring('today')
			->setDateIntervalFromString('1 day')
			->setDatePeriod();

		$this->log($date->getStartDate(), $date->getEndDate());

		foreach ($date->getDatePeriod() as $dt)
		{
			$this->LogInfo("Importing data for {$dt->format('Y-m-d H:i:s')}");

			$this->import(
				$dt,
				new DailyUniqueTrafficCreate,
				new DailyUniqueTrafficFind(new PictorConnection)
			);
		}

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($date->getStartDate());
	}

	public function run($startDate, $endDate, $datePeriod) {
		$this->log($startDate, $endDate);

		foreach ($datePeriod as $dt)
			$this->import(
				$dt,
				new DailyUniqueTrafficCreate,
				new DailyUniqueTrafficFind(new PictorConnection)
			);

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($startDate);
	}

	public function log($startDate, $endDate)
	{
		$this->LogInfo("Starting to import data for {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}

	public function updateDateInMongoDb($date)
	{
		$last = new LastUpdates();
		$last->updateDailyUniqueTrafficDate($date->format('Y-m-d'));
	}

	protected function findLastInsertedDate()
	{
		$last = new LastUpdates();
		return $last->findLastInsertedDailyUniqueTrafficDate();
	}
}
