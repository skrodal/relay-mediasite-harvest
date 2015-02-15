<?php namespace Uninett\Collections\Presentations;
// Checks if a presentation exists on disk, if not, it changes deleted attribute in mongo db from 0 (not deleted) to 1 (deleted)
use Uninett\Collections\Collection;
use Uninett\Collections\CollectionUpdateInterface;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\PresentationSchema;

class PresentationCheckForDeleted extends Collection implements CollectionUpdateInterface
{
    private $mongo;

    public function __construct()
    {
	    parent::__construct(PresentationSchema::COLLECTION_NAME);

        $this->mongo = new MongoConnection(PresentationSchema::COLLECTION_NAME);
    }

    public function update()
    {
        $currentPresentationId = 0;
        $stopId = $this->getLargestInsertedFileId();

        while($currentPresentationId <= $stopId) {

            $criteria = array(
                PresentationSchema::PRESENTATION_ID => $currentPresentationId,
                PresentationSchema::DELETED => 0
            );

            $presentationIsFoundInDb = $this->mongo->collection->find($criteria)->count();

            if($presentationIsFoundInDb != 0) {
                $document = $this->_getPartOfPresentation($this->mongo, $currentPresentationId);

                if(isset($document['result']['0'])) {
                    $shortPath = $document['result']['0'][PresentationSchema::FILES][PresentationSchema::PATH];

                    $localPath = $this->_convertToLocalPath($shortPath);

                    if($this->_presentationDoesNotExist($localPath))
                        $this->_changeDeletedAttribute($criteria, $localPath);
                }
            }
            $currentPresentationId++;
        }
	    $this->LogInfo("Finished checking for deleted presentations");
    }

    private function _presentationDoesNotExist($path)
    {
        return !file_exists($path);
    }

    private function _getPartOfPresentation($mongoDatabaseConnection, $id)
    {
        $unwind = array('$unwind' => '$'.PresentationSchema::FILES);

        $match = array
        (
            '$match' => array
            (
                PresentationSchema::PRESENTATION_ID => $id
            )
        );

        $limit = array('$limit' => 1);

        return $mongoDatabaseConnection->collection->aggregate($unwind, $match, $limit);

    }
    private function getLargestInsertedFileId()
    {
        $largestInsertedFileId = new LastUpdates();

        return $largestInsertedFileId->findLargestPresentationId();
    }

    private function _convertToLocalPath($presentation)
    {
        return Config::get('settings')['relaymedia'] . DIRECTORY_SEPARATOR . $presentation;
    }

    private function _changeDeletedAttribute($criteria, $path)
    {
        $operationSucceeded = $this->mongo->update($criteria, '$set', PresentationSchema::DELETED, 1, 0);

        if ($operationSucceeded)
	        $this->LogInfo("Did not find " .  $path . ". Marked as deleted");
        else
	        $this->LogError("Could not mark " .  $path . "as deleted");
    }
}
