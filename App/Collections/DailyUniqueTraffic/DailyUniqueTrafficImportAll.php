<?php namespace Uninett\Collections\DailyUniqueTraffic;

use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\StatisticDateImporter;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\ScreencastSQLConnection;
use Uninett\Helpers\StatisticDate;
use Uninett\Schemas\DailyUniqueTrafficSchema;

class DailyUniqueTrafficImportAll extends StatisticDateImporter implements UpdateInterface {
	public function __construct() {
		parent::__construct(DailyUniqueTrafficSchema::COLLECTION_NAME);
	}

	public function update() {
		$this->LogInfo("Start");

		$lastImportedDateInDb = $this->findLastInsertedDate();

		$date = (new StatisticDate)
			->setStartDateTodayByTimestamp($lastImportedDateInDb->sec)
			->setEndDateBystring('today')
			->setDateIntervalFromString('1 day')
			->createDatePeriod();

		$this->log($date->getStartDate(), $date->getEndDate());

		foreach($date->getDatePeriod() as $dt) {
			$this->import(
				$dt,
				new DailyUniqueTrafficCreate,
				new DailyUniqueTrafficFind(new ScreencastSQLConnection)
			);
		}

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($date->getEndDate());

	}

	protected function findLastInsertedDate() {
		$last = new LastUpdates();

		return $last->findLastInsertedDailyUniqueTrafficDate();
	}

	public function log($startDate, $endDate) {
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to (including) {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}

	public function updateDateInMongoDb($date) {
		$last = new LastUpdates();
		$last->updateDailyUniqueTrafficDate($date->format('Y-m-d'));
	}
}
