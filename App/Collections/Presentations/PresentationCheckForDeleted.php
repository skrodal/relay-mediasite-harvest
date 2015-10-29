<?php namespace Uninett\Collections\Presentations;

// Checks if a presentation exists on disk, if not, it changes deleted attribute in mongo db from 0 (not deleted) to 1 (deleted)
use Uninett\Collections\Collection;
use Uninett\Collections\UpdateInterface;
use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Config;
use Uninett\Database\MongoConnection;
use Uninett\Schemas\PresentationSchema;
use MongoCursorException;

class PresentationCheckForDeleted extends Collection implements UpdateInterface {
	private $mongo;

	public function __construct() {
		parent::__construct(PresentationSchema::COLLECTION_NAME);

		$this->mongo = new MongoConnection(PresentationSchema::COLLECTION_NAME);
	}

	public function update() {
		$this->LogInfo('PresentationCheckForDeleted: INIT');
		$criteria = array(PresentationSchema::DELETED => 0);

		try {


			$cursor = $this->mongo->find($criteria);

			foreach($cursor as $document) {
				$id = $document[PresentationSchema::PRESENTATION_ID];

				$subDocument = $this->getFirstSubdocumentOfPresentation($id);

				if($subDocument !== false) {

					$shortPath = $subDocument[PresentationSchema::FILES][PresentationSchema::PATH];

					$pathOnDisk = $this->convertToLocalPath($shortPath);

					$this->LogInfo('PresID #' . $id);

					if($this->onePresentationFileDoesNotExist($pathOnDisk)) {
						$this->LogInfo('PresID #' . $id . ' DOES NOT EXIST!!!!');

						//IF one out of four files are deleted, the whole presentation is marked as deleted
						$this->changeDeletedAttribute(array(
							PresentationSchema::PRESENTATION_ID => $id,
							PresentationSchema::DELETED         => 0
						), $pathOnDisk);
					}
				}
			}
		} catch(MongoCursorException $e) {
			$this->LogError("MongoCursorException! Message: " . $e->getMessage());
			$this->LogError("MongoCursorException! Code: " . $e->getCode());
		}
		//

		$cursor->reset();
		$this->LogInfo("Finished checking for deleted presentations");
	}

	private function onePresentationFileDoesNotExist($path) {
		return !file_exists($path);
	}

	private function getFirstSubdocumentOfPresentation($id) {
		$unwind = array('$unwind' => '$' . PresentationSchema::FILES);

		$match = array
		(
			'$match' => array
			(
				PresentationSchema::PRESENTATION_ID => $id
			)
		);

		$limit = array('$limit' => 1);

		$subDocument = $this->mongo->collection->aggregate($unwind, $match, $limit);

		if(isset($subDocument['result']['0'])) {
			return $subDocument['result']['0'];
		}

		return false;
	}

	private function convertToLocalPath($presentation) {
		return Config::get('settings')['relaymedia'] . DIRECTORY_SEPARATOR . $presentation;
	}

	private function changeDeletedAttribute($criteria, $path) {
		$operationSucceeded = $this->mongo->update($criteria, '$set', PresentationSchema::DELETED, 1, 0);

		if($operationSucceeded) {
			$this->LogInfo("Did not find {$path}. Marked as deleted");
		} else {
			$this->LogError("Could not mark {$path} as deleted");
		}
	}
}
