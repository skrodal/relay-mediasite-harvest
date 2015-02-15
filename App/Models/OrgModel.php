<?php namespace Uninett\Models;
//This is a model file
use JsonSerializable;
use Uninett\Schemas\OrgSchema;

class OrgModel implements JsonSerializable
{
    private $_org;

    //Empty array when created. orgsAggregateSizeUsed take care of this.
    private $_storage;

    public function __construct()
    {
        $this->_storage = array
        (

        );
    }

    public function setOrg($org)
    {
        if(empty($org))

            return false;

        if($this->_looksLikeEmail($org))

            return false;

        if(!is_string($org))

            return false;

        $this->_org = $org;

        return true;
    }

    public function getOrg()
    {
        return $this->_org;
    }

    public function setStorageSize($size)
    {
        if(!is_int($size))

            return false;

        $this->_storage[OrgSchema::SIZE] = $size;

        return true;
    }

    public function jsonSerialize()
    {
        return
            [
                OrgSchema::ORG => $this->getOrg(),
                OrgSchema::STORAGE =>
                    array
                    (

                    )
            ];
    }

    private function _looksLikeEmail($org)
    {
        return filter_var($org, FILTER_VALIDATE_EMAIL);
    }
}
