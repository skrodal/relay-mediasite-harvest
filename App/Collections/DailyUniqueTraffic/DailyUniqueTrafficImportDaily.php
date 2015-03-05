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

		$this->prepareForImport
		(
			date('Y-m-d', $startDate->sec),
			'today' ,
			'1 day',
			true
		);
	}

	public function logStart($startDate, $endDate)
	{
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}
}
