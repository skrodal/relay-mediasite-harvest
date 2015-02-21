<?php namespace Uninett\Collections\Presentations;
//Insert presentations to Mongodb
use Uninett\Collections\Logging;
use Uninett\Database\MongoConnection;
use Uninett\Models\PresentationModel;
use Uninett\Schemas\PresentationSchema;

class PresentationInsert extends Logging
{
    private $mongo;

    public function __construct()
    {
	    parent::__construct(PresentationSchema::COLLECTION_NAME);

        $this->mongo = new MongoConnection(PresentationSchema::COLLECTION_NAME);
    }

    public function insertNewVideoToMongoDb(PresentationModel $presentation)
    {
        $insertedVideo = false;

        $criteria = array(PresentationSchema::PRESENTATION_ID => $presentation->getPresentationId());

        if($this->fileNotFound($criteria))
            $insertedVideo = $this->mongo->save($presentation->jsonSerialize());
        else
            $this->LogError("Presentation already exists in db. PresentationId: " . $criteria[PresentationSchema::PRESENTATION_ID]);
        return $insertedVideo;
    }

    private function fileNotFound($criteria)
    {
        $count = $this->mongo->findLimitOneCount($criteria);

        if($count == 0)
            return true;
        else
            return false;
    }
}
