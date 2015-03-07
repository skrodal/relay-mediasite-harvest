<?php namespace Uninett\Collections;
use DateInterval;
use DatePeriod;
use DateTime;

trait StatisticsDateImporterTrait {
	protected $numberFound = 0;
	protected $numberInserted = 0;

	public function prepareForImport($fromDate, $toDate, $interval)
	{
		$startDate = new DateTime($fromDate);
		$endDate = new DateTime($toDate);

		$dateInterval = DateInterval::createFromDateString($interval);

		$datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);

		$this->logStart($startDate, $endDate);

		foreach ($datePeriod as $dt)
			$this->startImport($dt);

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($endDate);
	}

	public function prepareForImportAndExludeStartDate($fromDate, $toDate, $interval)
	{
		$startDate = new DateTime($fromDate);
		$endDate = new DateTime($toDate);

		$dateInterval = DateInterval::createFromDateString($interval);

		$datePeriod = new DatePeriod($startDate, $dateInterval, $endDate, DatePeriod::EXCLUDE_START_DATE);

		$this->logStart($startDate, $endDate);

		foreach ($datePeriod as $dt)
			$this->startImport($dt);

		$this->LogInfo("Found {$this->numberFound} results");
		$this->LogInfo("Inserted {$this->numberInserted} results");

		$this->updateDateInMongoDb($endDate);
	}
}