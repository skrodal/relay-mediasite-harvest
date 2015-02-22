<?php namespace Uninett\Collections\DailyUniqueTraffic;
//This class creates a new UniqueTraffic object with result data

use Exception;
use GeoIp2\Database\Reader;
use Uninett\Config;
use Uninett\Models\DailyUniqueTrafficModel;

class DailyUniqueTrafficCreate
{
    private $object;
    private $reader;

    public function __construct()
    {
        $this->object = new DailyUniqueTrafficModel();

        $this->reader = new Reader(Config::get('settings')['root'] . DIRECTORY_SEPARATOR .  'GeoLite2-City.mmdb');
    }

    public function createObjectFromResult($result)
    {
        if (is_array($result)) {
            $variableWasSetSuccessfully = $this->object->setDate($result['Dates']);

            if($variableWasSetSuccessfully == false)

                return null;

            $convertedUri = $this->covertPathToMatchPathInPresentations($result['Uri']);

            $variableWasSetSuccessfully = $this->object->setUri($convertedUri);

            if($variableWasSetSuccessfully == false)

                return null;

            $variableWasSetSuccessfully = $this->object->setIp($result['Ip']);

            if($variableWasSetSuccessfully == false)

                return null;

            //Results from GeoLite may be empty because database is not complete, therefore its no validation
            try {
                $record = $this->reader->city($result['Ip']);

                $this->object->setCountry($record->country->name);

                $this->object->setCity($record->city->name);

                $this->object->setRegion($record->mostSpecificSubdivision->name);

                $this->object->setLatitude($record->location->latitude);

                $this->object->setLongtitude($record->location->longitude);

            } catch(Exception $e) {
            }

            $variableWasSetSuccessfully = $this->object->setReferer($result['Referer']);

            if($variableWasSetSuccessfully == false)

                return null;

            return $this->object;
        } else

            return null;
    }
    private function covertPathToMatchPathInPresentations($uri)
    {
        $uriz = explode('/', $uri);

        $convertedUri = "";

        for($c = 2; $c < sizeof($uriz); $c++)
            if($c == (sizeof($uriz) -1))
                $convertedUri .= $uriz[$c];
            else
                $convertedUri .= $uriz[$c].DIRECTORY_SEPARATOR;

        return $convertedUri;
    }
}
