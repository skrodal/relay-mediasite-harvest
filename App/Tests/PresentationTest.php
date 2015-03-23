<?php

use Uninett\Models\PresentationModel;

class PresentationTest extends PHPUnit_Framework_TestCase
{
    private $presentation;

    public function setUp()
    {
        $this->presentation = new PresentationModel;
    }

    public function tearDown()
    {
        $this->presentation = null;
    }

	public function test_setting_wrong_recorder_name()
	{
		$equals = $this->presentation->setRecorderName(1);
		$this->assertEquals(false, $equals);
	}

	public function test_setting_correct_recorder_name()
	{
		$equals = $this->presentation->setRecorderName('Karl');
		$this->assertEquals(true, $equals);
	}


	public function test_setting_correct_org_name()
	{
		$equals = $this->presentation->setOrg('example.com');
		$this->assertEquals(true, $equals);
	}


	public function test_setting_wrong_org_name()
	{
		$equals = $this->presentation->setOrg(123);
		$this->assertEquals(false, $equals);
	}


	public function test_setting_correct_path()
	{
		$equals = $this->presentation->setPath('example.com');
		$this->assertEquals(true, $equals);
	}


	public function test_setting_wrong_path()
	{
		$equals = $this->presentation->setOrg(123);
		$this->assertEquals(false, $equals);
	}


	public function test_setting_correct_presentationId()
	{
		$equals = $this->presentation->setPresentationId(123);
		$this->assertEquals(true, $equals);
	}


	public function test_setting_wrong_presentationId()
	{
		$equals = $this->presentation->setPresentationId('asdf');
		$this->assertEquals(false, $equals);
	}

	public function test_setting_correct_description()
	{
		$equals = $this->presentation->setDescription('asdf');
		$this->assertEquals(true, $equals);
	}

	public function test_setting_correct_title()
	{
		$equals = $this->presentation->setDescription('asdf');
		$this->assertEquals(true, $equals);
	}


	public function test_setting_correct_size()
	{
		$equals = $this->presentation->setSize(12.12);
		$this->assertEquals(true, $equals);
	}

	public function test_setting_wrong_size()
	{
		$equals = $this->presentation->setSize('asdf');
		$this->assertEquals(false, $equals);
	}



	/*    public function testSetOutputFilesSizeInt()
		{
			$equals = $this->presentation->setOutputFilesSize(1);
			$this->assertEquals(false, $equals);
		}*/

