<?php namespace Uninett\Collections\DailyUniqueTraffic;
use DateInterval;
use DatePeriod;
use DateTime;
use Uninett\Collections\Collection;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\StatisticsDateImporterTrait;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\MongoConnection;
use Uninett\Database\PictorConnection;
use Uninett\Schemas\DailyUniqueTrafficSchema;

abstract class DailyUniqueTrafficImport extends Collection implements UpdateInterface
{
	protected $mongo;

	use StatisticsDateImporterTrait;

	public function __construct()
    {
	    parent::__construct(DailyUniqueTrafficSchema::PICTOR_TABLE_NAME);

	    $this->mongo = new MongoConnection(DailyUniqueTrafficSchema::COLLECTION_NAME);
    }

	public abstract function update();
	public abstract function logStart($startDate, $endDate);

	protected function startImport($date)
	{
		$create = new DailyUniqueTrafficCreate();

		$find = new DailyUniqueTrafficFind(new PictorConnection);

		$query = $find->findData($date);

		if ($this->queryContainsNewFiles($query)) {

			while ($result = mssql_fetch_assoc($query)) {

				$this->numberFound = $this->numberFound + 1;

				$objectWasCreatedSuccessfully = $create->createObjectFromResult($result);

				if (!is_null($objectWasCreatedSuccessfully)) {

					$objectWasInsertedSuccessfully = $this->mongo->insert($objectWasCreatedSuccessfully->jsonSerialize());

					if($objectWasInsertedSuccessfully == true)
						$this->numberInserted = $this->numberInserted + 1;

				} else
					$this->LogError("Could not create object from result at {$date}");
			}
		}
	}

	protected function queryContainsNewFiles($query)
	{
		//return $query ? true : false;
		if($query == false)
			return false;

		return true;
	}

	protected function updateDateInMongoDb($date)
	{
		$last = new LastUpdates();
		$last->updateDailyUniqueTrafficDate($date->format('Y-m-d'));
	}

	protected function findLastInsertedDate()
	{
		$last = new LastUpdates();
		return $last->findLastInsertedDailyUniqueTrafficDate();
	}


}
