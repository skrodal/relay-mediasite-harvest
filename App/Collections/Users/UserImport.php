<?php namespace Uninett\Collections\Users;

// Prerequisites: None. This class manages user finding from a source database and inserting to mongodb
use Uninett\Collections\Collection;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\RelaySQLConnection;
use Uninett\Database\MongoConnection;
use Uninett\Models\UserModel;
use Uninett\Models\UserModel2;
use Uninett\Schemas\UserRelaySchema;
use Uninett\Schemas\UsersSchema;


class UserImport extends Collection {
	private $latestUserId = 0;
	private $mongo;
	private $insert;

	private $usersInserted = 0;

	public function __construct() {
		parent::__construct(UsersSchema::COLLECTION_NAME);

		$this->mongo = new MongoConnection(UsersSchema::COLLECTION_NAME);

		$this->insert = new UserInsert(new MongoConnection(UsersSchema::COLLECTION_NAME));
	}

	public function update() {
		$this->latestUserId = $this->lastInsertedUserIdInMongoDb();

		$newUsersInDatabase = new UserFind($this->latestUserId, new RelaySQLConnection);

		$query = $newUsersInDatabase->findNewUsersInDatabase();

		$this->LogInfo("Found " . mssql_num_rows($query) . " new users");

		if($this->queryContainsNewUsers($query)) {
			while($result = mssql_fetch_assoc($query)) {
				$criteria = array(UsersSchema::USERNAME => $result[UserRelaySchema::USERNAME]);

				if($this->foundNewUser($criteria)) {
					$this->LogInfo("Found new user " . $result[UserRelaySchema::USERNAME]);

					$user = (new UserCreate)->create($result);

					if(is_null($user)) {
						$this->LogError("Could not create the user with username: " . $result[UserRelaySchema::USERNAME]);
						continue;
					}

					$this->insertUserToDb($user, $result[UserRelaySchema::USER_ID]);

				} else {
					$this->LogInfo("Tried to insert user: " .
						$result[UserRelaySchema::USERNAME] . ", but user is already in database");
				}
			}
		}

		if($this->usersInserted > 0) {
			$this->updateLargestInsertedUserIdInMongoDb();

			$this->LogInfo("Inserted " . $this->usersInserted . " new users");
		}
	}

	private function queryContainsNewUsers($query) {
		if($query == false) {
			return false;
		}

		return (mssql_num_rows($query) > 0) ? true : false;
	}

	private function foundNewUser($criteria) {
		$cursor = $this->mongo->findOne($criteria);

		return empty($cursor) ? true : false;
	}

	private function insertUserToDb(UserModel $user, $userId) {
		$success = $this->insert->insertUserToMongoDb($user);

		if($success) {
			$this->keepLargestUserId($userId);
			$this->usersInserted = $this->usersInserted + 1;
			$this->LogInfo("New user: " . $user->getUsername() . " inserted in collection " . UsersSchema::COLLECTION_NAME);
		} else {
			$this->LogError("Something went wrong when inserting new user: " . $user->getUsername());
		}
	}

	private function keepLargestUserId($newUserId) {
		if($newUserId > $this->latestUserId) {
			$this->latestUserId = $newUserId;
		}
	}

	private function lastInsertedUserIdInMongoDb() {
		$lastUpdates = new LastUpdates();

		return $lastUpdates->findUserId();
	}

	private function updateLargestInsertedUserIdInMongoDb() {
		$last = new LastUpdates();
		$last->updateUserId($this->latestUserId);
	}
}