  /*  public function testSetOutputFilesSizeString()
    {
        $equals = $this->_presentation->setOutputFilesSize("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetOutputFilesSizeDouble()
    {
        $equals = $this->_presentation->setOutputFilesSize(123.12);
        $this->assertEquals(true, $equals);
    }

    public function testSetOutputFilesSizeObject()
    {
        $equals = $this->_presentation->setOutputFilesSize(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetOutputFilesSizeEmail()
    {
        $equals = $this->_presentation->setOutputFilesSize("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetOutputFilesSizeArray()
    {
        $equals = $this->_presentation->setOutputFilesSize(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetResolutionInt()
    {
        $equals = $this->_presentation->setResolution(1);
        $this->assertEquals(false, $equals);
    }

    public function testSetResolutionString()
    {
        $equals = $this->_presentation->setResolution("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetResolutionDouble()
    {
        $equals = $this->_presentation->setResolution(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetResolutionObject()
    {
        $equals = $this->_presentation->setResolution(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetResolutionEmail()
    {
        $equals = $this->_presentation->setResolution("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetResolutionArray()
    {
        $equals = $this->_presentation->setResolution(array());
        $this->assertEquals(true, $equals);
    }

    public function testSetTimeInQueueInt()
    {
        $equals = $this->_presentation->setTimeInQueue(1);
        $this->assertEquals(true, $equals);
    }

    public function testSetTimeInQueueString()
    {
        $equals = $this->_presentation->setTimeInQueue("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeInQueueDouble()
    {
        $equals = $this->_presentation->setTimeInQueue(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeInQueueObject()
    {
        $equals = $this->_presentation->setTimeInQueue(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeInQueueEmail()
    {
        $equals = $this->_presentation->setTimeInQueue("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeInQueueArray()
    {
        $equals = $this->_presentation->setTimeInQueue(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeToEncodeInt()
    {
        $equals = $this->_presentation->setTimeToEncode(1);
        $this->assertEquals(true, $equals);
    }

    public function testSetTimeToEncodeString()
    {
        $equals = $this->_presentation->setTimeToEncode("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeToEncodeDouble()
    {
        $equals = $this->_presentation->setTimeToEncode(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeToEncodeObject()
    {
        $equals = $this->_presentation->setTimeToEncode(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeToEncodeEmail()
    {
        $equals = $this->_presentation->setTimeToEncode("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetTimeToEncodeArray()
    {
        $equals = $this->_presentation->setTimeToEncode(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetEncodingPresetInt()
    {
        $equals = $this->_presentation->setEncodingPreset(1);
        $this->assertEquals(false, $equals);
    }

    public function testSetEncodingPresetString()
    {
        $equals = $this->_presentation->setEncodingPreset("asdf");
        $this->assertEquals(true, $equals);
    }

    public function testSetEncodingPresetDouble()
    {
        $equals = $this->_presentation->setEncodingPreset(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetEncodingPresetObject()
    {
        $equals = $this->_presentation->setEncodingPreset(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetEncodingPresetEmail()
    {
        $equals = $this->_presentation->setEncodingPreset("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetEncodingPresetArray()
    {
        $equals = $this->_presentation->setEncodingPreset(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetXInt()
    {
        $equals = $this->_presentation->setX(1);
        $this->assertEquals(true, $equals);
    }

    public function testSetXString()
    {
        $equals = $this->_presentation->setX("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetXDouble()
    {
        $equals = $this->_presentation->setX(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetXObject()
    {
        $equals = $this->_presentation->setX(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetXArray()
    {
        $equals = $this->_presentation->setX(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetYInt()
    {
        $equals = $this->_presentation->setY(1);
        $this->assertEquals(true, $equals);
    }

    public function testSetYString()
    {
        $equals = $this->_presentation->setY("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetYDouble()
    {
        $equals = $this->_presentation->setY(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetYObject()
    {
        $equals = $this->_presentation->setY(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetYArray()
    {
        $equals = $this->_presentation->setY(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetFilenameInt()
    {
        $equals = $this->_presentation->setFilename(1);
        $this->assertEquals(false, $equals);
    }

    public function testSetFilenameString()
    {
        $equals = $this->_presentation->setFilename("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetFilenameStringCorrect()
    {
        $equals = $this->_presentation->setFilename("File.txt");
        $this->assertEquals(true, $equals);
    }

    public function testSetFilenameStringCorrect2()
    {
        $equals = $this->_presentation->setFilename("123.txt");
        $this->assertEquals(true, $equals);
    }

    public function testSetFilenameStringNotCorrect()
    {
        $equals = $this->_presentation->setFilename("File.123");
        $this->assertEquals(false, $equals);
    }

    public function testSetFilenameStringNotCorrect2()
    {
        $equals = $this->_presentation->setFilename("123.123");
        $this->assertEquals(false, $equals);
    }

    public function testSetFilenameDouble()
    {
        $equals = $this->_presentation->setFilename(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetFilenameObject()
    {
        $equals = $this->_presentation->setFilename(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetFilenameEmail()
    {
        $equals = $this->_presentation->setFilename("asdf@asdf.com");
        $this->assertEquals(true, $equals);
    }

    public function testSetFilenameArray()
    {
        $equals = $this->_presentation->setFilename(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetCreatedInt()
    {
        $equals = $this->_presentation->setCreated(1);
        $this->assertEquals(false, $equals);
    }

    public function testSetCreatedString()
    {
        $equals = $this->_presentation->setCreated("asdf");
        $this->assertEquals(true, $equals);
    }

    public function testSetCreatedDouble()
    {
        $equals = $this->_presentation->setCreated(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetCreatedObject()
    {
        $equals = $this->_presentation->setCreated(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetCreatedEmail()
    {
        $equals = $this->_presentation->setCreated("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetCreatedArray()
    {
        $equals = $this->_presentation->setCreated(array());
        $this->assertEquals(false, $equals);
    }
    public function testSetDeletedInt()
    {
        $equals = $this->_presentation->setDeleted(1);
        $this->assertEquals(true, $equals);
    }

    public function testSetDeletedString()
    {
        $equals = $this->_presentation->setDeleted("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetDeletedDouble()
    {
        $equals = $this->_presentation->setDeleted(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetDeletedObject()
    {
        $equals = $this->_presentation->setDeleted(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetDeletedEmail()
    {
        $equals = $this->_presentation->setDeleted("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetDeletedArray()
    {
        $equals = $this->_presentation->setDeleted(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetUsernameInt()
    {
        $equals = $this->_presentation->setUsername(1);
        $this->assertEquals(false, $equals);
    }

    public function testSetUsernameString()
    {
        $equals = $this->_presentation->setUsername("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetUsernameDouble()
    {
        $equals = $this->_presentation->setUsername(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetUsernameObject()
    {
        $equals = $this->_presentation->setUsername(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetUsernameEmail()
    {
        $equals = $this->_presentation->setUsername("asdf@asdf.com");
        $this->assertEquals(true, $equals);
    }

    public function testSetUsernameEmailCorrect2()
    {
        $equals = $this->_presentation->setUsername("asdf.asd1@asdf.com");
        $this->assertEquals(true, $equals);
    }

    public function testSetUsernameEmailCorrect3()
    {
        $equals = $this->_presentation->setUsername("asdf.asd1@asdf.asdf.com");
        $this->assertEquals(true, $equals);
    }

    public function testSetUsernameEmailWrong()
    {
        $equals = $this->_presentation->setUsername("asdf.asd1@asdf.asdf.123");
        $this->assertEquals(false, $equals);
    }

    public function testSetUsernameArray()
    {
        $equals = $this->_presentation->setUsername(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetTotalDurationInt()
    {
        $equals = $this->_presentation->setTotalDuration(1);
        $this->assertEquals(true, $equals);
    }

    public function testSetTotalDurationString()
    {
        $equals = $this->_presentation->setTotalDuration("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetTotalDurationDouble()
    {
        $equals = $this->_presentation->setTotalDuration(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetTotalDurationObject()
    {
        $equals = $this->_presentation->setTotalDuration(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetTotalDurationEmail()
    {
        $equals = $this->_presentation->setTotalDuration("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetTotalDurationArray()
    {
        $equals = $this->_presentation->setTotalDuration(array());
        $this->assertEquals(false, $equals);
    }

    public function testSetTrimmedDurationInt()
    {
        $equals = $this->_presentation->setTrimmedDuration(1);
        $this->assertEquals(true, $equals);
    }

    public function testSetTrimmedDurationString()
    {
        $equals = $this->_presentation->setTrimmedDuration("asdf");
        $this->assertEquals(false, $equals);
    }

    public function testSetTrimmedDurationDouble()
    {
        $equals = $this->_presentation->setTrimmedDuration(123.12);
        $this->assertEquals(false, $equals);
    }

    public function testSetTrimmedDurationObject()
    {
        $equals = $this->_presentation->setTrimmedDuration(new PresentationModel());
        $this->assertEquals(false, $equals);
    }

    public function testSetTrimmedDurationEmail()
    {
        $equals = $this->_presentation->setTrimmedDuration("asdf@asdf.com");
        $this->assertEquals(false, $equals);
    }

    public function testSetTrimmedDurationArray()
    {
        $equals = $this->_presentation->setTrimmedDuration(array());
        $this->assertEquals(false, $equals);
    }*/
}
