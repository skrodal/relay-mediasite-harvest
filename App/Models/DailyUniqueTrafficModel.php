<?php namespace Uninett\Models;
//This class is a model class
use JsonSerializable;
use MongoDate;
use Uninett\Schemas\DailyUniqueTrafficSchema;

class DailyUniqueTrafficModel implements JsonSerializable
{
    private $_referer = "";
    private $_ip = "";
    private $_date = "";
    private $_uri = "";

    private $_country;
    private $_city;
    private $_region;

    private $_latitude;
    private $_longtitude;

    public function setCity($city)
    {
        $this->_city = $city;
    }

    public function getCity()
    {
        return $this->_city;
    }

    public function setCountry($country)
    {
        $this->_country = $country;
    }

    public function getCountry()
    {
        return $this->_country;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function getLatitude()
    {
        return $this->_latitude;
    }

    public function setLongtitude($longtitude)
    {
        $this->_longtitude = $longtitude;
    }

    public function getLongtitude()
    {
        return $this->_longtitude;
    }

    public function setRegion($region)
    {
        $this->_region = $region;
    }

    public function getRegion()
    {
        return $this->_region;
    }



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

    public function getUri()
    {
        return $this->_uri;
    }

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

    public function getDate()
    {
        return $this->_date;
    }

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

    public function getIp()
    {
        return $this->_ip;
    }

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

    public function getReferer()
    {
        return $this->_referer;
    }
}
