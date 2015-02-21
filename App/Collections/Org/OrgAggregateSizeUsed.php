<?php namespace Uninett\Collections\Org;
//Prerequisites: orgs collection is up to date, access to directory on disk
use MongoDate;
use Uninett\Collections\Collection;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\MongoConnection;
use Uninett\Helpers\Arithmetic;
use Uninett\Helpers\ConvertHelper;
use Uninett\Helpers\LinuxOperationsHelper;
use Uninett\Helpers\UserHelper;
use Uninett\Schemas\OrgSchema;

class OrgAggregateSizeUsed extends Collection implements UpdateInterface
{
    private $mongo;
	private $numberFound;
    public function __construct()
    {
	    parent::__construct(OrgSchema::COLLECTION_NAME);

        $this->mongo = new MongoConnection(OrgSchema::COLLECTION_NAME);
    }

    public function update()
    {
        $math = new Arithmetic();

        $u = new UserHelper;

        $allOrgsInIOrgsCollection = $u->findUsersInOrganisationsInDatabase();

        if(count($allOrgsInIOrgsCollection) == 0)
            $this->LogError("Did not find any organisations to aggregate size used for");

        $aggregatedSize = 0.0;

        foreach($allOrgsInIOrgsCollection as $orgName => $arrayOfUsersInOrg) {
            $diskSize = 0.0;
            $dbSize = 0.0;

            foreach($arrayOfUsersInOrg as $userPath)
                $diskSize = $math->add($diskSize, $this->calculateSize($userPath));

            $criteria = array(OrgSchema::ORG =>  $orgName);

            $orgToUpdate = $this->mongo->findOne($criteria);

            if ($this->organisationExists($orgToUpdate)) {

                $dbSize = $this->hasProducedMoreSinceLastSave($orgName);

                if($math->consideredToBeEqual($diskSize, $dbSize)) {
                    $this->LogInfo("No change in size used by " . $orgName);
                    continue;
                } else {
                    $storage = array
                    (
                        OrgSchema::DATE => new MongoDate(),
                        OrgSchema::SIZE => $diskSize,
                    );

                    $newArrayWasPushedToCollection = $this->mongo->update($criteria, '$push', OrgSchema::STORAGE, $storage, 0);

                    if ($newArrayWasPushedToCollection) {

	                    $this->numberFound = $this->numberFound + 1;
                        $aggregatedSize = $math->add($aggregatedSize, $diskSize);
                    }
                }

                $this->LogInfo("Aggregated " . $orgName." (". $math->subtract($diskSize, $dbSize) . "MiB diff). Last size was " . $dbSize . "MiB");
            } else
                $this->LogError("Did not find " . $orgName . " in db");

        }

        $this->numberFound = (int)ceil($this->numberFound);

        $this->LogInfo("Aggregated daily disk usage; " . $aggregatedSize ."MiB" . " for {$this->numberFound} users");
    }

    private function calculateSize($path)
    {
        $lop = new LinuxOperationsHelper;
        $sizeByte = $lop->getSpaceUsedInDirectory($path);

        $convert = new ConvertHelper();

        return $convert->bytesToMegabytes($sizeByte);
    }

    private function organisationExists($org)
    {
        return !empty($org);
    }

    private function hasProducedMoreSinceLastSave($org)
    {
        $unwind = array('$unwind' => '$storage');

        $match = array
        (
            '$match' => array
            (
                '$and' => array
                (
                    [
                        OrgSchema::ORG => $org,
                    ]
                )
            )
        );

        $sort = array
        (
            '$sort' => array
            (
                'storage.date' => -1
            )
        );

        $limit = array('$limit' => 1);

        $size = $this->mongo->collection->aggregate($unwind, $match, $sort, $limit);

        if(isset($size['result']['0'][OrgSchema::STORAGE][OrgSchema::SIZE]))
            return (double) $size['result']['0'][OrgSchema::STORAGE][OrgSchema::SIZE];
        else
            return 0.0;
    }
}