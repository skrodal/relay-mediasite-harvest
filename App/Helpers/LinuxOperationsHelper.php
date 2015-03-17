<?php namespace Uninett\Helpers; 
class LinuxOperationsHelper
{

	public function getSpaceUsedByOrgInBytes($directory, $org)
	{
		$cmd = 'du --block-size=1 -s '. $directory.DIRECTORY_SEPARATOR.'* | grep ' . $org . " | awk '{s+=$1} END {print s}'";

		$output = shell_exec($cmd);

		return (int) $output;
	}

	public function getSpaceUsedByUserInBytes($directory, $user)
	{
		$output = shell_exec("du --block-size=1 -s " . $directory.DIRECTORY_SEPARATOR.$user . " | awk {'print $1'}");

		return (int) $output;
	}

	public function getSpaceUsedInDirectory($directory)
	{
		$output = shell_exec("du --block-size=1 -s " . $directory . " | awk {'print $1'}");

		return (int) $output;
	}

	public function getSpaceUsedByMediasiteOrg($directory, $org)
	{
		//$output = shell_exec('du --block-size=1 -s '. $directory.$org . " | awk '{s+=$1} END {print s}' ");

		//2>/dev/null sends errors to /dev/null
		$output = shell_exec('du --block-size=1 -s '. $directory.$org . "2>/dev/null | awk '{s+=$1} END {print s}' ");

		return (int) $output;
	}

	public function getFolderNamesFromDirectory($directory)
	{
		$users = array();

		exec('ls -l ' . $directory . " | egrep ^d\|^l | awk '{print $9}'", $users);

		return $users;
	}

	public function getDistinctOrgsFromDisk()
	{
		$arr = array();

		foreach (Config::Instance()['folders_to_scan_for_files'] as $directory) {
			exec('ls -l '. $directory .  '| egrep ^d |  cut -d "@" -f2 | sort | uniq', $y);

			foreach($y as $value)
				if( !in_array($value,$arr)) $arr[] = $value;

			return $arr;
		}
	}
}