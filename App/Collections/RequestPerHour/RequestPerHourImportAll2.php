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

class RequestPerHourImportAll2 extends StatisticDateImporter implements UpdateInterface
{
    public function __construct()
    {
	    parent::__construct(RequestsPerHourSchema::COLLECTION_NAME,  new RequestPerHourCreate, new RequestPerHourFind(new PictorConnection));
    }

    public function update()
    {
	    $startDate = $this->findLastInsertedDate();

        $this->prepareForImport
        (
	        date('Y-m-d', $startDate->sec),
            'today',
            '1 hour'
        );
    }

	public function logStart($startDate, $endDate)
	{
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
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
}
