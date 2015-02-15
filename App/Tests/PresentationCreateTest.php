<?php
class PresentationCreateTest extends PHPUnit_Framework_TestCase
{
    private $xmlFile = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<presentation relayVersion="4.3.0">
  <title>Den gode veiledningen</title>
  <description>Description Not Provided.</description>
  <date>09-Sep-13 19:49:12</date>
  <utcDate>09-Sep-13 17:49:12</utcDate>
  <profile>PC | Nettbrett | Mobil | Lyd</profile>
  <destinationUrl>https://screencast.uninett.no/relay/ansatt/ABM@uin.no/2013/09.09/686933/Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4</destinationUrl>
  <totalDuration>686933</totalDuration>
  <trimmedDuration>686933</trimmedDuration>
  <startTrimTime>0</startTrimTime>
  <endTrimTime>686933</endTrimTime>
  <recordedBy>
    <displayName>Aase Møllersen</displayName>
    <email>Aase.Mollersen@uin.no</email>
  </recordedBy>
  <presenter>
    <displayName>Aase Møllersen</displayName>
    <userName>ABM@uin.no</userName>
    <email>Aase.Mollersen@uin.no</email>
  </presenter>
  <clientInfo>
    <clientIP>158.39.57.26</clientIP>
    <clientComputerName>ipb1305650.uin.no</clientComputerName>
  </clientInfo>
  <serverInfo>
    <serverHostname>https://relay.uninett.no/Relay/rest.ashx</serverHostname>
    <encodingPreset>Nettbrett</encodingPreset>
    <timeToEncode>1022840</timeToEncode>
    <timeInQueue>2824</timeInQueue>
  </serverInfo>
  <sourceRecording>
    <!--resolution is the max resolution of the file collection.-->
    <resolution>1600x900</resolution>
    <fileList>
      <file clientPath="C:\Users\abm\AppData\Local\TechSmith\Camtasia Relay\Recordings\REC_28bf0c1.avi" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\SourceFiles\24318.avi" size="19498656" sequence="0" type="ScreenRecordingFile" resolution="1600x900" duration="686933" />
      <file clientPath="C:\Users\abm\AppData\Local\TechSmith\Camtasia Relay\Recordings\REC_28bf0c1_ppt.xml" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\SourceFiles\24319.xml" size="5337" sequence="0" type="TableOfContentsFile" resolution="0x0" duration="0" />
      <file clientPath="C:\Users\abm\AppData\Local\TechSmith\Camtasia Relay\Recordings\REC_28bf0c1_sysaudio.wav" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\SourceFiles\24320.wav" size="30296304" sequence="0" type="SystemAudioRecordingFile" resolution="0x0" duration="686933" />
      <file clientPath="Den_gode_veiledningen_-_PC_(Flash)_-_20130909_08.12.52PM_xmp.xml" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\EncodeFiles\5570\Den_gode_veiledningen_-_PC_(Flash)_-_20130909_08.12.52PM_xmp.xml" size="48942" sequence="1" type="XmpFile" />
    </fileList>
  </sourceRecording>
  <encodeFiles>
    <fileList>
      <file name="Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\EncodeFiles\5572\Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4" size="8526560" resolution="1280x720" duration="686866" />
    </fileList>
  </encodeFiles>
  <outputFiles>
    <fileList>
      <file name="Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4" destinationPath="https://screencast.uninett.no/relay/ansatt/ABM@uin.no/2013/09.09/686933/Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4" size="8526560" resolution="1280x720" duration="686866" />
    </fileList>
  </outputFiles>
  <metaData type="TableOfContents">
    <item startTime="300" text="Den gode veiledningen" />
    <item startTime="53900" text="Veiledning er en etisk handling" />
    <item startTime="203567" text="Veiledning som et møte" />
    <item startTime="439833" text="Et etisk faktum – å være prisgitt den andres tolkning" />
    <item startTime="573633" text="Levinas`poeng om etikk;" />
    <item startTime="627233" text="Litteratur" />
  </metaData>
  <metaData type="ScreenText">
    <item startTime="300" text=" Den gode veiledningen  " />
    <item startTime="53900" text=" Veiledning er en etisk handling   Skape rom for veiledning gjennom retningslinjer Ta i mot den andre eller den som veiledes på en god måte  " />
    <item startTime="203567" text=" Veiledning som et møte   Møtet Respons  - uttrykk Mening Begynn veiledningen der den andre er  " />
    <item startTime="439833" text=" Et etisk faktum – å være prisgitt den andres tolkning    At jeg er tvunget til å respondere på den andres uttrykk eller ansikt   Det er mitt ansvar ”å svare for” mine uttrykk " />
    <item startTime="573633" text=" Levinas`poeng om etikk;   ”Jeg har frihet til å velge mine ytringer, men jeg har ikke frihet til å fraskrive meg den friheten, og dermed heller ikke til å fraskrive meg ansvaret for den andre”. " />
    <item startTime="627233" text=" Litteratur  Aasland, Dag (20119. veiledningens etiske forutsetninger. I: Eide, Solveig Botnen et. Al. Til den andres beste. Oslo, Gyldendal Akademiske Tveiten, Sissel (2008). Veiledning – mer enn ord. Fagbokforlaget " />
  </metaData>
</presentation>
EOD;

