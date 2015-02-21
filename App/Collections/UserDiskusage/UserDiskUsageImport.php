<?php namespace Uninett\Collections\UserDiskusage;
// Find disk usage for users from disk and insert to collection in mongodb
use MongoDate;
use Uninett\Collections\Logging;
use Uninett\Collections\UpdateInterface;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Helpers\Arithmetic;
use Uninett\Helpers\ConvertHelper;
use Uninett\Helpers\LinuxOperationsHelper;
use Uninett\Helpers\UserHelper;
use Uninett\Schemas\OrgSchema;
use Uninett\Schemas\UserDiskUsageSchema;
use Uninett\Schemas\UsersSchema;

class UserDiskUsageImport extends Logging implements UpdateInterface
{
    private $userDiskUsageCollection;
    private $userCollection;

	private $numberInserted = 0;

    public function __construct()
    {
	    parent::__construct(UserDiskUsageSchema::COLLECTION_NAME);

        $this->userDiskUsageCollection = new MongoConnection(UserDiskUsageSchema::COLLECTION_NAME);

        $this->userCollection = new MongoConnection(UsersSchema::COLLECTION_NAME);
    }

    public function update()
    {
        $math = new Arithmetic();

        $aggregatedSize = 0.0;
        $u = new UserHelper;

	    $directories = Config::get('folders_to_scan_for_files');

        foreach ($directories as $directory) {

            if (is_dir($directory)) {

                $users = $u->findUsersInDatabaseInDirectoryOnDisk($directory);

                foreach($users as $feideUsername => $arrayOfPossibleUsernamesForAUser)  {

                    $criteria = array(UserDiskUsageSchema::USERNAME => $feideUsername);

                    $userDiskusageDocument = $this->userDiskUsageCollection->findOne($criteria);

                    $diskSize = 0.0;

                    foreach($arrayOfPossibleUsernamesForAUser as $usernameAndDir)
                        if(is_dir($usernameAndDir))
                            $diskSize = $math->add($diskSize, $this->_calculateSize($usernameAndDir));

                    $dbSize = $this->_producedMoreSinceLastSave($feideUsername);

                    if ($this->_userExistsInCollection($userDiskusageDocument)) {

                        if(!$math->consideredToBeEqual($dbSize, $diskSize)) {

                            $storage = array(
                                UserDiskUsageSchema::DATE => new Mongodate(),
                                UserDiskUsageSchema::SIZE => $diskSize
                            );

                            $operationOK = $this->_updateDocumentInCollection($criteria, $storage);
                        } else
                            continue;

                    } else {

                        $userDocument = $this->userCollection->findOne($criteria);

                        $org = $userDocument[UsersSchema::ORG];

                        $newUser = $this->_createUser($feideUsername, $diskSize, $org);

                        $operationOK = $this->_insertDocumentToMongoDatabase($newUser);
                    }

                    if ($operationOK) {

                        $this->LogInfo("Aggregated " . $feideUsername." (". $math->subtract($diskSize, $dbSize) . "MiB diff). Last size was " . $dbSize . "MiB");

                        $this->numberInserted = $this->numberInserted + 1;

                        $aggregatedSize = $math->add($aggregatedSize, $diskSize);
                    } else
                        $this->LogError("Could not update " . $feideUsername  . var_dump($criteria));

                }
            }
        }
        $this->LogInfo("Aggregated " . $aggregatedSize ."MiB for {$this->numberInserted} users ");
    }



    private function _producedMoreSinceLastSave($username)
    {
        $unwind = array('$unwind' => '$storage');

        $match = array
        (
            '$match' => array
            (
                '$and' => array
                (
                    [
                        UserDiskUsageSchema::USERNAME => $username,
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

        $size = $this->userDiskUsageCollection->collection->aggregate($unwind, $match, $sort, $limit);

        if(isset($size['result']['0'][OrgSchema::STORAGE][OrgSchema::SIZE]))
            return (double) $size['result']['0'][OrgSchema::STORAGE][OrgSchema::SIZE];
        else
            return 0.0;
    }


    private function _calculateSize($a)
    {
        $lop = new LinuxOperationsHelper();
        $sizeByte = $lop->getSpaceUsedInDirectory($a);

        $convert = new ConvertHelper();

        return $convert->bytesToMegabytes($sizeByte);
    }

    private function _userExistsInCollection($result)
    {
        return !empty($result);
    }

    private function _createUser($username, $size, $org)
    {
        $newUser = new UserDiskUsage;

        $newUser->setUsername($username);

        $newUser->setSize((double)$size);

        $newUser->setDate(new Mongodate);

        $newUser->setOrg($org);

        return $newUser;
    }

    private function _updateDocumentInCollection($criteria, $storage)
    {
        return $this->userDiskUsageCollection->update($criteria, '$push', 'storage', $storage, 0);
    }

    private function _insertDocumentToMongoDatabase(UserDiskUsage $document)
    {
        return $this->userDiskUsageCollection->insert($document->jsonSerialize());
    }
}