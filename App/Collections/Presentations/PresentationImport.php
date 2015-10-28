<?php namespace Uninett\Collections\Presentations;

//Queries Perseus DB for new files based on largest fileId in MongoDB and inserts to MongoDB
use Uninett\Collections\Collection;
use Uninett\Collections\UpdateInterface;
use Uninett\Collections\DailyVideos\DailyVideoImport;
use Uninett\Database\RelaySQLConnection;
use Uninett\Helpers\ConvertHelper;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Schemas\PresentationSchema;

class PresentationImport extends Collection implements UpdateInterface {
	private $insert;
	private $find;

	private $currentPresentationId;
	private $shouldUpdateDailyVideosCollection;

	private $numberInserted = 0;
	private $numberFound = 0;

	public function __construct($shouldUpdateDailyVideosCollection = false) {
		parent::__construct(PresentationSchema::COLLECTION_NAME);

		$this->currentPresentationId = $this->getLargestInsertedFileId();

		$this->insert = new PresentationInsert();

		$this->shouldUpdateDailyVideosCollection = $shouldUpdateDailyVideosCollection;

	}

	private function getLargestInsertedFileId() {
		$largestInsertedFileId = new LastUpdates();

		return $largestInsertedFileId->findLargestPresentationId();
	}

	public function update() {
		$this->LogInfo("Commencing presentation import");

		$this->findAndInsertNewVideos();

		$this->LogInfo("Finished importing {$this->numberInserted} presentations");

		if($this->numberInserted > 0) {
			$this->updateLargestPresentationIdInMongoDb();
		}

		if($this->shouldUpdateDailyVideosCollection) {
			$this->updateDailyVideosCollection();
		}
	}

	private function findAndInsertNewVideos() {
		//
		$this->find = new PresentationFind(new RelaySQLConnection);
		//
		$convertedPath = new ConvertHelper();
		//
		$objectCreator = new PresentationCreate();
		//
		$presentationsNotFound = 0;
		// Last presentation entry in Relay SQL DB
		$largestPresentationIdFromSource = $this->findLargestPresentationIdFromSource();
		// If Relay SQL returned no data
		if($largestPresentationIdFromSource === false) {
			$this->LogError("Could not retrieve largest presentationId from database");
			return;
		}
		// Inexact number, but will do ok as an indication of how many presentations will be checked.
		$this->LogInfo("Checking " . ($largestPresentationIdFromSource - $this->currentPresentationId) . " new presentations");

		// Scan Relay SQL for each and every new presentation since last run
		while($largestPresentationIdFromSource != $this->currentPresentationId) {
			// All XML file entries for a single presentation (filePresentation_presId, fileId, filePath, createdOn)
			// Typically returns 4 rows; one per XML-file
			$query = $this->find->findXMLsForPresentationWithId($this->currentPresentationId);
			// Will hold the paths to XML metadata files pertaining to a SINGLE presentation (typically 4 paths, one per encoding format)
			$arrayWithPathToXMLFilesForPresentation = array();
			// If we got a hit on our queried presentation ID from Relay SQL
			if($this->presentationIdContainsPresentation($query)) {
				// Counter
				$this->numberFound = $this->numberFound + 1;
				// Unused?
				$presentationIdFromResult = NULL;
				// Loop all XML files (typically 4) for a single presentation
				while($presentation = mssql_fetch_assoc($query)) {
					// Convert FROM filePath in XML (can be kastra, screencast, samba, whatever) TO exact path to XML file
					// on current system (e.g. /.../.../.../relaymedia/ansatt/simonuninett.no/2015/14.09/89400/filename.xml
					$this->LogInfo('Coverting path: ' . $presentation['filePath']);
					$path = $convertedPath->convertExternalToLocalPath($presentation['filePath']);
					// If an XML file is not found, break out and assume presentation is deleted
					if($this->presentationDoesNotExistOnDisk($path)) {
						$this->LogError("Presentation path " . $path . ' not found on disk.');
						break;
					} else {
						// Add path to this XML-file to array)
						$arr = array('path' => $path, 'id' => $presentation['filePresentation_presId']);
						array_push($arrayWithPathToXMLFilesForPresentation, $arr);
					}
				}
				// If we have the path to one or more XML files for this presentation,
				// pass them on to the presentation creator for processing
				if(count($arrayWithPathToXMLFilesForPresentation) > 0) {
					// PresentationCreate will use XML metadata to create a presentation JSON object that can be
					// entered into the collection
					$newPresentation = $objectCreator->createPresentationFromArrayResult($arrayWithPathToXMLFilesForPresentation);
					// As long as the XML files we sent to the creator are OK, we won't have any problems here.
					if(!is_null($newPresentation)) {
						$this->insertPresentationToMongoDb($newPresentation);
					}
				}
			} else {
				$presentationsNotFound = $presentationsNotFound + 1;
			}
			// Go to next presentationID and start new loop.
			$this->currentPresentationId = $this->currentPresentationId + 1;
		}
	}

	private function findLargestPresentationIdFromSource() {
		$find = new PresentationFind(new RelaySQLConnection);
		$max  = $find->findHighestPresentationsId();

		$maxRes = mssql_fetch_assoc($max);

		if($maxRes == false) {
			return false;
		}

		return (int)$maxRes['computed'];
	}

	private function presentationIdContainsPresentation($query) {
		if($query == false) {
			return false;
		}

		return true;
	}

	private function presentationDoesNotExistOnDisk($path) {
		return !file_exists($path) || !is_file($path);
	}

	private function insertPresentationToMongoDb($newFile) {
		$inserted = $this->insert->insertNewVideoToMongoDb($newFile);

		if($inserted) {
			$this->numberInserted = $this->numberInserted + 1;
		}
	}

	private function updateLargestPresentationIdInMongoDb() {
		$largestIdInMongoDb = new LastUpdates();
		$largestIdInMongoDb->updatePresentationId($this->currentPresentationId);
	}

	private function updateDailyVideosCollection() {
		$dv = new DailyVideoImport();
		$dv->insert($this->numberInserted);
	}
}
