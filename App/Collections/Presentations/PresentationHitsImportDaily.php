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

class PresentationHitsImportDaily extends PresentationHitsImport  implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct();
	}

	public function update()
	{
		$lastImportedDateInDb = $this->findLastInsertedDate();

		$date = (new StatisticDate)
			->setStartDateNextDayByTimestamp($lastImportedDateInDb->sec)
			->setEndDateBystring('today')
			->setDateIntervalFromString('1 day')
			->createDatePeriod();

		$this->logStart($date->getStartDate(), $date->getEndDate());

		foreach ($date->getDatePeriod() as $dt) {

			$this->import($dt);

			$this->logAndResetCounters($dt);
		}

		$this->updateDateInMongoDb($date->getDateForDatabase());
	}

	public function logStart($startDate, $endDate)
	{
		$this->LogInfo("Importing data for {$startDate->format('Y-m-d')}");
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
}