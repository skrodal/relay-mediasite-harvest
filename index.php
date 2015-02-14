<?php
require 'start/bootstrap.php';

$users = new \Uninett\Collections\Users\UserImport();

$users->update();

