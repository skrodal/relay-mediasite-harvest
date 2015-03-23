<?php namespace Uninett\Models;
use JsonSerializable;
use MongoDate;
use Uninett\Schemas\RequestsPerHourSchema;

/**
 * Class RequestPerHourModel
 * @package Uninett\Models
 */
class RequestPerHourModel implements JsonSerializable
{

	/**
	 * @var
	 */
	private $_date;
	/**
	 * @var
	 */
	private $_request;

	/**
	 * @return array
	 */
	public function jsonSerialize()
    {
        return
        [
            RequestsPerHourSchema::DATE => $this->getDate(),
            RequestsPerHourSchema::REQUEST => $this->getRequest(),
        ];
    }

	/**
	 * @param $date
	 * @return bool
	 */
	public function setDate($date)
    {
        if (empty($date)) {
            return false;
        }
        if(!is_string($date))

            return false;

        if(filter_var($date, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_date = new MongoDate(strtotime($date));

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getDate()
    {
        return $this->_date;
    }

	/**
	 * @param $request
	 * @return bool
	 */
	public function setRequest($request)
    {
        if(empty($request))
            $request = 0;

        if(!is_int($request))

            return false;

        $this->_request = $request;

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getRequest()
    {
        return $this->_request;
    }

}
