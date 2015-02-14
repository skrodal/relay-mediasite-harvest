<?php namespace Uninett\Collections\Users;
//Uses users collection to match usernames in db and foldernames on disk and sets the correct affiliation if they maches a certain criteria
class UserSetAffiliation implements IUpdate
{
    private $_mongoDatabaseConnection;
    private $_diskOperation;

    private $_log;
    public function __construct()
    {
        $this->_log = new Logging(UsersSchema::COLLECTION_NAME, __FILE__);

        $this->_mongoDatabaseConnection = new MongoConnection(
            Config::Instance()['mongoDatabase']['username'],
            Config::Instance()['mongoDatabase']['password'],
            Config::Instance()['mongoDatabase']['host'],
            Config::Instance()['mongoDatabase']['db'],
            UsersSchema::COLLECTION_NAME);

        $this->_diskOperation = new Convert();
    }

    public function update()
    {
        $u = new UserSupport();

        foreach (Config::Instance()['folders_to_scan_for_files'] as $directory) {
            $users =  $u->findUsersInDatabaseInDirectoryOnDisk($directory);

            $this->_validateUsersInDirectory($users);

        }

        $this->_log->logFinished();
    }

    private function _validateUsersInDirectory($users)
    {
        foreach($users as $feideUsername => $userArrays)  {

            foreach ($userArrays as $userPath) {

                if(is_dir($userPath)) {

                    $criteria = array
                    (
                        'affiliation' => 'willBeSetIfFolderExistsAndUserSetAffiliationHaveDoneItsThing',
                        'username' => $feideUsername
                    );

                    $cursor = $this->_mongoDatabaseConnection->collection->find($criteria)->limit(1);

                    if ($this->_resultExists($cursor->count())) {
                        $affiliation = $this->_diskOperation->getAffiliationFromPath($userPath);

                        $success = $this->_updateAffiliationInMongoDb($criteria, $affiliation);

                        if(!$success)
                            $this->_log->logError("Could not update affiliation for " . $userPath);
                        else
                            $this->_log->numberInserted++;

                        break;
                    }
                }
            }
        }
    }

    private function _resultExists($count)
    {
        return $count != 0 ? true : false;
    }

    private function _updateAffiliationInMongoDb($criteria, $affiliation)
    {
        return $this->_mongoDatabaseConnection->update
            ($criteria, '$set', UsersSchema::AFFILIATION, $affiliation, 0);
    }
}
