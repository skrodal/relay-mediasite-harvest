<?php namespace Uninett\Models;
//This is a model class for the $_files variable in one presentation. Used in addition with Presentation.php
use JsonSerializable;
use Uninett\Schemas\PresentationSchema;

class PresentationFilesModel implements JsonSerializable{
    private $_encodingPreset = "";
    private $_timeToEncode = 0;
    private $_timeInQueue = 0;

    private $_size;
    private $_path = "";


    private $_hits;

    private $_resolution = "";
    private $_x;
    private $_y;

    function __construct()
    {
        $this->_size = 0.0;
    }

    public function jsonSerialize()
    {
        return
        [
            PresentationSchema::PATH => $this->getPath(),
            PresentationSchema::SIZE => $this->getSize(),
            PresentationSchema::RESOLUTION => $this->getResolution(),
            PresentationSchema::ENCODING => $this->getEncodingPreset(),
            PresentationSchema::ENCODETIME => $this->getTimeToEncode(),
            PresentationSchema::QUEUETIME => $this->getTimeInQueue(),
            PresentationSchema::HITS => $this->getHits()
        ];
    }
    public function setHits($hits)
    {
        if(!is_int($hits))

            return false;

        $this->_hits = $hits;

        return true;
    }

    public function getHits()
    {
        return $this->_hits;
    }


    public function setPath($fileName)
    {
        if(!is_string($fileName))

            return false;

        $this->_path = $fileName;

        return true;
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function getEncodingPreset()
    {
        return $this->_encodingPreset;
    }

    public function setEncodingPreset($encodingPreset)
    {
        if(!is_string($encodingPreset))

            return false;

        if(filter_var($encodingPreset, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_encodingPreset = $encodingPreset;

        return true;
    }

    public function getTimeToEncode()
    {
        return $this->_timeToEncode;
    }

    public function setTimeToEncode($timeToEncode)
    {
        if(!is_int($timeToEncode))

            return false;

        $this->_timeToEncode = $timeToEncode;

        return true;
    }

    public function getTimeInQueue()
    {
        return $this->_timeInQueue;
    }

    public function setTimeInQueue($timeInQueue)
    {
        if(!is_int($timeInQueue))

            return false;

        $this->_timeInQueue = $timeInQueue;

        return true;
    }


    public function setSize($size)
    {
        if(!is_double($size))

            return false;

        $this->_size = $size;

        return true;
    }

    public function getSize()
    {
        return $this->_size;
    }

    public function getResolution()
    {
        return $this->_resolution;
    }

    public function setResolution($resolution)
    {
        if(!is_array($resolution))

            return false;

        $this->_resolution = $resolution;

        return true;
    }

    public function setX($x)
    {
        if(!is_int($x))

            return false;

        $this->_x = $x;

        return true;
    }

    public function getX()
    {
        return $this->_x;
    }

    public function setY($y)
    {
        if(!is_int($y))

            return false;

        $this->_y = $y;

        return true;
    }

    public function getY()
    {
        return $this->_y;
    }
} 