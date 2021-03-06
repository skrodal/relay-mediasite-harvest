<?php


	use Uninett\Collections\UpdateInterface;
	use Uninett\Run\RunMediasite;
	use Uninett\Run\RunRelayAll;
	use Uninett\Run\RunRelayDaily;
	use Uninett\Run\RunRelayHourly;
	use Uninett\Run\RunRelayNightly;


	require 'start/bootstrap.php';

	if(defined('STDIN') && isset($argv[1])) {
		echo PHP_EOL . "Report for " . gethostname() . PHP_EOL;

		switch($argv[1]) {
			case "init":
				$run = new RunRelayAll();
				$run->run();
				break;
			case "daily":
				$run = new RunRelayDaily();
				$run->run();
				break;

			case "relayHourly":
				$run = new RunRelayHourly();
				$run->run();
				break;
			case "relayNightly":
				$run = new RunRelayNightly();
				$run->run();
				break;


			case "mediasite":
				$run = new RunMediasite();
				$run->run();
				break;
			case "pdeleted":
				$pcd = new \Uninett\Collections\Presentations\PresentationCheckForDeleted();
				$pcd->update();
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
			case "presentations":
				$run = new \Uninett\Collections\Presentations\PresentationImport(false);
				$run->update();
				break;
			case "phits":
				$run = new \Uninett\Collections\Presentations\PresentationHitsImportAll();
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
				foreach($collections as $collection) {
					$collection->update();
				}
				break;
			case "statistics":
				$collections = [
					new \Uninett\Collections\RequestPerHour\RequestPerHourImportAll(),
					new \Uninett\Collections\DailyUserAgents\DailyUserAgentImportAll(),
					new \Uninett\Collections\DailyUniqueTraffic\DailyUniqueTrafficImportAll
				];
				/* @var $collection UpdateInterface */
				foreach($collections as $collection) {
					$collection->update();
				}
				break;
			case "test":
				$date = new \Uninett\Helpers\StatisticDate();

				$date->setStartDateTodayByTimestamp(1426318732)->setEndDateBystring('today')->setDateIntervalFromString('1 day')->createDatePeriod();

				foreach($date->getDateInterval() as $dt) {
					echo $dt->format('Y-m-d H:i:s');
				}
				break;
			default:
				echo PHP_EOL . "Something went wrong. Wrong parameter?" . PHP_EOL;
				break;
		}
		echo PHP_EOL . "End of " . $argv[1] . PHP_EOL;
	}
