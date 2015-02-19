<?php
require 'start/bootstrap.php';
use Uninett\Collections\CollectionUpdateInterface;


$presentation =	new \Uninett\Collections\Presentations\PresentationImport(true);

$presentation->update();
