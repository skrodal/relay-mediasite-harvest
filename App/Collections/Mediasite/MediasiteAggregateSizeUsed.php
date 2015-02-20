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
	private $numberInserted = 0;

    public function __construct()
    {
	    parent::__construct(MediaSiteSchema::COLLECTION_NAME);

        $this->mongo = new MongoConnection(MediaSiteSchema::COLLECTION_NAME);
    }

    public function update()
    {
        $directory = "/home/uninett/mediasite/";

        $lop = new LinuxOperationsHelper();

        $organisations = $lop->getFolderNamesFromDirectory($directory);

        foreach ($organisations as $organisation) {
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
                $this->LogError("Could not find {$directory}/{$organisation}");
        }

        $this->LogInfo("Aggregated data and inserted {$this->numberInserted} items");

    }
}
