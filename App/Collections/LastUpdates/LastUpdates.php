<?php namespace Uninett\Collections\LastUpdates;
//Responsibility: Keep track of the ids fetched from PERSEUS that is synced with mongo

use Carbon\Carbon;
use Monolog\Logger;
use Uninett\Collections\Collection;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\LastUpdatesSchema;

class LastUpdates extends Collection
{
    private $_mongo;
    private $_criteria;

    private $_log;

    public function __construct()
    {
	    parent::__construct(LastUpdatesSchema::COLLECTION_NAME);

        $this->_log = new Logger('import');

        $this->_mongo = new MongoConnection(LastUpdatesSchema::COLLECTION_NAME);

        $this->_criteria = array(LastUpdatesSchema::DOCUMENT_KEY => Config::get('settings')['lastupdates_doc_key']);

        if($this->_collectionDoesNotExist())
            $this->createCollection();
    }

    private function _collectionDoesNotExist()
    {
        $cursor = $this->_mongo->collection->find($this->_criteria)->limit(1)->count();
        if(empty($cursor))
            return true;

        return false;
    }

    private function createCollection()
    {
        $cursor = $this->_mongo->findLimitOneCount($this->_criteria);

        if (empty($cursor)) {
            //Returns true if inserted
            //Somehow User and FileId needs (int) before 0. Don't know why.
            $document = array
            (
                LastUpdatesSchema::DOCUMENT_KEY => Config::get('settings')['lastupdates_doc_key'],
                LastUpdatesSchema::USER_ID => (int) 0,
                LastUpdatesSchema::PRESENTATION_ID => (int) 0,
            );
            $success = $this->_mongo->createLastUpdates($document);

            if($success)
                $this->_log->addNotice("Created collection: " .  LastUpdatesSchema::COLLECTION_NAME);

            return $success;
        }
        return false;
    }

    private function _updateFieldInCollection($field, $id)
    {
        $operation = '$set';
        $options = array
        (
            'multiple' => false,
            'upsert' => true,
        );

        $updateWentWell = $this->_mongo->update($this->_criteria, $operation, $field, $id, $options);

        if($updateWentWell)
            $this->LogInfo($field. " set to " . $id);

        return $updateWentWell;
    }

    public function updateUserId($id)
    {
        return $this->_updateFieldInCollection(LastUpdatesSchema::USER_ID, $id);
    }

    public function updatePresentationId($id)
    {
        return $this->_updateFieldInCollection(LastUpdatesSchema::PRESENTATION_ID, $id);
    }

    private function find($field)
    {
        //Finds the one document that matches the criteria, which depends on MONGO_LASTUPDATES_DOCUMENT_KEY
        $cursor = $this->_mongo->collection->find($this->_criteria)->limit(1);

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
}
