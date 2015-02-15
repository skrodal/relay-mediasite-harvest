<?php namespace Uninett\Collections\Users;
//This class finds users from perseus database
use Uninett\Database\MSSQLDatabaseConnectionInterface;

class UserFind
{
    private $largestInsertedFileIdInMongoDb;

    private $connection;


    public function __construct($largestInsertedFileIdInMongoDb, MSSQLDatabaseConnectionInterface $connection)
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

        return $this->connection->query($query);
    }
}
