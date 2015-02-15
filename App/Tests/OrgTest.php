<?php
class OrgTest extends PHPUnit_Framework_TestCase
{
    protected $_org;

    public function setUp()
    {
        require_once 'Org.php';
        $this->_org = new Org();
    }

    public function tearDown()
    {
        $this->_org = null;
    }

    public function testsetOrg()
    {
        $equals = $this->_org->setOrg("kim.syv");
        $this->assertEquals(true, $equals);
    }

    public function testsetOrgFalse1()
    {
        $equals = $this->_org->setOrg("kim.syversen@gmail.com");
        $this->assertEquals(false, $equals);
    }

    public function testsetOrgString()
    {
        $equals = $this->_org->setOrg("uninett");
        $this->assertEquals(true, $equals);
    }

    public function testsetOrgInt()
    {
        $equals = $this->_org->setOrg(1);
        $this->assertEquals(false, $equals);
    }

    public function testsetOrgDouble()
    {
        $equals = $this->_org->setOrg(1.234);
        $this->assertEquals(false, $equals);
    }

    public function testsetOrgArray()
    {
        $equals = $this->_org->setOrg(array("1", "2"));
        $this->assertEquals(false, $equals);
    }

    public function testsetOrgObject()
    {
        $equals = $this->_org->setOrg(new Org);
        $this->assertEquals(false, $equals);
    }
}
