<?php namespace Uninett\Collections\Users;
//Uses users collection to match usernames in db and foldernames on disk and sets the correct affiliation if they maches a certain criteria

use Uninett\Collections\Collection;
use Uninett\Collections\CollectionUpdateInterface;
use Uninett\Collections\Helpers\Convert;
use Uninett\Collections\Helpers\UserHelper;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\UsersSchema;

class UserSetAffiliation extends Collection implements CollectionUpdateInterface
{
    private $_mongoDatabaseConnection;

    private $numberOfUsersInserted;

    public function __construct()
    {
        parent::__construct(UsersSchema::COLLECTION_NAME);

        $this->_mongoDatabaseConnection = new MongoConnection(UsersSchema::COLLECTION_NAME);
    }

    public function update()
    {
        $u = new UserHelper();

        $directories = Config::get('folders_to_scan_for_files');

        foreach ($directories as $directory) {
            $users =  $u->findUsersInDatabaseInDirectoryOnDisk($directory);

            $this->validateUsersInDirectory($users);
        }

        $this->LogInfo("Affiliation was set for " . $this->numberOfUsersInserted .  " users");
    }

    private function validateUsersInDirectory($users)
    {
        $diskOperation = new Convert();

        foreach($users as $feideUsername => $userArrays)  {

            foreach ($userArrays as $userPath) {

                if(is_dir($userPath)) {

                    $criteria = array
                    (
                        UsersSchema::AFFILIATION => 'willBeSetIfFolderExistsAndUserSetAffiliationHaveDoneItsThing',
                        UsersSchema::USERNAME => $feideUsername
                    );

                    $cursor = $this->_mongoDatabaseConnection->collection->find($criteria)->limit(1);

                    if ($this->resultExists($cursor->count())) {
                        $affiliation = $diskOperation->getAffiliationFromPath($userPath);

                        $success = $this->updateAffiliationInMongoDb($criteria, $affiliation);

                        if(!$success)
                            $this->LogError("Could not update affiliation for " . $userPath);
                        else
                            $this->numberOfUsersInserted = $this->numberOfUsersInserted + 1;

                        break;
                    }
                }
            }
        }
    }

    private function resultExists($count)
    {
        return $count != 0 ? true : false;
    }

    private function updateAffiliationInMongoDb($criteria, $affiliation)
    {
        return $this->_mongoDatabaseConnection->update
            ($criteria, '$set', UsersSchema::AFFILIATION, $affiliation, 0);
    }
}
