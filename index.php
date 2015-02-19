<?php
require 'start/bootstrap.php';

use Uninett\Collections\CollectionUpdateInterface;

$collections = [
	new \Uninett\Collections\Presentations\PresentationImport(false)
];

/* @var $collection CollectionUpdateInterface */
foreach($collections as $collection)
	$collection->update();

$pres = new \Uninett\Collections\Presentations\PresentationImport(false);

$pres->update();
/*$mongo = new \Uninett\Database\MongoConnection(\Uninett\Schemas\PresentationSchema::COLLECTION_NAME);

$criteria = array('presId' => 15319);

$cursor = $mongo->find($criteria);

foreach($cursor as $document)
{
	echo "PATH: " . $document[PresentationSchema::PATH];
	echo "SUBPATH" . $document[PresentationSchema::FILES][0][PresentationSchema::PATH];
}*/


/*	new \Uninett\Collections\Users\UserImport,
	new \Uninett\Collections\Users\UserSetAffiliation,
	new \Uninett\Collections\Users\UserCheckStatus,
	new \Uninett\Collections\UserDiskusage\UserDiskUsageImport,
	new \Uninett\Collections\Presentations\PresentationImport(true),
	new \Uninett\Collections\Org\OrgImport,
	new \Uninett\Collections\Org\OrgAggregateSizeUsed,
	new \Uninett\Collections\Mediasite\MediasiteAggregateSizeUsed
	new \Uninett\Collections\Presentations\PresentationCheckForDeleted */