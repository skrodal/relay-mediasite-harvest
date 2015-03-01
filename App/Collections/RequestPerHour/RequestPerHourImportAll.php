<?php namespace Uninett\Collections\RequestPerHour;
//This class imports yesterdays related IIS-logdata
use DateInterval;
use DatePeriod;
use DateTime;

use Uninett\Collections\Collection;
use Uninett\Collections\UpdateInterface;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\MongoConnection;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\RequestsPerHourSchema;

class RequestPerHourImportAll extends RequestPerHourImport implements UpdateInterface
{
    public function __construct()
    {
	    parent::__construct(RequestsPerHourSchema::COLLECTION_NAME);
    }

    public function update()
    {
	    $startDate = $this->findLastInsertedDate();

        $this->prepareForImport
        (
	        date('Y-m-d', $startDate->sec),
            'today - 1 day',
            '1 hour',
	        false
        );
    }

	public function logStart($startDate, $endDate)
	{
		//Modify endDate by - 1 day because prepareImport does not include endDate.
		$this->LogInfo("Starting to import data from {$startDate->format('Y-m-d')} to {$endDate->modify('- 1 day')->format('Y-m-d')}");
	}
}
