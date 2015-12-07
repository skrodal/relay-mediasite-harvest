<?php namespace Uninett\Run;
use Uninett\Collections\UpdateInterface;

/**
 * Class RunRelayHourly
 * @package Uninett\Run
 */
class RunRelayHourly implements RunnableInterface
{

	/**
	 * Create and run a new collection of jobs
	 */
	public function run()
	{
		$collections = [
			new \Uninett\Collections\Users\UserImport,
			new \Uninett\Collections\Users\UserSetAffiliation,
			new \Uninett\Collections\Users\UserCheckStatus,
			new \Uninett\Collections\Presentations\PresentationImport(false),           // (true) will only ever work if PresentationImport is run only once per 24hrs
			new \Uninett\Collections\Org\OrgImport

			// new \Uninett\Collections\UserDiskusage\UserDiskUsageImport,              // SLOW, do nightly
			// new \Uninett\Collections\Org\OrgAggregateSizeUsed,                       // Slow, do nightly

			// new \Uninett\Collections\Presentations\PresentationCheckForDeleted,      // Determine speed when deleting is an option

		    // Screencast IIS dependency
			// new \Uninett\Collections\RequestPerHour\RequestPerHourImportDaily,
			// new \Uninett\Collections\DailyUniqueTraffic\DailyUniqueTrafficImportDaily,
			// new \Uninett\Collections\DailyUserAgents\DailyUserAgentImportDaily,
			// new \Uninett\Collections\Presentations\PresentationHitsImportDaily

		];
		/* @var $collection UpdateInterface */
		foreach($collections as $collection)
			$collection->update();
	}

}