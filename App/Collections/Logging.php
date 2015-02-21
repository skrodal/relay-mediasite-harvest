<?php namespace Uninett\Collections;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use ReflectionClass;

abstract class Logging
{
    protected $logger;
    protected $reflect;
    protected $collectionName;

    function __construct($dailyVideosCollectionName)
    {
        $this->collectionName =
            !empty($dailyVideosCollectionName) ? $dailyVideosCollectionName : "Seems like you forgot to set the collectionName";

        $this->reflect = new ReflectionClass($this);

	    $this->logger = new Logger($this->reflect->getFileName());

	    $this->setLogHandler();
    }

	private function setLogHandler()
	{
		$dateFormat = 'Y-m-d H:i:s';
		$output = "[%datetime%] (%level_name%):  %message%\n";

		$formatter = new LineFormatter($output, $dateFormat);

		$stream = new StreamHandler('php://stdout');

		$stream->setFormatter($formatter);

		$this->logger->pushHandler($stream);
	}

    public function LogError($message)
    {
        $this->logger->error($this->logMessageFormat($message));
    }

    public function LogInfo($message)
    {
        $this->logger->info($this->logMessageFormat($message));
    }

	private function logMessageFormat($message)
	{
		return basename($this->logger->getName()) . " | " . $this->collectionName. ": " . $message;
	}
}