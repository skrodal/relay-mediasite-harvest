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

abstract class PresentationHitsImport extends Collection
{
    private $presentations;
    private $uniqueTraffic;


	protected $numberInserted = 0;
	protected $numberFound = 0;
	protected $numberErrors = 0;


	public abstract function update();
	public abstract function logStart($startDate, $endDate);

    public function __construct()
    {
	    parent::__construct(PresentationSchema::COLLECTION_NAME);

        $this->presentations = new MongoConnection(PresentationSchema::COLLECTION_NAME);

        $this->uniqueTraffic = new MongoConnection(DailyUniqueTrafficSchema::COLLECTION_NAME);
    }

	protected function prepareForImport($fromDate, $toDate, $interval, $excludeStartDate)
	{
		$startDate = new DateTime($fromDate);
		$endDate = new DateTime($toDate);

		$dateInterval = DateInterval::createFromDateString($interval);

		if($excludeStartDate)
			$datePeriod = new DatePeriod($startDate, $dateInterval, $endDate, DatePeriod::EXCLUDE_START_DATE);
		else
			$datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);

		$this->logStart($startDate, $endDate);

		foreach ($datePeriod as $dt) {
			$this->startImport($dt);

			if($this->numberInserted > 0) {
				$this->LogInfo("Inserted {$this->numberInserted} results for {$dt->format('Y-m-d')}");
				$this->numberInserted = 0;
			}
			if($this->numberErrors > 0) {
				$this->LogError("Error when importing data for {$dt->format('Y-m-d')}");
				$this->numberErrors = 0;

			}
			$this->numberFound = $this->numberFound + 1;
		}

		$this->LogInfo("Found {$this->numberFound} results");

		$this->updateDateInMongoDb($endDate);
	}

	protected function prepareForImportWithOutStartDate($fromDate, $toDate, $interval)
	{
		$startDate = new DateTime($fromDate);
		$endDate = new DateTime($toDate);

		$dateInterval = DateInterval::createFromDateString($interval);

		$datePeriod = new DatePeriod($startDate, $dateInterval, $endDate, DatePeriod::EXCLUDE_START_DATE);

		$this->logStart($startDate, $endDate);

		foreach ($datePeriod as $dt) {
			$this->startImport($dt);

			if($this->numberInserted > 0) {
				$this->LogInfo("Inserted {$this->numberInserted} results for {$dt->format('Y-m-d')}");
				$this->numberInserted = 0;
			}
			if($this->numberErrors > 0) {
				$this->LogError("Error when importing data for {$dt->format('Y-m-d')}");
				$this->numberErrors = 0;

			}
			$this->numberFound = $this->numberFound + 1;
		}

		$this->LogInfo("Found {$this->numberFound} results");

		$this->updateDateInMongoDb($endDate);
	}


	public function startImport($date) {
        $criteriaDaily = array(DailyVideosSchema::DATE => new MongoDate(strtotime($date->format('Y-m-d'))));

        $cursor = $this->uniqueTraffic->collection->find($criteriaDaily);

        if($cursor->count() > 0) {
            foreach($cursor as $document) {
	            $criteria = array(
		            PresentationSchema::FILES.'.'.PresentationSchema::PATH => $document[DailyUniqueTrafficSchema::URI]);

	            $this->increaseHits($criteria);
            }
        } else {
            $this->LogError("Did not find hits for " . $date->format('Y-m-d'));
        }
    }

    private function increaseHits($criteria) {

        $updateHitsForFile = $this->presentations->updateIncrease(
            $criteria,
            array('$inc' => array(PresentationSchema::FILES.'.$.'.PresentationSchema::HITS => 1))
        );

        $updateTotalHits =  $this->presentations->updateIncrease(
            $criteria,
            array('$inc' => array(PresentationSchema::HITS => 1))
        );

        if($updateHitsForFile && $updateTotalHits) {
            $this->numberInserted = $this->numberInserted + 1;
        }
        else
            $this->numberErrors = $this->numberErrors + 1;
    }

	protected function updateDateInMongoDb($date)
	{
		$last = new LastUpdates();
		$last->updatePresentationHitsDate($date->format('Y-m-d'));
	}

	protected function findLastInsertedDate()
	{
		$last = new LastUpdates();
		return $last->findLastInsertedPresentationHitsDate();
	}
}