<?php namespace Uninett\Collections\Presentations;
use DateInterval;
use DatePeriod;
use DateTime;
use MongoDate;
use Uninett\Collections\Collection;

use Uninett\Collections\UpdateInterface;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\DailyVideosSchema;
use Uninett\Schemas\PresentationSchema;
use Uninett\Schemas\UniqueTrafficSchema;

class PresentationHitsImport extends Collection implements UpdateInterface
{
    private $presentations;
    private $uniqueTraffic;

    private $startDate;
    private $endDate;
    private $interval;

	private $numberInserted = 0;
	private $numberErrors = 0;

    public function __construct($startDate, $endDate, $interval)
    {
	    parent::__construct(PresentationSchema::COLLECTION_NAME);

        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->interval = $interval;

        $this->presentations = new MongoConnection(PresentationSchema::COLLECTION_NAME);

        $this->uniqueTraffic = new MongoConnection(UniqueTrafficSchema::COLLECTION_NAME);
    }

    public function update()
    {
        $count = 0;
        $startDate = new DateTime($this->startDate);
        $endDate = new DateTime($this->endDate);

        $dateInterval = DateInterval::createFromDateString($this->interval);

        $datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);

        foreach ($datePeriod as $dt) {
            $this->find($dt);

            if($this->numberInserted > 0) {
	            $this->LogInfo("{$this->numberInserted} Updated collection with data for date " . $dt->format('Y-m-d H:i:s'));
                $this->numberInserted = 0;
            }
            if($this->numberErrors > 0) {
	            $this->LogInfo("{$this->numberErrors} Updated collection with data for date " . $dt->format('Y-m-d H:i:s'));
	            $this->numberErrors = 0;
            }
            $count++;
        }
        $this->LogInfo("Finished with {$count} imports");
    }

    public function find($date) {
        $criteriaDaily = array(DailyVideosSchema::DATE => new MongoDate(strtotime($date->format('Y-m-d'))));

        $cursor = $this->uniqueTraffic->collection->find($criteriaDaily);

        if($cursor->count() > 0) {
            foreach($cursor as $document) {
	            $criteria = array(
		            PresentationSchema::FILES.'.'.PresentationSchema::PATH => $document[UniqueTrafficSchema::URI]);

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
}