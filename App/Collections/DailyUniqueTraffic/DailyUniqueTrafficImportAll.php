<?php namespace Uninett\Collections\DailyUniqueTraffic;
use Uninett\Schemas\DailyUniqueTrafficSchema;

class DailyUniqueTrafficImportAll extends DailyUniqueTrafficImport
{
	public function __construct()
	{
		parent::__construct(DailyUniqueTrafficSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$startDate = $this->findLastInsertedDate();

		$this->prepareForImport
		(
			date('Y-m-d', $startDate->sec),
			'today - 1 day',
			'1 hour'
		);

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");
	}

	public function logStart($startDate, $endDate)
	{
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
	}
}
