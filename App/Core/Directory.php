<?php namespace Uninett\Core; 
class Directory {

	public function getFullDirectorypath($path, $includeLastSlash = true){
		if($includeLastSlash)
			return $this->getDirectoryPath($path . '/');

		return $this->getDirectoryPath($path);
	}

	public function getDirectoryPath($path) {
		return rtrim($path, '/');

	}
	public function setupDirectory($path)
	{
		if(!is_dir($path))
			mkdir($path, 0777, true);

		return $path;
	}
}