<?php namespace Uninett\Run;
use Uninett\Collections\UpdateInterface;

/**
 * Class RunRelayDaily
 * @package Uninett\Run
 */
class RunRelayDaily implements RunnableInterface
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
			new \Uninett\Collections\UserDiskusage\UserDiskUsageImport,
			new \Uninett\Collections\Presentations\PresentationImport(true),
			new \Uninett\Collections\Presentations\PresentationCheckForDeleted,
			new \Uninett\Collections\Org\OrgImport,
			new \Uninett\Collections\Org\OrgAggregateSizeUsed,
			new \Uninett\Collections\RequestPerHour\RequestPerHourImportDaily,
			new \Uninett\Collections\DailyUniqueTraffic\DailyUniqueTrafficImportDaily,
			new \Uninett\Collections\DailyUserAgents\DailyUserAgentImportDaily,
			new \Uninett\Collections\Presentations\PresentationHitsImportDaily

		];
		/* @var $collection UpdateInterface */
		foreach($collections as $collection)
			$collection->update();
	}

}