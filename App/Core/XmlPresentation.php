<?php namespace Uninett\Core; 
use Carbon\Carbon;
use DOMDocument;
use SimpleXMLElement;
use Uninett\Models\Ecampussql\TblFile;

class XmlPresentation {
	private $presentation = array(
		'mp3' => array(
			'encodingPreset' => 'MP3 (Audio Only)',
			'resolution' => '-1x-1'
		),
		'mobile' => array(
			'encodingPreset' => 'MP4 with Smart Player (480p)',
			'resolution' => '1152x720'
		),
		'nettbrett' => array(
			'encodingPreset' => 'MP4 with Smart Player (720p)',
			'resolution' => '1152x720'
		),
		'pc' => array(
			'encodingPreset' => 'MP4 with Smart Player (1080p)',
			'resolution' => '1920x1080'
		)
	);

	private $faker;

	/**
	 * @var Directory
	 */
	private $directory;

	function __construct()
	{
		$this->faker = \Faker\Factory::create();

		$this->directory = new \Uninett\Core\Directory;
	}

	public function create($file, $times = 1)
	{
		while ($times--)
			$this->createPresentation($file);
	}

	private function generateUsername($length = 7)
	{
		return substr(md5(rand()), 0, $length);
	}


	private function generateOrganisation() {
		$organisations = array(
			'example.com',
			'example.org',
			'example.net'
		);
		return array_rand($organisations);
	}

	private function generatePresentationTitle($length = 10){
		return substr(md5(rand()), 0, $length);
	}

	/**
	 * $files contains the same data as found in the database, but mainly the path and createdOn is used here
	 *
	 * @param $files
	 * @return bool
	 */
	private function createPresentation($files) {

		$destinationUrl = 'http://example.com/relay/ansatt/';

		$username = $this->generateUsername();
		$email = $username . $this->generateOrganisation();
		$presentationTitle = $this->generatePresentationTitle();

		$totalDuration  = $this->faker->numberBetween(1,25000);

		$clientIp = $this->faker->ipv4;
		$clientComputerName = $this->faker->word;

		//Create xmlfile for mp3, pc, mobile and tablet file
		foreach($this->presentation as $type => $fields) {

			$file = array_shift($files);

			$date = $file['createdOn'];

			$utcDate = $file['createdOn'];

			$fileName = basename($file['filePath']);

			$presentation = new SimpleXMLElement('<presentation/>');
			$presentation->addAttribute('relayVersion', '4.4.1');

			$presentation->addChild('title', $presentationTitle);
			$presentation->addChild('description',  $this->faker->text());
			$presentation->addChild('date', $date);
			$presentation->addChild('utcDate', $utcDate);
			$presentation->addChild('profile', 'PC | Nettbrett | Mobil | Lyd');
			$presentation->addChild('destinationUrl', $destinationUrl . '/' . $presentationTitle);
			$presentation->addChild('totalDuration', $totalDuration);
			$presentation->addChild('trimmedDuration', $this->faker->numberBetween(1,5000));
			$presentation->addChild('startTrimTime', $this->faker->numberBetween(1,1000));

			$recordedBy = $presentation->addChild('recordedBy');
			$recordedBy->addChild('displayName', $username);
			$recordedBy->addChild('email', $email);

			$clientInfo = $presentation->addChild('clientInfo');
			$clientInfo->addChild('clientIP', $clientIp);
			$clientInfo->addChild('clientComputerName', $clientComputerName);

			$serverInfo = $presentation->addChild('serverInfo', '');
			$serverInfo->addChild('serverHostname', 'http://doesnotmatter.example.com');
			$serverInfo->addChild('encodingPreset', $fields['encodingPreset']);
			$serverInfo->addChild('timeToEncode', $this->faker->numberBetween(1,1000));
			$serverInfo->addChild('timeInQueue', $this->faker->numberBetween(0,1000));

			$sourceRecording = $presentation->addChild('sourceRecording');
			$sourceRecording->addChild('resolution', $fields['resolution']);

			$fileList = $sourceRecording->addChild('fileList');
			$file1 = $fileList->addChild('file');
			$file1->addAttribute('clientPath', $this->faker->word);
			$file1->addAttribute('serverPath', $this->faker->word);
			$file1->addAttribute('size', $this->faker->numberBetween(1, 100000));
			$file1->addAttribute('sequence', 0);
			$file1->addAttribute('resolution', '0x0');
			$file1->addAttribute('duration', 0);

			$file2 = $fileList->addChild('file');
			$file2->addAttribute('clientPath', $this->faker->word);
			$file2->addAttribute('serverPath', $this->faker->word);
			$file2->addAttribute('size', $this->faker->numberBetween(1, 100000));
			$file2->addAttribute('sequence', 0);
			$file2->addAttribute('resolution', '0x0');
			$file2->addAttribute('duration', 0);

			$file3 = $fileList->addChild('file');
			$file3->addAttribute('clientPath', $this->faker->word);
			$file3->addAttribute('serverPath', $this->faker->word);
			$file3->addAttribute('size', $this->faker->numberBetween(1, 100000));
			$file3->addAttribute('sequence', 0);
			$file3->addAttribute('resolution', '0x0');
			$file3->addAttribute('duration', 0);

			$encodeFiles = $presentation->addChild('encodeFiles');
			$encodeFilesFilelist = $encodeFiles->addChild('fileList');

			$encodeFile = $encodeFilesFilelist->addChild('file');
			$encodeFile->addAttribute('clientPath', $this->faker->word);
			$encodeFile->addAttribute('serverPath', $this->faker->word);
			$encodeFile->addAttribute('size', $this->faker->numberBetween(1, 100000));
			$encodeFile->addAttribute('sequence', 0);
			$encodeFile->addAttribute('resolution', '0x0');
			$encodeFile->addAttribute('duration', 0);

			$outputFiles = $presentation->addChild('outputFiles');
			$outputFileList = $outputFiles->addChild('fileList');
			$outputFile = $outputFileList->addChild('file');
			$outputFile->addAttribute('clientPath', $this->faker->word);
			$outputFile->addAttribute('serverPath', $this->faker->word);
			$outputFile->addAttribute('size', $this->faker->numberBetween(1, 100000));
			$outputFile->addAttribute('sequence', 0);
			$outputFile->addAttribute('resolution', '0x0');
			$outputFile->addAttribute('duration', 0);

			$xmlDocument = $this->preserveWhitespace($presentation);

			$savePath = $this->directory->setupDirectory($file['filePath']);

			$this->save($savePath. $fileName, $xmlDocument);
		}
		return true;
	}

	private function save($fullPath, $xmlDocument)
	{
		file_put_contents($fullPath, $xmlDocument);
	}

	private function preserveWhitespace(SimpleXMLElement $xml) {
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		return $dom->saveXML();
	}
}