    private $badXmlFile = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<badname relayVersion="4.3.0">
  <title>Den gode veiledningen</title>
  <description>Description Not Provided.</description>
  <date>09-Sep-13 19:49:12</date>
  <utcDate>09-Sep-13 17:49:12</utcDate>
  <profile>PC | Nettbrett | Mobil | Lyd</profile>
  <destinationUrl>https://screencast.uninett.no/relay/ansatt/ABM@uin.no/2013/09.09/686933/Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4</destinationUrl>
  <totalDuration>686933</totalDuration>
  <trimmedDuration>686933</trimmedDuration>
  <startTrimTime>0</startTrimTime>
  <endTrimTime>686933</endTrimTime>
  <recordedBy>
    <displayName>Aase Møllersen</displayName>
    <email>Aase.Mollersen@uin.no</email>
  </recordedBy>
  <presenter>
    <displayName>Aase Møllersen</displayName>
    <userName>ABM@uin.no</userName>
    <email>Aase.Mollersen@uin.no</email>
  </presenter>
  <clientInfo>
    <clientIP>158.39.57.26</clientIP>
    <clientComputerName>ipb1305650.uin.no</clientComputerName>
  </clientInfo>
  <serverInfo>
    <serverHostname>https://relay.uninett.no/Relay/rest.ashx</serverHostname>
    <encodingPreset>Nettbrett</encodingPreset>
    <timeToEncode>1022840</timeToEncode>
    <timeInQueue>2824</timeInQueue>
  </serverInfo>
  <sourceRecording>
    <!--resolution is the max resolution of the file collection.-->
    <resolution>1600x900</resolution>
    <fileList>
      <file clientPath="C:\Users\abm\AppData\Local\TechSmith\Camtasia Relay\Recordings\REC_28bf0c1.avi" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\SourceFiles\24318.avi" size="19498656" sequence="0" type="ScreenRecordingFile" resolution="1600x900" duration="686933" />
      <file clientPath="C:\Users\abm\AppData\Local\TechSmith\Camtasia Relay\Recordings\REC_28bf0c1_ppt.xml" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\SourceFiles\24319.xml" size="5337" sequence="0" type="TableOfContentsFile" resolution="0x0" duration="0" />
      <file clientPath="C:\Users\abm\AppData\Local\TechSmith\Camtasia Relay\Recordings\REC_28bf0c1_sysaudio.wav" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\SourceFiles\24320.wav" size="30296304" sequence="0" type="SystemAudioRecordingFile" resolution="0x0" duration="686933" />
      <file clientPath="Den_gode_veiledningen_-_PC_(Flash)_-_20130909_08.12.52PM_xmp.xml" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\EncodeFiles\5570\Den_gode_veiledningen_-_PC_(Flash)_-_20130909_08.12.52PM_xmp.xml" size="48942" sequence="1" type="XmpFile" />
    </fileList>
  </sourceRecording>
  <encodeFiles>
    <fileList>
      <file name="Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4" serverPath="C:\ProgramData\TechSmith\Camtasia Relay\Media\641\EncodeFiles\5572\Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4" size="8526560" resolution="1280x720" duration="686866" />
    </fileList>
  </encodeFiles>
  <outputFiles>
    <fileList>
      <file name="Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4" destinationPath="https://screencast.uninett.no/relay/ansatt/ABM@uin.no/2013/09.09/686933/Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4" size="8526560" resolution="1280x720" duration="686866" />
    </fileList>
  </outputFiles>
  <metaData type="TableOfContents">
    <item startTime="300" text="Den gode veiledningen" />
    <item startTime="53900" text="Veiledning er en etisk handling" />
    <item startTime="203567" text="Veiledning som et møte" />
    <item startTime="439833" text="Et etisk faktum – å være prisgitt den andres tolkning" />
    <item startTime="573633" text="Levinas`poeng om etikk;" />
    <item startTime="627233" text="Litteratur" />
  </metaData>
  <metaData type="ScreenText">
    <item startTime="300" text=" Den gode veiledningen  " />
    <item startTime="53900" text=" Veiledning er en etisk handling   Skape rom for veiledning gjennom retningslinjer Ta i mot den andre eller den som veiledes på en god måte  " />
    <item startTime="203567" text=" Veiledning som et møte   Møtet Respons  - uttrykk Mening Begynn veiledningen der den andre er  " />
    <item startTime="439833" text=" Et etisk faktum – å være prisgitt den andres tolkning    At jeg er tvunget til å respondere på den andres uttrykk eller ansikt   Det er mitt ansvar ”å svare for” mine uttrykk " />
    <item startTime="573633" text=" Levinas`poeng om etikk;   ”Jeg har frihet til å velge mine ytringer, men jeg har ikke frihet til å fraskrive meg den friheten, og dermed heller ikke til å fraskrive meg ansvaret for den andre”. " />
    <item startTime="627233" text=" Litteratur  Aasland, Dag (20119. veiledningens etiske forutsetninger. I: Eide, Solveig Botnen et. Al. Til den andres beste. Oslo, Gyldendal Akademiske Tveiten, Sissel (2008). Veiledning – mer enn ord. Fagbokforlaget " />
  </metaData>
</badname>
EOD;
    private $_presentation;
    private $file;
    private $badFile;

