<?php
use Dotenv\Dotenv;

require 'global.php';

$projectRoot = dirname( __DIR__);


/**
 * Autoload third party modules
 */
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config.php';


Dotenv::load($projectRoot);

