<?php namespace Uninett\Collections\DailyUserAgents;
use Uninett\Schemas\DailyUserAgentsSchema;

class DailyUserAgentImportDaily extends DailyUserAgentImport
{
	public function __construct()
	{
		parent::__construct(DailyUserAgentsSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$startDate = $this->findLastInsertedDate();

		$this->prepareForImport
		(
			date('Y-m-d', $startDate->sec),
			'today + 1 day',
			'1 hour'
		);
	}

	public function logStart($startDate, $endDate)
	{
		//Modify endDate by - 1 day because prepareImport does not include endDate.
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}
}