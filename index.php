<?php
use Uninett\Collections\Presentations\PresentationHitsImportAll;
use Uninett\Collections\UpdateInterface;
use Uninett\Models\UserModel;
use Uninett\Models\UserModel2;
use Uninett\Run\RunMediasite;
use Uninett\Run\RunRelayAll;
use Uninett\Run\RunRelayDaily;
use Uninett\Schemas\UsersSchema;

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
			$run = new \Uninett\Collections\RequestPerHour\RequestPerHourImportAll();
			$run->update();
			break;
		case "reqd":
			$run = new \Uninett\Collections\RequestPerHour\RequestPerHourImportDaily();
			$run->update();
			break;
		case "useragentsa":
			$run = new \Uninett\Collections\DailyUserAgents\DailyUserAgentImportAll();
			$run->update();
			break;
		case "useragentsd":
			$run = new \Uninett\Collections\DailyUserAgents\DailyUserAgentImportDaily();
			$run->update();
			break;
		case "uniquea":
			$run = new \Uninett\Collections\DailyUniqueTraffic\DailyUniqueTrafficImportAll();
			$run->update();
			break;
		case "uniqued":
			$run = new \Uninett\Collections\DailyUniqueTraffic\DailyUniqueTrafficImportDaily();
			$run->update();
			break;
		case "diverse":
			$collections = [
				new \Uninett\Collections\Presentations\PresentationImport,
				new \Uninett\Collections\Presentations\PresentationHitsImportAll
			];
			/* @var $collection UpdateInterface */
			foreach($collections as $collection)
				$collection->update();
			break;
		default:
			echo PHP_EOL . "Something went wrong. Wrong parameter?" . PHP_EOL;
			break;
	}
	echo PHP_EOL . "End of " . $argv[1] . PHP_EOL;
}



$res = [
		     'userDisplayName' => 'Kim Syversen',
		     'userName' => 'kim@example.com',
		     UsersSchema::USERNAME_ON_DISK => 'kimatexample.com',
		     UsersSchema::ORG => 'example.com',
		     'userEmail' => 'kim@example.com',
		     UsersSchema::AFFILIATION => 'ansatt',
		     'createdOn' => date('Y-m-d H:i:s'),
		     UsersSchema::STATUS => -1,
	     ];


