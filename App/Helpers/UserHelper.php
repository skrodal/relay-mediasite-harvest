<?php namespace  Uninett\Helpers;

use Uninett\Collections\Collection;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\UsersSchema;

class UserHelper extends Collection {
    private $mongo;

    function __construct()
    {
        parent::__construct(UsersSchema::COLLECTION_NAME);

        $this->mongo = new MongoConnection(UsersSchema::COLLECTION_NAME);
    }

    public function findUsersInDatabaseInDirectoryOnDisk($directory)
    {
        $cursor = $this->mongo->find();

        $users = array();

        $dir = $directory . DIRECTORY_SEPARATOR;

        foreach($cursor as $doc)
        {
            $array = array();

            if(is_dir($dir . $doc[UsersSchema::USERNAME_ON_DISK]))
                $array[] = $dir. $doc[UsersSchema::USERNAME_ON_DISK];

            if(is_dir($dir . $doc[UsersSchema::USERNAME]))
                $array[] = $dir . $doc[UsersSchema::USERNAME];

            if(count($array) > 0)
                $users[$doc[UsersSchema::USERNAME]] = $array;
        }

        return $users;
    }


    public function findUsersInDatabase()
    {
        $cursor = $this->mongo->find();

        $users = array();

        foreach($cursor as $doc)
        {
            $array = array();

            $array[] = $doc[UsersSchema::USERNAME_ON_DISK];
            $array[] = $doc[UsersSchema::USERNAME];

            $users[$doc[UsersSchema::USERNAME]] = $array;
        }

        return $users;
    }

    public function userHasFolderOnDisk($possibleUserfolderNames)
    {
        $directories = Config::get('folders_to_scan_for_files');

        foreach ($directories as $directory)
            foreach($possibleUserfolderNames as $username)
                if(is_dir($directory.DIRECTORY_SEPARATOR.$username))
                    return true;

        return false;
    }


    public function findUsersInOrganisationsInDatabase()
    {
        $directories = Config::get('folders_to_scan_for_files');

        $cursor = $this->mongo->distinct("org");

        $users = array();

        foreach($cursor as $org) {

            $array = array();

            foreach ($directories as $directory) {

                $dir = $directory . DIRECTORY_SEPARATOR;

                $usersInOrg = $this->mongo->find(array(UsersSchema::ORG => $org));

                foreach($usersInOrg as $u) {

                    if(is_dir($dir . $u[UsersSchema::USERNAME_ON_DISK]))
                        $array[] = $dir . $u[UsersSchema::USERNAME_ON_DISK];

                    if(is_dir($dir . $u[UsersSchema::USERNAME]))
                        $array[] = $dir . $u[UsersSchema::USERNAME];
                }
            }
            $users[$org] = $array;
        }
        return $users;
    }
} 