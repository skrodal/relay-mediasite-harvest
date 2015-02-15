<?php
use Uninett\Collections\CollectionUpdateInterface;


require 'start/bootstrap.php';

$collections = [
	//new \Uninett\Collections\Users\UserImport(),
	//new \Uninett\Collections\Users\UserCheckStatus(),
	//new \Uninett\Collections\Users\UserSetAffiliation(),
	new \Uninett\Collections\Presentations\PresentationImport(false)
];

/* @var $collection CollectionUpdateInterface */
foreach($collections as $collection)
	$collection->update();

