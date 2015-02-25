<?php
use Uninett\Collections\Presentations\PresentationHitsImportAll;
use Uninett\Run\RunMediasite;
use Uninett\Run\RunRelayAll;
use Uninett\Run\RunRelayDaily;

require 'start/bootstrap.php';

if (defined('STDIN') && isset($argv[1])) {
	echo PHP_EOL . "Report for " . gethostname() . PHP_EOL;

	switch ($argv[1])
	{
		case "init":
			$run = new RunRelayAll();
			$run->run();
			break;
		case "daily":
			$run = new RunRelayDaily();
			$run->run();
			break;
		case "mediasite":
			$run = new RunMediasite();
			$run->run();
			break;
		case "reqa":
			$run = new \Uninett\Collections\RequestPerHour\RequestPerHourImportAll);
			$run->update();
		case "reqd":
			$run = new \Uninett\Collections\RequestPerHour\RequestPerHourImportDaily();
			$run->update();
			break;
		default:
			echo PHP_EOL . "Something went wrong. Wrong parameter?" . PHP_EOL;
			break;
	}
	echo PHP_EOL . "End of " . $argv[1] . PHP_EOL;
}

