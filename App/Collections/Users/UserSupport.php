<?php namespace Uninett\Collections\Users;
class UserSupport {
    private $_mongoDatabaseConnection;

    function __construct()
    {
        $this->_mongoDatabaseConnection = new MongoConnection(
        Config::Instance()['mongoDatabase']['username'],
        Config::Instance()['mongoDatabase']['password'],
        Config::Instance()['mongoDatabase']['host'],
        Config::Instance()['mongoDatabase']['db'],
        UsersSchema::COLLECTION_NAME);
    }

    public function findUsersInDatabaseInDirectoryOnDisk($directory)
    {
        $cursor = $this->_mongoDatabaseConnection->find();

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
        $cursor = $this->_mongoDatabaseConnection ->find();

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
        foreach (Config::Instance()['folders_to_scan_for_files'] as $directory)
            foreach($possibleUserfolderNames as $username)
                if(is_dir($directory.DIRECTORY_SEPARATOR.$username))
                    return true;

        return false;
    }


    public function findUsersInOrganisationsInDatabase()
    {
        $cursor = $this->_mongoDatabaseConnection->distinct("org");

        $users = array();

        foreach($cursor as $org) {

            $array = array();

            foreach (Config::Instance()['folders_to_scan_for_files'] as $directory) {

                $dir = $directory . DIRECTORY_SEPARATOR;

                $usersInOrg = $this->_mongoDatabaseConnection->find(array(UsersSchema::ORG => $org));

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