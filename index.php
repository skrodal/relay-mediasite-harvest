<?php
require 'start/bootstrap.php';

echo "Starting script\n";
$p = new \Uninett\Collections\Presentations\PresentationImport(false);
$p->update();

