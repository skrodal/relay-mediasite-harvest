<?php namespace Uninett\Collections\Users;
//This class finds users from perseus database
use Uninett\Database\Database;
use Uninett\Database\MSSQLDatabaseConnectionInterface;

class UserFind2
{
    private $largestInsertedFileIdInMongoDb;

    private $connection;

    public function __construct($largestInsertedFileIdInMongoDb, Database $connection)
    {
        $this->largestInsertedFileIdInMongoDb = $largestInsertedFileIdInMongoDb;

        $this->connection = $connection;
    }

    public function findNewUsersInDatabase()
    {
        $query = "
        SELECT userId, userName, userEmail, userDisplayName, createdOn
        FROM tblUser
        WHERE userId > " . $this->largestInsertedFileIdInMongoDb;

        return $this->connection->connection->getPdo()->exec($query);
    }
}
