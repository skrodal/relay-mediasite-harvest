<?php namespace Uninett\Collections\RequestPerHour;
//This class imports yesterdays related IIS-logdata

use Uninett\Collections\StatisticDateImporter;
use Uninett\Collections\UpdateInterface;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\RequestsPerHourSchema;

class RequestPerHourImportDaily extends StatisticDateImporter implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct(RequestsPerHourSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$startDate = $this->findLastInsertedDate();

		$this->import
		(
			date('Y-m-d', $startDate->sec),
			'today + 1 day' ,
			'1 hour',
			true
		);
	}

	public function run($startDate, $endDate, $datePeriod) {
		$this->logStart($startDate, $endDate);

		foreach ($datePeriod as $dt)
		{
			$this->LogInfo("Importing {$dt->format('Y-m-d H:i:s')} results");

			$this->startImport(
				$dt,
				new RequestPerHourCreate,
				new RequestPerHourFind(new PictorConnection)
			);
		}


		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($endDate);
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

	public function logStart($startDate, $endDate)
	{
		$this->LogInfo("Starting to import data for {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}
}
