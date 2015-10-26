<?php namespace  Uninett\Helpers;

use Uninett\Config;

class ConvertHelper
{
	/*
	 * New as of 26.10.2015. See replaced function below with comments.
	 *
	 * SimonS
	 */
	public function convertExternalToLocalPath($path) {
		$array = null;
		$delimiterKastra = "\\";
		$delimiterScreencast = "/";
		// Path in XML file can either be '\\kastra.bibsys....' (OLD) or 'https://screen...' (NEW)
		$isScreencast = strpos($path, "https://screencast");
		// Length of path up to 'ansatt|student' varies depending on which $path (OLD|NEW) is passed to function
		// NEW destinationPath
		$pathLen = 3;
		if($isScreencast === false){
			$array = explode($delimiterKastra, $path);
			// OLD destinationPath
			$pathLen = 2;
		} else {
			$array = explode($delimiterScreencast, $path);
		}

		$index = 0;
		$cPath = "";

		foreach ($array as $pieces) {
			if($index > $pathLen)
				$cPath .= DIRECTORY_SEPARATOR . $pieces;
			$index++;
		}

		$file = '/var/www/mnt/relaymedia' . $cPath;

		return $file;
	}

	/*
	 * New as of 26.10.2015. See replaced function below with comments.
	 *
	 * SimonS
	 */
	public function getFilePathWithoutMediaPath($file)
	{
		$f = explode(DIRECTORY_SEPARATOR, $file);
		$fileWithoutMediaPath = "";
		// Length of path up to 'ansatt|student' varies pending on linux-path vs. URL
		// Local absolute path
		$pathLen = 5;
		// Full URL
		if(strpos($file, "https://screencast") !== false){
			$pathLen = 4;
		}

		for ($i = $pathLen; $i < count($f); $i++) {
			if($i == $pathLen)
				$fileWithoutMediaPath.=$f[$i];
			else
				$fileWithoutMediaPath.=DIRECTORY_SEPARATOR.$f[$i];
		}
		return $fileWithoutMediaPath;
	}

	/* REPLACED 26.10.2015.
	 *
	 * This function does not work on new setup. Nor does it seem to have functioned in a consistent was earlier.
	 * Folder depth is using a static number - which does not correspond when alternating old/new destinationURL

	public function convertExternalToLocalPath($path)
	{
		//Note: destinationPath from xml file:
		// \\kastra.bibsys.no\relay\ansatt\erlendbr@uio.no\2013\26.09\2626733\Kant_-_praktisk_filosofi_time_2_-_PC_(Flash)_-_20131017_03.03.06PM\media\video.mp4

		//Input https://screencast.uninett.no/relay/ansatt/erlendbr@uio.no/2013/26.09/2626733/Kant_-_praktisk_filosofi_time_2_-_Lyd_(MP3)_-_20131017_03.03.06PM.xml
		//Output /home/uninett/relaymedia/ansatt/erlendbr@uio.no/2013/26.09/2626733/Kant_-_praktisk_filosofi_time_2_-_Lyd_(MP3)_-_20131017_03.03.06PM.xml

		//Input \\kastra.bibsys.no\relay\ansatt\erlendbr@uio.no\2013\26.09\2626733\Kant_-_praktisk_filosofi_time_2_-_PC_(Flash)_-_20131017_03.03.06PM.xml
		//Output /home/uninett/relaymedia/erlendbr@uio.no/2013/26.09/2626733/Kant_-_praktisk_filosofi_time_2_-_PC_(Flash)_-_20131017_03.03.06PM.xml

		$array = null;

		$delimiterKastra = "\\";
		$delimiterScreencast = "/";

		//Look for screencast. Returns true if found
		$isScreencast = strpos($path, "https://screencast");

		if($isScreencast === false)
			$array = explode($delimiterKastra, $path);
		else
			$array = explode($delimiterScreencast, $path);

		$index = 0;
		$cPath = "";

		foreach ($array as $pieces) {
			if($index > 3)
				$cPath .= DIRECTORY_SEPARATOR . $pieces;
			$index++;
		}

		//Validate that the path actually exists
		//returns something like /home/uninett/[ansatt|student]/...
		$file = Config::get('settings')['relaymedia'] . $cPath;

		return $file;
	}*/

	/* REPLACED 26.10.2015.
	 *
	 * This function does not work on new setup (to work out server path, a static number is used to count folder depth.)
	public function getFilePathWithoutMediaPath($file)
	{
		//Input https://screencast.uninett.no/relay/ansatt/olew@hig.no/2013/12.04/256933/hamartest5_-_Mobil_-_20130412_01.13.32PM.mp4
		//Output ansatt/olew@hig.no/2013/12.04/256933/hamartest5_-_Mobil_-_20130412_01.13.32PM.mp4

		//Input: /home/uninett/relaymedia/ansatt/olew@hig.no/2013/12.04/256933/hamartest5_-_PC_(Flash)_-_20130412_01.13.32PM/media/video.mp4
		//Output ansatt/olew@hig.no/2013/12.04/256933/hamartest5_-_PC_(Flash)_-_20130412_01.13.32PM/media/video.mp4

		$f = explode(DIRECTORY_SEPARATOR, $file);

		$fileWithoutMediaPath = "";
		for ($i = 4; $i < count($f); $i++) {
			if($i == 4)
				$fileWithoutMediaPath.=$f[$i];
			else
				$fileWithoutMediaPath.=DIRECTORY_SEPARATOR.$f[$i];
		}
		return $fileWithoutMediaPath;
	}*/

	/**
	 * Convert milliseconds to seconds
	 *
	 * @param $ms
	 * @return int
	 */
	public function millisecondsToSeconds($ms)
	{
		//Round up
		return (int) ceil($ms / pow(10, 3));
	}

	/**
	 * @param $bytes
	 * @return double
	 */
	public function bytesToMegabytes($bytes)
	{
		return (double) round(($bytes / pow(1024, 2)), Config::get('arithmetic')['numberOfDecimals'], PHP_ROUND_HALF_UP);
	}



	/**
	 * $directory is built from PATH_TO_MEDIA plus [ansatt|student]
	 * @param $directory
	 * @return mixed
	 */
	public function getAffiliationFromPath($directory)
	{
		$affiliatonFromDirectory = explode(DIRECTORY_SEPARATOR, $directory);

		return $affiliatonFromDirectory[4];
	}

}