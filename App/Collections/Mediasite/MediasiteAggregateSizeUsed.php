<?php namespace Uninett\Collections\Mediasite;

use MongoDate;
use Uninett\Collections\Collection;
use Uninett\Collections\CollectionUpdateInterface;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Helpers\Arithmetic;
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
        $directories = Config::get('mediasite')['directories'];

        //'$directory = "/home/uninett/mediasite/";

	    $math = new Arithmetic();
        $disk = new LinuxOperationsHelper();

	    $convert = new ConvertHelper();

	    foreach($directories as $directory) {
		    $organisations = $disk->getFolderNamesFromDirectory($directory);

		    foreach ($organisations as $organisation) {
			    $sizeB = $disk->getSpaceUsedByMediasiteOrg($directory, $organisation);

			    $sizeMiB = $convert->bytesToMegabytes($sizeB);

			    $criteria = array(MediaSiteSchema::ORG => $organisation);

			    $lastKnownUsedSize = $this->producedMoreSinceLastSave($organisation);

			    if($lastKnownUsedSize !== $sizeMiB) {
				    $storage = array (
					    MediaSiteSchema::DATE => new MongoDate(),
					    MediaSiteSchema::SIZE => $sizeMiB,
				    );

				    $success = $this->mongo->update($criteria, '$push', MediaSiteSchema::STORAGE, $storage, 1);

				    if($success) {
					    $this->numberInserted = $this->numberInserted + 1;

					    $diff = $math->subtract($lastKnownUsedSize, $sizeMiB);

					    $this->LogInfo("{$diff}MiB diff for {$organisation}");
				    }
		        }
	        }
	    }
        $this->LogInfo("Aggregated data and inserted {$this->numberInserted} items");
    }

	private function producedMoreSinceLastSave($organisation)
	{
		$unwind = array('$unwind' => '$storage');

		$match = array (
			'$match' => array (
				MediaSiteSchema::ORG => $organisation,
			)
		);

		$sort = array (
			'$sort' => array (
				'storage.date' => -1
			)
		);

		$limit = array('$limit' => 1);

		$size = $this->mongo->collection->aggregate($unwind, $match, $sort, $limit);

		if(isset($size['result']['0'][MediaSiteSchema::STORAGE][MediaSiteSchema::SIZE]))
			return (double) $size['result']['0'][MediaSiteSchema::STORAGE][MediaSiteSchema::SIZE];
		else
			return 0.0;
	}
}
