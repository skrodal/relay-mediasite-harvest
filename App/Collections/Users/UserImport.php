<?php namespace Uninett\Collections\Users;
// Prerequisites: None. This class manages user finding from a source database and inserting to mongodb
use Carbon\Carbon;
use Monolog\Logger;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\EcampussqlMSSQLDatabaseConnection;
use Uninett\Database\MongoConnection;


class UserImport
{
    private $_largestUserIdInserted =  0;

    private $_mongoDatabaseConnection;

    private $_userInserter;

    private $_log;

    public function __construct()
    {
        $this->_log = new Logger('import');
        //$this->_log = new Logging(UsersSchema::COLLECTION_NAME, __FILE__);

        $this->_mongoDatabaseConnection = new MongoConnection(UsersSchema::COLLECTION_NAME);

        $this->_userInserter = new UserInsert(new MongoConnection(UsersSchema::COLLECTION_NAME));

        //$this->_log->numberFound  = 0;
    }

    public function update()
    {
        $this->_largestUserIdInserted = 0;

        $newUsersInDatabase = new UserFind(0, new EcampussqlMSSQLDatabaseConnection);

        $query = $newUsersInDatabase->findNewUsersInDatabase();

        $this->_log->addNotice("Found " . mssql_num_rows($query) . " results");

        if ($this->_queryContainsNewUsers($query))
        {
            $this->_log->numberFound = mssql_num_rows($query);

            while ($result = mssql_fetch_assoc($query))
            {
                $criteria = array(UsersSchema::USERNAME => $result[UserMediasiteSchema::USERNAME]);

                if ($this->foundNewUser($criteria))
                {
                    //$this->_log->logSingleUpdate("Found new user " . $result[UserMediasiteSchema::USERNAME]);
                    $this->_log->addNotice("Found new user " . $result[UserMediasiteSchema::USERNAME]);

                    $user = (new UserCreate)->create($result);

                    if (is_null($user))
                        continue;

                    $this->_insertUserToDb($user, $result[UserMediasiteSchema::USER_ID]);

                } else
                    $this->_log->addNotice("Tried to insert user: " .
                $result[UserMediasiteSchema::USERNAME] . ", but user is already in database", false, false);
            }
        }
    }



    private function _queryContainsNewUsers($query)
    {
        if($query == false)
            return false;

        return (mssql_num_rows($query) > 0) ? true : false;
    }

    private function foundNewUser($criteria)
    {
        $cursor = $this->_mongoDatabaseConnection->findOne($criteria);

        return empty($cursor) ? true : false;
    }

    private function _insertUserToDb($user, $userId)
    {
        $success = $this->_userInserter->insertUserToMongoDb($user);

        if ($success) {
            //$this->_log->numberInserted++;
            $this->_keepLargestUserId($userId);
        } else
            $this->_log->addError("Something went wrong when inserting new user: " . $user->getUsername());

    }

    private function _keepLargestUserId($id)
    {
        if($id > $this->_largestUserIdInserted)
            $this->_largestUserIdInserted = $id;
    }

    private function _lastInsertedUserIdInMongoDb()
    {
        $last = new LastUpdates();
        return $last->findUserId();
    }

    private function _updateLargestInsertedUserIdInMongoDb()
    {
        $last = new LastUpdates();
        $last->updateUserId($this->_largestUserIdInserted);
    }

    }


