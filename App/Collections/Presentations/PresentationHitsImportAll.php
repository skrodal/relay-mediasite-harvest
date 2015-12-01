<?php namespace Uninett\Collections\Presentations;
use DateInterval;
use DatePeriod;
use DateTime;
use MongoDate;
use Uninett\Collections\Collection;

use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\MongoConnection;
use Uninett\Helpers\StatisticDate;
use Uninett\Schemas\DailyVideosSchema;
use Uninett\Schemas\PresentationSchema;
use Uninett\Schemas\DailyUniqueTrafficSchema;

class PresentationHitsImportAll extends PresentationHitsImport implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct();
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

		$this->logStart($date->getStartDate(), $date->getEndDate());

		foreach ($date->getDatePeriod() as $dt) {
			$this->import($dt);
			$this->logAndResetCounters($dt);
		}

		$this->updateDateInMongoDb($date->getEndDate());
	}


	private function logAndResetCounters($dt)
	{
		if($this->numberInserted > 0) {
			$this->LogInfo("Inserted {$this->numberInserted} results for {$dt->format('Y-m-d')}");
			$this->numberInserted = 0;
		}
		if($this->numberErrors > 0) {
			$this->LogError("Error when importing data for {$dt->format('Y-m-d')}");
			$this->numberErrors = 0;
		}
		$this->numberFound = $this->numberFound + 1;
	}
	public function logStart($startDate, $endDate)
	{
		//Modify endDate by - 1 day because import does not include endDate.
		$this->LogInfo("Importing data from {$startDate->format('Y-m-d')} to {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}
}