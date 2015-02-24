<?php namespace Uninett\Collections\LastUpdates;
//Responsibility: Keep track of the ids fetched from PERSEUS that is synced with mongo

use Carbon\Carbon;
use MongoDate;
use Monolog\Logger;
use Uninett\Collections\Collection;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\LastUpdatesSchema;
use Uninett\Schemas\RequestsPerHourSchema;

class LastUpdates extends Collection
{
    private $mongo;
    private $criteria;

    private $log;

    public function __construct()
    {
	    parent::__construct(LastUpdatesSchema::COLLECTION_NAME);

        $this->log = new Logger('import');

        $this->mongo = new MongoConnection(LastUpdatesSchema::COLLECTION_NAME);

        $this->criteria = array(LastUpdatesSchema::DOCUMENT_KEY => Config::get('settings')['lastupdates_doc_key']);

        if($this->_collectionDoesNotExist())
            $this->createCollection();
    }

    private function _collectionDoesNotExist()
    {
        $cursor = $this->mongo->collection->find($this->criteria)->limit(1)->count();
        if(empty($cursor))
            return true;

        return false;
    }

    private function createCollection()
    {
        $cursor = $this->mongo->findLimitOneCount($this->criteria);

        if (empty($cursor)) {
            //Returns true if inserted
            //Somehow User and FileId needs (int) before 0. Don't know why.
            $document = array
            (
                LastUpdatesSchema::DOCUMENT_KEY => Config::get('settings')['lastupdates_doc_key'],
                LastUpdatesSchema::USER_ID => (int) 0,
                LastUpdatesSchema::PRESENTATION_ID => (int) 0,
                LastUpdatesSchema::LAST_IMPORTED_REQUESTS_DATE => new MongoDate(strtotime(Config::get('settings')['startDateToImportIISLogs']))
            );
            $success = $this->mongo->createLastUpdates($document);

            if($success)
                $this->log->addNotice("Created collection: " .  LastUpdatesSchema::COLLECTION_NAME);

            return $success;
        }
        return false;
    }

    private function updateFieldInCollection($field, $id)
    {
        $operation = '$set';
        $options = array
        (
            'multiple' => false,
            'upsert' => true,
        );

        $updateWentWell = $this->mongo->update($this->criteria, $operation, $field, $id, $options);

        /*if($updateWentWell)
            $this->LogInfo($field. " set to " . $id);*/

        return $updateWentWell;
    }

    public function updateUserId($id)
    {
        return $this->updateFieldInCollection(LastUpdatesSchema::USER_ID, $id);
    }

    public function updatePresentationId($id)
    {
        return $this->updateFieldInCollection(LastUpdatesSchema::PRESENTATION_ID, $id);
    }

    private function find($field)
    {
        //Finds the one document that matches the criteria, which depends on MONGO_LASTUPDATES_DOCUMENT_KEY
        $cursor = $this->mongo->collection->find($this->criteria)->limit(1);

        //Returns the field. It will be found only one field.
        foreach($cursor as $obj)
            return $obj[$field];

        return null;
    }

    public function findUserId()
    {
        return $this->find(LastUpdatesSchema::USER_ID);
    }

    public function findLargestPresentationId()
    {
        return $this->find(LastUpdatesSchema::PRESENTATION_ID);
    }

	public function updateRequestPerHourDate($date)
	{
		return $this->updateFieldInCollection(
			LastUpdatesSchema::LAST_IMPORTED_REQUESTS_DATE,
			new MongoDate(strtotime($date)));
	}

	public function findLastInsertedRequestPerHourDate()
	{
		return $this->find(LastUpdatesSchema::LAST_IMPORTED_REQUESTS_DATE);
	}


	public function updateDailyUniqueTrafficDate($date)
	{
		return $this->updateFieldInCollection(
			LastUpdatesSchema::LAST_IMPORTED_DAILYUNIQUETRAFFIC_DATE,
			new MongoDate(strtotime($date)));
	}

	public function findLastInsertedDailyUniqueTrafficDate()
	{
		return $this->find(LastUpdatesSchema::LAST_IMPORTED_DAILYUNIQUETRAFFIC_DATE);
	}


	public function updateDailyUserAgentcDate($date)
	{
		return $this->updateFieldInCollection(
			LastUpdatesSchema::LAST_IMPORTED_DAILYUSERAGENTS_DATE,
			new MongoDate(strtotime($date)));
	}

	public function findLastInserteDailyUserAgentcDate()
	{
		return $this->find(LastUpdatesSchema::LAST_IMPORTED_DAILYUSERAGENTS_DATE);
	}
}
