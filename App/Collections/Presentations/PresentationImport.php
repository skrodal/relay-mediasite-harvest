<?php namespace Uninett\Collections\Presentations;

//Queries Perseus DB for new files based on largest fileId in MongoDB and inserts to MongoDB
use Uninett\Collections\Collection;
use Uninett\Collections\CollectionUpdateInterface;
use Uninett\Helpers\Convert;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Schemas\PresentationSchema;

class PresentationImport extends Collection implements CollectionUpdateInterface
{

    private $insert;
    private $find;

	private $currentPresentationId;
    private $shouldUpdateDailyVideosCollection;

	private $numberInserted;
	private $numberFound;

    public function __construct($shouldUpdateDailyVideosCollection = false)
    {
	    parent::__construct(PresentationSchema::COLLECTION_NAME);

        $this->currentPresentationId = $this->getLargestInsertedFileId();

	    $this->find = new PresentationFind($this->currentPresentationId);

        $this->insert = new PresentationInsert();

        $this->shouldUpdateDailyVideosCollection = $shouldUpdateDailyVideosCollection;

    }

    private function getLargestInsertedFileId()
    {
        $largestInsertedFileId = new LastUpdates();

        return $largestInsertedFileId->findLargestPresentationId();
    }

    public function update()
    {
        $this->findAndInsertNewVideos();

	    $this->LogInfo("Finished importing {$this->numberInserted} presentations");

        if($this->numberInserted > 0)
            $this->updateLargestPresentationIdInMongoDb();

	    //TODO: Implement daily videos
        //if($this->updateDailyVideos)
        //    $this->_updateDailyVideos();
    }

    private function findAndInsertNewVideos()
    {
        $convertedPath = new Convert();

        $objectCreator = new PresentationCreate();

        $presentationsNotFound = 0;

        $largestPresentationIdFromSource = $this->findLargestPresentationIdFromSource();

        if($largestPresentationIdFromSource == false)
        {
            $this->LogError("Could not retrieve largest presentationId from database");
            return;
        }
        while($largestPresentationIdFromSource != $this->currentPresentationId) {

            $query = $this->find->findPresentationWithId($this->currentPresentationId);

            $arrayWithPathToXMLFilesForPresentation = array();

            if($this->presentationIdContainsPresentation($query)) {

                $this->numberFound = $this->numberFound + 1;

                $presentationIdFromResult = null;

                while ($presentation = mssql_fetch_assoc($query)) {
                    $path = $convertedPath->convertExternalToLocalPath($presentation['filePath']);

                    if ($this->presentationDoesNotExistOnDisk($path))
                        break;
                    else {
                        $arr = array('path' => $path, 'id' => $presentation['filePresentation_presId']);
                        array_push($arrayWithPathToXMLFilesForPresentation, $arr);
                    }
                }
                  if(count($arrayWithPathToXMLFilesForPresentation) > 0) {
                    $newPresentation = $objectCreator->createPresentationFromArrayResult($arrayWithPathToXMLFilesForPresentation);

                    if(!is_null($newPresentation))
                        $this->insertPresentationToMongoDb($newPresentation);
                }
            } else
                $presentationsNotFound = $presentationsNotFound + 1;

            $this->currentPresentationId++;
        }
    }

    private function findLargestPresentationIdFromSource()
    {


        $max = $this->find->findHighestPresentationsId();

        $maxRes = mssql_fetch_assoc($max);

        if($maxRes == false)
            return false;

        return (int)$maxRes['computed'];
    }

    private function presentationIdContainsPresentation($query)
    {
        if($query == false)
            return false;

        return true;
    }

    private function presentationDoesNotExistOnDisk($path)
    {
        return !file_exists($path);
    }

    private function insertPresentationToMongoDb($newFile)
    {
        $inserted = $this->insert->insertNewVideoToMongoDb($newFile);

        if ($inserted)
            $this->numberInserted = $this->numberInserted + 1;
    }

    private function updateLargestPresentationIdInMongoDb()
    {
        $largestIdInMongoDb = new LastUpdates();
        $largestIdInMongoDb->updatePresentationId($this->currentPresentationId);
    }

    private function _updateDailyVideos()
    {
	    //TODO: Implement daily video
       // $dv = new DailyVideoImport();
       // $dv->insert($this->_log->numberInserted);
    }
}
