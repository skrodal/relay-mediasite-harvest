<?php namespace Uninett\Models;
use JsonSerializable;
use MongoDate;
use Uninett\Schemas\UsersSchema;

// *** Experimental model ***
class UserModel2 extends BaseModel
{
    private $username = "";
    private $username_on_disk = "";
    private $email = "";
    private $name = "";
    private $created_date = "";
    private $org = "";
    private $status = "";
    private $affiliation = "";

	public function variablesToArray(){
		$var = get_object_vars($this);
		foreach($var as &$value){
			if(is_object($value) && method_exists($value,'variablesToArray')){
				$value = $value->variablesToArray();
			}
		}
		return $var;
	}
	public function toArray()
	{
		return $this->variablesToArray();
	}


    public function setUsernameOnDisk($u)
    {
        if(!is_string($u))

            return false;

        $this->username_on_disk = $u;

        return true;
    }

    public function getUsernameOnDisk()
    {
        return $this->username_on_disk;
    }


    public function setAffiliation($affiliation)
    {
        if(!is_string($affiliation))

            return false;

        $this->affiliation = $affiliation;

        return true;
    }

    public function getAffiliation()
    {
        return $this->affiliation;
    }

    public function setCreatedDate($date)
    {
        if(!is_string($date))

            return false;

        $this->created_date = new MongoDate(strtotime($date));

        return true;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))

            return false;

        $this->email = $email;

        return true;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setName($name)
    {
        if(!is_string($name))

            return false;

        $this->name = $name;

        return true;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUsername($username)
    {
        if(!filter_var($username, FILTER_VALIDATE_EMAIL))

            return false;

        $this->username = $username;

        return true;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setOrg($org)
    {
        if(!is_string($org))

            return false;

        $this->org = $org;

        return true;
    }

    public function getOrg()
    {
        return $this->org;
    }

    public function setStatus($status)
    {
        if(!is_int($status))

            return false;

        $this->status = $status;

        return true;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
