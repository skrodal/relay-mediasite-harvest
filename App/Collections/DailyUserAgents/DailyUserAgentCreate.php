<?php namespace Uninett\Collections\DailyUserAgents;
use Uninett\Models\DailyUserAgentModel;

class DailyUserAgentCreate
{
    public function createObjectFromResult($result)
    {
        if (is_array($result)) {
            $object = new DailyUserAgentModel();

            $variableWasSetSuccessfully = $object->setDate($result['Dates']);

            if($variableWasSetSuccessfully == false)

                return null;

            $variableWasSetSuccessfully = $object->setHits($result['Hits']);

            if($variableWasSetSuccessfully == false)

                return null;

            $variableWasSetSuccessfully = $object->setUserAgent($result['UserAgent']);

            if($variableWasSetSuccessfully == false)

                return null;

            return $object;
        } else

            return false;
    }

}
