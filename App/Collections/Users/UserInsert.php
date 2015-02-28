<?php namespace Uninett\Collections\Users;
//This class inserts users to mongodb
use Uninett\Database\MongoConnection;
use Uninett\Models\UserModel;
use Uninett\Models\UserModel2;

class UserInsert
{
    private $connection;

    public function __construct(MongoConnection $connection)
    {
        $this->connection = $connection;
    }

    public function insertUserToMongoDb(UserModel2 $user)
    {
        return $this->connection->save($user->toArray());
    }
}
