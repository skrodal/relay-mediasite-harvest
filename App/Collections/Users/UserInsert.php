<?php namespace Uninett\Collections\Users;
//This class inserts users to mongodb
use Uninett\Database\MongoConnection;

class UserInsert
{
    private $connection;

    public function __construct(MongoConnection $connection)
    {
        $this->connection = $connection;
    }

    public function insertUserToMongoDb(User $user)
    {
        return $this->connection->save($user->jsonSerialize());
    }
}
