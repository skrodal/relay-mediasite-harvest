<?php namespace Uninett\Models;
//This is a model class for one presentation.
use JsonSerializable;
use MongoDate;
use Uninett\Schemas\PresentationSchema;

class PresentationModel implements JsonSerializable
{
    private $_presentationId;
    private $_created = "";
    private $_deleted = "";
    private $_userName = "";
    private $_totalDuration = 0;
    private $_trimmedDuration = 0;


    private $_files;
    private $_org;
    private $_hits;
    private $_size = 0.0;
    private $_title;
    private $_description;
    private $_path;

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


    function __construct()
    {
        $this->_files = array();
    }

    public function setOrg($org)
    {
        if(!is_string($org))
            return false;

        $this->_org = $org;

        return true;
    }

    public function getOrg()
    {
        return $this->_org;
    }

    public function setPath($p)
    {
        if(!is_string($p))
            return false;

        $this->_path = $p;

        return true;
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function setPresentationId($presentationId)
    {
        if(!is_int($presentationId))
            return false;

        $this->_presentationId = $presentationId;

        return true;
    }

    public function getPresentationId()
    {
        return $this->_presentationId;
    }

    public function setDescription($description)
    {
        if(!is_string(($description)))
            return false;

        $this->_description = $description;

        return true;
    }

    public function getDescription()
    {
        return $this->_description;
    }


    public function setTitle($title)
    {
        if(!is_string($title))
            return false;

        $this->_title = $title;

        return true;
    }


    public function getTitle()
    {
        return $this->_title;
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

    public function addFileToFilesArray($file)
    {
        if(!is_object($file))
            return false;

        array_push($this->_files, $file);

        return true;
    }

    public function getFiles()
    {
        return $this->_files;
    }

    public function setCreated($created)
    {
        if(!is_string($created))

            return false;

        if(filter_var($created, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_created = new MongoDate(strtotime($created));

        return true;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    public function setDeleted($deleted)
    {
        if(!is_int($deleted))

            return false;

        $this->_deleted = $deleted;

        return true;
    }

    public function getDeleted()
    {
        return $this->_deleted;
    }

    public function getUserName()
    {
        return $this->_userName;
    }

    public function setUserName($userName)
    {
        if(!filter_var($userName, FILTER_VALIDATE_EMAIL))

            return false;

        $this->_userName = $userName;

        return true;
    }

    public function getTotalDuration()
    {
        return $this->_totalDuration;
    }

    public function setTotalDuration($totalDuration)
    {
        if(!is_int($totalDuration))

            return false;

        $this->_totalDuration = $totalDuration;

        return true;
    }

    public function getTrimmedDuration()
    {
        return $this->_trimmedDuration;
    }

    public function setTrimmedDuration($trimmedDuration)
    {
        if(!is_int($trimmedDuration))

            return false;

        $this->_trimmedDuration = $trimmedDuration;

        return true;
    }

    public function serializeFiles() {
        $arr = array();
        foreach($this->_files as $file)
            $arr[] = $file->jsonSerialize();

        return $arr;
    }

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
