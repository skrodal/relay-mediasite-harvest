<?php namespace Uninett\Collections;
use Monolog\Logger;
use ReflectionClass;

abstract class Collection {
    protected $logger;
    protected $reflect;

    protected $collectionName;

    function __construct($collectionName)
    {
        $this->collectionName =
            !empty($collectionName) ? $collectionName : "Seems like you forgot to set the collectionName";

        $this->reflect = new ReflectionClass($this);

        $this->logger = new Logger($this->reflect->getFileName());
    }

    public function LogError($message)
    {
        $this->logger->error($this->collectionName . ' --------'. $message);
    }

    public function LogNotice($message)
    {
        $this->logger->notice($this->collectionName . ' --------'. $message);
    }
}