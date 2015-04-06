<?php namespace Uninett\Models;
use JsonSerializable;
use MongoDate;
use Uninett\Schemas\UsersSchema;


/**
 * Class UserModel
 * @package Uninett\Models
 */
class UserModel implements JsonSerializable
{

	public function create(){

	}
	/**
	 * @var string
	 */
	private $username = "";
	/**
	 * @var string
	 */
	private $username_on_disk = "";
	/**
	 * @var string
	 */
	private $email = "";
	/**
	 * @var string
	 */
	private $name = "";
	/**
	 * @var string
	 */
	private $created_date = "";
	/**
	 * @var string
	 */
	private $org = "";
	/**
	 * @var string
	 */
	private $status = "";
	/**
	 * @var string
	 */
	private $affiliation = "";

/*	public function variablesToArray(){
		$var = get_object_vars($this);
		foreach($var as &$value){
			if(is_object($value) && method_exists($value,'variablesToArray')){
				$value = $value->variablesToArray();
			}
		}
		return $var;
	}*/

	/**
	 * @return array
	 */
	public function jsonSerialize()
    {
        return [
            UsersSchema::NAME => $this->getName(),
            UsersSchema::USERNAME => $this->getUsername(),
            UsersSchema::USERNAME_ON_DISK => $this->getUsernameOnDisk(),
            UsersSchema::ORG => $this->getOrg(),
            UsersSchema::EMAIL => $this->getEmail(),
            UsersSchema::AFFILIATION => $this->getAffiliation(),
            UsersSchema::CREATED => $this->getCreatedDate(),
            UsersSchema::STATUS => $this->getStatus(),
        ];
    }

	/**
	 * @param $u
	 * @return bool
	 */
	public function setUsernameOnDisk($u)
    {
        if(!is_string($u))

            return false;

        $this->username_on_disk = $u;

        return true;
    }

	/**
	 * @return string
	 */
	public function getUsernameOnDisk()
    {
        return $this->username_on_disk;
    }


	/**
	 * @param $affiliation
	 * @return bool
	 */
	public function setAffiliation($affiliation)
    {
        if(!is_string($affiliation))

            return false;

        $this->affiliation = $affiliation;

        return true;
    }

	/**
	 * @return string
	 */
	public function getAffiliation()
    {
        return $this->affiliation;
    }

	/**
	 * @param $date
	 * @return bool
	 */
	public function setCreatedDate($date)
    {
        if(!is_string($date))

            return false;

        $this->created_date = new MongoDate(strtotime($date));

        return true;
    }

	/**
	 * @return string
	 */
	public function getCreatedDate()
    {
        return $this->created_date;
    }

	/**
	 * @param $email
	 * @return bool
	 */
	public function setEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))

            return false;

        $this->email = $email;

        return true;
    }

	/**
	 * @return string
	 */
	public function getEmail()
    {
        return $this->email;
    }

	/**
	 * @param $name
	 * @return bool
	 */
	public function setName($name)
    {
        if(!is_string($name))

            return false;

        $this->name = $name;

        return true;
    }

	/**
	 * @return string
	 */
	public function getName()
    {
        return $this->name;
    }

	/**
	 * @param $username
	 * @return bool
	 */
	public function setUsername($username)
    {
        if(!filter_var($username, FILTER_VALIDATE_EMAIL))

            return false;

        $this->username = $username;

        return true;
    }

	/**
	 * @return string
	 */
	public function getUsername()
    {
        return $this->username;
    }

	/**
	 * @param $org
	 * @return bool
	 */
	public function setOrg($org)
    {
        if(!is_string($org))

            return false;

        $this->org = $org;

        return true;
    }

	/**
	 * @return string
	 */
	public function getOrg()
    {
        return $this->org;
    }

	/**
	 * @param $status
	 * @return bool
	 */
	public function setStatus($status)
    {
        if(!is_int($status))

            return false;

        $this->status = $status;

        return true;
    }

	/**
	 * @return string
	 */
	public function getStatus()
    {
        return $this->status;
    }
}
