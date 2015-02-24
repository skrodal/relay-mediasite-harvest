<?php namespace Uninett\Collections\Presentations;
use DateInterval;
use DatePeriod;
use DateTime;
use MongoDate;
use Uninett\Collections\Collection;

use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\MongoConnection;
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
}