    public function setUp()
    {
        require_once '../../Support/Logging/Logging.php';
        require_once 'Presentation.php';
        require_once 'VideoCreate.php';

        require_once '../../Support/Convert.php';
        require_once '../Presentations/PresentationSchema.php';

        $this->_presentation = new PresentationCreate();
        $this->file = $this->_presentation->create(simplexml_load_string(utf8_encode($this->xmlFile)));
    }

    public function tearDown()
    {
        $this->_presentation = null;
    }

    private function _compareStrings($string1, $string2)
    {
        $strlen1 = strlen($string1);
        $strlen2 = strlen($string2);

        if($strlen1 != $strlen2)

            return false;

        for ($i = 0; $i < strlen($string1); $i++) {

            $ord1 = ord($string1);
            $ord2 = ord($string2);

            if($ord1 =! $ord2)

                return false;
        }

        return true;
    }

    public function testCreateCreated()
    {
        $this->assertEquals($this->file->getCreated(), new MongoDate(strtotime("2013-09-09T17:49:12Z")));
    }

    public function testCreateDeleted()
    {
        $this->assertEquals($this->file->getDeleted(), 0);
    }

    public function testUsername()
    {
        $this->assertEquals(strcasecmp(strval($this->file->getUsername()), "ABM@uin.no") === 0, true);
    }

    public function testFilename()
    {
        $stringToCompare = "ansatt/ABM@uin.no/2013/09.09/686933/Den_gode_veiledningen_-_Nettbrett_-_20130909_08.12.52PM.mp4";

        $IsStringsEqual = $this->_compareStrings($this->file->getFilename(), $stringToCompare);

        if($IsStringsEqual == true)
            $this->assertTrue(true);
    }

    public function testDuration()
    {
        $this->assertEquals($this->file->getTotalDuration() == 687, true);
    }

    public function testTrimmed()
    {
        $this->assertEquals($this->file->getTrimmedDuration() == 687, true);
    }

    public function testEncoding()
    {
        //strcasecmp returns 0 if strings are equal and doesnt care about capslock
        $this->assertEquals(strcasecmp(strval($this->file->getEncodingPreset()), "Nettbrett") === 0, true);
    }

    public function testEncodeTime()
    {
        $this->assertEquals($this->file->getTimeToEncode() == 1023, true);
    }

    public function testQueueTime()
    {
        $this->assertEquals($this->file->getTimeInQueue() == 3, true);
    }

    public function testResolution()
    {
        $resolution = $this->file->getResolution();

        $this->assertEquals($resolution[PresentationSchema::X] == 1600, true);
        $this->assertEquals($resolution[PresentationSchema::Y] == 900, true);
    }
    public function testSize()
    {
        $this->assertEquals($this->file->getOutputFilesSize() == 8.13, true);
    }

}
