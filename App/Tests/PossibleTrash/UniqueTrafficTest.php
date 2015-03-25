<?php
class UniqueTrafficTest extends PHPUnit_Framework_TestCase
{
    private $_record;

    public function setUp()
    {
        require_once 'UniqueTraffic.php';
        $this->_record = new DailyUniqueTrafficModel();
    }

    public function tearDown()
    {
        $this->_record = null;
    }

    public function testsetUriInt()
    {
        $equals = $this->_record->setUri(1);
        $this->assertEquals(false, $equals);
    }

    public function testsetUriString()
    {
        $equals = $this->_record->setUri("asdf");
        $this->assertEquals(true, $equals);
    }

    public function testsetUriDouble()
    {
        $equals = $this->_record->setUri(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testsetUriObject()
    {
        $equals = $this->_record->setUri(new DailyUniqueTrafficModel());
        $this->assertEquals(false, $equals);
    }

    public function testsetUriEmail()
    {
        $equals = $this->_record->setUri("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testsetUriArray()
    {
        $equals = $this->_record->setUri(array());
        $this->assertEquals(false, $equals);
    }

    public function testsetDateInt()
    {
        $equals = $this->_record->setDate(1);
        $this->assertEquals(false, $equals);
    }

    public function testsetDateString()
    {
        $equals = $this->_record->setDate("asdf");
        $this->assertEquals(true, $equals);
    }

    public function testsetDateDouble()
    {
        $equals = $this->_record->setDate(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testsetDateObject()
    {
        $equals = $this->_record->setDate(new DailyUniqueTrafficModel());
        $this->assertEquals(false, $equals);
    }

    public function testsetDateEmail()
    {
        $equals = $this->_record->setDate("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testsetDateArray()
    {
        $equals = $this->_record->setDate(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetIpInt()
    {
        $equals = $this->_record->setIp(1);
        $this->assertEquals(false, $equals);
    }

    public function testSetIpString()
    {
        $equals = $this->_record->setIp("asdf");
        $this->assertEquals(true, $equals);
    }

    public function testSetIpDouble()
    {
        $equals = $this->_record->setIp(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetIpObject()
    {
        $equals = $this->_record->setIp(new DailyUniqueTrafficModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetIpEmail()
    {
        $equals = $this->_record->setIp("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetIpArray()
    {
        $equals = $this->_record->setIp(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetRefererInt()
    {
        $equals = $this->_record->setReferer(1);
        $this->assertEquals(false, $equals);
    }

    public function testSetRefererString()
    {
        $equals = $this->_record->setReferer("asdf");
        $this->assertEquals(true, $equals);
    }

    public function testSetRefererDouble()
    {
        $equals = $this->_record->setReferer(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetRefererObject()
    {
        $equals = $this->_record->setReferer(new DailyUniqueTrafficModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetRefererEmail()
    {
        $equals = $this->_record->setReferer("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetRefererArray()
    {
        $equals = $this->_record->setReferer(array());
        $this->assertEquals(false, $equals);
    }

}
