<?php
class RequestPerHourCreateTest extends PHPUnit_Framework_TestCase
{
    private $_record;

    private $_array;
    private $_array2;
    public function setUp()
    {
        require_once '../Interfaces/IStatisticsCreate.php';
        require_once 'RequestPerHour.php';
        require_once 'RequestPerHourCreate.php';

        $this->_record = new RequestPerHourCreate();

        $this->_array = array
        (
            "Dates" => "Jul  9 2014 11:00:00:000PM",
            "Requests" => 11,
            "BytesSent" => 3047
        );

        $this->_array2 = array
        (
            "Dates" => "Jul  9 2014 11:00:00:000PM",
            "Requests" => "",
            "BytesSent" => ""
        );

        $this->_array3 = array
        (
            "Dates" => "Jul  9 2014 11:00:00:000PM",
            "Requests" => NULL,
            "BytesSent" => NULL,
        );

        $this->_array4 = array
        (
            "Dates" => "",
            "Requests" => "",
            "BytesSent" => "",
        );
    }

    public function tearDown()
    {
        $this->_record = null;
    }

    public function testArray()
    {
        $val = $this->_record->createObjectFromResult($this->_array);

        $this->assertNotNull($val);
    }

    public function testArray2()
    {
        $val = $this->_record->createObjectFromResult($this->_array2);

        $this->assertNotNull($val);
    }

    public function testArray3()
    {
        $val = $this->_record->createObjectFromResult($this->_array3);

        $this->assertNotNull($val);
    }

        public function testArray4()
        {
            $val = $this->_record->createObjectFromResult($this->_array4);

            $this->assertNull($val);
        }

}
