<?php namespace Uninett\Models;
use JsonSerializable;
use MongoDate;
use Uninett\Schemas\RequestsPerHourSchema;

class RequestPerHourModel implements JsonSerializable
{
    private $_date;
    private $_request;

    public function jsonSerialize()
    {
        return
        [
            RequestsPerHourSchema::DATE => $this->getDate(),
            RequestsPerHourSchema::REQUEST => $this->getRequest(),
        ];
    }

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

    public function getDate()
    {
        return $this->_date;
    }

    public function setRequest($request)
    {
        if(empty($request))
            $request = 0;

        if(!is_int($request))

            return false;

        $this->_request = $request;

        return true;
    }

    public function getRequest()
    {
        return $this->_request;
    }

}
