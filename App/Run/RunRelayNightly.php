<?php namespace Uninett\Run;

use Uninett\Collections\UpdateInterface;

/**
 * Class RunRelayNightly
 * @package Uninett\Run
 */
class RunRelayNightly implements RunnableInterface {

	/**
	 * Create and run a new collection of jobs
	 */
	public function run() {
		$collections = [
			// Slow jobs pertaining to disk usage. Run at night.
			new \Uninett\Collections\UserDiskusage\UserDiskUsageImport,
			new \Uninett\Collections\Org\OrgAggregateSizeUsed,
			new \Uninett\Collections\Presentations\PresentationImport(true)     // With true only once a day
			// new \Uninett\Collections\Users\UserImport,                       // Hourly
			// new \Uninett\Collections\Users\UserSetAffiliation,               // Hourly
			// new \Uninett\Collections\Users\UserCheckStatus,                  // Hourly
			// new \Uninett\Collections\Presentations\PresentationImport(true), // Hourly
			// new \Uninett\Collections\Org\OrgImport                           // Hourly


			// new \Uninett\Collections\Presentations\PresentationCheckForDeleted,     // Determine speed when deleting is an option

			// Screencast IIS dependency
			// new \Uninett\Collections\RequestPerHour\RequestPerHourImportDaily,
			// new \Uninett\Collections\DailyUniqueTraffic\DailyUniqueTrafficImportDaily,
			// new \Uninett\Collections\DailyUserAgents\DailyUserAgentImportDaily,
			// new \Uninett\Collections\Presentations\PresentationHitsImportDaily

		];
		/* @var $collection UpdateInterface */
		foreach($collections as $collection) {
			$collection->update();
		}
	}

}