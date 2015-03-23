<?php namespace Uninett\Models;
//This is a model class for the $_files variable in one presentation. Used in addition with Presentation.php
use JsonSerializable;
use Uninett\Schemas\PresentationSchema;

/**
 * Class PresentationFilesModel
 * @package Uninett\Models
 */
class PresentationFilesModel implements JsonSerializable{

	/**
	 * @var string
	 */
	private $_encodingPreset = "";
	/**
	 * @var int
	 */
	private $_timeToEncode = 0;
	/**
	 * @var int
	 */
	private $_timeInQueue = 0;

	/**
	 * @var float
	 */
	private $_size;
	/**
	 * @var string
	 */
	private $_path = "";


	/**
	 * @var
	 */
	private $_hits;

	/**
	 * @var string
	 */
	private $_resolution = "";
	/**
	 * @var
	 */
	private $_x;
	/**
	 * @var
	 */
	private $_y;

	/**
	 *
	 */
	function __construct()
    {
        $this->_size = 0.0;
    }

	/**
	 * @return array
	 */
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

	/**
	 * @param $hits
	 * @return bool
	 */
	public function setHits($hits)
    {
        if(!is_int($hits))

            return false;

        $this->_hits = $hits;

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getHits()
    {
        return $this->_hits;
    }


	/**
	 * @param $fileName
	 * @return bool
	 */
	public function setPath($fileName)
    {
        if(!is_string($fileName))

            return false;

        $this->_path = $fileName;

        return true;
    }

	/**
	 * @return string
	 */
	public function getPath()
    {
        return $this->_path;
    }

	/**
	 * @return string
	 */
	public function getEncodingPreset()
    {
        return $this->_encodingPreset;
    }

	/**
	 * @param $encodingPreset
	 * @return bool
	 */
	public function setEncodingPreset($encodingPreset)
    {
        if(!is_string($encodingPreset))

            return false;

        if(filter_var($encodingPreset, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_encodingPreset = $encodingPreset;

        return true;
    }

	/**
	 * @return int
	 */
	public function getTimeToEncode()
    {
        return $this->_timeToEncode;
    }

	/**
	 * @param $timeToEncode
	 * @return bool
	 */
	public function setTimeToEncode($timeToEncode)
    {
        if(!is_int($timeToEncode))

            return false;

        $this->_timeToEncode = $timeToEncode;

        return true;
    }

	/**
	 * @return int
	 */
	public function getTimeInQueue()
    {
        return $this->_timeInQueue;
    }

	/**
	 * @param $timeInQueue
	 * @return bool
	 */
	public function setTimeInQueue($timeInQueue)
    {
        if(!is_int($timeInQueue))

            return false;

        $this->_timeInQueue = $timeInQueue;

        return true;
    }


	/**
	 * @param $size
	 * @return bool
	 */
	public function setSize($size)
    {
        if(!is_double($size))

            return false;

        $this->_size = $size;

        return true;
    }

	/**
	 * @return float
	 */
	public function getSize()
    {
        return $this->_size;
    }

	/**
	 * @return string
	 */
	public function getResolution()
    {
        return $this->_resolution;
    }

	/**
	 * @param $resolution
	 * @return bool
	 */
	public function setResolution($resolution)
    {
        if(!is_array($resolution))

            return false;

        $this->_resolution = $resolution;

        return true;
    }

	/**
	 * @param $x
	 * @return bool
	 */
	public function setX($x)
    {
        if(!is_int($x))

            return false;

        $this->_x = $x;

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getX()
    {
        return $this->_x;
    }

	/**
	 * @param $y
	 * @return bool
	 */
	public function setY($y)
    {
        if(!is_int($y))

            return false;

        $this->_y = $y;

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getY()
    {
        return $this->_y;
    }
} 