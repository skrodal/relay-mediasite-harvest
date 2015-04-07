<?php namespace Uninett\Core; 
use Carbon\Carbon;
use DOMDocument;
use SimpleXMLElement;
use Uninett\Models\Ecampussql\TblFile;

class XmlCreator {
	public function createFile() {
		$faker = \Faker\Factory::create();

		$destinationUrl = 'http://example.com/relay/ansatt/';
		$username = substr(md5(rand()), 0, 7);

		$presentationTitle = md5(rand());

		$presentation = new SimpleXMLElement('<presentation/>');
		$presentation->addAttribute('relayVersion', '4.4.1');

		$presentation->addChild('title', $presentationTitle);
		$presentation->addChild('description',  $faker->text());
		$presentation->addChild('date', Carbon::now());
		$presentation->addChild('utcDate', Carbon::createFromTimestampUTC(Carbon::now()->timestamp));
		$presentation->addChild('profile', 'PC | Nettbrett | Mobil | Lyd');
		$presentation->addChild('destinationUrl', '');
		$presentation->addChild('totalDuration', '');
		$presentation->addChild('trimmedDuration', '');
		$presentation->addChild('startTrimTime', '');

		$recordedBy = $presentation->addChild('recordedBy');
		$recordedBy->addChild('displayName', '');
		$recordedBy->addChild('email', '');

		$clientInfo = $presentation->addChild('clientInfo');
		$clientInfo->addChild('clientIP', '');
		$clientInfo->addChild('clientComputerName', '');


		$serverInfo = $presentation->addChild('serverInfo', '');
		$serverInfo->addChild('serverHostname', '');
		$serverInfo->addChild('encodingPreset', '');
		$serverInfo->addChild('timeToEncode', '');
		$serverInfo->addChild('timeInQueue', '');

		$sourceRecording = $presentation->addChild('sourceRecording');
		$sourceRecording->addChild('resolution', '');

		//TODO: FIX
		$fileList = $sourceRecording->addChild('fileList', '');
		$fileList->addChild('file');
		$fileList->addChild('file');
		$fileList->addChild('file');

		$presentation->addChild('startTrimTime', '');
		$presentation->addChild('startTrimTime', '');
		$presentation->addChild('startTrimTime', '');
		$presentation->addChild('startTrimTime', '');
		$presentation->addChild('startTrimTime', '');

		$encodeFiles = $presentation->addChild('encodeFiles');
		$encodeFilesFilelist = $encodeFiles->addChild('fileList');
		$encodeFilesFilelist->addChild('file');

		$outputFiles = $presentation->addChild('outputFiles');

		$document = $this->preserveWhitespace($presentation);

		var_dump($document);
		file_put_contents(getenv('APP_PATH') . '/Test.xml', $document);
	}

	private function preserveWhitespace(SimpleXMLElement $xml){
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		return $dom->saveXML();
	}
}