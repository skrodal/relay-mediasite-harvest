<?php namespace Uninett\Collections\Presentations;
use Uninett\Collections\Collection;

use Uninett\Collections\CollectionUpdateInterface;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\DailyVideosSchema;
use Uninett\Schemas\PresentationSchema;
use Uninett\Schemas\UniqueTrafficSchema;

class PresentationHitsImport extends Collection implements CollectionUpdateInterface
{
    private $_presentationCollection;
    private $_uniqueTrafficCollection;

    private $_startDate;
    private $_endDate;
    private $_interval;

	private $numberInserted = 0;
	private $numberErrors = 0;
    public function __construct($startDate, $endDate, $interval)
    {
	    parent::__construct(PresentationSchema::COLLECTION_NAME);

        $this->_startDate = $startDate;
        $this->_endDate = $endDate;
        $this->_interval = $interval;

        $this->_presentationCollection = new MongoConnection(PresentationSchema::COLLECTION_NAME);

        $this->_uniqueTrafficCollection = new MongoConnection(UniqueTrafficSchema::COLLECTION_NAME);
    }

    public function update()
    {

        $count = 0;
        $startDate = new DateTime($this->_startDate);
        $endDate = new DateTime($this->_endDate);

        $dateInterval = DateInterval::createFromDateString($this->_interval);

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
        $this->LogInfo("Finished with " . $count . " imports");
    }

    public function find($date) {
        $criteriaDaily = array(DailyVideosSchema::DATE => new MongoDate(strtotime($date->format('Y-m-d'))));

        $res = $this->_uniqueTrafficCollection->collection->find($criteriaDaily);

        if($res->count() > 0) {
            foreach($res as $document)
               $this->_increaseHits(array(
                   PresentationSchema::FILES.'.'.PresentationSchema::PATH => $document[UniqueTrafficSchema::URI]));

        } else {
            $this->LogError("Did not find hits for " . $date->format('Y-m-d'));
        }
    }

    private function _increaseHits($criteria) {
        $success1 = $this->_presentationCollection->updateIncrease(
            $criteria,
            array('$inc' => array(PresentationSchema::FILES.'.$.'.PresentationSchema::HITS => 1))
        );

        $success2 =  $this->_presentationCollection->updateIncrease(
            $criteria,
            array('$inc' => array(PresentationSchema::HITS => 1))
        );

        if($success1 && $success2) {
            $this->numberInserted = $this->numberInserted + 1;
        }
        else
            $this->numberErrors = $this->numberErrors + 1;
    }
}