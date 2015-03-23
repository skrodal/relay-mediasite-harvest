<?php namespace Uninett\Models;

use JsonSerializable;
use MongoDate;
use Uninett\Schemas\PresentationSchema;

/**
 * Class PresentationModel
 * //This is a model class for one presentation.
 * @package Uninett\Models
 */
class PresentationModel implements JsonSerializable
{

	/**
	 * @var
	 */
	private $_presentationId;
	/**
	 * @var string
	 */
	private $_created = "";
	/**
	 * @var string
	 */
	private $_deleted = "";
	/**
	 * @var string
	 */
	private $_userName = "";
	/**
	 * @var int
	 */
	private $_totalDuration = 0;
	/**
	 * @var int
	 */
	private $_trimmedDuration = 0;


	/**
	 * @var array
	 */
	private $_files;
	/**
	 * @var
	 */
	private $_org;
	/**
	 * @var
	 */
	private $_hits;
	/**
	 * @var float
	 */
	private $_size = 0.0;
	/**
	 * @var
	 */
	private $_title;
	/**
	 * @var
	 */
	private $_description;
	/**
	 * @var
	 */
	private $_path;

	/**
	 * @var
	 */
	private $recorderName;

	/**
	 * @return mixed
	 */
	public function getRecorderName()
	{
		return $this->recorderName;
	}

	/**
	 * @param mixed $recorderName
	 */
	public function setRecorderName($recorderName)
	{
		if(!is_string($recorderName))
			return false;

		$this->recorderName = $recorderName;

		 return true;
	}


	/**
	 *
	 */
	function __construct()
    {
        $this->_files = array();
    }

	/**
	 * @param $org
	 * @return bool
	 */
	public function setOrg($org)
    {
        if(!is_string($org))
            return false;

        $this->_org = $org;

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getOrg()
    {
        return $this->_org;
    }

	/**
	 * @param $p
	 * @return bool
	 */
	public function setPath($p)
    {
        if(!is_string($p))
            return false;

        $this->_path = $p;

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getPath()
    {
        return $this->_path;
    }

	/**
	 * @param $presentationId
	 * @return bool
	 */
	public function setPresentationId($presentationId)
    {
        if(!is_int($presentationId))
            return false;

        $this->_presentationId = $presentationId;

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getPresentationId()
    {
        return $this->_presentationId;
    }

	/**
	 * @param $description
	 * @return bool
	 */
	public function setDescription($description)
    {
        $this->_description = $description;

        return true;
    }

	/**
	 * @return mixed
	 */
	public function getDescription()
    {
        return $this->_description;
    }


	/**
	 * @param $title
	 * @return bool
	 */
	public function setTitle($title)
    {
        $this->_title = $title;

        return true;
    }


	/**
	 * @return mixed
	 */
	public function getTitle()
    {
        return $this->_title;
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
	 * @param $file
	 * @return bool
	 */
	public function addFileToFilesArray($file)
    {
        if(!is_object($file))
            return false;

        array_push($this->_files, $file);

        return true;
    }

	/**
	 * @return array
	 */
	public function getFiles()
    {
        return $this->_files;
    }

	/**
	 * @param $created
	 * @return bool
	 */
	public function setCreated($created)
    {
        if(!is_string($created))

            return false;

        if(filter_var($created, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_created = new MongoDate(strtotime($created));

        return true;
    }

	/**
	 * @return string
	 */
	public function getCreated()
    {
        return $this->_created;
    }

	/**
	 * @param $deleted
	 * @return bool
	 */
	public function setDeleted($deleted)
    {
        if(!is_int($deleted))

            return false;

        $this->_deleted = $deleted;

        return true;
    }

	/**
	 * @return string
	 */
	public function getDeleted()
    {
        return $this->_deleted;
    }

	/**
	 * @return string
	 */
	public function getUserName()
    {
        return $this->_userName;
    }

	/**
	 * @param $userName
	 * @return bool
	 */
	public function setUserName($userName)
    {
        if(!filter_var($userName, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_userName = $userName;

        return true;
    }

	/**
	 * @return int
	 */
	public function getTotalDuration()
    {
        return $this->_totalDuration;
    }

	/**
	 * @param $totalDuration
	 * @return bool
	 */
	public function setTotalDuration($totalDuration)
    {
        if(!is_int($totalDuration))

            return false;

        $this->_totalDuration = $totalDuration;

        return true;
    }

	/**
	 * @return int
	 */
	public function getTrimmedDuration()
    {
        return $this->_trimmedDuration;
    }

	/**
	 * @param $trimmedDuration
	 * @return bool
	 */
	public function setTrimmedDuration($trimmedDuration)
    {
        if(!is_int($trimmedDuration))

            return false;

        $this->_trimmedDuration = $trimmedDuration;

        return true;
    }

	/**
	 * @return array
	 */
	public function serializeFiles() {
        $arr = array();
        foreach($this->_files as $file)
            $arr[] = $file->jsonSerialize();

        return $arr;
    }

	/**
	 * @return array
	 */
	public function jsonSerialize()
    {
        return [
            PresentationSchema::PRESENTATION_ID => $this->getPresentationId(),
            PresentationSchema::TITLE => $this->getTitle(),
            PresentationSchema::DESCRIPTION => $this->getDescription(),
	        PresentationSchema::RECORDER_NAME => $this->getRecorderName(),
            PresentationSchema::PATH => $this->getPath(),
            PresentationSchema::USERNAME => $this->getUserName(),
            PresentationSchema::ORG => $this->getOrg(),
            PresentationSchema::CREATED => $this->getCreated(),
            PresentationSchema::DELETED => $this->getDeleted(),
            PresentationSchema::DURATION => $this->getTotalDuration(),
            PresentationSchema::TRIMMED => $this->getTrimmedDuration(),
            PresentationSchema::HITS => $this->getHits(),
            PresentationSchema::SIZE => $this->getSize(),
            PresentationSchema::FILES => $this->serializeFiles()
        ];
    }
    //
}
