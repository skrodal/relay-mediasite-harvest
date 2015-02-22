<?php
class UniqueTrafficCreateTest extends PHPUnit_Framework_TestCase
{
    private $_record;

    private $_array;
    private $_array2;
    public function setUp()
    {
        require_once '../Interfaces/IStatisticsCreate.php';
        require_once 'UniqueTraffic.php';
        require_once 'UniqueTrafficCreate.php';

        $this->_record = new UniqueTrafficCreate();

        $this->_array = array
        (
            "Dates" => "Jul 13 2014 12:00:00:000AM",
            "Uri" => "ansatt/odder@uio.no/2014/14.02/5771333/1._Folkerett_14_-_20140214_154908_10.mp4",
            "Ip" => "129.240.8.29",
            "Referer" => "http://www.jus.uio.no/studier/ressurser/podcast/rettsvitenskap/jus2111/folkerett---var-2014.html"
        );

        $this->_array2 = array
        (
            "Dates" => 1111,
            "Uri" => "",
            "Ip" => "",
            "Referer" => ""
        );
    }

    public function tearDown()
    {
        $this->_record = null;
    }

    public function testSetArray()
    {
        $val = $this->_record->createObjectFromResult($this->_array);

        $this->assertNotNull($val);
    }

    public function testSetArrayWrong()
    {
        $val = $this->_record->createObjectFromResult($this->_array2);

        $this->assertNull($val);
    }

}
