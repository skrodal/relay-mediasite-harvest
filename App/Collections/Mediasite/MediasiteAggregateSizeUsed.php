<?php namespace Uninett\Collections\Mediasite;

use MongoDate;
use Uninett\Collections\Collection;
use Uninett\Collections\CollectionUpdateInterface;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Helpers\ConvertHelper;
use Uninett\Helpers\LinuxOperationsHelper;
use Uninett\Schemas\MediaSiteSchema;

/**
 * Class MediaCiteAggregateSizeUsed
 *
 * Temporary file used to gather daily storage statistics for mediasite
 */
class MediasiteAggregateSizeUsed extends Collection implements CollectionUpdateInterface
{
    private $criteria = array();

	private $numberInserted = 0;

    public function __construct()
    {
	    parent::__construct(MediaSiteSchema::COLLECTION_NAME);

        $this->mongo = new MongoConnection(MediaSiteSchema::COLLECTION_NAME);

	    //TODO: Tror dette kan fjernes. Sjekk mongospec (update, upsert)
        //Check if collection exists, if not, create it
/*        if (!$this->collectionExists(MediaSiteSchema::COLLECTION_NAME)) {

            $this->insert();

            $this->criteria = array(MediaSiteSchema::ORG => "Dummy");

            //Then remove dummy
            $this->mongo->collection->remove($this->criteria);
        }*/
    }

    public function update()
    {
	    //TODO: Bruk array og gå gjennom alle potensielle directories for mediasite
        $directory = "/home/uninett/mediasite/";

        $lop = new LinuxOperationsHelper();

        $organisations = $lop->getFolderNamesFromDirectory($directory);

        foreach ($organisations as $organisation) {
            $lop = new LinuxOperationsHelper();

            $sizeB = $lop->getSpaceUsedByMediasiteOrg($directory, $organisation);

            $convert = new ConvertHelper();

	        $sizeMiB = $convert->bytesToMegabytes($sizeB);

            $criteria = array(MediaSiteSchema::ORG => $organisation);

            $cursor = $this->mongo->findLimitOne($criteria);

            if (!empty($cursor)) {

                $storage = array
                (
                    MediaSiteSchema::DATE => new MongoDate(),
                    MediaSiteSchema::SIZE => $sizeMiB,
                );

                $success = $this->mongo->update($criteria, '$push', MediaSiteSchema::STORAGE, $storage, 1);

                if($success)
                    $this->numberInserted = $this->numberInserted + 1;

            } else
                $this->LogError("Could not find " . $directory.DIRECTORY_SEPARATOR.$organisation);
        }


        $this->LogInfo("Aggregated data and inserted {$this->numberInserted} items");

    }

/*    public function collectionExists($collection)
    {
	    $config = Config::get('mongoDatabase');

        if ($this->mongo->collection->system->namespaces->findOne(array("name" => $config['db'] .".".$collection)) === null)
            return false;
        return true;
    }*/

    /**
     * Use only first time system is set up. This creates the collection and document
     * @return array|bool
     */
/*    public function insert()
    {
        $cursor = $this->mongo->findLimitOneCount($this->criteria);

        if (empty($cursor)) {
            $success =  $this->mongo->insert
            (
                array
                (
                    MediaSiteSchema::ORG => "Dummy",
                    MediaSiteSchema::STORAGE => array (),
                )
            );
            if($success)
                $this->LogInfo("Created collection {MediaSiteSchema::COLLECTION_NAME}");

            return $success;
        }

        return false;
    }*/
}
