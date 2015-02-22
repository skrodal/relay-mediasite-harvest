<?php
require 'start/bootstrap.php';

use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\UpdateInterface;

$collections = [
	new \Uninett\Collections\DailyUniqueTraffic\DailyUniqueTrafficImportDaily(),

	/*new \Uninett\Collections\Users\UserImport,
	new \Uninett\Collections\Users\UserSetAffiliation,
	new \Uninett\Collections\Users\UserCheckStatus,
	new \Uninett\Collections\UserDiskusage\UserDiskUsageImport,
	new \Uninett\Collections\Presentations\PresentationImport(true),
	new \Uninett\Collections\Org\OrgImport,
	new \Uninett\Collections\Org\OrgAggregateSizeUsed,
	new \Uninett\Collections\Mediasite\MediasiteAggregateSizeUsed,
	new \Uninett\Collections\Presentations\PresentationCheckForDeleted,
	new \Uninett\Collection\RequestPerHour\RequestPerHourImport*/
];

/* @var $collection UpdateInterface */
foreach($collections as $collection)
	$collection->update();




