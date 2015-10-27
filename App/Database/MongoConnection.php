<?php namespace Uninett\Database;

use Exception;
use MongoClient;

use MongoCollection;
use MongoConnectionException;
use Uninett\Config;

class MongoConnection {
	private $database;

	public $collection;

	private $debug;

	public function __construct($collection) {
		$this->debug = Config::get('settings')['debug'];

		try {
			$authString = sprintf('mongodb://%s:%s@%s/%s',
				getenv('MONGO_USERNAME'),
				getenv('MONGO_PASSWORD'),
				getenv('MONGO_HOST'),
				getenv('MONGO_DATABASE'));

			$mongoClient    = new MongoClient($authString);
			$this->database = $mongoClient->selectDB(getenv('MONGO_DATABASE'));
		} catch(MongoConnectionException $e) {
			die('Error connecting to MongoDB server: ' . $e->getMessage() . PHP_EOL);
		}
		$this->collection = new MongoCollection($this->database, $collection);

		return $this->collection;
	}

	public function update($criteria, $operation, $field, $value, $upsert) {
		if(!$this->debug) {
			try {
				return $this->collection->update
				(
					$criteria,
					array($operation => array($field => $value)),
					array("upsert" => $upsert)
				);
			} catch(Exception $e) {
				echo $e->getMessage();

				return 0;
			}
		} else {
			return 1;
		}
	}

	public function updateIncrease($criteria, $operation) {
		if(!$this->debug) {
			try {
				return $this->collection->update
				(
					$criteria,
					$operation
				);
			} catch(Exception $e) {
				echo $e->getMessage();

				return 0;
			}
		} else {
			return 1;
		}
	}

	public function save($document) {
		if(!$this->debug) {
			return $this->collection->save($document);
		} else {
			return 1;
		}
	}

	public function insert($document) {
		if(!$this->debug) {
			return $this->collection->insert($document);
		} else {
			return 1;
		}
	}

	public function createLastUpdates($document) {
		return $this->collection->insert($document);
	}

	public function find($criteria = NULL) {
		if($criteria == NULL) {
			return $this->collection->find();
		} else {
			return $this->collection->find($criteria);
		}
	}

	public function findDocument($criteria) {
		return $this->collection->find($criteria);
	}

	public function findOne($criteria) {
		return $this->collection->findOne($criteria);
	}

	public function findLimitOne($criteria) {
		return $this->collection->find($criteria)->limit(1);
	}

	public function findLimitOneCount($criteria) {
		return $this->collection->find($criteria)->limit(1)->count();
	}

	public function databaseCommand($array) {
		return $this->database->command($array);
	}

	public function distinct($field) {
		return $this->collection->distinct($field);
	}
}
