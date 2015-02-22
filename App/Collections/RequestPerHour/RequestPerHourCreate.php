<?php namespace Uninett\Collections\RequestPerHour;

use Uninett\Models\RequestPerHourModel;

class RequestPerHourCreate
{
    private $object;

    public function createObjectFromResult($result)
    {
	    $this->object = new RequestPerHourModel();

        if (is_array($result)) {

            $variableWasSetSuccessfully = $this->object->setDate($result['Dates']);

            if($variableWasSetSuccessfully == false)

                return null;

            $variableWasSetSuccessfully = $this->object->setBytesSent($result['BytesSent']);

            if($variableWasSetSuccessfully == false)

                return null;

            $variableWasSetSuccessfully = $this->object->setRequest($result['Requests']);

            if($variableWasSetSuccessfully == false)

                return null;

            return $this->object;
        } else

            return null;
    }
}
