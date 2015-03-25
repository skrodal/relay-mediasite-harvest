<?php
class UserAgentTest extends PHPUnit_Framework_TestCase
{
    private $_record;

    public function setUp()
    {
        require_once 'UserAgent.php';
        $this->_record = new UserAgentModel();
    }

    public function tearDown()
    {
        $this->_record = null;
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
        $equals = $this->_record->setDate(new UserAgentModel());
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

    public function testsetUserAgentInt()
    {
        $equals = $this->_record->setUserAgent(1);
        $this->assertEquals(false, $equals);
    }

    public function testsetUserAgentString()
    {
        $equals = $this->_record->setUserAgent("asdf");
        $this->assertEquals(true, $equals);
    }

    public function testsetUserAgentDouble()
    {
        $equals = $this->_record->setUserAgent(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testsetUserAgentObject()
    {
        $equals = $this->_record->setUserAgent(new UserAgentModel());
        $this->assertEquals(false, $equals);
    }

    public function testsetUserAgentEmail()
    {
        $equals = $this->_record->setUserAgent("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testsetUserAgentArray()
    {
        $equals = $this->_record->setUserAgent(array());
        $this->assertEquals(false, $equals);
    }

    public function testsetHitsInt()
    {
        $equals = $this->_record->setHits(1);
        $this->assertEquals(true, $equals);
    }

    public function testsetHitsString()
    {
        $equals = $this->_record->setHits("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testsetHitsDouble()
    {
        $equals = $this->_record->setHits(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testsetHitsObject()
    {
        $equals = $this->_record->setHits(new UserAgentModel());
        $this->assertEquals(false, $equals);
    }

    public function testsetHitsEmail()
    {
        $equals = $this->_record->setHits("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testsetHitsArray()
    {
        $equals = $this->_record->setHits(array());
        $this->assertEquals(false, $equals);
    }
}
