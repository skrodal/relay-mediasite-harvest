<?php namespace Uninett\Collections\UserDiskusage;

use Uninett\Schemas\UserDiskUsageSchema;

class UserDiskUsage
{
    private $_username = "";
    private $_date = "";

    private $_storage;
    private $_size;

    private $_org;


    public function setOrg($org)
    {
        $this->_org = $org;
    }


    public function getOrg()
    {
        return $this->_org;
    }

    public function __construct()
    {
        $this->_storage = array
        (

        );
    }

    public function setDate($date)
    {
        $this->_date = $date;

        return true;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function setUsername($username)
    {
        if(!filter_var($username, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_username = $username;

        return true;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function setSize($size)
    {
        if(!is_float($size))

            return false;

        $this->_size = $size;

        return true;
    }

    public function getSize()
    {
        return $this->_size;
    }

    public function jsonSerialize()
    {
        return
            [
                UserDiskUsageSchema::USERNAME => $this->getUsername(),
                UserDiskUsageSchema::ORG => $this->getOrg(),
                UserDiskUsageSchema::STORAGE  =>
                    [
                        array
                        (
                            UserDiskUsageSchema::DATE => $this->getDate(),
                            UserDiskUsageSchema::SIZE => $this->getSize()
                        )
                    ]
            ];
    }
}
