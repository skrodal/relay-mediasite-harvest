<?php namespace Uninett\Collections\RequestPerHour;
//This class imports yesterdays related IIS-logdata
use DateInterval;
use DatePeriod;
use DateTime;

use Uninett\Collections\Collection;
use Uninett\Collections\StatisticDateImporter;
use Uninett\Collections\UpdateInterface;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\MongoConnection;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\RequestsPerHourSchema;

class RequestPerHourImportDaily2 extends StatisticDateImporter implements UpdateInterface
{
    public function __construct()
    {
	    parent::__construct(RequestsPerHourSchema::COLLECTION_NAME);
    }

    public function update()
    {
	    $startDate = $this->findLastInsertedDate();

        $this->prepareForImportAndExludeStartDate
        (
	        date('Y-m-d', $startDate->sec),
	        'today' ,
            '1 hour'
        );
    }

	public function setup()
	{
		$this->create = new RequestPerHourCreate;
		$this->find = new RequestPerHourFind(new PictorConnection);
	}

	public function updateDateInMongoDb($date)
	{
		$last = new LastUpdates();
		$last->updateRequestPerHourDate($date->format('Y-m-d'));
	}

	public function findLastInsertedDate()
	{
		$last = new LastUpdates();
		return $last->findLastInsertedRequestPerHourDate();
	}


	public function logStart($startDate, $endDate)
	{
		$this->LogInfo("Starting to import for {$startDate->format('Y-m-d')}");
	}
}
