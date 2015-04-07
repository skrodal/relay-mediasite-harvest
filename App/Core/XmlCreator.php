<?php namespace Uninett\Core; 
use Carbon\Carbon;
use DOMDocument;
use SimpleXMLElement;
use Uninett\Models\Ecampussql\TblFile;

class XmlCreator {
	private $resolution = [
		[
			'x' => 1366,
			'y' => 768,
		],
		[
			'x' => 1650,
			'y' => 1050,
		],
		[
			'x' => 1280,
			'y' => 720,
		],
		[
			'x' => 2560,
			'y' => 1440,
		],
		[
			'x' => 1920,
			'y' => 1200,
		],
		[
			'x' => 480,
			'y' => 360,
		],
	];
	public function createFile() {
		$faker = \Faker\Factory::create();

		$destinationUrl = 'http://example.com/relay/ansatt/';
		$username = substr(md5(rand()), 0, 7);
		$email = $username . '@example.com';
		$presentationTitle = md5(rand());
		$totalDuration  = $faker->numberBetween(1,25000);
		$resolution = array_rand($this->resolution);
		$resolution = $resolution[0] . 'x' . $resolution[1];

		$presentation = new SimpleXMLElement('<presentation/>');
		$presentation->addAttribute('relayVersion', '4.4.1');

		$presentation->addChild('title', $presentationTitle);
		$presentation->addChild('description',  $faker->text());
		$presentation->addChild('date', Carbon::now());
		$presentation->addChild('utcDate', Carbon::createFromTimestampUTC(Carbon::now()->timestamp));
		$presentation->addChild('profile', 'PC | Nettbrett | Mobil | Lyd');
		$presentation->addChild('destinationUrl', $destinationUrl . '/' . $presentationTitle);
		$presentation->addChild('totalDuration', $totalDuration);
		$presentation->addChild('trimmedDuration', $faker->numberBetween(1,5000));
		$presentation->addChild('startTrimTime', $faker->numberBetween(1,1000));

		$recordedBy = $presentation->addChild('recordedBy');
		$recordedBy->addChild('displayName', $username);
		$recordedBy->addChild('email', $email);

		$clientInfo = $presentation->addChild('clientInfo');
		$clientInfo->addChild('clientIP', $faker->ipv4);
		$clientInfo->addChild('clientComputerName', $faker->word);


		$serverInfo = $presentation->addChild('serverInfo', '');
		$serverInfo->addChild('serverHostname', 'http://doesnotmatter.example.com');
		$serverInfo->addChild('encodingPreset', 'Adjust this to fit Mp3 pc mobile nettbrett');
		$serverInfo->addChild('timeToEncode', $faker->numberBetween(1,1000));
		$serverInfo->addChild('timeInQueue', $faker->numberBetween(0,1000));

		$sourceRecording = $presentation->addChild('sourceRecording');
		$sourceRecording->addChild('resolution', $resolution);

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