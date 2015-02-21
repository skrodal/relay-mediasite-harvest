<?php namespace Uninett\Collection\Statistics\RequestPerHour;
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

class RequestPerHourImport extends Collection implements UpdateInterface
{
	protected $create;
	protected $find;

	protected $numberFound;
	protected $numberInserted;

	protected $date;

	protected $mongo;

    public function __construct()
    {
	    $this->create = new RequestPerHourCreate();

	    $this->find = new RequestPerHourFind(new PictorConnection);

	    $this->mongo = new MongoConnection(RequestsPerHourSchema::COLLECTION_NAME);
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

    public function prepareForImport($fromDate, $toDate, $interval)
    {
        $startDate = new DateTime($fromDate);
        $endDate = new DateTime($toDate);

        $dateInterval = DateInterval::createFromDateString($interval);
        $datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);

        foreach ($datePeriod as $dt)
            $this->startImport($dt);

	    $this->LogInfo('Found {$this->numberFound} results');
	    $this->LogInfo('Inserted {$this->numberInserted} results');

	    $this->updateDateInMongoDb($endDate);
    }

	protected function startImport($date)
	{
		$query = $this->find->findData($date);

		if ($this->queryContainsNewFiles($query)) {

			while ($result = mssql_fetch_assoc($query)) {

				$this->numberFound = $this->numberFound + 1;

				$objectWasCreatedSuccessfully = $this->create->createObjectFromResult($result);

				if (!is_null($objectWasCreatedSuccessfully)) {

					$objectWasInsertedSuccessfully = $this->mongo->insert($objectWasCreatedSuccessfully->jsonSerialize());

					if($objectWasInsertedSuccessfully == true)
						$this->numberInserted = $this->numberInserted + 1;
				} else
					$this->LogError("Could not create object from result at {$date}");

			}
		}
	}

	private function queryContainsNewFiles($query)
	{
		//return $query ? true : false;
		if($query == false)
			return false;

		return true;
	}

	private function updateDateInMongoDb()
	{
		$last = new LastUpdates();
		$last->updateRequestPerHourDate($this->date);
	}

	private function findLastInsertedDate()
	{
		$last = new LastUpdates();
		return $last->findLastInsertedRequestPerHourDate();
	}
}
