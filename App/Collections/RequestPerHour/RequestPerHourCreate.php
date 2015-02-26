<?php namespace Uninett\Collections\RequestPerHour;

use Uninett\Helpers\ConvertHelper;
use Uninett\Models\RequestPerHourModel;

class RequestPerHourCreate
{
    private $object;

	//private $convert;

	function __construct()
	{
		//$this->convert = new ConvertHelper();
	}


	public function createObjectFromResult($result)
    {
	    $this->object = new RequestPerHourModel();

        if (is_array($result)) {

            $variableWasSetSuccessfully = $this->object->setDate($result['Dates']);

            if($variableWasSetSuccessfully == false)

                return null;

	    /*    $mib_sent = $this->convert->bytesToMegabytes((int)$result['BytesSent']);

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
