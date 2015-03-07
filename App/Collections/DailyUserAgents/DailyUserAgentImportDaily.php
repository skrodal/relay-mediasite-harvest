<?php namespace Uninett\Collections\DailyUserAgents;
use Uninett\Collections\UpdateInterface;
use Uninett\Schemas\DailyUserAgentsSchema;

class DailyUserAgentImportDaily extends DailyUserAgentImport  implements UpdateInterface
{
	public function __construct()
	{
		parent::__construct(DailyUserAgentsSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$startDate = $this->findLastInsertedDate();

		$this->prepareForImportAndExludeStartDate
		(
			date('Y-m-d', $startDate->sec),
			'today' ,
			'1 day'
		);
	}

	public function logStart($startDate, $endDate)
	{
		$this->LogInfo("Starting to import data for {$startDate->format('Y-m-d')}");
	}
}
