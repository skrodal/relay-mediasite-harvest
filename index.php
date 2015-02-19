<?php
require 'start/bootstrap.php';

use Uninett\Collections\CollectionUpdateInterface;

$collections = [
	new \Uninett\Collections\Presentations\PresentationCheckForDeleted
/*	new \Uninett\Collections\Users\UserImport,
	new \Uninett\Collections\Users\UserSetAffiliation,
	new \Uninett\Collections\Users\UserCheckStatus,
	new \Uninett\Collections\Presentations\PresentationImport(false),
	new \Uninett\Collections\Presentations\PresentationCheckForDeleted*/

/*	new \Uninett\Collections\Users\UserImport,
	new \Uninett\Collections\Users\UserSetAffiliation,
	new \Uninett\Collections\Users\UserCheckStatus,
	new \Uninett\Collections\UserDiskusage\UserDiskUsageImport,
	new \Uninett\Collections\Presentations\PresentationImport(false),
	new \Uninett\Collections\Org\OrgImport,
	new \Uninett\Collections\Org\OrgAggregateSizeUsed,
	new \Uninett\Collections\Mediasite\MediasiteAggregateSizeUsed,
	new \Uninett\Collections\Presentations\PresentationCheckForDeleted*/
];

/* @var $collection CollectionUpdateInterface */
foreach($collections as $collection)
	$collection->update();
