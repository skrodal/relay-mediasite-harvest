<?php namespace Uninett\Collections\Users;
// Prerequisites: None. This class manages user finding from a source database and inserting to mongodb
use Carbon\Carbon;
use Monolog\Logger;
use Uninett\Collections\Collection;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\EcampussqlMSSQLDatabaseConnection;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\UserMediasiteSchema;
use Uninett\Schemas\UsersSchema;


class UserImport extends Collection
{
    private $latestUserId =  0;
    private $mongo;
    private $insert;

    private $usersInserted = 0;

    public function __construct()
    {
        parent::__construct(UsersSchema::COLLECTION_NAME);

        $this->mongo = new MongoConnection(UsersSchema::COLLECTION_NAME);

        $this->insert = new UserInsert(new MongoConnection(UsersSchema::COLLECTION_NAME));
    }

    public function update()
    {
        $this->latestUserId = $this->_lastInsertedUserIdInMongoDb();

        $newUsersInDatabase = new UserFind(0, new EcampussqlMSSQLDatabaseConnection);

        $query = $newUsersInDatabase->findNewUsersInDatabase();

        $this->LogNotice("Found " . mssql_num_rows($query) . " results");

        if ($this->_queryContainsNewUsers($query))
        {
            while ($result = mssql_fetch_assoc($query))
            {
                $criteria = array(UsersSchema::USERNAME => $result[UserMediasiteSchema::USERNAME]);

                if ($this->foundNewUser($criteria))
                {
                    $this->LogNotice("Found new user " . $result[UserMediasiteSchema::USERNAME]);

                    $user = (new UserCreate)->create($result);

                    if (is_null($user))
                    {
                        $this->LogError("Could not create the user with username:"
                            . $result[UserMediasiteSchema::USERNAME]);
                        continue;
                    }

                    $this->_insertUserToDb($user, $result[UserMediasiteSchema::USER_ID]);

                } else
                    $this->LogNotice("Tried to insert user: " .
                $result[UserMediasiteSchema::USERNAME] . ", but user is already in database");
            }
        }

        if($this->usersInserted > 0)
            $this->_updateLargestInsertedUserIdInMongoDb();
    }

    private function _queryContainsNewUsers($query)
    {
        if($query == false)
            return false;

        return (mssql_num_rows($query) > 0) ? true : false;
    }

    private function foundNewUser($criteria)
    {
        $cursor = $this->mongo->findOne($criteria);

        return empty($cursor) ? true : false;
    }

    private function _insertUserToDb($user, $userId)
    {
        $success = $this->insert->insertUserToMongoDb($user);

        if ($success) {
            $this->_keepLargestUserId($userId);
        } else
            $this->LogError("Something went wrong when inserting new user: " . $user->getUsername());
    }

    private function _keepLargestUserId($newUserId)
    {
        if($newUserId > $this->latestUserId)
            $this->latestUserId = $newUserId;
    }

    private function _lastInsertedUserIdInMongoDb()
    {
        return (new LastUpdates)->findUserId();
    }

    private function _updateLargestInsertedUserIdInMongoDb()
    {
        $last = new LastUpdates();
        $last->updateUserId($this->latestUserId);
    }
}


