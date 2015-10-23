<?php namespace Uninett\Collections\Presentations;
//This class creates a presentation object after reading its xmlfiles
use Uninett\Collections\Collection;


use Uninett\Helpers\Arithmetic;
use Uninett\Helpers\ConvertHelper;
use Uninett\Models\PresentationFilesModel;
use Uninett\Models\PresentationModel;
use Uninett\Schemas\PresentationSchema;

class PresentationCreate extends Collection
{
    private $math;

    function __construct()
    {
	    parent::__construct(PresentationSchema::COLLECTION_NAME);

        $this->math = new Arithmetic();
    }

    public function createPresentationFromArrayResult($presentationXmlFiles) {
        $haveFilledInGeneralInfo = false;

        $convert = new ConvertHelper();

        $newPresentation = new PresentationModel();

        foreach($presentationXmlFiles as $xmlFile) {

            $xml = simplexml_load_file($xmlFile['path']);

            if ($xml->getName() == "presentation") {

                 $newPresentation->setPresentationId($xmlFile['id']);

                if($haveFilledInGeneralInfo == false) {

                    $newPresentation->setTitle((string)$xml->title);

                    $newPresentation->setDescription((string)$xml->description);

                    $newPresentation->setCreated((string) $xml->utcDate);

                    $newPresentation->setDeleted((int) 0);

	                $newPresentation->setRecorderName((string) $xml->recordedBy->displayName);

                    $newPresentation->setUsername((string) $xml->presenter->userName);

                    $newPresentation->setTotalDuration($convert->millisecondsToSeconds($xml->totalDuration));

                    $newPresentation->setTrimmedDuration($convert->millisecondsToSeconds($xml->trimmedDuration));

                    $newPresentation->setHits((int) 0);

                    $dUrl = $convert->convertExternalToLocalPath((string) $xml->destinationUrl);

                    $newPresentation->setPath($this->destUrlToToRootPath($dUrl));

                    $org = explode('@', $xml->presenter->userName);

                    if(isset($org[1]))
                        $newPresentation->setOrg($org[1]);

                    $haveFilledInGeneralInfo = true;
                }

                $newFile = new PresentationFilesModel();


                $newFile->setEncodingPreset((string) $xml->serverInfo->encodingPreset);

                $newFile->setTimeToEncode($convert->millisecondsToSeconds($xml->serverInfo->timeToEncode));

                $newFile->setTimeInQueue($convert->millisecondsToSeconds($xml->serverInfo->timeInQueue));

                $newFile->setHits(0);

                $fileOutputSize = 0;

                $destinationPath = "";

                foreach($xml->outputFiles->fileList as $fileList)
                    foreach($fileList as $file) {
                        $fileOutputSize = $file['size'];
                        $destinationPath .= $file['destinationPath'];

                        $res = (string) $file['resolution'];

                        $resSplit = explode("x", $res);

	                    if(!isset($resSplit[0]) || !isset($resSplit[1]))
	                    {
		                    $presentation_id = $newPresentation->getpresentationId();
		                    $presentation_path = $newPresentation->getPath();

		                    $this->LogError("Did not find attribute resolution in presentation with id {$presentation_id} and path {$presentation_path}");
	                    }

                        $newFile->setX((int) $resSplit[0]);
                        $newFile->setY((int) $resSplit[1]);

                        $resArr = array
                        (
                            PresentationSchema::X => $newFile->getX(),
                            PresentationSchema::Y => $newFile->getY(),
                        );

                        $newFile->setResolution($resArr);

                        break;
                    }

                $size = $convert->bytesToMegabytes($fileOutputSize);

                $newFile->setSize($size);

                $newPresentation->setSize($this->math->add($newPresentation->getSize(), $size));

                /* At this point, destinationPath is to a a xml file for some mp3 or mp4 file
                 * To make it easy to check if a file exists and calculate hits later,
                 * is the destinationPath converted to a local path on the computer
                 * this script is running on and xml is swapped with mp3 or mp4.
                 */

                $convertedPath = $convert->convertExternalToLocalPath($destinationPath);

                $strippedPath = $convert->getFilePathWithoutMediaPath($convertedPath);

                $newFile->setPath($strippedPath);

                $newPresentation->addFileToFilesArray($newFile);
            }
            else {
                $this->LogError("Tried to create a presentation but one of the xml documents root element did not
                match, therefore presentation will not be created. Rootelement was: " . $xml->getName());
                return null;
            }
        }
        return $newPresentation;
    }

    private function destUrlToToRootPath($path)
    {
        //Input: /home/uninett/relaymedia/ansatt/simon@uninett.no/2013/07.12/135393/HK_Julebord_-_20131207_030839_11.mp4
        //Output: ansatt/simon@uninett.no/2013/07.12/135393/

        $baseUrl = "";
        $pa = explode(DIRECTORY_SEPARATOR, $path);

        for($i = 4; $i < sizeof($pa); $i++) {
            if($i == (sizeof($pa)-1))
                break;
            else
                $baseUrl .= $pa[$i].DIRECTORY_SEPARATOR;
        }

        return $baseUrl;
    }
}
