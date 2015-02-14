<?php namespace Uninett\Collections\Users;
/**
 *  A user may have different statuses
 *
 * -1. Not set yet. This is the default value a user will have when it is just imported from relay to mongodb
 *  1. Account on relay, and none recordings (userfolder does not exist)
 *  2. Account on relay, and have recordings (userfolder exists)
 *  3. Deleted account on relay, has content on disk  (userfolder exists, do not delete!)
 *  4. Deleted account on relay, has no content on disk (userfolder does not exist)
 *
 */

//Prerequisites: Users collection in mongodb is updated, access to tblUser on ecampussql
//This class compares the updated existence of users in tblUser, disk and reflect changes in mongodb
class UserCheckStatus implements IUpdate
{
    private $_mongoDatabaseConnection;
    private $_ecampussqlDatabaseConnection;

    private $_log;

    public function __construct()
    {
        $this->_log = new Logging(UsersSchema::COLLECTION_NAME, __FILE__);

        $this->_mongoDatabaseConnection = new MongoConnection(
            Config::Instance()['mongoDatabase']['username'],
            Config::Instance()['mongoDatabase']['password'],
            Config::Instance()['mongoDatabase']['host'],
            Config::Instance()['mongoDatabase']['db'], UsersSchema::COLLECTION_NAME);

        $this->_ecampussqlDatabaseConnection =  new MSSQLDatabaseConnection(
            Config::Instance()['ecampussqlDatabase']['host'],
            Config::Instance()['ecampussqlDatabase']['username'],
            Config::Instance()['ecampussqlDatabase']['password'],
            Config::Instance()['ecampussqlDatabase']['db']);
    }

    public function update()
    {
        if(!$this->_foundUsersInMongoDatabase())
            return 0;

        $this->_updateStatusForUsers();

        $this->_log->logSingleUpdate3($this->_log->numberInserted, "Changed status");
    }

    private function _updateStatusForUsers()
    {
        $u = new UserSupport();

        $users = $u->findUsersInDatabase();

        foreach($users as $feideUsername => $arrayOfPossibleUsernamesForAUser)  {

             $userHasFolderOnDisk = $u->userHasFolderOnDisk($arrayOfPossibleUsernamesForAUser);

             $userExistsInRelayDb = $this->_accountExistsOnRelayDatabase($feideUsername);

             $criteria = array(UsersSchema::USERNAME => $feideUsername);

             $userDocument = $this->_mongoDatabaseConnection->findOne($criteria);

             $statusFrom = $userDocument[UsersSchema::STATUS];

             if ($userExistsInRelayDb && !$userHasFolderOnDisk) {
                 if($userDocument[UsersSchema::STATUS] == 1)
                     continue;
                 else
                     $statusTo = 1;

             } elseif ($userExistsInRelayDb && $userHasFolderOnDisk) {
                 if($userDocument[UsersSchema::STATUS] == 2)
                     continue;
                 else
                     $statusTo = 2;

             } elseif (!$userExistsInRelayDb && $userHasFolderOnDisk) {
                 if($userDocument[UsersSchema::STATUS] == 3)
                     continue;
                 else
                     $statusTo = 3;

             } elseif (!$userExistsInRelayDb && !$userHasFolderOnDisk) {
                 if($userDocument[UsersSchema::STATUS] == 4)
                     continue;
                 else
                     $statusTo = 4;

             } else {
                 $this->_log->logError("None matched when checking statuses. Should never happen.");
                 continue;
             }

             $success = $this->_mongoDatabaseConnection->update($criteria, '$set', UsersSchema::STATUS, $statusTo, 0);

             if ($success) {

                 $this->_log->logSingleUpdate("Changed status for " .
                     $userDocument[UsersSchema::USERNAME] . " from: " .
                     Config::Instance()['userStatus'][$statusFrom] . " to: " .
                     Config::Instance()['userStatus'][$statusTo]);

                 $this->_log->numberInserted++;
             }
        }
     }


    private function _foundUsersInMongoDatabase()
    {
        $numberOfusersFound = $this->_mongoDatabaseConnection->find()->count();

        if ($numberOfusersFound == 0) {
            $this->_log->logSingleUpdate( "No users found in database");
            return false;
        }
        return true;
    }

    private function _accountExistsOnRelayDatabase($username)
    {
        $query = "
        SELECT userName
        FROM tblUser
        WHERE userName LIKE '" . $username . "'";

        $query = $this->_ecampussqlDatabaseConnection->query($query);

        //New code
        if($query == false)
            return false;

        return true;

        /*
        //Old code
        $rowsFound = mssql_num_rows($query);


        if($rowsFound  > 0)

            return true;
        else
            return false;*/
    }
}
