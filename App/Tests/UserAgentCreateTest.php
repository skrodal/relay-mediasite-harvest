<?php
class UserAgentCreateTest extends PHPUnit_Framework_TestCase
{
    private $_record;

    private $_array;
    private $_array2;
    private $_array3;

    public function setUp()
    {
        require_once '../Interfaces/IStatisticsCreate.php';
        require_once 'UserAgent.php';
        require_once 'UserAgentCreate.php';

        $this->_record = new UserAgentCreate;

        $this->_array = array
        (
            "UserAgent" => "Mozilla/5.0+(Macintosh;+Intel+Mac+OS+X+10.6;+rv:30.0)+Gecko/20100101+Firefox/30.0",
            "Hits" => "1",
            "Dates" => "Jul 13 2014 12:00:00:000AM"
        );

        $this->_array2 = array
        (
            "UserAgent" => "",
            "Hits" => "",
            "Dates" => ""
        );

        $this->_array3 = array
        (
            "UserAgent" => 123,
            "Hits" => 1,
            "Dates" => "@"
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

    public function testSetArrayWrong2()
    {
        $val = $this->_record->createObjectFromResult($this->_array3);

        $this->assertNull($val);
    }
}
