<?php namespace Uninett\Collections\Org;

//Finds and inserts orgs. Prerequisites: Users collection is up to date
use Uninett\Collections\Collection;
use Uninett\Collections\UpdateInterface;
use Uninett\Database\MongoConnection;
use Uninett\Models\OrgModel;
use Uninett\Schemas\OrgSchema;
use Uninett\Schemas\UsersSchema;

class OrgImport extends Collection implements UpdateInterface {
	private $_usersCollection;
	private $_orgCollection;

	private $numberFound;
	private $numberInserted;

	public function __construct() {
		parent::__construct(OrgSchema::COLLECTION_NAME);

		$this->_usersCollection = new MongoConnection(UsersSchema::COLLECTION_NAME);

		$this->_orgCollection = new MongoConnection(OrgSchema::COLLECTION_NAME);
	}

	public function update() {
		$this->LogInfo("Start");

		$distinctOrgs = $this->_usersCollection->distinct(OrgSchema::ORG);

		$this->lookForNewOrganisations($distinctOrgs);

		$this->LogInfo("Finished importing organisations");

	}

	private function lookForNewOrganisations($document) {
		foreach($document as $orgName) {
			$criteria = array(OrgSchema::ORG => $orgName);

			$orgFromDb = $this->_orgCollection->findLimitOneCount($criteria);

			if($this->organisationDoesNotExist($orgFromDb)) {

				$this->numberFound = $this->numberFound + 1;
				$this->LogInfo("Found new org: " . $orgName);

				$newOrg            = $this->create($orgName);
				$objectWasInserted = $this->insert($newOrg);

				if($objectWasInserted) {
					$this->LogInfo("Inserted new org: " . $orgName);
				}
				$this->numberInserted = $this->numberInserted + 1;
			}
		}
	}

	private function organisationDoesNotExist($orgName) {
		return empty($orgName);
	}


	private function create($orgName) {
		$newInstitution = new OrgModel;

		$newInstitution->setOrg($orgName);

		return $newInstitution;
	}

	private function insert(OrgModel $object) {
		return $this->_orgCollection->save($object->jsonSerialize());
	}
}
