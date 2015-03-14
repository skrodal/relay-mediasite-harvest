<?php namespace Uninett\Collections\RequestPerHour;

use Uninett\Helpers\ConvertHelper;
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


	        /* This is removed because BytesSent ends up being negative.
	         * The believed reason is that the script uses 32 bit integers that the number
	         * from log file needs a 64 bit integer. Anyway, it was not used for anything anyway.
	         */


	        /*$mib_sent = $this->convert->bytesToMegabytes((int)$result['BytesSent']);
            $variableWasSetSuccessfully = $this->object->setBytesSent($mib_sent);
            if($variableWasSetSuccessfully == false)
				return null;*/

            $variableWasSetSuccessfully = $this->object->setRequest($result['Requests']);

            if($variableWasSetSuccessfully == false)

                return null;

            return $this->object;
        } else

            return null;
    }
}
