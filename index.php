<?php
use Uninett\Collections\CollectionUpdateInterface;
use Uninett\Collections\Users\UserTest;

require 'start/bootstrap.php';

$collections = [
	new \Uninett\Collections\Users\UserImport(),
	new \Uninett\Collections\Users\UserCheckStatus(),
	new \Uninett\Collections\Users\UserSetAffiliation(),
];

/* @var $collection CollectionUpdateInterface */
foreach($collections as $collection)
	$collection->update();

