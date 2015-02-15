<?php namespace Uninett\Collections;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use ReflectionClass;

abstract class Collection
{
    protected $logger;
    protected $reflect;
    protected $collectionName;

    function __construct($collectionName)
    {
        $this->collectionName =
            !empty($collectionName) ? $collectionName : "Seems like you forgot to set the collectionName";

        $this->reflect = new ReflectionClass($this);

	    $this->logger = new Logger($this->reflect->getFileName());

	    $this->setLogHandler();
    }

    public function LogError($message)
    {
        $this->logger->error($this->collectionName. ": " . $message);
    }

    public function LogInfo($message)
    {
        $this->logger->info($this->collectionName . ": " .  $message);
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
}