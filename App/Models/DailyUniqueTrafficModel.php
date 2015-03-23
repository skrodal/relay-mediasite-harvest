<?php namespace Uninett\Models;
//This class is a model class
use JsonSerializable;
use MongoDate;
use Uninett\Schemas\DailyUniqueTrafficSchema;

/**
 * Class DailyUniqueTrafficModel
 * @package Uninett\Models
 */
class DailyUniqueTrafficModel implements JsonSerializable
{

	/**
	 * @var string
	 */
	private $_referer = "";
	/**
	 * @var string
	 */
	private $_ip = "";
	/**
	 * @var string
	 */
	private $_date = "";
	/**
	 * @var string
	 */
	private $_uri = "";

	/**
	 * @var
	 */
	private $_country;
	/**
	 * @var
	 */
	private $_city;
	/**
	 * @var
	 */
	private $_region;

	/**
	 * @var
	 */
	private $_latitude;
	/**
	 * @var
	 */
	private $_longtitude;

	/**
	 * @param $city
	 */
	public function setCity($city)
    {
        $this->_city = $city;
    }

	/**
	 * @return mixed
	 */
	public function getCity()
    {
        return $this->_city;
    }

	/**
	 * @param $country
	 */
	public function setCountry($country)
    {
        $this->_country = $country;
    }

	/**
	 * @return mixed
	 */
	public function getCountry()
    {
        return $this->_country;
    }

	/**
	 * @param $latitude
	 */
	public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

	/**
	 * @return mixed
	 */
	public function getLatitude()
    {
        return $this->_latitude;
    }

	/**
	 * @param $longtitude
	 */
	public function setLongtitude($longtitude)
    {
        $this->_longtitude = $longtitude;
    }

	/**
	 * @return mixed
	 */
	public function getLongtitude()
    {
        return $this->_longtitude;
    }

	/**
	 * @param $region
	 */
	public function setRegion($region)
    {
        $this->_region = $region;
    }

	/**
	 * @return mixed
	 */
	public function getRegion()
    {
        return $this->_region;
    }


	/**
	 * @return array
	 */
	public function jsonSerialize()
    {
        return [
            DailyUniqueTrafficSchema::DATE => $this->getDate(),
            DailyUniqueTrafficSchema::URI => $this->getUri(),
            DailyUniqueTrafficSchema::REFERER => $this->getReferer(),
            DailyUniqueTrafficSchema::GEO => array
            (
	            DailyUniqueTrafficSchema::IP => $this->getIp(),
	            DailyUniqueTrafficSchema::COUNTRY => $this->getCountry(),
	            DailyUniqueTrafficSchema::REGION => $this->getRegion(),
	            DailyUniqueTrafficSchema::CITY => $this->getCity(),
	            DailyUniqueTrafficSchema::LATITUDE => $this->getLatitude(),
	            DailyUniqueTrafficSchema::LONGTITUDE => $this->getLongtitude()
            )
        ];
    }

	/**
	 * @param $uri
	 * @return bool
	 */
	public function setUri($uri)
    {
        if(is_array($uri))

            return false;

        if(empty($uri))
            $uri = "";

        if(!is_string($uri))

            return false;

        if(filter_var($uri, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_uri = $uri;

        return true;
    }

	/**
	 * @return string
	 */
	public function getUri()
    {
        return $this->_uri;
    }

	/**
	 * @param $date
	 * @return bool
	 */
	public function setDate($date)
    {
        if(is_array($date))

            return false;

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
	 * @param $ip
	 * @return bool
	 */
	public function setIp($ip)
    {
        if(is_array($ip))

            return false;

        if(empty($ip))
            $ip = "";

        if(!is_string($ip))

            return false;

        if(filter_var($ip, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_ip = $ip;

        return true;
    }

	/**
	 * @return string
	 */
	public function getIp()
    {
        return $this->_ip;
    }

	/**
	 * @param $referer
	 * @return bool
	 */
	public function setReferer($referer)
    {
        if(is_array($referer))

            return false;

        if(empty($referer))
            $referer = "";

        if(!is_string($referer))

            return false;

        if(filter_var($referer, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_referer = $referer;

        return true;
    }

	/**
	 * @return string
	 */
	public function getReferer()
    {
        return $this->_referer;
    }
}
