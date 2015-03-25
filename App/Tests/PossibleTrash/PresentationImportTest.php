<?php
/**
 * Created by PhpStorm.
 * User: kim
 * Date: 12/27/14
 * Time: 4:43 AM
 */
require '../../vendor/autoload.php';
use \Mockery as m;
class PresentationImportTest extends PHPUnit_Framework_TestCase {
    public function setUp()
    {

    }
    public function tearDown()
    {
        \Mockery::close();
    }

    public function testA()
    {
        $a = \Mockery::mock('PresentationImport');

        $value = $a->shouldReceive('findHighestPresentationsId')->andReturn(12523);


    }
} 