<?php
/**
 * Created by PhpStorm.
 * User: kim
 * Date: 10/07/14
 * Time: 17:35
 */

class RequestPerHourTest extends PHPUnit_Framework_TestCase
{
    private $_record;

    public function setUp()
    {
        require_once 'RequestPerHour.php';
        $this->_record = new RequestPerHourModel();
    }

    public function tearDown()
    {
        $this->_record = null;
    }

    public function testsetBytesSentInt()
    {
        $equals = $this->_record->setBytesSent(0);
        $this->assertEquals(true, $equals);
    }

    public function testsetBytesSentString()
    {
        $equals = $this->_record->setBytesSent("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testsetBytesSentString2()
    {
        $equals = $this->_record->setBytesSent("Jul  9 2014 12:00:00:000AM");
        $this->assertEquals(false, $equals);
    }

    public function testsetBytesSentDouble()
    {
        $equals = $this->_record->setBytesSent(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testsetBytesSentObject()
    {
        $equals = $this->_record->setBytesSent(new RequestPerHourModel());
        $this->assertEquals(false, $equals);
    }

    public function testsetBytesSentEmail()
    {
        $equals = $this->_record->setBytesSent("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testsetBytesSentArray()
    {
        $equals = $this->_record->setBytesSent(array());
        $this->assertEquals(false, $equals);
    }

    public function testsetRequestInt()
    {
        $equals = $this->_record->setRequest(1);
        $this->assertEquals(true, $equals);
    }

    public function testsetRequestString()
    {
        $equals = $this->_record->setRequest("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testsetRequestString2()
    {
        $equals = $this->_record->setRequest("Jul  9 2014 12:00:00:000AM");
        $this->assertEquals(false, $equals);
    }

    public function testsetRequestDouble()
    {
        $equals = $this->_record->setRequest(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testsetRequestObject()
    {
        $equals = $this->_record->setRequest(new RequestPerHourModel());
        $this->assertEquals(false, $equals);
    }

    public function testsetRequestEmail()
    {
        $equals = $this->_record->setRequest("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testsetRequestArray()
    {
        $equals = $this->_record->setRequest(array());
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

    public function testsetDateString2()
    {
        $equals = $this->_record->setDate("Jul  9 2014 12:00:00:000AM");
        $this->assertEquals(true, $equals);
    }

    public function testsetDateDouble()
    {
        $equals = $this->_record->setDate(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testsetDateObject()
    {
        $equals = $this->_record->setDate(new RequestPerHourModel());
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
}
