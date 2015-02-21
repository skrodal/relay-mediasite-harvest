<?php
require 'start/bootstrap.php';

use Uninett\Collections\LastUpdates\LastUpdates;
use Uninett\Collections\UpdateInterface;

$collections = [
/*	new \Uninett\Collections\Users\UserImport,
	new \Uninett\Collection\Statistics\RequestPerHour\RequestPerHourImport*/
/*	new \Uninett\Collections\Users\UserImport,
	new \Uninett\Collections\Users\UserSetAffiliation,
	new \Uninett\Collections\Users\UserCheckStatus,
	new \Uninett\Collections\UserDiskusage\UserDiskUsageImport,
	new \Uninett\Collections\Presentations\PresentationImport(true),
	new \Uninett\Collections\Org\OrgImport,
	new \Uninett\Collections\Org\OrgAggregateSizeUsed,
	new \Uninett\Collections\Mediasite\MediasiteAggregateSizeUsed,
	new \Uninett\Collections\Presentations\PresentationCheckForDeleted*/
];

/* @var $collection UpdateInterface */
foreach($collections as $collection)
	$collection->update();



$last = new LastUpdates();
$date =  $last->findLastInsertedRequestPerHourDate();



echo $date->sec;

echo PHP_EOL;

echo date($date->sec);