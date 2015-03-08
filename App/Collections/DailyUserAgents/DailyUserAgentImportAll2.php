<?php namespace Uninett\Collections\DailyUserAgents;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\StatisticDateImporter;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\DailyUserAgentsSchema;

class DailyUserAgentImportAll2 extends StatisticDateImporter implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct(DailyUserAgentsSchema::COLLECTION_NAME,  new DailyUserAgentCreate, new DailyUserAgentFind(new PictorConnection));
	}

	public function update()
	{
		$startDate = $this->findLastInsertedDate();

		$this->prepareForImport
		(
			date('Y-m-d', $startDate->sec),
			'today',
			'1 day'
		);
	}

	public function logStart($startDate, $endDate)
	{
		//Modify endDate by - 1 day because prepareImport does not include endDate.
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}

	public function updateDateInMongoDb($date)
	{
		$last = new LastUpdates();
		$last->updateRequestPerHourDate($date->format('Y-m-d'));
	}

	public function findLastInsertedDate()
	{
		$last = new LastUpdates();
		$this->LogInfo($last->findLastInsertedRequestPerHourDate());
		return $last->findLastInsertedRequestPerHourDate();
	}
}
