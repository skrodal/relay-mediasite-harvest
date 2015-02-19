<?php namespace Uninett\Collections\Users;

use Uninett\Collections\Collection;
use Uninett\Collections\CollectionUpdateInterface;
use Uninett\Helpers\UserHelper;
use Uninett\Config;
use Uninett\Database\EcampusSQLConnection;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\UsersSchema;

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
class UserCheckStatus extends Collection implements CollectionUpdateInterface
{
    private $mongo;
    private $ecampussql;

    private $numberOfStatusChanges = 0;

    public function __construct()
    {
        parent::__construct(UsersSchema::COLLECTION_NAME);

        $this->mongo = new MongoConnection(UsersSchema::COLLECTION_NAME);
        $this->ecampussql = new EcampusSQLConnection();
    }

    public function update()
    {
        if(!$this->foundUsersInMongoDatabase())
            return 0;

        $this->_updateStatusForUsers();

        $this->LogInfo("Changed status for " . $this->numberOfStatusChanges . " users");
    }

    private function _updateStatusForUsers()
    {
        $userStatus = Config::get('userStatus');
        $u = new UserHelper();

        $users = $u->findUsersInDatabase();

        foreach($users as $feideUsername => $arrayOfPossibleUsernamesForAUser)  {

             $userHasFolderOnDisk = $u->userHasFolderOnDisk($arrayOfPossibleUsernamesForAUser);

	         $query = "SELECT userName FROM tblUser WHERE userName LIKE '" . $feideUsername . "'";

	        $userExistsInRelayDb = $this->ecampussql->query($query);

             $criteria = array(UsersSchema::USERNAME => $feideUsername);

             $userDocument = $this->mongo->findOne($criteria);

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
                 $this->LogError("None matched when checking statuses. Should never happen.");
                 continue;
             }

             $success = $this->mongo->update($criteria, '$set', UsersSchema::STATUS, $statusTo, 0);

             if ($success) {

                 $this->LogInfo("Changed status for " .
                     $userDocument[UsersSchema::USERNAME] . " from: " .
                     $userStatus[$statusFrom] . " to: " .
                     $userStatus[$statusTo]);

                 $this->numberOfStatusChanges = $this->numberOfStatusChanges + 1;
             }
        }
     }


    private function foundUsersInMongoDatabase()
    {
        $numberOfusersFound = $this->mongo->find()->count();

        if ($numberOfusersFound == 0) {
            $this->LogInfo("No users found in database");
            return false;
        }
        return true;
    }
}
