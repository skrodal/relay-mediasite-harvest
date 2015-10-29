<?php namespace Uninett\Helpers;

use Uninett\Config;

class ConvertHelper {
	/*
	 * New as of 28.10.2015. See replaced function below with comments.
	 *
	 * SimonS
	 */
	public function convertExternalToLocalPath($path) {
		// In case it's the old kastra-style path with backslashes, replace these first
		$path = str_replace("\\", "/", $path);

		// Get substring starting with [student | ansatt] onwards (NOTE: requires that student or ansatt is in path!)
		// From: https://screencast.uninett.no/relay/ansatt/simonuninett.no/2015/14.09/89400/TechSmith_Relay_innfring_p_130_-_20150914_085355_36.html
		// To:                                       ansatt/simonuninett.no/2015/14.09/89400/TechSmith_Relay_innfring_p_130_-_20150914_085355_36.html
		$cPath = strstr($path, 'student') ? strstr($path, 'student') : strstr($path, 'ansatt');
		// From:                                     ansatt/simonuninett.no/2015/14.09/89400/TechSmith_Relay_innfring_p_130_-_20150914_085355_36.html
		// To:               /var/www/mnt/relaymedia/ansatt/simonuninett.no/2015/14.09/89400/TechSmith_Relay_innfring_p_130_-_20150914_085355_36.html
		return Config::get('settings')['relaymedia'] . DIRECTORY_SEPARATOR . $cPath;
	}
	/* REPLACED 28.10.2015.
	 *
	 * This function does not work on new setup. Nor does it seem to have functioned in a consistent was earlier.
	 * Folder depth is using a static number - which does not correspond when alternating old/new destinationURL
	 *
	public function convertExternalToLocalPath($path)
	{
		// Sample destinationPaths from xml file (old and new versions):
		// \\kastra.bibsys.no\relay\ansatt\erlendbr@uio.no\2013\26.09\2626733\Kant_-_praktisk_filosofi_time_2_-_PC_(Flash)_-_20131017_03.03.06PM\media\video.mp4
		// https://screencast.uninett.no/relay/ansatt/simonuninett.no/2015/12.10/7800/Simons_test_Profile_Test_-_20151012_084917_39.html

		// Expected:
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

	/*
	 * New as of 28.10.2015. See replaced function below with comments.
	 *
	 * SimonS
	 */
	public function getFilePathWithoutMediaPath($file) {
		$file = str_replace("\\", "/", $file);

		return strstr($file, 'student') ? strstr($file, 'student') : strstr($file, 'ansatt');
	}

	/* REPLACED 28.10.2015.
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
	 *
	 * @return int
	 */
	public function millisecondsToSeconds($ms) {
		//Round up
		return (int)ceil($ms / pow(10, 3));
	}

	/**
	 * @param $bytes
	 *
	 * @return double
	 */
	public function bytesToMegabytes($bytes) {
		return (double)round(($bytes / pow(1024, 2)), Config::get('arithmetic')['numberOfDecimals'], PHP_ROUND_HALF_UP);
	}

	/**
	 * Simon @ 29.10.2015: Rewritten old function that broke when moving the service.
	 *
	 * We only have two affiliations: student | ansatt. Check for these words in the path instead...
	 *
	 * @param $directory
	 *
	 * @return mixed
	 */
	public function getAffiliationFromPath($directory) {
		// case insensitive
		if(stripos($directory, 'ansatt') !== false) {
			return 'ansatt';
		}
		if(stripos($directory, 'student') !== false) {
			return 'student';
		}

		return 'n/a';
	}
	/**
	 * $directory is built from PATH_TO_MEDIA plus [ansatt|student]
	 *
	 * @param $directory
	 *
	 * @return mixed
	 */
	/*
	!! Simon @ 29.10.2015: This is not future-proof and, as most other functions reading paths,
	!! it depends on a static folder-depth to find data. Replaced with new function, see above.
	public function getAffiliationFromPath($directory)
	{
		$affiliatonFromDirectory = explode(DIRECTORY_SEPARATOR, $directory);

		return $affiliatonFromDirectory[4];
	}*/

}