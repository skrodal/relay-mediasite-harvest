<?php namespace Uninett\Models;
use JsonSerializable;
use MongoDate;
use Uninett\Schemas\UsersSchema;

/**
 * status, see UsersCheckStatus.php
 */
class User implements JsonSerializable
{
    private $_username = "";
    private $_username_disk = "";
    private $_email = "";
    private $_name = "";
    private $_date = "";
    private $_org = "";
    private $_status = "";
    private $_affiliation = "";

    public function jsonSerialize()
    {
        return [
            UsersSchema::NAME => $this->getName(),
            UsersSchema::USERNAME => $this->getUsername(),
            UsersSchema::USERNAME_ON_DISK => $this->getUsernameDisk(),
            UsersSchema::ORG => $this->getOrg(),
            UsersSchema::EMAIL => $this->getEmail(),
            UsersSchema::AFFILIATION => $this->getAffiliation(),
            UsersSchema::CREATED => $this->getDate(),
            UsersSchema::STATUS => $this->getStatus(),
        ];
    }

    public function setUsernameOnDisk($u)
    {
        if(!is_string($u))

            return false;

        $this->_username_disk = $u;

        return true;
    }

    public function getUsernameDisk()
    {
        return $this->_username_disk;
    }


    public function setAffiliation($affiliation)
    {
        if(!is_string($affiliation))

            return false;

        $this->_affiliation = $affiliation;

        return true;
    }

    public function getAffiliation()
    {
        return $this->_affiliation;
    }

    public function setDate($date)
    {
        if(!is_string($date))

            return false;

        $this->_date = new MongoDate(strtotime($date));

        return true;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function setEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_email = $email;

        return true;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setName($name)
    {
        if(!is_string($name))

            return false;

        $this->_name = $name;

        return true;
    }

    public function getName()
    {
        return $this->_name;
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

    public function setOrg($org)
    {
        if(!is_string($org))

            return false;

        $this->_org = $org;

        return true;
    }

    public function getOrg()
    {
        return $this->_org;
    }

    public function setStatus($status)
    {
        if(!is_int($status))

            return false;

        $this->_status = $status;

        return true;
    }

    public function getStatus()
    {
        return $this->_status;
    }
}
