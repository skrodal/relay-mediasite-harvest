<?php namespace Uninett\Models;

use JsonSerializable;
use MongoDate;
use Uninett\Schemas\DailyUserAgentsSchema;

/**
 * Class DailyUserAgentModel
 * @package Uninett\Models
 */
class DailyUserAgentModel implements JsonSerializable
{

	/**
	 * @var string
	 */
	private $_userAgent = "";
	/**
	 * @var int
	 */
	private $_hits = 0;
	/**
	 * @var string
	 */
	private $_date = "";

	/**
	 * @return array
	 */
	public function jsonSerialize()
    {
        return [
            DailyUserAgentsSchema::DATE => $this->getDate(),
            DailyUserAgentsSchema::HITS => $this->getHits(),
            DailyUserAgentsSchema::USER_AGENT => $this->getUserAgent()
        ];
    }

	/**
	 * @param $date
	 * @return bool
	 */
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

	/**
	 * @return string
	 */
	public function getDate()
    {
        return $this->_date;
    }

	/**
	 * @param $hits
	 * @return bool
	 */
	public function setHits($hits)
    {
        if(empty($hits))
            $hits = 0;

        if(!is_int($hits))

            return false;

        $this->_hits = $hits;

        return true;
    }

	/**
	 * @return int
	 */
	public function getHits()
    {
        return $this->_hits;
    }

	/**
	 * @param $userAgent
	 * @return bool
	 */
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

	/**
	 * @return string
	 */
	public function getUserAgent()
    {
        return $this->_userAgent;
    }

}
