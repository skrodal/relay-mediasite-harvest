<?php namespace Uninett\Collections\RequestPerHour;
//This class imports yesterdays related IIS-logdata

use DatePeriod;
use DateTime;
use DateInterval;
use Uninett\Collections\StatisticDateImporter;
use Uninett\Collections\UpdateInterface;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\PictorConnection;
use Uninett\Helpers\StatisticDate;
use Uninett\Schemas\RequestsPerHourSchema;

class RequestPerHourImportDaily extends StatisticDateImporter implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct(RequestsPerHourSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$lastImportedDateInDb = $this->findLastInsertedDate();

		$date = (new StatisticDate)
			->setStartDateNextDayByTimestamp($lastImportedDateInDb->sec)
			->setEndDateBystring('today')
			->setDateIntervalFromString('1 hour')
			->createDatePeriod();

		$this->log($date->getStartDate(), $date->getEndDate());

		foreach ($date->getDatePeriod() as $dt) {
			$this->LogInfo("Importing data for {$dt->format('Y-m-d H:i:s')}");

			$this->import(
				$dt,
				new RequestPerHourCreate,
				new RequestPerHourFind(new PictorConnection)
			);
		}

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->LogInfo("Updating startDate for requestPerHour with {$date->getDateForDatabase()->format('Y-m-d')}");


		$this->updateDateInMongoDb($date->getDateForDatabase());
	}


	public function updateDateInMongoDb($date)
	{
		$last = new LastUpdates();
		$last->updateRequestPerHourDate($date->format('Y-m-d'));
	}

	public function findLastInsertedDate()
	{
		$last = new LastUpdates();
		return $last->findLastInsertedRequestPerHourDate();
	}

	public function log($startDate, $endDate)
	{
		$this->LogInfo("Starting to import data for {$startDate->format('Y-m-d')}  - {$endDate->format('Y-m-d')}");

		//$this->LogInfo("Starting to import data for {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}
}
