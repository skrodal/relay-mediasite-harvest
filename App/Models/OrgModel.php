<?php namespace Uninett\Models;
//This is a model file
use JsonSerializable;
use Uninett\Schemas\OrgSchema;

/**
 * Class OrgModel
 * @package Uninett\Models
 */
class OrgModel implements JsonSerializable
{

	/**
	 * @var
	 */
	private $_org;

    //Empty array when created. orgsAggregateSizeUsed take care of this.
	/**
	 * @var array
	 */
	private $_storage;

	/**
	 *
	 */
	public function __construct()
    {
        $this->_storage = array
        (

        );
    }

	/**
	 * @param $org
	 * @return bool
	 */
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

	/**
	 * @return mixed
	 */
	public function getOrg()
    {
        return $this->_org;
    }

	/**
	 * @param $size
	 * @return bool
	 */
	public function setStorageSize($size)
    {
        if(!is_int($size))

            return false;

        $this->_storage[OrgSchema::SIZE] = $size;

        return true;
    }

	/**
	 * @return array
	 */
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

	/**
	 * @param $org
	 * @return mixed
	 */
	private function _looksLikeEmail($org)
    {
        return filter_var($org, FILTER_VALIDATE_EMAIL);
    }
}
