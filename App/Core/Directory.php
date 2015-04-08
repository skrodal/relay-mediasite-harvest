<?php namespace Uninett\Core; 
class Directory {
	/**
	 * Remove the filename from a directory path
	 * @param $path
	 * @return string
	 */
	public function getDirectoryPath($path, $includeLastSlash = true)
	{
		return $includeLastSlash ? dirname($path) . '/' : dirname($path);
	}

	/**
	 * ********** NOT COVERED BY TESTS **********
	 * Create a directory if it not exists
	 * @param $fullFilePath
	 * @return mixed
	 */
	public function setupDirectory($fullFilePath, $includeLastSlash = true)
	{
		$path = $this->getDirectoryPath($fullFilePath, $includeLastSlash);

		if(!is_dir($path))
			mkdir($path, 0777, true);

		return $path;
	}


}