<?php namespace Uninett\Models;

use JsonSerializable;
use MongoDate;
use Uninett\Schemas\DailyUserAgentsSchema;

class DailyUserAgentModel implements JsonSerializable
{
    private $_userAgent = "";
    private $_hits = 0;
    private $_date = "";

    public function jsonSerialize()
    {
        return [
            DailyUserAgentsSchema::DATE => $this->getDate(),
            DailyUserAgentsSchema::HITS => $this->getHits(),
            DailyUserAgentsSchema::USER_AGENT => $this->getUserAgent()
        ];
    }

    public function setDate($date)
    {
        if(empty($date))

            return false;

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

    public function setHits($hits)
    {
        if(empty($hits))
            $hits = 0;

        if(!is_int($hits))

            return false;

        $this->_hits = $hits;

        return true;
    }

    public function getHits()
    {
        return $this->_hits;
    }

    public function setUserAgent($userAgent)
    {
        if(empty($userAgent))
            $userAgent = "Unknown";

        if(!is_string($userAgent))

            return false;

        if(filter_var($userAgent, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_userAgent = $userAgent;

        return true;
    }

    public function getUserAgent()
    {
        return $this->_userAgent;
    }

}
