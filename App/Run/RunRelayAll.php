<?php namespace Uninett\Run;
use Uninett\Collections\UpdateInterface;

class RunRelayAll implements RunnableInterface
{

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
			new \Uninett\Collections\RequestPerHour\RequestPerHourImportAll,
			new \Uninett\Collections\DailyUniqueTraffic\DailyUniqueTrafficImportAll,
			new \Uninett\Collections\DailyUserAgents\DailyUserAgentImportAll,
			new \Uninett\Collections\Presentations\PresentationHitsImportAll

		];
		/* @var $collection UpdateInterface */
		foreach($collections as $collection)
			$collection->update();


	}
}