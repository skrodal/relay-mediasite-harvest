<?php namespace Uninett\Collections\Users;
// Prerequisites: None. This class manages user finding from a source database and inserting to mongodb

use Uninett\Collections\Collection;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Database\DatabaseConnection;
use Uninett\Database\MongoConnection;
use Uninett\Models\Ecampussql\TblUser;
use Uninett\Models\UserModel2;
use Uninett\Schemas\UserMediasiteSchema;
use Uninett\Schemas\UsersSchema;

class UserImport extends Collection
{
	private $latestUserId =  0;
	private $mongo;

	private $usersInserted = 0;

	public function __construct()
	{
		parent::__construct(UsersSchema::COLLECTION_NAME);

		$this->mongo = new MongoConnection(UsersSchema::COLLECTION_NAME);
	}

	public function update()
	{
		$this->latestUserId = $this->lastInsertedUserIdInMongoDb();

		$connection = new DatabaseConnection('ecampussql');

		$query = $connection->connection->from('tblUser')->where('userId > ?', $this->latestUserId)->select('userName, userEmail, userDisplayName, createdOn')->fetchAll();

		if ($this->queryContainsNewUsers($query)) {
			foreach ($query as $row) {
				$criteria = array(UsersSchema::USERNAME => $row[UserMediasiteSchema::USERNAME]);

				if ($this->foundNewUser($criteria)) {
					$this->LogInfo("Found new user {$row[UserMediasiteSchema::USERNAME]}");

					//See if the recieved results with potential user data is valid according to its rules
					if($this->validate($row, TblUser::$rules))
					{
						$user = $this->createNewUser($row);
						$this->insertUserToDb($user, $row[UserMediasiteSchema::USER_ID]);
					}
				} else
					$this->LogInfo("Tried to insert user {$row[UserMediasiteSchema::USERNAME]}, but user is already in database");
			}
		}

		if($this->usersInserted > 0)
		{
			$this->updateLargestInsertedUserIdInMongoDb();

			$this->LogInfo("Inserted {$this->usersInserted} new users");
		}
		else
			$this->LogInfo("Did not find new users! :(");
	}

	private function createNewUser($data)
	{
		return (new UserModel2)->withAttributes($data)->andMerge(['status' => 1, 'affiliation' => 'notSetYet']);
	}

	private function validate($data, $model)
	{
		$validation_result = \SimpleValidator\Validator::validate($data, $model);

		if ($validation_result->isSuccess() == true) {
			return true;

		} else {
			//TODO: Throw exception?
			var_dump($validation_result->getErrors());
			return false;
		}
	}

	private function queryContainsNewUsers($query)
	{
		if($query == false)
			return false;

		return count($query) > 0;
	}

	private function foundNewUser($criteria)
	{
		$cursor = $this->mongo->findOne($criteria);

		return empty($cursor) ? true : false;
	}

	private function insertUserToDb(UserModel2 $user, $userId)
	{
		$success = $this->mongo->save($user->attributes);
		if ($success) {
			$this->keepLargestUserId($userId);
			$this->usersInserted = $this->usersInserted + 1;
		} else
			$this->LogError("Something went wrong when inserting new user: " . $user->getUsername());
	}

	private function keepLargestUserId($newUserId)
	{
		if($newUserId > $this->latestUserId)
			$this->latestUserId = $newUserId;
	}

	private function lastInsertedUserIdInMongoDb()
	{
		$lastUpdates = new LastUpdates;
		return $lastUpdates->findUserId();
	}

	private function updateLargestInsertedUserIdInMongoDb()
	{
		$last = new LastUpdates();
		$last->updateUserId($this->latestUserId);
	}
}


