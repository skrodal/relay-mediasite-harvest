<?php namespace Uninett\Collections\DailyUniqueTraffic;
use DateTime;
use Uninett\Schemas\DailyUniqueTrafficSchema;

class DailyUniqueTrafficImportDaily extends DailyUniqueTrafficImport
{
	public function __construct()
	{
		parent::__construct(DailyUniqueTrafficSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$startDate = $this->findLastInsertedDate();

		$start = date_create();

		date_timestamp_set($start, $startDate->sec);

		$start = $start->modify('-1 day')->format('Y-m-d');

		$this->prepareForImport
		(
			$start,
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
