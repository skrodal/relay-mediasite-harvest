<?php namespace Uninett\Collections\DailyVideos;
//This collection will hold one document for each day, that contains the date and number of videos that were added
//to db. This makes it possible to easy get statistics for how it have evolved over time.
use MongoDate;
use Uninett\Collections\Collection;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\DailyVideosSchema;

class DailyVideoImport extends Collection
{
    private $_log;

    public function __construct()
    {
	    parent::__construct(DailyVideosSchema::COLLECTION_NAME);
    }
    public function insert($count)
    {
        $mongo = new MongoConnection(DailyVideosSchema::COLLECTION_NAME);

        $success = $mongo->save
        (
            array
            (
                DailyVideosSchema::DATE => new MongoDate(),
                DailyVideosSchema::COUNT=> $count
            )
        );
        if($success)
	        $this->LogInfo("Inserted {$count} new videos");
        else
	        $this->LogError("Could not insert new videos");
    }
}
